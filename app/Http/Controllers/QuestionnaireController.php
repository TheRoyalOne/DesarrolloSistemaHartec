<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Questionnaire;
use App\Questions_key;
use App\Question;
use App\Answer;
use App\Permission;
use App\User;
use App\Sponsor;
use App\Adoption;
use App\Events;
use App\Buyers;
use App\WorkshopMaterial;
use Illuminate\Support\Facades\DB;

class QuestionnaireController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $profile = User::findProfile();
        $perm = Permission::permView($profile, 18);
        $perm_btn = Permission::permBtns($profile, 18);

        if($perm == 0) {
            return redirect()->route('home');
        } else {
            $Sponsor = Sponsor::select('enterprise_name as name', 'id')->get();
            $questions_init = Question::select(
                'id',
                'sentence'
            )
            ->where('first_question', '=', '1')
            ->get()
            ->keyBy('id');

            $questions = Question::orderBy('sentence', 'asc')->get();
            foreach ($questions as $q) {
                $answers = Answer::select(
                    'id',
                    'text',
                    'id_question',
                    'id_next_question'
                )
                ->where('id_question', '=', $q->id)
                ->get();
                $q->answers = $answers;
            }

            return view('admin.questionnaire', compact('perm_btn','Sponsor','questions_init', 'questions'));
        }
    }

    /**
     * Read the specified resource.
     *
     * @param  Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function questionnaire(Request $request, $id)
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
                'user_message' => 'Id desconocido.' // Posible modificacion / depende del caso
            ], 422);
        }

        if($id >= 0) {
            //id positivo: recupera nuevas adopciones para encuestar
            $news = self::getNewAdoptions($id);
            return response()->json([
                'success' => true,
                'message' => 'QUESTIONNAIRE_READ',
                'data' => $news
            ], 200);
        } else {
            $_ = explode(',', $id);
            if(count($_) == 4 and !is_numeric($_[2])) {
                $id    = $_[0];
                $quest = $_[1];
                $f_i   = $_[2];
                $f_f   = $_[3];
                //id negativo recupera viejos ya encuestados
                return self::getOlds(-$id, $quest, $f_i, $f_f);
            } else {
                $response = collect([]);

                for($i = 2; $i < count($_); $i++) {
                    $response->push(self::getAnswers($_[$i]));
                }

                return response()->json([
                    'success' => true,
                    'message' => 'QUESTIONNAIRE_READ',
                    'data' => $response
                ], 200);
            }
        }
    }

    private function getNewAdoptions($id) {
        // Encontrar y devolver adopcioens
        $adoptions = Adoption::select(
            //'sponsors.enterprise_name as sponsor_name',
            'events.id as event_id',
            'events.name as event_name',
            'adoptions.id as adoption_id'
        )
        ->where('adoptions.sponsor_id', '=', $id)
        ->join('events', 'events.id', '=', 'adoptions.event_id')
        ->get();

        foreach($adoptions as $adoption) {
            $buyers = Buyers::select(
            'buyers.id as buyer_id',
            'buyers.name as buyer_name',
            'buyers.id_adoption as adoption_id',
            'buyers.phone as buyer_phone',
            'buyers.Surveyed as buyer_Surveyed'
            )
            ->where('buyers.id_adoption', '=', $adoption->adoption_id)
            ->where('buyers.Surveyed', '=', '0')//solo los no encuestados
            ->join('adoptions', 'adoptions.id', '=', 'buyers.id_adoption')
            ->get();
            $adoption->buyers = $buyers;
        }

        return $adoptions;
    }

    private function getOlds($Tid, $questId, $f_i, $f_f) {
        if($Tid == 1.1) {
            // todas las encuestas (sin filtros)

            $questionnaire = Questionnaire::select(
                'questionnaire.id_question_key as anchor',
                'questionnaire.id_event as event_key',
                'questions_key.id_question as question_key',
                'buyers.id as buyer_id',
                'buyers.name as buyer_name'
            )
            ->join('buyers', 'buyers.id', '=', 'questionnaire.id_buyer')
            ->join('questions_key', 'questions_key.id', '=', 'questionnaire.id_question_key')
            ->where('questionnaire.updated_at', '>=', $f_i)
            ->where('questionnaire.updated_at', '<=', $f_f)
            ->get();
        } else {
            // Encontrar y devolver respuesta
            $sponsor = Sponsor::find($Tid);

            $questionnaire = Questionnaire::select(
                'questionnaire.id_question_key as anchor',
                'questionnaire.id_event as event_key',
                'questions_key.id_question as question_key',
                'buyers.id as buyer_id',
                'buyers.name as buyer_name'
            )
            ->where('questionnaire.id_sponsor', '=', $sponsor->id)
            ->join('buyers', 'buyers.id', '=', 'questionnaire.id_buyer')
            ->join('questions_key', 'questions_key.id', '=', 'questionnaire.id_question_key')
            ->where('questionnaire.updated_at', '>=', $f_i)
            ->where('questionnaire.updated_at', '<=', $f_f)
            ->get();
        }





        list($question, $ids) = self::getQuestion($questId);
        $questions = collect([$question]);


        while($ids->count() > 0) {
            //nueva recoleccion de ids
            $idsTemp = collect([]);

            //si el siguiente ya esta no se debe agregar de nuevo
            foreach($ids->unique() as $id) {
                //si el Siguiente es -1 no existe
                if($id != -1) {
                    list($qt, $it) = self::getQuestion($id);
                    //agregar la pregunta y los ids obtenidos al temporal
                    $questions->push($qt);
                    $idsTemp = $idsTemp->concat($it);
                }
            }
            //eliminar los ids anteriores y agregar los nuevos
            $ids = $idsTemp;
        }

        //->sortBy('question', SORT_DESC)
        return response()->json([
            'success' => true,
            'message' => 'QUESTIONNAIRE_READ',
            'data' => [$questionnaire, $questions->unique()->values()->all()]
        ], 200);
    }

    private function getAnswers($anchor) {
        if (!strlen($anchor)) return [];

        $qk = Questions_key::find($anchor);
        $questions_keys = collect([$qk]);

        $next = $qk->id_next;

        while($next != -1) {
            $qk = Questions_key::find($next);
            $questions_keys->push($qk);
            $next = $qk->id_next;
        }

        return $questions_keys;
    }

    private function getQuestion($id) {
        $question = Question::find(
            $id,
            [
            'id as question',
            'sentence as text'
            ]
        );

        $question->answers = Answer::select(
            'id',
            'text',
            'id_next_question as next'
        )
        ->where('answers.id_question', '=', $id)
        ->where('answers.deleted_at', '=', null)
        ->get();

        $ids = collect([]);

        foreach($question->answers as $answer) {
            $ids->push($answer->next);
        }

        return array($question, $ids);
    }

    /**
     * Read the specified resource.
     *
     * @param  Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function questionnaires(Request $request)
    {
        // Recolectar todos los registros y devolver respuesta

        $sponsor = Sponsor::get();
        $questions_key = Questions_key::get();
        $questions = Question::where('first_question', '=', 1)->get();
        $answers = Answer::get();
        $questionnaire = Questionnaire::select(
            'questionnaire.id',
            //'questionnaire.id_question_key',
            //'questionnaire.id_sponsor',
            'sponsors.enterprise_name as sponsor_name',
            'questions.sentence as question_name',
            'questions_key.id_question',
            //'questions_key.id_answer',
            'answers.text as answer_text',
            'questions_key.id_next as key'

        )
        ->join('sponsors', 'sponsors.id', '=', 'questionnaire.id_sponsor')
        ->join('questions_key', 'questions_key.id', '=', 'questionnaire.id_question_key')
        ->join('questions', 'questions.id', '=', 'questions_key.id_question')
        ->join('answers', 'answers.id', '=', 'questions_key.id_answer')
        ->get();


        return response()->json([
            'success' => true,
            'message' => 'QUESTIONNAIRE_READ',
            'data' => $questionnaire
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
            'id_sponsor',
            'id_buyer',
            'id_event'
            //'all_questions' //obtener el ancla
        );

        // Validar input
        $validator = Validator::make($input_data, [
            'id_sponsor' => 'required',
            'id_buyer' => 'required',
            'id_event' => 'required'
            // 'all_questions' => 'required',
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

        DB::beginTransaction();
        $id = -1;
        //subir la lista de question keys (invertilo primero)
        foreach (array_reverse($request->all_questions) as $question) {
            // id_next -> este se genera a partir de la question generada
            $question['id_next'] = $id;
            $id = Questions_key::create($question)->id;
        }

        //obtener el ancla antes de crearlo
        $input_data['id_question_key'] = $id;
        $questionnaire = Questionnaire::create($input_data);
        //cambiar estado de beneficiario a encuestado
        //Buyers::find($input_data['id_buyer'])->update(['Surveyed' => 1]);
        Buyers::where('id', $input_data['id_buyer'])
        ->update(['Surveyed' => 1]);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'QUESTIONNAIRE_STORE',
            'data' => $request
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
            'id',
            'id_question_key',
            'id_sponsor'
        );

        // Validar input
        $validator = Validator::make($input_data, [
            'id' => 'required',
            'id_question_key' => 'required',
            'id_sponsor' => 'required'
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


        // Encontrar registro, actualizar y devolver respuesta
        $questionnaire = Questionnaire::find($id);
        DB::beginTransaction();


        /*$materialLeaving->update($input_data);
        PivotLeavingMaterial::where('material_leaving_id', '=', $materialLeaving->id)->delete();

        foreach ($input_data['leaving_material'] as $leaving_material) {
            $leaving_material['material_leaving_id'] = $materialLeaving->id;
            PivotLeavingMaterial::create($leaving_material);
        }*/

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'MATERIAL_LEAVINGS_UPDATE',
            'data' => $questionnaire
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
            'id' => 'required'
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'RECORD_NOT_FOUND',
                'errors' => $validator->errors(),
                'user_message' => 'Salida de Material desconocida.'
            ], 422);
        }

        // Eliminar registro y devolver respuesta
        $questionnaire = Questionnaire::find($id);

        DB::beginTransaction();
        /*try {
            pivotLeavingMaterial::where('material_leaving_id', '=', $questionnaire->id)->delete();
            $questionnaire->delete();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'DB_ERROR',
                'errors' => $e,
                'user_message' => 'Ocurrio un error al intentar escribir en la Base de Datos.'
            ], 422);
        }*/

        return response()->json([
            'success' => true,
            'message' => 'MATERIAL_LEAVINGS_DELETE',
            'data' => $questionnaire
        ], 200);
    }
}
