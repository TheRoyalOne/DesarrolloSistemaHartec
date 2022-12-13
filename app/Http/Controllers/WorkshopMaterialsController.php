<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Permission;
use App\User;
use App\WorkshopMaterial;

class WorkshopMaterialsController extends Controller
{
    public function index()
    {
        $profile = User::findProfile();
        $perm = Permission::permView($profile, 17);
        $perm_btn = Permission::permBtns($profile, 17);
        if($perm == 0) {
            return redirect()->route('home');
        } else {

            return view('admin.workshopmaterials', compact('perm_btn'));
        }
    }

    /**
     * Read the specified resource.
     *
     * @param  Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function workshopMaterial(Request $request, $id)
    {
        // Validar existencia
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|exists:workshop_materials,id'
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'RECORD_NOT_FOUND',
                'errors' => $validator->errors(),
                'user_message' => 'Material de Taller desconocido.'
            ], 422);
        }

        // Encontrar y devolver respuesta
        $workshopMaterial = WorkshopMaterial::find($id);

        return response()->json([
            'success' => true,
            'message' => 'WORKSHOP_MATERIAL_READ',
            'data' => $workshopMaterial
        ], 200);
    }

    /**
     * Read the specified resource.
     *
     * @param  Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function workshopMaterials(Request $request)
    {
        // Recolectar todos los registros y devolver respuesta
        $workshopMaterials = WorkshopMaterial::orderBy('name', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'WORKSHOP_MATERIALS_READ',
            'data' => $workshopMaterials
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
            'description'
        );

        // Validar input
        $validator = Validator::make($input_data, [
            'name',
            'description',
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

        $exists = WorkshopMaterial::where([
            ['name', '=', $input_data['name']],
        ])->exists();

        if($exists)
        {
            return response()->json([
                'success' => false,
                'message' => 'RECORD_EXISTS',
                'input_data' => $input_data,
                'errors' => ['name' => 'The name has been taked.'],
                'user_message' => 'Material de Taller previamente registrado.'
            ], 422);
        }

        // Crear registro y devolver respuesta
        $workshopMaterial = WorkshopMaterial::create($input_data);
        $workshopMaterial->save();

        return response()->json([
            'success' => true,
            'message' => 'WORKSHOP_MATERIAL_STORE',
            'data' => $workshopMaterial
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
            'description'
        );

        // Validar input
        $validator = Validator::make($input_data, [
            'name',
            'description',
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

        $exists = WorkshopMaterial::where([
            ['id', '!=', $id],
            ['name', '=', $input_data['name']],
        ])->exists();

        if($exists)
        {
            return response()->json([
                'success' => false,
                'message' => 'RECORD_EXISTS',
                'input_data' => $input_data,
                'errors' => ['name' => 'The name has been taked.'],
                'user_message' => 'Material de Taller previamente registrado.'
            ], 422);
        }

        // Encontrar registro, actualizar y devolver respuesta
        $workshopMaterial = WorkshopMaterial::find($id);
        $workshopMaterial->update($input_data);

        return response()->json([
            'success' => true,
            'message' => 'WORKSHOP_MATERIAL_UPDATE',
            'data' => $workshopMaterial
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
            'id' => 'required|exists:workshop_materials,id'
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
        $workshopMaterial = WorkshopMaterial::find($id);
        $workshopMaterial->delete();

        return response()->json([
            'success' => true,
            'message' => 'WORKSHOP_MATERIAL_DELETE',
            'data' => $workshopMaterial
        ], 200);
    }
}
