<?php

namespace App\Http\Controllers;

use Validator;
use App\Answer;
use App\Permission;
use App\User;
use App\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuestionsController extends Controller
{
    public function index()
    {
        $profile = User::findProfile();
        $perm = Permission::permView($profile, 18);
        $perm_btn = Permission::permBtns($profile, 18);
        
        if($perm == 0) {
            return redirect()->route('home');
        } else {
            return view('admin.questions', compact('perm_btn'));
        }
    }

    /**
     * Read the specified resource.
     *
     * @param  Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function question(Request $request, $id)
    {
        // Validar existencia
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|exists:questions,id'
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'RECORD_NOT_FOUND',
                'errors' => $validator->errors(),
                'user_message' => 'Pregunta desconocida.'
            ], 422);
        }

        // Encontrar y devolver respuesta
        $question = Question::find($id);
        $s = Answer::find($id);
        $answers = Answer::select(
            'answers.id',
            'answers.text',
            'answers.id_question',
            'answers.id_next_question'
        )
        ->where('answers.id_question', '=', $question->id)
        ->get();

        

        $question->answers = $answers;

        return response()->json([
            'success' => true,
            'message' => 'QUESTION_READ',
            'data' => $question
        ], 200);
    }

    /**
     * Read the specified resource.
     *
     * @param  Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function questions(Request $request)
    {
        // Recolectar todos los registros y devolver respuesta
        $question = Question::orderBy('sentence', 'asc')->get();

        return response()->json([
            'success' => true,
            'message' => 'QUESTIONS_READ',
            'data' => $question
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
            'sentence',
            'first_question',
            'answers'
        );

        // Validar input
        $validator = Validator::make($input_data, [
            'sentence' => 'required',
            'first_question' => 'required',
            'answers'
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

        $exists = Question::where([
            ['sentence', '=', $input_data['sentence']],
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
        DB::beginTransaction();
        $question = Question::create($input_data);

        //
        try {
            foreach ($input_data['answers'] as $answer) {
                $answer['id_question'] = $question->id;
                Answer::create($answer);
            }
        } catch (\Exception $e) {

        }
        


        $question->save();
        DB::commit();



        return response()->json([
            'success' => true,
            'message' => 'QUESTION_STORE',
            'data' => $question
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
            'sentence',
            'first_question',
            'answers'
        );

        // Validar input
        $validator = Validator::make($input_data, [
            'sentence' => 'required',
            'first_question' => 'required',
            'answers'
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


        $exists = Question::where([
            ['id', '!=', $id],
            ['sentence', '=', $input_data['sentence']],
        ])->exists();

        if($exists) {
            return response()->json([
                'success' => false,
                'message' => 'RECORD_EXISTS',
                'input_data' => $input_data,
                'errors' => ['question' => 'The question has been previously recorded.'],
                'user_message' => 'Pregunta previamente registrada.'
            ], 422);
        }

        // Encontrar registro, actualizar y devolver respuesta
        $question = Question::find($id);
        DB::beginTransaction();

        //$question->update($input_data);
        $question->update($request->all());
        Answer::where('id_question', '=', $question->id)->delete();


        try {
            foreach ($input_data['answers'] as $answer) {
                $answer['id_question'] = $question->id;
                Answer::create($answer);
            }
        } catch (\Exception $e) { }    
        

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'QUESTION_UPDATE',
            'data' => $question
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
            'id' => 'required|exists:questions,id'
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
        $question = Question::find($id);

        // Encontrar dependencias y borrar registros de manera segura; en caso contrario devolver error
        DB::beginTransaction();
        try {
            Answer::where('id_question', '=', $question->id)->delete();
            $question->delete();

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
            'message' => 'QUESTION_DELETE',
            'data' => $question
        ], 200);
    }
}
