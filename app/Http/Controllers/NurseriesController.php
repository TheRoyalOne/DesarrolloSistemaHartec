<?php

namespace App\Http\Controllers;
use Validator;
use App\Permission;
use App\Nursery;
use App\User;
use App\Species;

use Illuminate\Http\Request;

class NurseriesController extends Controller
{
    public function index()
    {
        // Administration vars
        $profile = User::findProfile();
        $perm = Permission::permView($profile,12);
        $perm_btn = Permission::permBtns($profile,12);
    
        // view data resources
        $users = User::pluck('name', 'id');
        $species = Species::get();

        if($perm == 0) {
            return redirect()->route('home');
        } else {
            return view('admin.nurseries', compact('perm_btn', 'users', 'species'));
        }
    }

    /**
     * Read the specified resource.
     *
     * @param  Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function nursery(Request $request, $id)
    {
        // Validar existencia
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|exists:nurseries,id'
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'RECORD_NOT_FOUND',
                'errors' => $validator->errors(),
                'user_message' => 'Vivero desconocido.'
            ], 422);
        }

        // Encontrar y devolver respuesta
        $nursery = Nursery::find($id);

        return response()->json([
            'success' => true,
            'message' => 'NURESERY_READ',
            'data' => $nursery
        ], 200);
    }

    /**
     * Read the specified resource.
     *
     * @param  Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function nurseries(Request $request)
    {
        // Recolectar todos los registros y devolver respuesta
        $nurseries = Nursery::select('nurseries.*','users.name as responsable')
            ->join('users', 'users.id', '=', 'nurseries.responsable_user_id')
            ->orderBy('nurseries.name', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'NURSERIES_READ',
            'data' => $nurseries
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
            'responsable_user_id',
            'ubication',
            'edad'
        );

        // Validar input
        $validator = Validator::make($input_data, [
            'name' => 'required',
            'responsable_user_id' => 'required|exists:users,id',
            'ubication' => 'nullable'
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

        $exists = Nursery::where([
            ['name', '=', $input_data['name']],
        ])->exists();

        if($exists)
        {
            return response()->json([
                'success' => false,
                'message' => 'RECORD_EXISTS',
                'input_data' => $input_data,
                'errors' => ['name' => 'The name has been taked.'],
                'user_message' => 'Vivero previamente registrado.'
            ], 422);
        }

        // Crear registro y devolver respuesta
        $nursery = Nursery::create($input_data);
        $nursery->save();

        return response()->json([
            'success' => true,
            'message' => 'NURSERY_STORE',
            'data' => $nursery
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
            'responsable_user_id',
            'ubication'
        );

        // Validar input
        $validator = Validator::make($input_data, [
            'name' => 'required',
            'responsable_user_id' => 'required|exists:users,id',
            'ubication' => 'nullable'
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

        $exists = Nursery::where([
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
                'user_message' => 'Vivero previamente registrado.'
            ], 422);
        }

        // Encontrar registro, actualizar y devolver respuesta
        $nursery = Nursery::find($id);
        $nursery->update($input_data);

        return response()->json([
            'success' => true,
            'message' => 'NURSERY_UPDATE',
            'data' => $nursery
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
            'id' => 'required|exists:nurseries,id'
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'RECORD_NOT_FOUND',
                'errors' => $validator->errors(),
                'user_message' => 'Vivero desconocido.'
            ], 422);
        }

        // Eliminar registro y devolver respuesta
        $nursery = Nursery::find($id);
        $nursery->delete();

        return response()->json([
            'success' => true,
            'message' => 'NURSERY_DELETE',
            'data' => $nursery
        ], 200);
    }
}
