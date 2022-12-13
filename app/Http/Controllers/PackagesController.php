<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Permission;
use App\User;
use App\Package;
use App\AdoptionEvent;
use App\WorkshopEvent;

class PackagesController extends Controller
{
    public function index()
    {
        $profile = User::findProfile();
        $perm = Permission::permView($profile, 20);
        $perm_btn =Permission::permBtns($profile, 20);

        // $adoption = AdoptionEvent::pluck('name', 'id');
        $workshopEvents = WorkshopEvent::where('type', 'Taller')->pluck('name', 'id');
        $adoptionEvents = WorkshopEvent::where('type', 'Adopcion')->pluck('name', 'id');

        if($perm == 0) {
            return redirect()->route('home');
        } else {
            return view('admin.packages', compact('perm_btn', 'workshopEvents', 'adoptionEvents'));
        }
    }

    /**
     * Read the specified resource.
     *
     * @param  Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function package(Request $request, $id)
    {
        // Validar existencia
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|exists:packages,id'
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'RECORD_NOT_FOUND',
                'errors' => $validator->errors(),
                'user_message' => 'Respuesta desconocida.'
            ], 422);
        }

        // Encontrar y devolver respuesta
        $package = Package::find($id);

        return response()->json([
            'success' => true,
            'message' => 'PACKAGE_READ',
            'data' => $package
        ], 200);
    }

    /**
     * Read the specified resource.
     *
     * @param  Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function packages(Request $request)
    {
        // Recolectar todos los registros y devolver respuesta
        $packages = Package::orderBy('name', 'asc')->get();

        return response()->json([
            'success' => true,
            'message' => 'PACKAGES_READ',
            'data' => $packages
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
            'name',
            'description',
            'workshop_event_1_id',
            'workshop_event_2_id',
            'workshop_event_3_id',
            'workshop_event_4_id',
            'adoption_event_1_id',
            'adoption_event_2_id',
            'adoption_event_3_id',
            'adoption_event_4_id'
        );

        // Validar input
        $validator = Validator::make($input_data, [
            'name' => 'required',
            'description' => 'nullable',
            'workshop_event_1_id' => 'nullable',
            'workshop_event_2_id' => 'nullable',
            'workshop_event_3_id' => 'nullable',
            'workshop_event_4_id' => 'nullable',
            'adoption_event_1_id' => 'nullable',
            'adoption_event_2_id' => 'nullable',
            'adoption_event_3_id' => 'nullable',
            'adoption_event_4_id' => 'nullable'
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

        $exists = Package::where([
            ['name', '=', $input_data['name']],
        ])->exists();

        if($exists)
        {
            return response()->json([
                'success' => false,
                'message' => 'RECORD_EXISTS',
                'input_data' => $input_data,
                'errors' => ['name' => 'The name has been previously taked.'],
                'user_message' => 'Paquete previamente registrado.'
            ], 422);
        }

        // Crear registro y devolver respuesta
        $package = Package::create($input_data);
        $package->save();

        return response()->json([
            'success' => true,
            'message' => 'PACKAGE_STORE',
            'data' => $package
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
            'name',
            'description',
            'workshop_event_1_id',
            'workshop_event_2_id',
            'workshop_event_3_id',
            'workshop_event_4_id',
            'adoption_event_1_id',
            'adoption_event_2_id',
            'adoption_event_3_id',
            'adoption_event_4_id'
        );

        // Validar input
        $validator = Validator::make($input_data, [
            'name' => 'required',
            'description' => 'nullable',
            'workshop_event_1_id' => 'nullable',
            'workshop_event_2_id' => 'nullable',
            'workshop_event_3_id' => 'nullable',
            'workshop_event_4_id' => 'nullable',
            'adoption_event_1_id' => 'nullable',
            'adoption_event_2_id' => 'nullable',
            'adoption_event_3_id' => 'nullable',
            'adoption_event_4_id' => 'nullable'
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

        $exists = Package::where([
            ['id', '!=', $id],
            ['name', '=', $input_data['name']],
        ])->exists();

        if($exists)
        {
            return response()->json([
                'success' => false,
                'message' => 'RECORD_EXISTS',
                'input_data' => $input_data,
                'errors' => ['name' => 'The name has been previously taked.'],
                'user_message' => 'Paquete previamente registrado.'
            ], 422);
        }

        // Encontrar registro, actualizar y devolver respuesta
        $package = Package::find($id);
        $package->update($input_data);

        return response()->json([
            'success' => true,
            'message' => 'PACKAGE_UPDATE',
            'data' => $package
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
            'id' => 'required|exists:packages,id'
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'RECORD_NOT_FOUND',
                'errors' => $validator->errors(),
                'user_message' => 'Respuesta desconocida.'
            ], 422);
        }

        // Eliminar registro y devolver respuesta
        $packages = Package::find($id);
        $packages->delete();

        return response()->json([
            'success' => true,
            'message' => 'PACKAGE_DELETE',
            'data' => $packages
        ], 200);
    }
}
