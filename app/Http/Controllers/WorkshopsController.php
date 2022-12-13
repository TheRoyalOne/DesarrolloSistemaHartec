<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;
use App\EducativeInstitution;
use App\Permission;
use App\PivotMaterialWorkshop;
use App\Sponsor;
use App\User;
use App\Workshop;
use App\WorkshopEvent;
use App\WorkshopMaterial;

class WorkshopsController extends Controller
{
    public function index()
    {
        $profile = User::findProfile();
        $perm = Permission::permView($profile, 16);
        $perm_btn = Permission::permBtns($profile, 16);
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

            $recFeeTypes = [
                'rec_fee_online' => 'Online',
                'rec_fee_presencial' => 'Presencial',
                'rec_fee_business' => 'Empresarial',
                'rec_fee_online_kits' => 'Online + Kits'
            ];

            $workshopMaterials = WorkshopMaterial::select(
                    'id',
                    'name',
                    'description'
                )
                ->get()
                ->keyBy('id');

            $users = User::pluck('name', 'id');

            $events = WorkshopEvent::select(
                    'id',
                    'name',
                    'type',
                    'prefix_code',
                    'rec_fee_online',
                    'rec_fee_presencial',
                    'rec_fee_business',
                    'rec_fee_online_kits'
                )
                ->where('type', '=', 'Taller')
                ->get()
                ->keyBy('id');

            $sponsors = Sponsor::select(
                    'id',
                    'enterprise_name',
                    'prefix_code_event'
                )
                ->get()
                ->keyBy('id');

            return view('admin.workshops', compact('perm_btn', 'educativeInstitutions', 'recFeeTypes', 'workshopMaterials', 'users', 'events', 'sponsors'));
        }
    }

    /**
     * Read the specified resource.
     *
     * @param  Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function workshop(Request $request, $id)
    {
        // Validar existencia
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|exists:workshops,id'
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'RECORD_NOT_FOUND',
                'errors' => $validator->errors(),
                'user_message' => 'Taller desconocido.'
            ], 422);
        }

        // Encontrar y devolver respuesta
        $workshop = Workshop::find($id);
        $pivotMaterialWorkshop = PivotMaterialWorkshop::select(
                'pivot_material_workshop.id as pivot_id',
                'pivot_material_workshop.workshop_material_id',
                'workshop_materials.name as workshop_material_name',
                'workshop_materials.description as workshop_material_description',
                'pivot_material_workshop.workshop_material_amount'
            )
            ->where('pivot_material_workshop.workshop_id', '=', $workshop->id)
            ->join('workshop_materials', 'workshop_materials.id', '=', 'pivot_material_workshop.workshop_material_id')
            ->get();
        $workshop->workshop_materials = $pivotMaterialWorkshop;
        // $workshop->load('workshopMaterials');

        return response()->json([
            'success' => true,
            'message' => 'WORKSHOP_READ',
            'data' => $workshop
        ], 200);
    }

    /**
     * Read the specified resource.
     *
     * @param  Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function workshops(Request $request)
    {
        // Recolectar todos los registros y devolver respuesta
        $workshops = Workshop::select(
                'workshops.*',
                'sponsors.enterprise_name as sponsor_name',
                'educative_institutions.institution_name as educative_institution_name',
                'events.name as event_name',
                DB::Raw(
                    'CASE ' . 
                        'WHEN workshops.rec_fee_type = "rec_fee_online" THEN "Online" ' .
                        'WHEN workshops.rec_fee_type = "rec_fee_presencial" THEN "Presencial" ' .
                        'WHEN workshops.rec_fee_type = "rec_fee_business" THEN "Empresarial" ' .
                        'WHEN workshops.rec_fee_type = "rec_fee_online_kits" THEN "Online + Kits" ' .
                    'END as rec_fee_type'
                ),
                DB::Raw(
                    'CASE ' . 
                        'WHEN workshops.rec_fee_type = "rec_fee_online" THEN events.rec_fee_online ' .
                        'WHEN workshops.rec_fee_type = "rec_fee_presencial" THEN events.rec_fee_presencial ' .
                        'WHEN workshops.rec_fee_type = "rec_fee_business" THEN events.rec_fee_business ' .
                        'WHEN workshops.rec_fee_type = "rec_fee_online_kits" THEN events.rec_fee_online_kits ' .
                    'END as rec_fee'
                ),
                'users.name as workshop_user_name'
            )
            ->leftJoin('sponsors', 'sponsors.id', '=', 'workshops.sponsor_id')
            ->leftJoin('educative_institutions', 'educative_institutions.id', '=', 'workshops.educative_institution_id')
            ->leftJoin('events', 'events.id', '=', 'workshops.event_id')
            ->leftJoin('users', 'users.id', '=', 'workshops.workshop_user_id')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'WORKSHOPS_READ',
            'data' => $workshops
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
            'sponsor_id',
            'educative_institution_id',
            'rec_fee_type',
            'event_id',
            'workshop_date',
            'workshop_time',
            'workshop_user_id',
            // 'rec_fee',
            'code_event',
            'workshop_materials'
        );

        // Validar input
        $validator = Validator::make($input_data, [
            'sponsor_id' => 'required|exists:sponsors,id',
            'educative_institution_id' => 'required|exists:educative_institutions,id',
            'rec_fee_type' => 'required',
            'event_id' => 'required|exists:events,id',
            'workshop_date' => 'required|date',
            'workshop_time' => 'required',
            'workshop_user_id' => 'required|exists:users,id',
            'code_event' => 'required'
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

        $exists = Workshop::where([
            ['code_event', '=', $input_data['code_event']]
        ])->exists();

        if($exists)
        {
            return response()->json([
                'success' => false,
                'message' => 'RECORD_EXISTS',
                'input_data' => $input_data,
                'errors' => ['type, prefix_code' => 'The type and code_event has been previously recorded together'],
                'user_message' => 'Taller previamente registrado (codigo: ' . $input_data['code_event'] . ').'
            ], 422);
        }

        // Crear registro y devolver respuesta
        DB::beginTransaction();
        try {
            $workshop = Workshop::create($input_data);

            foreach ($input_data['workshop_materials'] as $workshop_material) {
                $workshop_material['workshop_id'] = $workshop->id;
                PivotMaterialWorkshop::create($workshop_material);
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
            'message' => 'WORKSHOPS_STORE',
            'data' => $workshop
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
            'sponsor_id',
            'educative_institution_id',
            'rec_fee_type',
            'event_id',
            'workshop_date',
            'workshop_time',
            'workshop_user_id',
            // 'rec_fee',
            'code_event',
            'workshop_materials'
        );

        // Validar input
        $validator = Validator::make($input_data, [
            'sponsor_id' => 'required|exists:sponsors,id',
            'educative_institution_id' => 'required|exists:educative_institutions,id',
            'rec_fee_type' => 'required',
            'event_id' => 'required|exists:events,id',
            'workshop_date' => 'required|date',
            'workshop_time' => 'required',
            'workshop_user_id' => 'required|exists:users,id',
            // 'rec_fee' => 'required',
            'code_event' => 'required'
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

        $exists = Workshop::where([
            ['id', '!=', $id],
            ['code_event', '=', $input_data['code_event']]
        ])->exists();

        if($exists)
        {
            return response()->json([
                'success' => false,
                'message' => 'RECORD_EXISTS',
                'input_data' => $input_data,
                'errors' => ['type, prefix_code' => 'The type and code_event has been previously recorded together'],
                'user_message' => 'Taller previamente registrado (codigo: ' . $input_data['code_event'] . ').'
            ], 422);
        }

        // Encontrar registro, actualizar y devolver respuesta
        $workshop = Workshop::find($id);

        // Asegurar escritura completa en DB; o deshacer movimientos
        DB::beginTransaction();
        try {
            $workshop->update($input_data);
            PivotMaterialWorkshop::where('workshop_id', '=', $workshop->id)->delete();

            foreach ($input_data['workshop_materials'] as $input_workshop_material) {
                $input_workshop_material['workshop_id'] = $workshop->id;

                $pivotMaterialWorkshop = PivotMaterialWorkshop::where([
                        [ 'workshop_id', '=', $input_workshop_material['workshop_id'] ],
                        [ 'workshop_material_id', '=',  $input_workshop_material['workshop_material_id'] ]
                    ])->withTrashed()->first() ?: new PivotMaterialWorkshop();

                $pivotMaterialWorkshop->fill($input_workshop_material);
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
            'message' => 'WORKSHOPS_UPDATE',
            'data' => $workshop
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Verificar existencia 
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|exists:workshops,id'
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'RECORD_NOT_FOUND',
                'errors' => $validator->errors(),
                'user_message' => 'Taller desconocido.'
            ], 422);
        }

        // Eliminar registro y devolver respuesta
        $workshop = Workshop::find($id);

        DB::beginTransaction();
        try {
            PivotMaterialWorkshop::where('workshop_id', '=', $workshop->id)->delete();
            $workshop->delete();

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
            'message' => 'WORKSHOPS_DELETE',
            'data' => $workshop
        ], 200);
    }
}
