<?php

namespace App\Http\Controllers;

use App\Adoption;
use Illuminate\Http\Request;
use Validator;
use App\Nursery;
use App\Permission;
use App\PivotTreeLeavingSpecies;
use App\Species;
use App\TreeLeaving;
use App\User;
use Illuminate\Support\Facades\DB;

class TreeLeavingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $profile = User::findProfile();
        $perm = Permission::permView($profile, 8);
        $perm_btn = Permission::permBtns($profile, 8);

        if($perm == 0) {
            return redirect()->route('home');
        } else {
            //$events = Adoption::where('type','Adopcion')->pluck('name', 'id');
            $adoptions = Adoption::select(
                'id',
                'code_event'
            )
            // ->orderBy('code_event', 'asc')
            ->get()
            ->keyBy('id');

            $nurseries = Nursery::select('id', 'name')
                ->orderBy('name', 'asc')
                ->get()
                ->keyBy('id');

            $allSpecies = Species::select(
                    'id',
                    'name',
                    'scientific_name'
                )
                ->orderBy('name', 'asc')
                ->get()
                ->keyBy('id');

            $users = User::pluck('name', 'id');

            return view('admin.tree-leavings', compact('perm_btn', 'adoptions','nurseries','allSpecies','users'));
        }
    }

    /**
     * Read the specified resource.
     *
     * @param  Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function treeLeaving(Request $request, $id)
    {
        // Validar existencia
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|exists:tree_leavings,id'
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'RECORD_NOT_FOUND',
                'errors' => $validator->errors(),
                'user_message' => 'Salida de Árbol desconocida.'
            ], 422);
        }

        // Encontrar y devolver respuesta
        $treeLeaving = TreeLeaving::find($id);
        $pivotTreeLeavingSpecies = PivotTreeLeavingSpecies::select(
            'pivot_tree_leaving_species.id as pivot_id',
            'pivot_tree_leaving_species.species_id',
            'species.name as species_name',
            'species.scientific_name as species_scientific_name',
            'pivot_tree_leaving_species.species_amount',
            'pivot_tree_leaving_species.nursery_id'
        )
        ->where('pivot_tree_leaving_species.tree_leaving_id', '=', $treeLeaving->id)
        ->join('species', 'species.id', '=', 'pivot_tree_leaving_species.species_id')
        ->get();
        $treeLeaving->tree_leaving_species = $pivotTreeLeavingSpecies;

        return response()->json([
            'success' => true,
            'message' => 'TREE_LEAVING_READ',
            'data' => $treeLeaving
        ], 200);
    }

    /**
     * Read the specified resource.
     *
     * @param  Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function treeLeavings(Request $request)
    {
        // Recolectar todos los registros y devolver respuesta
        $treeLeavings = TreeLeaving::select(
                'tree_leavings.*',
                'adoptions.code_event as adoption_code_event',
                // 'nurseries.name as nursery_name',
                // 'species.name as species_name',
                'users.name as technical_user_name'
            )
            ->join('adoptions', 'adoptions.id', '=', 'tree_leavings.adoption_id')
            // ->join('nurseries', 'nurseries.id', '=', 'tree_leavings.nursery_id')
            // ->join('species', 'species.id', '=', 'tree_leavings.species_id')
            ->join('users', 'users.id', '=', 'tree_leavings.technical_user_id')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'TREE_LEAVINGS_READ',
            'data' => $treeLeavings
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Recolectar input
        $input_data = $request->only(
            'adoption_id',
            // 'nursery_id',
            // 'species_id',
            // 'amount',
            'labels',
            'technical_user_id',
            'leaving_date',
            'tree_leaving_species'
        );

        // Validar input
        $validator = Validator::make($input_data, [
            'adoption_id' => 'nullable|exists:adoptions,id',
            // 'nursery_id' => 'nullable|exists:nurseries,id',
            // 'species_id' => 'nullable|exists:species,id',
            // 'amount' => 'required|numeric',
            'labels' => 'nullable',
            'technical_user_id' => 'required|exists:users,id',
            'leaving_date' => 'required|date',
            'tree_leaving_species'
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'BAD_INPUT',
                'input_data' => $input_data,
                'errors' => $validator->errors(),
                'user_message' => 'Los datos ingresados son invalidos o falta alguno por ingresar.'
            ], 422);
        }

        // $exists = TreeLeaving::where([
        //     ['code_event', '=', $input_data['code_event']],
        // ])->exists();

        // if($exists)
        // {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'RECORD_EXISTS',
        //         'input_data' => $input_data,
        //         'errors' => ['code_event' => 'The code_event has been previously taked.'],
        //         'user_message' => 'Codigo de Adopción previamente registrado.'
        //     ], 422);
        // }

        // Crear registro y devolver respuesta
        DB::beginTransaction();
        // try {
            $treeLeaving = TreeLeaving::create($input_data);

            foreach ($input_data['tree_leaving_species'] as $tree_leaving_species) {
                $tree_leaving_species['tree_leaving_id'] = $treeLeaving->id;
                PivotTreeLeavingSpecies::create($tree_leaving_species);
            }

            DB::commit();
        // } catch (\Exception $e) {
        //     DB::rollBack();

        //     return response()->json([
        //         'success' => false,
        //         'message' => 'DB_ERROR',
        //         'errors' => $e,
        //         'user_message' => 'Ocurrio un error al intentar escribir en la Base de Datos.'
        //     ], 422);
        // }

        return response()->json([
            'success' => true,
            'message' => 'TREE_LEAVINGS_STORE',
            'data' => $treeLeaving
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
         // Recolectar input
         $input_data = $request->only(
            'adoption_id',
            // 'nursery_id',
            // 'species_id',
            // 'amount',
            'labels',
            'technical_user_id',
            'leaving_date',
            'tree_leaving_species'
        );

        // Validar input
        $validator = Validator::make($input_data, [
            'adoption_id' => 'nullable|exists:adoptions,id',
            // 'nursery_id' => 'nullable|exists:nurseries,id',
            // 'species_id' => 'nullable|exists:species,id',
            // 'amount' => 'required|numeric',
            'labels' => 'nullable',
            'technical_user_id' => 'required|exists:users,id',
            'leaving_date' => 'required|date',
            'tree_leaving_species'
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'BAD_INPUT',
                'input_data' => $input_data,
                'errors' => $validator->errors(),
                'user_message' => 'Los datos ingresados son invalidos o falta alguno por ingresar.'
            ], 422);
        }

        // $exists = TreeLeaving::where([
        //     ['id', '!=', $id],
        //     ['code_event', '=', $input_data['code_event']],
        // ])->exists();

        // if($exists)
        // {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'RECORD_EXISTS',
        //         'input_data' => $input_data,
        //         'errors' => ['code_event' => 'The code_event has been previously taked.'],
        //         'user_message' => 'Codigo de Adopción previamente registrado.'
        //     ], 422);
        // }

        // Encontrar registro, actualizar y devolver respuesta
        // $treeLeaving = TreeLeaving::find($id);
        // $treeLeaving->update($input_data);

        // Encontrar registro, actualizar y devolver respuesta
        $treeLeaving = TreeLeaving::find($id);

        // Asegurar escritura completa en DB; o deshacer movimientos
        DB::beginTransaction();
        // try {
            $treeLeaving->update($input_data);
            PivotTreeLeavingSpecies::where('tree_leaving_id', '=', $treeLeaving->id)->delete();

            foreach ($input_data['tree_leaving_species'] as $input_tree_leaving_species) {
                $input_tree_leaving_species['tree_leaving_id'] = $treeLeaving->id;

                $pivotTreeLeavingSpecies = PivotTreeLeavingSpecies::where([
                        [ 'tree_leaving_id', '=', $input_tree_leaving_species['tree_leaving_id'] ],
                        [ 'species_id', '=',  $input_tree_leaving_species['species_id'] ]
                    ])->withTrashed()->first() ?: new PivotTreeLeavingSpecies();

                $pivotTreeLeavingSpecies->fill($input_tree_leaving_species);
                $pivotTreeLeavingSpecies->deleted_at = null;
                $pivotTreeLeavingSpecies->save();
            }

            DB::commit();
        // } catch (\Exception $e) {
        //     DB::rollBack();

        //     return response()->json([
        //         'success' => false,
        //         'message' => 'DB_ERROR',
        //         'errors' => $e,
        //         'input_data' => $input_data,
        //         'user_message' => 'Ocurrio un error al intentar escribir en la Base de Datos.'
        //     ], 422);
        // }


        return response()->json([
            'success' => true,
            'message' => 'TREE_LEAVINGS_UPDATE',
            'data' => $treeLeaving
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        // Verificar existencia 
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|exists:tree_leavings,id'
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'RECORD_NOT_FOUND',
                'errors' => $validator->errors(),
                'user_message' => 'Adopción desconocida.'
            ], 422);
        }

        // Eliminar registro y devolver respuesta
        $treeLeaving = TreeLeaving::find($id);

        DB::beginTransaction();
        try {
            PivotTreeLeavingSpecies::where('tree_leaving_id', '=', $treeLeaving->id)->delete();
            $treeLeaving->delete();
 
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
 
            return response()->json([
                'success' => false,
                'message' => 'DB_ERROR',
                'errors' => $e,
                'user_message' => 'Ocurrio un error al intentar escribir en la Base de Datos.'
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'TREE_LEAVINGS_DELETE',
            'data' => $treeLeaving
        ], 200);
    }
}
