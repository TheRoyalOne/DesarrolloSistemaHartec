<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Permission;
use App\User;
use App\Sponsor;
class SponsorsController extends Controller
{
    public function index()
    {
        $profile = User::findProfile();
        $perm = Permission::permView($profile, 22);
        $perm_btn = Permission::permBtns($profile, 22);
        $users = User::get();
        if($perm == 0) {
            return redirect()->route('home');
        } else {
            return view('admin.sponsors', compact('perm_btn', 'users'));
        }
    }

    /**
     * Read the specified resource.
     *
     * @param  Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sponsor(Request $request, $id)
    {
        // Validar existencia
        $validator = Validator::make(['id' => $id], [
            'id' => 'required'
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'RECORD_NOT_FOUND',
                'errors' => $validator->errors(),
                'user_message' => 'Patrocinador desconocido.'
            ], 422);
        }

        // Encontrar y devolver respuesta
        $sponsor = Sponsor::find($id);

        return response()->json([
            'success' => true,
            'message' => 'SPONSOR_READ',
            'data' => $sponsor
        ], 200);
    }

    /**
     * Read the specified resource.
     *
     * @param  Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sponsors(Request $request)
    {
        // Recolectar todos los registros y devolver respuesta
        $sponsors = Sponsor::orderBy('enterprise_name', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'SPONSORS_READ',
            'data' => $sponsors
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
            'enterprise_name',
            'social_reason',
            'rfc',
            'prefix_code_event',
            'name',
            'firstname',
            'lastname',
            'email',
            'cellphone',
            'address',
            'numE',
            'numI',
            'colony',
            'postal_code',
            'num_employees',
            'observations',
            'web_site',
            'logotype_name',
            'logotype_url',
            'id_event_adoptions',
            'id_event_workshops',
            'id_packages',
            'roll',
            'size',
            'end_sponsorship',
            'user'
        );

        // Validar input
        $validator = Validator::make($input_data, [
            'enterprise_name' => 'required',
            'social_reason' => 'required',
            'rfc' => 'nullable',
            'prefix_code_event' => 'required',
            'name' => 'required',
            'firstname' => 'nullable',
            'lastname' => 'nullable',
            'email' => 'nullable',
            'cellphone' => 'nullable',
            'address' => 'nullable',
            'numE' => 'nullable',
            'numI' => 'nullable',
            'colony' => 'nullable',
            'postal_code' => 'nullable',
            'num_employees' => 'nullable',
            'observations' => 'nullable',
            'web_site' => 'nullable',
            'logotype' => 'nullable|mimes:jpeg,jpg,bmp,png',
            'id_event_adoptions' => 'nullable',
            'id_event_workshops' => 'nullable',
            'id_packages' => 'nullable'
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

        $exists = Sponsor::where([
            ['enterprise_name', '=', $input_data['enterprise_name']],
        ])->exists();

        if($exists)
        {
            return response()->json([
                'success' => false,
                'message' => 'RECORD_EXISTS',
                'input_data' => $input_data,
                'errors' => ['enterprise_name' => 'The enterprise_name has been previously taked.'],
                'user_message' => 'Patrocinador previamente registrado.'
            ], 422);
        }

        // Crear registro y devolver respuesta
        $sponsor = Sponsor::create($input_data);
        $host = $request->getSchemeAndHttpHost();

        if (isset($request->logotype)) {
            $extension = $request->file('logotype')->getClientOriginalExtension();

            $file_name = str_replace(' ', '-', $request->institution_name) . "." . $extension;
            $request->file('logotype')->move('img/sponsors', $file_name);
            $sponsor->logotype_name = $file_name;
            
            $sponsor->logotype_url = $host . '/public/img/sponsors/' . $file_name;
        }
        
        if (!$sponsor->logotype_url) {
            $sponsor->logotype_url = $host . '/public/img/imagen_no_disponible.png';
        }

        $sponsor->save();

        return response()->json([
            'success' => true,
            'message' => 'SPONSOR_STORE',
            'data' => $sponsor
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
            'enterprise_name',
            'social_reason',
            'rfc',
            'prefix_code_event',
            'enterpride_contact',
            'name',
            'firstname',
            'lastname',
            'email',
            'cellphone',
            'address',
            'numE',
            'numI',
            'colony',
            'postal_code',
            'num_employees',
            'observations',
            'web_site',
            'logotype_name',
            'logotype_url',
            'id_event_adoptions',
            'id_event_workshops',
            'id_packages',
            'roll',
            'size',
            'end_sponsorship',
            'user'
        );

        // Validar input
        $validator = Validator::make($input_data, [
            'enterprise_name',
            'social_reason',
            'rfc',
            'prefix_code_event',
            'enterpride_contact',
            'name',
            'firstname',
            'lastname',
            'email',
            'cellphone',
            'address',
            'numE',
            'numI',
            'colony',
            'postal_code',
            'num_employees',
            'observations',
            'web_site',
            'logotype' => 'nullable|mimes:jpeg,jpg,bmp,png',
            'id_event_adoptions',
            'id_event_workshops',
            'id_packages',
            'roll',
            'size',
            'end_sponsorship',
            'user'
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

        $exists = Sponsor::where([
            ['id', '!=', $id],
            ['enterprise_name', '=', $input_data['enterprise_name']],
        ])->exists();

        if($exists)
        {
            return response()->json([
                'success' => false,
                'message' => 'RECORD_EXISTS',
                'input_data' => $input_data,
                'errors' => ['enterprise_name' => 'The enterprise_name has been previously taked.'],
                'user_message' => 'Patrocinador previamente registrado.'
            ], 422);
        }

        // Encontrar registro, actualizar y devolver respuesta
        $sponsor = Sponsor::find($id);
        $host = $request->getSchemeAndHttpHost();

        if (isset($request->logotype)) {
            if ($sponsor->logotype_name &&  file_exists('img/sponsors/' . $sponsor->logotype_name)) {
                unlink('img/sponsors/' . $sponsor->logotype_name);
            }

            $extension = $request->file('logotype')->getClientOriginalExtension();

            $file_name = str_replace(' ', '-', $input_data['enterprise_name']) . "." . $extension;
            $request->file('logotype')->move('img/sponsors', $file_name);
            $input_data['logotype_name'] = $file_name;
            $input_data['logotype_url'] = $host . '/public/img/sponsors/' . $file_name;
        }

        $sponsor->update($input_data);

        return response()->json([
            'success' => true,
            'message' => 'SPONSOR_UPDATE',
            'data' => $sponsor
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
            'id' => 'required|exists:sponsors,id'
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'RECORD_NOT_FOUND',
                'errors' => $validator->errors(),
                'user_message' => 'Patrocinador desconocida.'
            ], 422);
        }

        // Eliminar registro y devolver respuesta
        $sponsor = Sponsor::find($id);
        if (file_exists('img/sponsors/' . $sponsor->logotype_name)) {
            unlink('img/sponsors/' . $sponsor->logotype_name);
        }
        $sponsor->delete();

        return response()->json([
            'success' => true,
            'message' => 'SPONSOR_DELETE',
            'data' => $sponsor
        ], 200);
    }
}
