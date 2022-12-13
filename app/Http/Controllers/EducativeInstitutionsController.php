<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Permission;
use App\User;
use App\EducativeInstitution;

class EducativeInstitutionsController extends Controller
{
    public function index()
    {
        $profile = User::findProfile();
        $perm = Permission::permView($profile,21);
        $perm_btn = Permission::permBtns($profile,21);

        if($perm == 0) {
            return redirect()->route('home');
        } else {
            return view('admin.educativeinstitutions', compact('perm_btn'));
        }
    }

    /**
     * Read the specified resource.
     *
     * @param  Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function educativeInstitution(Request $request, $id)
    {
        // Validar existencia
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|exists:educative_institutions,id'
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
        $educativeInstitution = EducativeInstitution::find($id);

        return response()->json([
            'success' => true,
            'message' => 'EDUCATIVE_INSTITUTION_READ',
            'data' => $educativeInstitution
        ], 200);
    }

    /**
     * Read the specified resource.
     *
     * @param  Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function educativeInstitutions(Request $request)
    {
        // Recolectar todos los registros y devolver respuesta
        $educativeInstitutions = EducativeInstitution::orderBy('institution_name', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'EDUCATIVE_INSTITUTIONS_READ',
            'data' => $educativeInstitutions
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
            'institution_name',
            'name',
            'firstname',
            'lastname',
            'email',
            'cellphone',
            'institutional_charge',
            'institutional_email',
            'institutional_phone',
            'address',
            'numE',
            'numI',
            'colony',
            'postal_code',
            'observations',
            'website',
            'logotype'
        );

        // Validar input
        $validator = Validator::make($input_data, [
            'institution_name' => 'required',
            'name' => 'required',
            'firstname' => 'nullable',
            'lastname' => 'nullable',
            'email' => 'nullable',
            'cellphone' => 'nullable',
            'institutional_charge' => 'nullable',
            'institutional_email' => 'nullable',
            'institutional_phone' => 'nullable',
            'address' => 'nullable',
            'numE' => 'nullable',
            'numI' => 'nullable',
            'colony' => 'nullable',
            'postal_code' => 'nullable',
            'observations' => 'nullable',
            'website' => 'nullable',
            'logotype' => 'nullable|mimes:jpeg,jpg,bmp,png'
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

        $exists = EducativeInstitution::where([
            ['institution_name', '=', $input_data['institution_name']],
        ])->exists();

        if($exists)
        {
            return response()->json([
                'success' => false,
                'message' => 'RECORD_EXISTS',
                'input_data' => $input_data,
                'errors' => ['institution_name' => 'The institution_name has been previously taked.'],
                'user_message' => 'Institución Educativa previamente registrada.'
            ], 422);
        }

        // Crear registro y devolver respuesta
        $educativeInstitution = EducativeInstitution::create($input_data);
        $host = $request->getSchemeAndHttpHost();

        if (isset($request->logotype) && $request->file('logotype')->isValid()) {
            $extension = $request->file('logotype')->getClientOriginalExtension();

            $file_name = str_replace(' ', '-', $request->institution_name) . "." . $extension;
            $request->file('logotype')->move('img/educative-institutions', $file_name);
            $educativeInstitution->logotype_name = $file_name;
            $educativeInstitution->logotype_url = $host . '/public/img/educative-institutions/' . $file_name;
        }
        
        if (!$educativeInstitution->logotype_url) {
            $educativeInstitution->logotype_url = $host . '/public/img/imagen_no_disponible.png';
        }

        $educativeInstitution->save();

        return response()->json([
            'success' => true,
            'message' => 'EDUCATIVE_INSTITUTION_STORE',
            'data' => $educativeInstitution
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
            'institution_name',
            'name',
            'firstname',
            'lastname',
            'email',
            'cellphone',
            'institutional_charge',
            'institutional_email',
            'institutional_phone',
            'address',
            'numE',
            'numI',
            'colony',
            'postal_code',
            'observations',
            'website',
            'logotype'
        );

        // Validar input
        $validator = Validator::make($input_data, [
            'institution_name' => 'required',
            'name' => 'required',
            'firstname' => 'nullable',
            'lastname' => 'nullable',
            'email' => 'nullable',
            'cellphone' => 'nullable',
            'institutional_charge' => 'nullable',
            'institutional_email' => 'nullable',
            'institutional_phone' => 'nullable',
            'address' => 'nullable',
            'numE' => 'nullable',
            'numI' => 'nullable',
            'colony' => 'nullable',
            'postal_code' => 'nullable',
            'observations' => 'nullable',
            'website' => 'nullable',
            'logotype' => 'nullable|mimes:jpeg,jpg,bmp,png'
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

        $exists = EducativeInstitution::where([
            ['id', '!=', $id],
            ['institution_name', '=', $input_data['institution_name']],
        ])->exists();

        if($exists)
        {
            return response()->json([
                'success' => false,
                'message' => 'RECORD_EXISTS',
                'input_data' => $input_data,
                'errors' => ['institution_name' => 'The institution_name has been previously taked.'],
                'user_message' => 'Institución Educativa previamente registrada.'
            ], 422);
        }

        // Encontrar registro, actualizar y devolver respuesta
        $educativeInstitution = EducativeInstitution::find($id);
        $host = $request->getSchemeAndHttpHost();

        if (isset($request->logotype)) {
            if ($educativeInstitution->logotype_name && file_exists('img/educative-institutions/' . $educativeInstitution->logotype_name)) {
                unlink('img/educative-institutions/' . $educativeInstitution->logotype_name);
            }

            $extension = $request->file('logotype')->getClientOriginalExtension();

            $file_name = str_replace(' ', '-', $input_data['institution_name']) . "." . $extension;
            $request->file('logotype')->move('img/educative-institutions', $file_name);
            $input_data['logotype_name'] = $file_name;
            $input_data['logotype_url'] = $host . '/public/img/educative-institutions/' . $file_name;
        }

        $educativeInstitution->update($input_data);

        return response()->json([
            'success' => true,
            'message' => 'EDUCATIVE_INSTITUTION_UPDATE',
            'data' => $educativeInstitution
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
            'id' => 'required|exists:educative_institutions,id'
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
        $educativeInstitution = EducativeInstitution::find($id);
        if ($educativeInstitution->logotype_name && file_exists('img/educative-institutions/' . $educativeInstitution->logotype_name)) {
            unlink('img/educative-institutions/' . $educativeInstitution->logotype_name);
        }
        $educativeInstitution->delete();

        return response()->json([
            'success' => true,
            'message' => 'EDUCATIVE_INSTITUTION_DELETE',
            'data' => $educativeInstitution
        ], 200);
    }
}