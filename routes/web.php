<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// use Illuminate\Routing\Route;


// Route::get('/', function () {
//     return view('auth/login');
// });

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');

Route::group(['prefix'=> 'admin', 'middleware' => 'auth'], function () {
    ////////// permisos
    Route::resource('permission', 'PermissionController');
    Route::get('permission/{id}/{id_seccion?}/{btn?}/{reference?}',[ 'uses' => 'PermissionController@update_store', 'as' => 'admin.permission.update_store']);

    // Rutas perfiles
    Route::prefix('profiles')->group(function () {
        Route::get('/index', 'ProfileController@index')->name('profiles-index');
        Route::get('/{id}', 'ProfileController@profile')->name('profile-read');
        Route::get('/', 'ProfileController@profiles')->name('profiles-read');
        Route::post('/', 'ProfileController@store')->name('profiles-store');
        Route::put('/{id}', 'ProfileController@update')->name('profiles-update');
        Route::delete('/{id}', 'ProfileController@destroy')->name('profiles-destroy');
    });

    // Rutas Usuarios
    Route::prefix('users')->group(function () {
        Route::get('/index', 'UserController@index')->name('users-index');
        Route::get('/{id}', 'UserController@user')->name('user-read');
        Route::get('/', 'UserController@users')->name('users-read');
        Route::post('/', 'UserController@store')->name('users-store');
        Route::put('/{id}', 'UserController@update')->name('users-update');
        Route::delete('/{id}', 'UserController@destroy')->name('users-destroy');
    });

    // Ruta Especies
    Route::prefix('species')->group(function () {
        Route::get('/index', 'SpeciesController@index')->name('species-index');
        Route::get('/{id}', 'SpeciesController@species')->name('species-read');
        Route::get('/', 'SpeciesController@allSpecies')->name('all-species-read');
        Route::post('/', 'SpeciesController@store')->name('species-store');
        Route::put('/{id}', 'SpeciesController@update')->name('species-update');
        Route::delete('/{id}', 'SpeciesController@destroy')->name('species-destroy');
    });

    // Rutas Especificaciones
    Route::prefix('specs')->group(function () {
        Route::get('/index', 'SpecsController@index')->name('specs-index');
        Route::get('/{id}', 'SpecsController@spec')->name('spec-read');
        Route::get('/', 'SpecsController@specs')->name('specs-read');
        Route::post('/', 'SpecsController@store')->name('specs-store');
        Route::put('/{id}', 'SpecsController@update')->name('specs-update');
        Route::delete('/{id}', 'SpecsController@destroy')->name('specs-destroy');
    });

    // Rutas Viveros
    Route::prefix('nurseries')->group(function () {
        Route::get('/index', 'NurseriesController@index')->name('nurseries-index');
        Route::get('/{id}', 'NurseriesController@nursery')->name('nursery-read');
        Route::get('/', 'NurseriesController@nurseries')->name('nurseries-read');
        Route::post('/', 'NurseriesController@store')->name('nurseries-store');
        Route::put('/{id}', 'NurseriesController@update')->name('nurseries-update');
        Route::delete('/{id}', 'NurseriesController@destroy')->name('nurseries-destroy');
    });

    //////// Rutas Tipos de Evento
    Route::resource('typeevent', 'TypeEventController');
    Route::get('typeevent/Getinfo/{id}', 'TypeEventController@Getinfo')->name('typeevent.Getinfo');
    Route::post('typeevent/actualizar', 'TypeEventController@actualizar')->name('typeevent.actualizar');

    //////// Rutas Eventos de adopción
    Route::resource('adoptionevents', 'AdoptionEventsController');
    Route::get('adoptionevents/Getinfo/{id}', 'AdoptionEventsController@Getinfo')->name('adoptionevents.Getinfo');
    Route::post('adoptionevents/actualizar', 'AdoptionEventsController@actualizar')->name('adoptionevents.actualizar');

    //////// Rutas Tipos Taller
    Route::resource('workshopstypes', 'WorkshopsTypesController');
    Route::get('workshopstypes/Getinfo/{id}', 'WorkshopsTypesController@Getinfo')->name('workshopstypes.Getinfo');
    Route::post('workshopstypes/actualizar', 'WorkshopsTypesController@actualizar')->name('workshopstypes.actualizar');

    // Rutas Talleres
    Route::prefix('workshop-events')->group(function () {
        Route::get('/index', 'WorkshopEventsController@index')->name('workshop-events-index');
        Route::get('/{id}', 'WorkshopEventsController@workshopEvent')->name('workshop-event-read');
        Route::get('/', 'WorkshopEventsController@workshopEvents')->name('workshop-events-read');
        Route::post('/', 'WorkshopEventsController@store')->name('workshop-events-store');
        Route::put('/{id}', 'WorkshopEventsController@update')->name('workshop-events-update');
        Route::delete('/{id}', 'WorkshopEventsController@destroy')->name('workshop-events-destroy');
    });

    /////// Ruta Eventos Reforestacion
    Route::resource('reforestation', 'ReforestationController');
    Route::get('reforestation/Getinfo/{id}', 'ReforestationController@Getinfo')->name('reforestation.Getinfo');
    Route::post('reforestation/actualizar', 'ReforestationController@actualizar')->name('reforestation.actualizar');

    // Materiales del Taller
    Route::prefix('workshop-materials')->group(function () {
        Route::get('/index', 'WorkshopMaterialsController@index')->name('workshop-materials-index');
        Route::get('/{id}', 'WorkshopMaterialsController@workshopMaterial')->name('workshop-material-read');
        Route::get('/', 'WorkshopMaterialsController@workshopMaterials')->name('workshop-materials-read');
        Route::post('/', 'WorkshopMaterialsController@store')->name('workshop-materials-store');
        Route::put('/{id}', 'WorkshopMaterialsController@update')->name('workshop-materials-update');
        Route::delete('/{id}', 'WorkshopMaterialsController@destroy')->name('workshop-materials-destroy');
    });

    // Preguntas de Supervivencia
    Route::prefix('survival-questions')->group(function () {
        Route::get('/index', 'SurvivalQuestionsController@index')->name('survival-questions-index');
        Route::get('/{id}', 'SurvivalQuestionsController@survivalQuestion')->name('survival-question-read');
        Route::get('/', 'SurvivalQuestionsController@survivalQuestions')->name('survival-questions-read');
        Route::post('/', 'SurvivalQuestionsController@store')->name('survival-questions-store');
        Route::put('/{id}', 'SurvivalQuestionsController@update')->name('survival-questions-update');
        Route::delete('/{id}', 'SurvivalQuestionsController@destroy')->name('survival-questions-destroy');
    });

    // Preguntas de Supervivencia TEST
    Route::prefix('questions')->group(function () {
        Route::get('/index', 'QuestionsController@index')->name('questions-index');
        Route::get('/{id}', 'QuestionsController@question')->name('question-read');
        Route::get('/', 'QuestionsController@questions')->name('questions-read');
        Route::post('/', 'QuestionsController@store')->name('questions-store');
        Route::put('/{id}', 'QuestionsController@update')->name('questions-update');
        Route::delete('/{id}', 'QuestionsController@destroy')->name('questions-destroy');
    });

    // Respuestas
    Route::prefix('answers')->group(function () {
        Route::get('/index', 'AnswersController@index')->name('answers-index');
        Route::get('/{id}', 'AnswersController@answer')->name('answer-read');
        Route::get('/', 'AnswersController@answers')->name('answers-read');
        Route::post('/', 'AnswersController@store')->name('answers-store');
        Route::put('/{id}', 'AnswersController@update')->name('answers-update');
        Route::delete('/{id}', 'AnswersController@destroy')->name('answers-destroy');
    });

    // Patrocinadores
    Route::prefix('sponsors')->group(function () {
        Route::get('/index', 'SponsorsController@index')->name('sponsors-index');
        Route::get('/{id}', 'SponsorsController@sponsor')->name('sponsor-read');
        Route::get('/', 'SponsorsController@sponsors')->name('sponsors-read');
        Route::post('/', 'SponsorsController@store')->name('sponsors-store');
        Route::put('/{id}', 'SponsorsController@update')->name('sponsors-update');
        Route::delete('/{id}', 'SponsorsController@destroy')->name('sponsors-destroy');
    });

    // Paquetes
    Route::prefix('packages')->group(function () {
        Route::get('/index', 'PackagesController@index')->name('packages-index');
        Route::get('/{id}', 'PackagesController@package')->name('package-read');
        Route::get('/', 'PackagesController@packages')->name('packages-read');
        Route::post('/', 'PackagesController@store')->name('packages-store');
        Route::put('/{id}', 'PackagesController@update')->name('packages-update');
        Route::delete('/{id}', 'PackagesController@destroy')->name('packages-destroy');
    });

    // Instituciones Educativas
    Route::prefix('educative-institutions')->group(function () {
        Route::get('/index', 'EducativeInstitutionsController@index')->name('educative-institutions-index');
        Route::get('/{id}', 'EducativeInstitutionsController@educativeInstitution')->name('educative-institutions-read');
        Route::get('/', 'EducativeInstitutionsController@educativeInstitutions')->name('educative-institutions-read');
        Route::post('/', 'EducativeInstitutionsController@store')->name('educative-institutions-store');
        Route::put('/{id}', 'EducativeInstitutionsController@update')->name('educative-institutions-update');
        Route::delete('/{id}', 'EducativeInstitutionsController@destroy')->name('educative-institutions-destroy');
    });

    // PROCESOS
    // entradas
    Route::resource('entries', 'TreesInventoryController');
    Route::get('entries/Getinfo/{id}', 'TreesInventoryController@Getinfo')->name('entries.Getinfo');

    // Registro de Adopciones
    Route::prefix('adoptions')->group(function () {
        Route::get('/index', 'AdoptionsController@index')->name('adoptions-index');
        Route::get('/{id}', 'AdoptionsController@adoption')->name('adoption-read');
        Route::get('/', 'AdoptionsController@adoptions')->name('adoptions-read');
        Route::post('/', 'AdoptionsController@store')->name('adoptions-store');
        Route::put('/{id}', 'AdoptionsController@update')->name('adoptions-update');
        Route::delete('/{id}', 'AdoptionsController@destroy')->name('adoptions-destroy');
    });

    // Salida de Arboles
    Route::prefix('tree-leavings')->group(function () {
        Route::get('/index', 'TreeLeavingsController@index')->name('tree-leavings-index');
        Route::get('/{id}', 'TreeLeavingsController@treeLeaving')->name('tree-leaving-read');
        Route::get('/', 'TreeLeavingsController@treeLeavings')->name('tree-leavings-read');
        Route::post('/', 'TreeLeavingsController@store')->name('tree-leavings-store');
        Route::put('/{id}', 'TreeLeavingsController@update')->name('tree-leavings-update');
        Route::delete('/{id}', 'TreeLeavingsController@destroy')->name('tree-leavings-destroy');
    });

    // Entrega de Arboles
    Route::prefix('tree-deliveries')->group(function () {
        Route::get('/index', 'TreeDeliveriesController@index')->name('tree-deliveries-index');
        Route::get('/form/{id?}', 'TreeDeliveriesController@form')->name('tree-deliveries-form');
        Route::get('/{id}', 'TreeDeliveriesController@treeDelevery')->name('tree-delivery-read');
        Route::get('/', 'TreeDeliveriesController@treeDeleveries')->name('tree-deliveries-read');
        Route::post('/', 'TreeDeliveriesController@store')->name('tree-deliveries-store');
        Route::put('/{id}', 'TreeDeliveriesController@update')->name('tree-deliveries-update');
        Route::delete('/{id}', 'TreeDeliveriesController@destroy')->name('tree-deliveries-destroy');
    });

    // Registro de Talleres
    Route::prefix('workshops')->group(function () {
        Route::get('/index', 'WorkshopsController@index')->name('workshops-index');
        Route::get('/{id}', 'WorkshopsController@workshop')->name('workshop-read');
        Route::get('/', 'WorkshopsController@workshops')->name('workshops-read');
        Route::post('/', 'WorkshopsController@store')->name('workshops-store');
        Route::put('/{id}', 'WorkshopsController@update')->name('workshops-update');
        Route::delete('/{id}', 'WorkshopsController@destroy')->name('workshops-destroy');
    });

    // Salida de Arboles
    Route::prefix('material-leavings')->group(function () {
        Route::get('/index', 'MaterialLeavingsController@index')->name('material-leavings-index');
        Route::get('/{id}', 'MaterialLeavingsController@materialLeaving')->name('material-leaving-read');
        Route::get('/', 'MaterialLeavingsController@materialLeavings')->name('material-leavings-read');
        Route::post('/', 'MaterialLeavingsController@store')->name('material-leavings-store');
        Route::put('/{id}', 'MaterialLeavingsController@update')->name('material-leavings-update');
        Route::delete('/{id}', 'MaterialLeavingsController@destroy')->name('material-leavings-destroy');
    });

    // Aplicar cuestionario
    Route::prefix('questionnaire')->group(function () {
        Route::get('/index', 'QuestionnaireController@index')->name('questionnaire-index');
        Route::get('/{id}', 'QuestionnaireController@questionnaire')->name('questionnaire-read');
        Route::get('/', 'QuestionnaireController@questionnaires')->name('questionnaire-read');
        Route::post('/', 'QuestionnaireController@store')->name('questionnaire-store');
        Route::put('/{id}', 'QuestionnaireController@update')->name('questionnaire-update');
        Route::delete('/{id}', 'QuestionnaireController@destroy')->name('questionnaire-destroy');
    });

    
    // Levantamiento de reforestacion
    Route::prefix('reforestationSurvey')->group(function () {
        Route::get('/index', 'ReforestationSurveyController@index')->name('reforestationSurvey-index');
        Route::get('/{id}', 'ReforestationSurveyController@questionnaire')->name('reforestationSurvey-read');
        Route::get('/', 'ReforestationSurveyController@questionnaires')->name('reforestationSurvey-read');
        Route::post('/', 'ReforestationSurveyController@store')->name('reforestationSurvey-store');
        Route::put('/{id}', 'ReforestationSurveyController@update')->name('reforestationSurvey-update');
        Route::delete('/{id}', 'ReforestationSurveyController@destroy')->name('reforestationSurvey-destroy');
    });
});