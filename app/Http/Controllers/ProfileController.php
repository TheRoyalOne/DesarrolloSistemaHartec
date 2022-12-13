<?php

namespace App\Http\Controllers;

use Validator;
use App\Permission;
use App\Profile;
use App\User;
use Illuminate\Http\Request;


class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $profile = User::findProfile();
        $perm = Permission::permView($profile, 7);
        $perm_btn =Permission::permBtns($profile, 7);

        if($perm == 0) {
            return redirect()->route('home');
        } else {
            return view('admin.profiles', compact('perm_btn'));
        }
    }

    /**
     * Read the specified resource.
     *
     * @param  Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function profile(Request $request, $id)
    {
        // Validar existencia
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|exists:profiles,id'
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'RECORD_NOT_FOUND',
                'errors' => $validator->errors(),
                'user_message' => 'Perfil desconocido.'
            ], 422);
        }

        // Encontrar y devolver respuesta
        $profile = Profile::find($id);

        return response()->json([
            'success' => true,
            'message' => 'PROFILE_READ',
            'data' => $profile
        ], 200);
    }

    /**
     * Read the specified resource.
     *
     * @param  Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function profiles(Request $request)
    {
        // Recolectar todos los registros y devolver respuesta
        $profiles = Profile::orderBy('name','asc')->get();

        return response()->json([
            'success' => true,
            'message' => 'PROFILES_READ',
            'data' => $profiles
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
            'name'
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

        $exists = Profile::where([
            ['name', '=', $input_data['name']],
        ])->exists();

        if($exists)
        {
            return response()->json([
                'success' => false,
                'message' => 'RECORD_EXISTS',
                'input_data' => $input_data,
                'errors' => ['name' => 'The name has been previously taked.'],
                'user_message' => 'Nombre previamente asignado a otro perfil.'
            ], 422);
        }

        // Crear registro y devolver respuesta
        $profile = Profile::create($input_data);
        $profile->save();

        return response()->json([
            'success' => true,
            'message' => 'PROFILE_STORE',
            'data' => $profile
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
            'name'
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

        $exists = Profile::where([
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
                'user_message' => 'Nombre previamente asignado a otro perfil.'
            ], 422);
        }

        // Encontrar registro, actualizar y devolver respuesta
        $profile = Profile::find($id);
        $profile->update($input_data);

        return response()->json([
            'success' => true,
            'message' => 'PROFILE_UPDATE',
            'data' => $profile
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
            'id' => 'required|exists:profiles,id'
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'RECORD_NOT_FOUND',
                'errors' => $validator->errors(),
                'user_message' => 'Perfil desconocido.'
            ], 422);
        }

        // Eliminar registro y devolver respuesta
        $profile = Profile::find($id);
        $profile->delete();

        return response()->json([
            'success' => true,
            'message' => 'PROFILE_DELETE',
            'data' => $profile
        ], 200);
    }

}
