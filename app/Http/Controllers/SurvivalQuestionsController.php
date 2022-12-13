<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Validator;
use App\Answer;
use App\Permission;
use App\User;
use App\SurvivalQuestion;
use Illuminate\Http\Request;

class SurvivalQuestionsController extends Controller
{
    public function index()
    {
        $profile = User::findProfile();
        $perm = Permission::permView($profile, 18);
        $perm_btn = Permission::permBtns($profile, 18);
        if($perm == 0) {
            return redirect()->route('home');
        } else {
            return view('admin.survivalquestions', compact('perm_btn'));
        }
    }

    /**
     * Read the specified resource.
     *
     * @param  Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function survivalQuestion(Request $request, $id)
    {
        // Validar existencia
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|exists:survival_questions,id'
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'RECORD_NOT_FOUND',
                'errors' => $validator->errors(),
                'user_message' => 'Pregunta de sobrevivencia desconocida.'
            ], 422);
        }

        // Encontrar y devolver respuesta
        $survival_question = SurvivalQuestion::find($id);

        return response()->json([
            'success' => true,
            'message' => 'SURVIVAL_QUESTION_READ',
            'data' => $survival_question
        ], 200);
    }

    /**
     * Read the specified resource.
     *
     * @param  Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function survivalQuestions(Request $request)
    {
        // Recolectar todos los registros y devolver respuesta
        $survival_question = SurvivalQuestion::orderBy('question','asc')->get();

        return response()->json([
            'success' => true,
            'message' => 'SURVIVAL_QUESTIONS_READ',
            'data' => $survival_question
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
            'question'
        );

        // Validar input
        $validator = Validator::make($input_data, [
            'question' => 'required',
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

        $exists = SurvivalQuestion::where([
            ['question', '=', $input_data['question']],
        ])->exists();

        if($exists)
        {
            return response()->json([
                'success' => false,
                'message' => 'RECORD_EXISTS',
                'input_data' => $input_data,
                'errors' => ['question' => 'The question has been previously recorded.'],
                'user_message' => 'Pregunta previamente registrada.'
            ], 422);
        }

        // Crear registro y devolver respuesta
        $survival_question = SurvivalQuestion::create($input_data);
        $survival_question->save();

        return response()->json([
            'success' => true,
            'message' => 'SURVIVAL_QUESTION_STORE',
            'data' => $survival_question
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
            'question'
        );

        // Validar input
        $validator = Validator::make($input_data, [
            'question' => 'required',
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

        $exists = SurvivalQuestion::where([
            ['id', '!=', $id],
            ['question', '=', $input_data['question']],
        ])->exists();

        if($exists)
        {
            return response()->json([
                'success' => false,
                'message' => 'RECORD_EXISTS',
                'input_data' => $input_data,
                'errors' => ['question' => 'The question has been previously recorded.'],
                'user_message' => 'Pregunta previamente registrada.'
            ], 422);
        }

        // Encontrar registro, actualizar y devolver respuesta
        $survival_question = SurvivalQuestion::find($id);
        $survival_question->update($input_data);

        return response()->json([
            'success' => true,
            'message' => 'SURVIVAL_QUESTION_UPDATE',
            'data' => $survival_question
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
            'id' => 'required|exists:survival_questions,id'
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'RECORD_NOT_FOUND',
                'errors' => $validator->errors(),
                'user_message' => 'Pregunta de Sobrevivencia desconocida.'
            ], 422);
        }

        // Encontrar registro
        $survival_question = SurvivalQuestion::find($id);

        // Encontrar dependencias y borrar registros de manera segura; en caso contrario devolver error
        DB::beginTransaction();
        try {
            Answer::where('survival_question_id', '=', $survival_question->id)->delete();
            $survival_question->delete();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'DB_ERROR',
                'errors' => $e,
                'user_message' => 'Ocurrio un error al intentar escribir en la Base de Datos.'
            ], 422);
        }

        // Devolver respuesta de éxito
        return response()->json([
            'success' => true,
            'message' => 'SURVIVAL_QUESTION_DELETE',
            'data' => $survival_question
        ], 200);
    }
}
