<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Permission;
use App\User;
use App\Answer;
use App\SurvivalQuestion;


class AnswersController extends Controller
{
    public function index()
    {
        $question = SurvivalQuestion::pluck('question', 'id');
        $profile = User::findProfile();
        $perm = Permission::permView($profile, 8);
        $perm_btn = Permission::permBtns($profile, 8);

        if($perm == 0) {
            return redirect()->route('home');
        } else {
            return view('admin.answers', compact('perm_btn', 'question'));
        }
    }

    /**
     * Read the specified resource.
     *
     * @param  Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function answer(Request $request, $id)
    {
        // Validar existencia
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|exists:answers,id'
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
        $answer = Answer::find($id);

        return response()->json([
            'success' => true,
            'message' => 'ANSWER_READ',
            'data' => $answer
        ], 200);
    }

    /**
     * Read the specified resource.
     *
     * @param  Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function answers(Request $request)
    {
        // Recolectar todos los registros y devolver respuesta
        $answers = Answer::select('answers.*','survival_questions.question')
            ->join('survival_questions', 'answers.survival_question_id', '=','survival_questions.id')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'ANSWERS_READ',
            'data' => $answers
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
            'answer_a',
            'answer_b',
            'answer_c',
            'answer_d',
            'answer_e',
            'survival_question_id'
        );

        // Validar input
        $validator = Validator::make($input_data, [
            'answer_a' => 'required',
            'answer_b' => 'required',
            'answer_c' => 'required',
            'answer_d' => 'nullable',
            'answer_e' => 'nullable',
            'survival_question_id' => 'required|exists:survival_questions,id'
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

        $exists = Answer::where([
            ['survival_question_id', '=', $input_data['survival_question_id']],
        ])->exists();

        if($exists)
        {
            return response()->json([
                'success' => false,
                'message' => 'RECORD_EXISTS',
                'input_data' => $input_data,
                'errors' => ['survival_question_id' => 'The survival_question_id has been previously selected for another answer.'],
                'user_message' => 'Pregunta previamente asignada a otra respuesta.'
            ], 422);
        }

        // Crear registro y devolver respuesta
        $answer = Answer::create($input_data);
        $answer->save();

        return response()->json([
            'success' => true,
            'message' => 'ANSWER_STORE',
            'data' => $answer
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
            'answer_a',
            'answer_b',
            'answer_c',
            'answer_d',
            'answer_e',
            'survival_question_id'
        );

        // Validar input
        $validator = Validator::make($input_data, [
            'answer_a' => 'required',
            'answer_b' => 'required',
            'answer_c' => 'required',
            'answer_d' => 'nullable',
            'answer_e' => 'nullable',
            'survival_question_id' => 'required|exists:survival_questions,id'
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

        $exists = Answer::where([
            ['id', '!=', $id],
            ['survival_question_id', '=', $input_data['survival_question_id']],
        ])->exists();

        if($exists)
        {
            return response()->json([
                'success' => false,
                'message' => 'RECORD_EXISTS',
                'input_data' => $input_data,
                'errors' => ['survival_question_id' => 'The survival_question_id has been previously selected for another answer'],
                'user_message' => 'Pregunta previamente asignada a otra respuesta.'
            ], 422);
        }

        // Encontrar registro, actualizar y devolver respuesta
        $answer = Answer::find($id);
        $answer->update($input_data);

        return response()->json([
            'success' => true,
            'message' => 'ANSWER_UPDATE',
            'data' => $answer
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
            'id' => 'required|exists:answers,id'
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
        $answer = Answer::find($id);
        $answer->delete();

        return response()->json([
            'success' => true,
            'message' => 'ANSWER_DELETE',
            'data' => $answer
        ], 200);
    }
}
