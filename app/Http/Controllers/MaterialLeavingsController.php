<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\MaterialLeaving;
use App\Permission;
use App\User;
use App\PivotLeavingMaterial;
use App\Workshop;
use App\WorkshopMaterial;
use Illuminate\Support\Facades\DB;

class MaterialLeavingsController extends Controller
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
            $events = Workshop::pluck('code_event as name', 'id');
            $workshopMaterials = WorkshopMaterial::pluck('name', 'id');
            $users = User::pluck('name', 'id');

            return view('admin.material-leavings', compact('perm_btn','events','workshopMaterials','users'));
        }
    }

    /**
     * Read the specified resource.
     *
     * @param  Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function materialLeaving(Request $request, $id)
    {
        // Validar existencia
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|exists:material_leavings,id'
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'RECORD_NOT_FOUND',
                'errors' => $validator->errors(),
                'user_message' => 'Salida de Material desconocida.'
            ], 422);
        }

        // Encontrar y devolver respuesta
        $materialLeaving = MaterialLeaving::find($id);
        $pivotLeavingMaterial = PivotLeavingMaterial::select(
            'pivot_leaving_material.id as pivot_id',
            'pivot_leaving_material.material_id',
            'workshop_materials.name as material_name',
            'pivot_leaving_material.material_amount'
        )
        -> where('pivot_leaving_material.material_leaving_id', '=', $materialLeaving->id)
        ->join('workshop_materials', 'workshop_materials.id', '=', 'pivot_leaving_material.material_id')
        ->get();
        $materialLeaving->leaving_material = $pivotLeavingMaterial;

        return response()->json([
            'success' => true,
            'message' => 'MATERIAL_LEAVING_READ',
            'data' => $materialLeaving
        ], 200);
    }

    /**
     * Read the specified resource.
     *
     * @param  Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function materialLeavings(Request $request)
    {
        // Recolectar todos los registros y devolver respuesta
        $materialLeavings = MaterialLeaving::select(
                'material_leavings.*',
                //'wm_1.name as workshop_material_1_name',
                'workshops.code_event as workshop_name',
                'users.name as technical_user_name'
            )
            //->join('workshop_materials as wm_1', 'wm_1.id', '=', 'material_leavings.workshop_material_1_id')
            ->join('users', 'users.id', '=', 'material_leavings.technical_user_id')
            ->join('workshops', 'workshops.id', '=', 'material_leavings.workshop_id')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'MATERIAL_LEAVINGS_READ',
            'data' => $materialLeavings
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
            'workshop_id',
            //'workshop_material_1_id',
            //'workshop_material_1_amount',
            'technical_user_id',
            'leaving_date',
            'leaving_material'
        );

        // Validar input
        $validator = Validator::make($input_data, [
            'workshop_id' => 'required|exists:workshops,id',
            //'workshop_material_1_id' => 'required|exists:workshop_materials,id',
            //'workshop_material_1_amount' => 'required|numeric',
            'technical_user_id' => 'required|exists:users,id',
            'leaving_date' => 'required|date',
            'leaving_material'
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

        DB::beginTransaction();
        $treeLeaving = MaterialLeaving::create($input_data);

        foreach ($input_data['leaving_material'] as $leaving_material) {
            $leaving_material['material_leaving_id'] = $treeLeaving->id;
            PivotLeavingMaterial::create($leaving_material);
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'MATERIAL_LEAVINGS_STORE',
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
            'workshop_id',
            //'workshop_material_1_id',
            //'workshop_material_1_amount',
            'technical_user_id',
            'leaving_date',
            'leaving_material'
        );

        // Validar input
        $validator = Validator::make($input_data, [
            'workshop_id' => 'required|exists:workshops,id',
            //'workshop_material_1_id' => 'required|exists:workshop_materials,id',
            //'workshop_material_1_amount' => 'required|numeric',
            'technical_user_id' => 'required|exists:users,id',
            'leaving_date' => 'required|date',
            'leaving_material'
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


        // Encontrar registro, actualizar y devolver respuesta
        $materialLeaving = MaterialLeaving::find($id);
        DB::beginTransaction();


        $materialLeaving->update($input_data);
        PivotLeavingMaterial::where('material_leaving_id', '=', $materialLeaving->id)->delete();

        foreach ($input_data['leaving_material'] as $leaving_material) {
            $leaving_material['material_leaving_id'] = $materialLeaving->id;
            PivotLeavingMaterial::create($leaving_material);
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'MATERIAL_LEAVINGS_UPDATE',
            'data' => $materialLeaving
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
            'id' => 'required|exists:material_leavings,id'
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'RECORD_NOT_FOUND',
                'errors' => $validator->errors(),
                'user_message' => 'Salida de Material desconocida.'
            ], 422);
        }

        // Eliminar registro y devolver respuesta
        $materialLeaving = MaterialLeaving::find($id);

        DB::beginTransaction();
        try {
            pivotLeavingMaterial::where('material_leaving_id', '=', $materialLeaving->id)->delete();
            $materialLeaving->delete();
 
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
            'message' => 'MATERIAL_LEAVINGS_DELETE',
            'data' => $materialLeaving
        ], 200);
    }
}
