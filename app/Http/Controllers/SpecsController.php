<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Permission;
use App\Profile;
use App\Spec;
use App\User;

class SpecsController extends Controller
{
    public function index()
    {
        $profile = User::findProfile();
        $perm = Permission::permView($profile, 11);
        $perm_btn = Permission::permBtns($profile, 11);
        if($perm == 0) {
            return redirect()->route('home');
        } else {
            return view('admin.specs', compact('perm_btn'));
        }
    }

    /**
     * Read the specified resource.
     *
     * @param  Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function spec(Request $request, $id)
    {
        // Validar existencia
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|exists:specs,id'
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'RECORD_NOT_FOUND',
                'errors' => $validator->errors(),
                'user_message' => 'Especificación desconocida.'
            ], 422);
        }

        // Encontrar y devolver respuesta
        $spec = Spec::find($id);

        return response()->json([
            'success' => true,
            'message' => 'SPEC_READ',
            'data' => $spec
        ], 200);
    }

    /**
     * Read the specified resource.
     *
     * @param  Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function specs(Request $request){
        // Recolectar todos los registros y devolver respuesta
        $specs = Spec::orderBy('name', 'asc')->get();

        return response()->json([
            'success' => true,
            'message' => 'SPECS_READ',
            'data' => $specs
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
            'spec_frut1',
            'spec_frut2',
            'spec_frut3',
            'spec_orn1',
            'spec_orn2',
            'spec_orn3',
            'spec_conymad1',
            'spec_conymad2',
            'spec_conymad3',
            'spec_hojacad1',
            'spec_hojacad2',
            'spec_hojacad3',
            'spec_banq1',
            'spec_banq2',
            'spec_banq3',
            'spec_llan1',
            'spec_llan2',
            'spec_llan3',
            'spec_mac1',
            'spec_mac2',
            'spec_mac3',
            'spec_azotea1',
            'spec_azotea2',
            'spec_azotea3',
            'spec_int1',
            'spec_int2',
            'spec_int3',
            'spec_ext1',
            'spec_ext2',
            'spec_ext3',
            'spec_plant1',
            'spec_plant2',
            'spec_plant3',
            'spec_suc1',
            'spec_suc2',
            'spec_suc3'
        );

        // Validar input
        $validator = Validator::make($input_data, [
            'name' => 'required',
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

        $exists = Spec::where([
            ['name', '=', $input_data['name']],
        ])->exists();

        if($exists)
        {
            return response()->json([
                'success' => false,
                'message' => 'RECORD_EXISTS',
                'input_data' => $input_data,
                'errors' => ['name' => 'The name has been previously taked.'],
                'user_message' => 'Nombre previamente asignado a otra especificación.'
            ], 422);
        }

        // Crear registro y devolver respuesta
        $spec = Spec::create($input_data);
        $spec->save();

        return response()->json([
            'success' => true,
            'message' => 'SPEC_STORE',
            'data' => $spec
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
            'spec_frut1',
            'spec_frut2',
            'spec_frut3',
            'spec_orn1',
            'spec_orn2',
            'spec_orn3',
            'spec_conymad1',
            'spec_conymad2',
            'spec_conymad3',
            'spec_hojacad1',
            'spec_hojacad2',
            'spec_hojacad3',
            'spec_banq1',
            'spec_banq2',
            'spec_banq3',
            'spec_llan1',
            'spec_llan2',
            'spec_llan3',
            'spec_mac1',
            'spec_mac2',
            'spec_mac3',
            'spec_azotea1',
            'spec_azotea2',
            'spec_azotea3',
            'spec_int1',
            'spec_int2',
            'spec_int3',
            'spec_ext1',
            'spec_ext2',
            'spec_ext3',
            'spec_plant1',
            'spec_plant2',
            'spec_plant3',
            'spec_suc1',
            'spec_suc2',
            'spec_suc3'
        );

        // Validar input
        $validator = Validator::make($input_data, [
            'name' => 'required',
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

        $exists = Spec::where([
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
                'user_message' => 'Nombre previamente asignado a otra especificación.'
            ], 422);
        }

        // Encontrar registro, actualizar y devolver respuesta
        $spec = Spec::find($id);
        $spec->update($input_data);

        return response()->json([
            'success' => true,
            'message' => 'SPEC_UPDATE',
            'data' => $spec
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
            'id' => 'required|exists:specs,id'
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'RECORD_NOT_FOUND',
                'errors' => $validator->errors(),
                'user_message' => 'Especificación desconocida.'
            ], 422);
        }

        // Eliminar registro y devolver respuesta
        $spec = Spec::find($id);
        $spec->delete();

        return response()->json([
            'success' => true,
            'message' => 'SPEC_DELETE',
            'data' => $spec
        ], 200);
    }
}
