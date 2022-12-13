<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Validator;
use App\Adoption;
use App\EducativeInstitution;
use App\Permission;
use App\PivotAdoptionSpecies;
use App\Species;
use App\Sponsor;
use App\User;
use App\WorkshopEvent;
use App\Buyers;

class TreeDeliveriesController extends Controller
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

            return view('admin.tree-deliveries', compact('perm_btn'));
        }
    }

    /**
     * Display a form.
     *
     * @return \Illuminate\Http\Response
     */
    public function form()
    {
        $profile = User::findProfile();
        $perm = Permission::permView($profile, 8);
        $perm_btn = Permission::permBtns($profile, 8);

        if($perm == 0) {
            return redirect()->route('home');
        } else {

            return view('admin.tree-deliveries-form', compact('perm_btn'));
        }
    }

    /**
     * Read the specified resource.
     *
     * @param  Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function treeDelevery(Request $request, $id)
    {
        $id2 = false;

        if(str_contains($id, '.')) {
            $v = explode('.', $id);
            $id = $v[0];
            $id2= $v[1];

        }
        // Validar existencia
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|exists:adoptions,id'
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'RECORD_NOT_FOUND',
                'errors' => $validator->errors(),
                'user_message' => 'Adopción desconocida.',
                'id' => $id,
                'id2'=> $id2
            ], 422);
        }

        if($id2) {
            // $id2 = eliminar de la base de datos
            $buyer = Buyers::find($id2);
            $buyer->delete();

            return response()->json([
                'success' => true,
                'message' => 'TREE_DELIVERY_REMOVE',
                'data' => $buyer
            ], 200);
        }

        // Encontrar y devolver respuesta
        $adoption = Adoption::find($id);
        $pivotAdoptionSpecies = PivotAdoptionSpecies::select(
                'pivot_adoption_species.id as pivot_id',
                'pivot_adoption_species.species_id',
                'species.name as species_name',
                'species.scientific_name as species_scientific_name',
                'pivot_adoption_species.species_amount'
            )
            ->where('pivot_adoption_species.adoption_id', '=', $adoption->id)
            ->join('species', 'species.id', '=', 'pivot_adoption_species.species_id')
            ->get();
        $buyers = Buyers::select(
            'buyers.id',
            'buyers.id_adoption',
            'buyers.name',
            'buyers.phone',
            'buyers.mail',
            'buyers.address',
            'buyers.suburb',
            'buyers.cp',
            'buyers.id_specie',
            'buyers.latitude',
            'buyers.length',
            'species.name as species_name'
        )
        ->where('buyers.id_adoption', '=', $adoption->id)
        ->join('species', 'species.id', '=', 'buyers.id_specie')
        ->get();
        $adoption->adoption_species = $pivotAdoptionSpecies;
        $adoption->buyers = $buyers;

        return response()->json([
            'success' => true,
            'message' => 'TREE_DELIVERY_READ',
            'data' => $adoption
        ], 200);
    }

    /**
     * Read the specified resource.
     *
     * @param  Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function treeDeleveries(Request $request)
    {
        // Recolectar todos los registros y devolver respuesta
        $adoptions = Adoption::select(
                'adoptions.*',
                'events.name as event_name',
                'sponsors.name as sponsor_name',
                'educative_institutions.institution_name as institution_name',
                'users.name as thecnical_user_name'
            )
            ->join('events', 'events.id', '=', 'adoptions.event_id')
            ->join('sponsors', 'sponsors.id', '=', 'adoptions.sponsor_id')
            ->join('educative_institutions', 'educative_institutions.id', '=', 'adoptions.educative_institution_id')
            ->join('users', 'users.id', '=', 'adoptions.technical_user_id')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'TREE_DELIVERIES_READ',
            'data' => $adoptions
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
            'educative_institution_id',
            'sponsor_id',
            'event_id',
            'species_id',
            'adoption_date',
            'adoption_time',
            'technical_user_id',
            'code_event',
            'name',
            'address',
            'phone',
            'email',
            'cp',
            'qr_code',
            'latitude',
            'length',
            'adoption_species'
        );

        // Validar input
        $validator = Validator::make($input_data, [
            'educative_institution_id',
            'sponsor_id',
            'event_id',
            'species_id',
            'adoption_date',
            'adoption_time',
            'technical_user_id',
            'code_event',
            'name',
            'address',
            'phone',
            'email',
            'cp',
            'qr_code',
            'latitude',
            'length'
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

        $exists = Adoption::where([
            ['code_event', '=', $input_data['code_event']],
        ])->exists();

        if($exists)
        {
            return response()->json([
                'success' => false,
                'message' => 'RECORD_EXISTS',
                'input_data' => $input_data,
                'errors' => ['code_event' => 'The code_event has been previously taked.'],
                'user_message' => 'Codigo de Adopción previamente registrado.'
            ], 422);
        }

        // Crear registro y devolver respuesta
        DB::beginTransaction();
        try {
            $adoption = Adoption::create($input_data);

            foreach ($input_data['adoption_species'] as $adoption_species) {
                $adoption_species['adoption_id'] = $adoption->id;
                PivotAdoptionSpecies::create($adoption_species);
            }

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
            'message' => 'TREE_DELIVERIES_STORE',
            'data' => $adoption
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
            'id_adoption',
            'name',
            'phone',
            'mail',
            'address',
            'suburb',
            'cp',
            'id_specie',
            'latitude',
            'length'
        );

        // Validar input
        $validator = Validator::make($input_data, [
            'id_adoption',
            'name',
            'phone',
            'mail',
            'address',
            'suburb',
            'cp',
            'id_specie',
            'latitude',
            'length'
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

        // Asegurar escritura completa en DB;
        DB::beginTransaction();

        Buyers::create($input_data);
        //Buyers::create($request->all());  

        DB::commit();


        return response()->json([
            'success' => true,
            'message' => 'TREE_DELIVERIES_UPDATE',
            'data' => $request->all()
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
        return response()->json([
            'success' => true,
            'message' => [(float)$id, (int)$id, (float)$id % 1, (int)$id % 1],
            'errors' => 'ninguno',
            'user_message' => (float)$id % 1
        ], 200);

        if((float)$id % 1 != 0) {
            return response()->json([
                'success' => false,
                'message' => 'si jalo',
                'errors' => $validator->errors(),
                'user_message' => 'no mms.'
            ], 422);
        }
        // Verificar existencia 
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|exists:adoptions,id'
        ]);
        

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => [(float)$id, (int)$id, (float)$id % 1, (int)$id % 1],
                'errors' => $validator->errors(),
                'user_message' => (float)$id % 1
            ], 422);
        }

        // Eliminar registro y devolver respuesta
        $adoption = Adoption::find($id);
        $adoption->delete();

        return response()->json([
            'success' => true,
            'message' => 'TREE_DELIVERIES_DELETE',
            'data' => $adoption
        ], 200);
    }
}
