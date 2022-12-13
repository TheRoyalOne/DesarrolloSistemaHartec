<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Adoption;
use App\EducativeInstitution;
use App\Permission;
use App\PivotAdoptionSpecies;
use App\Species;
use App\Sponsor;
use App\User;
use App\WorkshopEvent;
use Illuminate\Support\Facades\DB;

class AdoptionsController extends Controller
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
            $educativeInstitutions = EducativeInstitution::select(
                    'id',
                    'institution_name'
                    // 'prefix_code_event'
                )
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

            $events = WorkshopEvent::select(
                    'id',
                    'name',
                    'type',
                    'prefix_code'
                )
                ->where('type', '!=', 'Taller')
                ->get()
                ->keyBy('id');

            $sponsors = Sponsor::select(
                    'id',
                    'enterprise_name',
                    'prefix_code_event'
                )
                ->get()
                ->keyBy('id');

            return view('admin.adoptions', compact('perm_btn','sponsors','allSpecies','events','users','educativeInstitutions'));
        }
    }

    /**
     * Read the specified resource.
     *
     * @param  Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function adoption(Request $request, $id)
    {
        // Validar existencia
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|exists:adoptions,id'
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'RECORD_NOT_FOUND',
                'errors' => $validator->errors(),
                'user_message' => 'Adopción desconocida.'
            ], 422);
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
        $adoption->adoption_species = $pivotAdoptionSpecies;

        return response()->json([
            'success' => true,
            'message' => 'ADOPTION_READ',
            'data' => $adoption
        ], 200);
    }

    /**
     * Read the specified resource.
     *
     * @param  Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function adoptions(Request $request)
    {
        // Recolectar todos los registros y devolver respuesta
        $adoptions = Adoption::select(
                'adoptions.*',
                'events.name as event_name',
                'sponsors.enterprise_name as sponsor_name',
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
            'message' => 'ADOPTIONS_READ',
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
            // 'name',
            // 'address',
            // 'phone',
            // 'email',
            // 'postal_code',
            // 'qr_code',
            // 'latitude',
            // 'longitude',
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
            // 'name',
            // 'address',
            // 'phone',
            // 'email',
            // 'postal_code',
            // 'qr_code',
            // 'latitude',
            // 'longitude',
            'adoption_species'
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
            'message' => 'ADOPTION_STORE',
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
            'educative_institution_id',
            'sponsor_id',
            'event_id',
            'adoption_date',
            'adoption_time',
            'technical_user_id',
            'code_event',
            // 'name',
            // 'address',
            // 'phone',
            // 'email' ,
            // 'postal_code',
            // 'qr_code',
            // 'latitude',
            // 'longitude',
            'adoption_species'
        );

        // Validar input
        $validator = Validator::make($input_data, [
            'educative_institution_id',
            'sponsor_id',
            'event_id',
            'species_id',
            'adoption_date',
            'adoption_time' ,
            'technical_user_id',
            'species_number',
            'code_event',
            // 'name',
            // 'address',
            // 'phone',
            // 'email' ,
            // 'postal_code',
            // 'qr_code',
            // 'latitude',
            // 'longitude',
            'adoption_species'
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
            ['id', '!=', $id],
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

        // Encontrar registro, actualizar y devolver respuesta
        $adoption = Adoption::find($id);

        // Asegurar escritura completa en DB; o deshacer movimientos
        DB::beginTransaction();
        try {
            $adoption->update($input_data);
            PivotAdoptionSpecies::where('adoption_id', '=', $adoption->id)->delete();

            foreach ($input_data['adoption_species'] as $input_adoption_species) {
                $input_adoption_species['adoption_id'] = $adoption->id;

                $pivotMaterialWorkshop = PivotAdoptionSpecies::where([
                        [ 'adoption_id', '=', $input_adoption_species['adoption_id'] ],
                        [ 'species_id', '=',  $input_adoption_species['species_id'] ]
                    ])->withTrashed()->first() ?: new PivotAdoptionSpecies();

                $pivotMaterialWorkshop->fill($input_adoption_species);
                $pivotMaterialWorkshop->deleted_at = null;
                $pivotMaterialWorkshop->save();
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'DB_ERROR',
                'errors' => $e,
                'input_data' => $input_data,
                'user_message' => 'Ocurrio un error al intentar escribir en la Base de Datos.'
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'ADOPTION_UPDATE',
            'data' => $adoption
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
            'id' => 'required|exists:adoptions,id'
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
        $adoption = Adoption::find($id);

        DB::beginTransaction();
        try {
            PivotAdoptionSpecies::where('adoption_id', '=', $adoption->id)->delete();
            $adoption->delete();

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
            'message' => 'ADOPTION_DELETE',
            'data' => $adoption
        ], 200);
    }
}
