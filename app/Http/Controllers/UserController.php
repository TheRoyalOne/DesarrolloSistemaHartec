<?php

namespace App\Http\Controllers;
use Validator;
use Illuminate\Support\Facades\Hash;
use App\Permission;
use App\Profile;
use App\User;
use App\EducativeInstitution;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        // Administration vars
        $profile = User::findProfile();
        $perm = Permission::permView($profile, 8);
        $perm_btn = Permission::permBtns($profile, 8);

        // view data resources
        $profiles = Profile::pluck('name', 'id');
        $educative_institutions = EducativeInstitution::pluck('institution_name as name', 'id');

        if($perm == 0) {
            return redirect()->route('home');
        } else {
            return view('admin.users', compact('perm_btn', 'profiles', 'educative_institutions'));
        }
    }

    /**
     * Read the specified resource.
     *
     * @param  Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function user(Request $request, $id)
    {
        // Validar existencia
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|exists:users,id'
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'EVENT_NOT_FOUND',
                'errors' => $validator->errors(),
                'user_message' => 'Usuario desconocido.'
            ], 422);
        }

        // Encontrar y devolver respuesta
        $user = User::find($id);

        return response()->json([
            'success' => true,
            'message' => 'USER_READ',
            'data' => $user
        ], 200);
    }

    /**
     * Read the specified resource.
     *
     * @param  Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function users(Request $request)
    {
        // Recolectar todos los registros y devolver respuesta
        $workshops = User::select('users.*','profiles.name as profile')
            ->join('profiles','users.profile_id','=','profiles.id')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'USERS_READ',
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
            'username',
            'name',
            'lastname',
            'lastname2',
            'email',
            'password',
            'profile_id',
            'cellphone',
            'job',
            'educative_institution_id'
        );

        // Validar input
        $validator = Validator::make($input_data, [
            'username' => 'required',
            'name' => 'required',
            'lastname' => 'nullable',
            'lastname2' => 'nullable',
            'email' => 'nullable',
            'password' => 'required',
            'profile_id' => 'required|exists:profiles,id',
            'cellphone' => 'nullable',
            'job' => 'nullable',
            'educative_institution_id' => 'nullable|exists:educative_institutions,id'
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

        $exists = User::where([
            ['username', '=', $input_data['username']],
        ])->exists();

        if($exists)
        {
            return response()->json([
                'success' => false,
                'message' => 'RECORD_EXISTS',
                'input_data' => $input_data,
                'errors' => ['username' => 'The username has been taked'],
                'user_message' => 'Usuario previamente registrado.'
            ], 422);
        }

        // Crear registro y devolver respuesta
        $input_data['password'] = Hash::make($input_data['password']);
        $user = User::create($input_data);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'USER_STORE',
            'data' => $user
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        // Recolectar input
        $input_data = $request->only(
            'username',
            'name',
            'lastname',
            'lastname2',
            'email',
            'password',
            'profile_id',
            'cellphone',
            'job',
            'educative_institution_id'
        );

        // Validar input
        $validator = Validator::make($input_data, [
            'username' => 'required',
            'name' => 'required',
            'lastname' => 'nullable',
            'lastname2' => 'nullable',
            'email' => 'nullable',
            'password' => 'required',
            'profile_id' => 'required|exists:profiles,id',
            'cellphone' => 'nullable',
            'job' => 'nullable',
            'educative_institution_id' => 'nullable|exists:educative_institutions,id'
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

        $exists = User::where([
            ['id', '!=', $id],
            ['username', '=', $input_data['username']],
        ])->exists();

        if($exists)
        {
            return response()->json([
                'success' => false,
                'message' => 'RECORD_EXISTS',
                'input_data' => $input_data,
                'errors' => ['username' => 'The username has been taked'],
                'user_message' => 'Usuario previamente registrado.'
            ], 422);
        }

        // Encontrar registro, actualizar y devolver respuesta
        $user = User::find($id);
        $input_data['password'] = Hash::make($input_data['password']);
        $user->update($input_data);

        return response()->json([
            'success' => true,
            'message' => 'WORKSHOP_STORE',
            'data' => $user
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
            'id' => 'required|exists:users,id'
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'RECORD_NOT_FOUND',
                'errors' => $validator->errors(),
                'user_message' => 'Usuario desconocido.'
            ], 422);
        }

        // Eliminar registro y devolver respuesta
        $user = User::find($id);
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'USER_DELETE',
            'data' => $user
        ], 200);
    }
}
