<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Permission;
use App\User;
use App\Species;
use App\Spec;
use DB;
class SpeciesController extends Controller
{
    public function index()
    {
        $profile = User::findProfile();
        $specs = Spec::pluck('name','id');
        $perm = Permission::permView($profile, 23);
        $perm_btn = Permission::permBtns($profile, 23);

        if($perm == 0) {
            return redirect()->route('home');
        } else {
            return view('admin.species', compact('perm_btn', 'specs'));
        }
    }

    /**
     * Read the specified resource.
     *
     * @param  Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function species(Request $request, $id)
    {
        // Validar existencia
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|exists:species,id'
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'RECORD_NOT_FOUND',
                'errors' => $validator->errors(),
                'user_message' => 'Institución Educativa desconocida.'
            ], 422);
        }

        // Encontrar y devolver respuesta
        $species = Species::find($id);

        return response()->json([
            'success' => true,
            'message' => 'SPECIES_READ',
            'data' => $species
        ], 200);
    }

    /**
     * Read the specified resource.
     *
     * @param  Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function allSpecies(Request $request)
    {
        // Recolectar todos los registros y devolver respuesta
        $species = Species::orderBy('name', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'SPECIES_READ',
            'data' => $species
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
            'scientific_name',                         
            'recovery_fee_a',
            'recovery_fee_b',
            'recovery_fee_c',
            'recovery_fee_d',
            'spec_1',
            'spec_2',
            'spec_3',
            'spec_4',
            'spec_5',
            'spec_6',
            'observations',
            'picture'
        );

        // Validar input
        $validator = Validator::make($input_data, [
            'name' => 'required',
            'scientific_name' => 'required',                         
            'recovery_fee_a' => 'nullable|numeric',
            'recovery_fee_b' => 'nullable|numeric',
            'recovery_fee_c' => 'nullable|numeric',
            'recovery_fee_d' => 'nullable|numeric',
            'spec_1' => 'nullable',
            'spec_2' => 'nullable',
            'spec_3' => 'nullable',
            'spec_4' => 'nullable',
            'spec_5' => 'nullable',
            'spec_6' => 'nullable',
            'observations' => 'nullable',
            'picture' => 'nullable|mimes:jpeg,jpg,bmp,png'
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

        $exists = Species::where([
            ['scientific_name', '=', $input_data['scientific_name']],
        ])->exists();

        if($exists)
        {
            return response()->json([
                'success' => false,
                'message' => 'RECORD_EXISTS',
                'input_data' => $input_data,
                'errors' => ['scientific_name' => 'The scientific_name has been previously taked.'],
                'user_message' => 'Especie previamente registrada.'
            ], 422);
        }

        // Crear registro y devolver respuesta
        $species = Species::create($input_data);

        // Procesando imagen
        $host = $request->getSchemeAndHttpHost();

        if (isset($request->picture)) {
            $extension = $request->file('picture')->getClientOriginalExtension();

            $file_name = str_replace(' ', '-', $request->scientific_name) . "." . $extension;
            $request->file('picture')->move('img/species', $file_name);
            $species->picture_name = $file_name;
            $species->picture_url = $host . '/public/img/species/' . $file_name;
        }

        if (!$species->picture_url) {
            $species->picture_url = $host . '/public/img/imagen_no_disponible.png';
        }

        $species->save();

        return response()->json([
            'success' => true,
            'message' => 'SPECIES_STORE',
            'data' => $species
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
            'scientific_name',                         
            'recovery_fee_a',
            'recovery_fee_b',
            'recovery_fee_c',
            'recovery_fee_d',
            'spec_1',
            'spec_2',
            'spec_3',
            'spec_4',
            'spec_5',
            'spec_6',
            'observations',
            'picture'
        );

        // Validar input
        $validator = Validator::make($input_data, [
            'name' => 'required',
            'scientific_name' => 'required',                         
            'recovery_fee_a' => 'nullable',
            'recovery_fee_b' => 'nullable',
            'recovery_fee_c' => 'nullable',
            'recovery_fee_d' => 'nullable',
            'spec_1' => 'nullable',
            'spec_2' => 'nullable',
            'spec_3' => 'nullable',
            'spec_4' => 'nullable',
            'spec_5' => 'nullable',
            'spec_6' => 'nullable',
            'observations' => 'nullable',
            'picture' => 'nullable|mimes:jpeg,jpg,bmp,png'
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

        $exists = Species::where([
            ['id', '!=', $id],
            ['scientific_name', '=', $input_data['scientific_name']],
        ])->exists();

        if($exists)
        {
            return response()->json([
                'success' => false,
                'message' => 'RECORD_EXISTS',
                'input_data' => $input_data,
                'errors' => ['scientific_name' => 'The scientific_name has been previously taked.'],
                'user_message' => 'Especie previamente registrada.'
            ], 422);
        }

        // Encontrar registro, actualizar y devolver respuesta
        $species = Species::find($id);
        $host = $request->getSchemeAndHttpHost();

        if (isset($request->picture)) {
            if ($species->picture_name && file_exists('img/species/' . $species->picture_name)) {
                unlink('img/species/' . $species->picture_name);
            }

            $extension = $request->file('picture')->getClientOriginalExtension();

            $file_name = str_replace(' ', '-', $input_data['scientific_name']) . "." . $extension;
            $request->file('picture')->move('img/species', $file_name);
            $input_data['picture_name'] = $file_name;
            
            $input_data['picture_url'] = $host . '/public/img/species/' . $file_name;
        }

        $species->update($input_data);

        return response()->json([
            'success' => true,
            'message' => 'SPECIES_UPDATE',
            'data' => $species
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
            'id' => 'required|exists:species,id'
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'RECORD_NOT_FOUND',
                'errors' => $validator->errors(),
                'user_message' => 'Institución Educativa desconocida.'
            ], 422);
        }

        // Eliminar registro y devolver respuesta
        $species = Species::find($id);
        if ($species->picture_name && file_exists('img/species/' . $species->picture_name)) {
            unlink('img/species/' . $species->picture_name);
        }
        $species->delete();

        return response()->json([
            'success' => true,
            'message' => 'SPECIES_DELETE',
            'data' => $species
        ], 200);
    }
}
