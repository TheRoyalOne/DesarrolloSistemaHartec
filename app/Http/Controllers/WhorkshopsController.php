<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Permission;
use App\User;
use App\Workshop;

class WhorkshopsController extends Controller
{
    public function index()
    {
        $profile = User::findProfile();
        $perm = Permission::permView($profile,16);
        $perm_btn =Permission::permBtns($profile,16);
        if($perm==0) {
            return redirect()->route('home');
        } else {
            return view('admin.workshopsevents', compact('perm_btn'));
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
            'id' => 'required|exists:events,id'
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'RECORD_NOT_FOUND',
                'errors' => $validator->errors(),
                'user_message' => 'Evento desconocido.'
            ], 422);
        }

        // Encontrar y devolver respuesta
        $workshop = Workshop::find($id);

        return response()->json([
            'success' => true,
            'message' => 'EVENT_READ',
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
        $workshops = Workshop::orderBy('type', 'asc')->get();

        return response()->json([
            'success' => true,
            'message' => 'EVENTS_READ',
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
            'type',
            'name',
            'prefix_code',
            'donation',
            'description', 
            'route',
            'rec_fee_online',
            'rec_fee_presencial',
            'rec_fee_business',
            'rec_fee_online_kits'
        );

        // Validar input
        $validator = Validator::make($input_data, [
            'type' => 'required',
            'name' => 'required',
            'prefix_code' => 'required',
            'donation' => 'nullable|numeric',
            'description' => 'nullable', 
            'rec_fee_online' => 'nullable',
            'rec_fee_presencial' => 'nullable',
            'rec_fee_business' => 'nullable',
            'rec_fee_online_kits' => 'nullable'
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
            ['type', '=', $input_data['type']],
            ['prefix_code', '=', $input_data['prefix_code']]
        ])->exists();

        if($exists)
        {
            return response()->json([
                'success' => false,
                'message' => 'RECORD_EXISTS',
                'input_data' => $input_data,
                'errors' => ['type, prefix_code' => 'The type and prefix_code has been previously recorded together'],
                'user_message' => 'Evento previamente registrado (codigo: ' . $input_data['prefix_code'] . ').'
            ], 422);
        }

        // Crear registro y devolver respuesta
        $workshop = Workshop::create($input_data);
        $workshop->save();

        return response()->json([
            'success' => true,
            'message' => 'WORKSHOP_STORE',
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
            'type',
            'name',
            'prefix_code',
            'donation',
            'description', 
            'route',
            'rec_fee_online',
            'rec_fee_presencial',
            'rec_fee_business',
            'rec_fee_online_kits'
        );

        // Validar input
        $validator = Validator::make($input_data, [
            'type' => 'required',
            'name' => 'required',
            'prefix_code' => 'required',
            'donation' => 'nullable|numeric',
            'description' => 'nullable', 
            'rec_fee_online' => 'nullable',
            'rec_fee_presencial' => 'nullable',
            'rec_fee_business' => 'nullable',
            'rec_fee_online_kits' => 'nullable'
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
            ['type', '=', $input_data['type']],
            ['prefix_code', '=', $input_data['prefix_code']]
        ])->exists();

        if($exists)
        {
            return response()->json([
                'success' => false,
                'message' => 'RECORD_EXISTS',
                'input_data' => $input_data,
                'errors' => ['type, prefix_code' => 'The type and prefix_code has been previously recorded together'],
                'user_message' => 'Evento previamente registrado (codigo: ' . $input_data['prefix_code'] . ').'
            ], 422);
        }

        // Encontrar registro, actualizar y devolver respuesta
        $workshop = Workshop::find($id);
        $workshop->update($input_data);

        return response()->json([
            'success' => true,
            'message' => 'EVENT_UPDATE',
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
            'id' => 'required|exists:events,id'
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'RECORD_NOT_FOUND',
                'errors' => $validator->errors(),
                'user_message' => 'Evento desconocido.'
            ], 422);
        }

        // Eliminar registro y devolver respuesta
        $workshop = Workshop::find($id);
        $workshop->delete();

        return response()->json([
            'success' => true,
            'message' => 'EVENT_DELETE',
            'data' => $workshop
        ], 200);
    }
}
