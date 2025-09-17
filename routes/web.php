<?php

use App\Http\Controllers\ConsolidatedResponseController;
use App\Http\Controllers\DictaminatorForm2_2Controller;
use App\Http\Controllers\DictaminatorForm2_Controller;
use App\Http\Controllers\DictaminatorForm3_10Controller;
use App\Http\Controllers\DictaminatorForm3_11Controller;
use App\Http\Controllers\DictaminatorForm3_12Controller;
use App\Http\Controllers\DictaminatorForm3_13Controller;
use App\Http\Controllers\DictaminatorForm3_14Controller;
use App\Http\Controllers\DictaminatorForm3_15Controller;
use App\Http\Controllers\DictaminatorForm3_16Controller;
use App\Http\Controllers\DictaminatorForm3_17Controller;
use App\Http\Controllers\DictaminatorForm3_18Controller;
use App\Http\Controllers\DictaminatorForm3_19Controller;
use App\Http\Controllers\DictaminatorForm3_1Controller;
use App\Http\Controllers\DictaminatorForm3_2Controller;
use App\Http\Controllers\DictaminatorForm3_3Controller;
use App\Http\Controllers\DictaminatorForm3_4Controller;
use App\Http\Controllers\DictaminatorForm3_5Controller;
use App\Http\Controllers\DictaminatorForm3_6Controller;
use App\Http\Controllers\DictaminatorForm3_7Controller;
use App\Http\Controllers\DictaminatorForm3_8_1Controller;
use App\Http\Controllers\DictaminatorForm3_8Controller;
use App\Http\Controllers\DictaminatorForm3_9Controller;
use App\Http\Controllers\DictaminatorFormsGroupsController;
use App\Http\Controllers\DynamicFormController;
use App\Http\Controllers\EvaluatorSignatureController1;
use App\Http\Controllers\FormsController;
use App\Http\Controllers\FormContentController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\ResponseForm3_8_1Controller;
use App\Http\Controllers\ResumeController;
use App\Http\Controllers\ResumenComisionController;
use App\Http\Controllers\SecretariaController;
use App\Http\Controllers\ThemeController;
use App\Models\DictaminatorsResponseForm3_6;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\ResponseController;
use App\Http\Controllers\ResponseJson;
use App\Http\Controllers\ResponseForm2Controller;
use App\Http\Controllers\ResponseForm2_2Controller;
use App\Http\Controllers\ResponseForm3_1Controller;
use App\Http\Controllers\ResponseForm3_2Controller;
use App\Http\Controllers\ResponseForm3_3Controller;
use App\Http\Controllers\ResponseForm3_4Controller;
use App\Http\Controllers\ResponseForm3_5Controller;
use App\Http\Controllers\ResponseForm3_6Controller;
use App\Http\Controllers\ResponseForm3_7Controller;
use App\Http\Controllers\ResponseForm3_8Controller;
use App\Http\Controllers\ResponseForm3_8_1_Controller;
use App\Http\Controllers\ResponseForm3_9Controller;
use App\Http\Controllers\ResponseForm3_10Controller;
use App\Http\Controllers\ResponseForm3_11Controller;
use App\Http\Controllers\ResponseForm3_12Controller;
use App\Http\Controllers\ResponseForm3_13Controller;
use App\Http\Controllers\ResponseForm3_14Controller;
use App\Http\Controllers\ResponseForm3_15Controller;
use App\Http\Controllers\ResponseForm3_16Controller;
use App\Http\Controllers\ResponseForm3_17Controller;
use App\Http\Controllers\ResponseForm3_18Controller;
use App\Http\Controllers\ResponseForm3_19Controller;
use App\Http\Controllers\SessionsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EvaluatorSignatureController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DictaminatorController;
use App\Http\Controllers\PuntajeMaximosController;

Route::get('/', function () {
    return view('login');
});

Route::get('/', [SessionsController::class, 'index'])->name('login');
Route::post('/login', [SessionsController::class, 'login'])->name('login.post');
 
Route::middleware(['auth'])->group(function (){
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/welcome', [HomeController::class, 'showWelcome'])->name('welcome');
Route::get('/welcome', [App\Http\Controllers\WelcomeController::class, 'index'])->name('welcome');

Route::get('rules', function () {return view('rules'); })->name('rules');
Route::get('docencia', function () {return view('docencia'); })->name('docencia');
Route::get('resumen', function () {return view('resumen'); })->name('resumen');
Route::get('perfil', function () {return view('perfil'); })->name('perfil');
Route::get('general', function () {return view('general');})->name('general');
Route::get('edit_delete_form', [DynamicFormController::class, 'showSecretaria'])->name('edit_delete_form');

Route::get('/get-first-non-numeric-value/{formId}', [DynamicFormController::class, 'getFirstNonNumericValue']);

//formularios
Route::get('form2', function () {return view('form2'); })->name('form2');
Route::get('form2_2', function () {return view('form2_2'); })->name('form2_2');
Route::get('form3_1', function () {return view('form3_1'); })->name('form3_1');
Route::get('form3_2', function () {return view('form3_2'); })->name('form3_2');
Route::get('form3_3', function () {return view('form3_3'); })->name('form3_3');
Route::get('form3_4', function () { return view('form3_4'); })->name('form3_4');
Route::get('form3_5', function () {return view('form3_5'); })->name('form3_5');
Route::get('form3_6', function () {return view('form3_6'); })->name('form3_6');
Route::get('form3_7', function () {return view('form3_7'); })->name('form3_7');
Route::get('form3_8', function () {return view('form3_8'); })->name('form3_8');
Route::get('form3_8_1', function () {return view('form3_8_1'); })->name('form3_8_1');
Route::get('form3_9', function () {return view('form3_9'); })->name('form3_9');
Route::get('form3_10', function () {return view('form3_10'); })->name('form3_10');
Route::get('form3_11', function () {return view('form3_11'); })->name('form3_11');
Route::get('form3_12', function () {return view('form3_12'); })->name('form3_12');
Route::get('form3_13', function () {return view('form3_13'); })->name('form3_13');
Route::get('form3_14', function () {return view('form3_14'); })->name('form3_14');
Route::get('form3_15', function () {return view('form3_15'); })->name('form3_15');
Route::get('form3_16', function () {return view('form3_16'); })->name('form3_16');
Route::get('form3_17', function () {return view('form3_17'); })->name('form3_17');
Route::get('form3_18', function () {return view('form3_18'); })->name('form3_18');
Route::get('form3_19', function () {return view('form3_19'); })->name('form3_19');
Route::get('form4', function () {return view('form4'); })->name('form4');
Route::get('form5', function () {return view('form5'); })->name('form5');
Route::get('resumen_comision', function () {return view('resumen_comision'); })->name('resumen_comision');

Route::get('/reporte_pdf', [App\Http\Controllers\DictaminatorController::class, 'generarPDF'])->name('reporte_pdf');
Route::get('comision_dictaminadora', function () {return view('comision_dictaminadora'); })->name('comision_dictaminadora');
Route::get('dynamic_forms', function () {return view('dynamic_forms'); })->name('dynamic_forms');

Route::get('/secretaria', [SecretariaController::class, 'showSecretaria'])->name('secretaria');


Route::get('/show-all-users', [ProfileController::class, 'showAllUsers'])->name('show-all-users');
Route::get('/get-docentes', [DictaminatorController::class, 'getDocentes'])->name('getDocentes');
Route::get('/get-docente-data', [DictaminatorController::class, 'getDocenteData'])->name('getDocenteData');
//Route::get('/get-form-content/{form}', [FormContentController::class, 'getFormContent']);
Route::get('/get-dictaminadores', [FormsController::class, 'getdictaminadores'])->name('getdictaminadores');
Route::get('/form4', [ConsolidatedResponseController::class, 'showResumen'])->name('form4');
Route::get('/get-dictaminador-data', [FormsController::class, 'getDictaminadorData'])->name('getDictaminadorData');
Route::get('otros_formularios', function () {return view('otros_formularios'); })->name('otros_formularios');
    Route::get('/get-docentes-otros-form', [DynamicFormController::class, 'getDocentesOtrosForm'])->name('get-docentes-otros-form');
Route::get('/resumen-comision', [ResumenComisionController::class, 'getDictaminadorFinalData']);
// routes/web.php

Route::get('/dictaminador-final-data', [ResumenComisionController::class, 'getDictaminadorFinalData']);
Route::get('/convocatoria/{dictaminadorId}', [DictaminatorController::class, 'getConvocatoria']);


Route::get('/form3_1', [DictaminatorForm3_1Controller::class, 'showForm31']);



//POST formularios
Route::post('/store', [ResponseController::class, 'store'])->name('store');
Route::post('/store2', [ResponseForm2Controller::class, 'store2'])->name('store2');
Route::post('/store3', [ResponseForm2_2Controller::class, 'store3']);
Route::post('/store31', [ResponseForm3_1Controller::class, 'store31']);
Route::post('/store32', [ResponseForm3_2Controller::class, 'store32']);
Route::post('/store33', [ResponseForm3_3Controller::class, 'store33']);
Route::post('/store34', [ResponseForm3_4Controller::class, 'store34']);
Route::post('/store35', [ResponseForm3_5Controller::class, 'store35']);
Route::post('/store36', [ResponseForm3_6Controller::class, 'store36']);
Route::post('/store37', [ResponseForm3_7Controller::class, 'store37']);
Route::post('/store38', [ResponseForm3_8Controller::class, 'store38']);
Route::post('/store381', [ResponseForm3_8_1Controller::class, 'store381']);
Route::post('/store39', [ResponseForm3_9Controller::class, 'store39']);
Route::post('/store310', [ResponseForm3_10Controller::class, 'store310']);
Route::post('/store311', [ResponseForm3_11Controller::class, 'store311']);
Route::post('/store312', [ResponseForm3_12Controller::class, 'store312']);
Route::post('/store313', [ResponseForm3_13Controller::class, 'store313']);
Route::post('/store314', [ResponseForm3_14Controller::class, 'store314']);
Route::post('/store315', [ResponseForm3_15Controller::class, 'store315']);
Route::post('/store316', [ResponseForm3_16Controller::class, 'store316']);
Route::post('/store317', [ResponseForm3_17Controller::class, 'store317']);
Route::post('/store318', [ResponseForm3_18Controller::class, 'store318']);
Route::post('/store319', [ResponseForm3_19Controller::class, 'store319']);
Route::post('/store-resume', [ResumeController::class, 'storeResume']);
Route::post('/store-evaluator-signature', [EvaluatorSignatureController1::class, 'storeEvaluatorSignature'])->name('store-evaluator-signature');

// Dictaminadores
Route::post('/store-form2', [DictaminatorForm2_Controller::class, 'storeform2'])->withoutMiddleware('auth');
Route::post('/store-form22', [DictaminatorForm2_2Controller::class, 'storeform22'])->withoutMiddleware('auth');
Route::post('/store-form31', [DictaminatorForm3_1Controller::class, 'storeform31'])->withoutMiddleware('auth');
Route::post('/store-form32', [DictaminatorForm3_2Controller::class, 'storeform32'])->withoutMiddleware('auth');
Route::post('/store-form33', [DictaminatorForm3_3Controller::class, 'storeform33'])->withoutMiddleware('auth');
Route::post('/store-form34', [DictaminatorForm3_4Controller::class, 'storeform34'])->withoutMiddleware('auth');
Route::post('/store-form35', [DictaminatorForm3_5Controller::class, 'storeform35'])->withoutMiddleware('auth');
Route::post('/store-form36', [DictaminatorForm3_6Controller ::class, 'storeform36'])->withoutMiddleware('auth');
Route::post('/store-form37', [DictaminatorForm3_7Controller::class, 'storeform37'])->withoutMiddleware('auth');
Route::post('/store-form38', [DictaminatorForm3_8Controller::class, 'storeform38'])->withoutMiddleware('auth');
Route::post('/store-form381', [DictaminatorForm3_8_1Controller::class, 'storeform381'])->withoutMiddleware('auth');
Route::post('/store-form39', [DictaminatorForm3_9Controller::class, 'storeform39'])->withoutMiddleware('auth');
Route::post('/store-form310', [DictaminatorForm3_10Controller::class, 'storeform310'])->withoutMiddleware('auth');
Route::post('/store-form311', [DictaminatorForm3_11Controller::class, 'storeform311'])->withoutMiddleware('auth');
Route::post('/store-form312', [DictaminatorForm3_12Controller::class, 'storeform312'])->withoutMiddleware('auth');
Route::post('/store-form313', [DictaminatorForm3_13Controller::class, 'storeform313'])->withoutMiddleware('auth');
Route::post('/store-form314', [DictaminatorForm3_14Controller::class, 'storeform314'])->withoutMiddleware('auth');
Route::post('/store-form315', [DictaminatorForm3_15Controller::class, 'storeform315'])->withoutMiddleware('auth');
Route::post('/store-form316', [DictaminatorForm3_16Controller::class, 'storeform316'])->withoutMiddleware('auth');
Route::post('/store-form317', [DictaminatorForm3_17Controller::class, 'storeform317'])->withoutMiddleware('auth');
Route::post('/store-form318', [DictaminatorForm3_18Controller::class, 'storeform318'])->withoutMiddleware('auth');
Route::post('/store-form319', [DictaminatorForm3_19Controller::class, 'storeform319'])->withoutMiddleware('auth');
Route::post('/generate-pdf', [ResponseForm3_19Controller::class, 'generatePdf'])->name('generate.pdf');
// Ruta para asignar varios docentes a un dictaminador
Route::post('/asignar-docentes/{dictaminador_id}', [DictaminatorForm2_Controller::class, 'asignarDocentes'])
    ->name('asignar.docentes');


Route::post('/update-form/{formId}', [DynamicFormController::class, 'updateFormData']);
// Ruta para agregar un solo docente a un dictaminador
Route::post('/agregar-docente/{dictaminador_id}', [DictaminatorForm2_Controller::class, 'agregarDocente'])
    ->name('agregar.docente');

//Ruta para la tabla fragmentada de comision de formularios dinamicos
Route::get('/get-teacher-form-data/{email}/{formName}', [DynamicFormController::class, 'getTeacherFormData']);
Route::post('/update-commission-data/{formId}', [DynamicFormController::class, 'updateCommissionData']);
//GET formularios
Route::get('/get-data1', [ResponseController::class, 'getData1'])->name('getData1');
Route::get('/get-data2', [ResponseForm2Controller::class, 'getData2'])->name('getData2');
Route::get('/get-data22', [ResponseForm2_2Controller::class, 'getData22'])->name('getData22');
Route::get('/get-data22', [ResponseForm2_2Controller::class, 'getData22'])->name('getData22');
Route::get('/get-data-31', [ResponseForm3_1Controller::class, 'getData31'])->name('getData31');
Route::get('/get-data-32', [ResponseForm3_2Controller::class, 'getData32'])->name('getData32');
Route::get('/get-data-33', [ResponseForm3_3Controller::class, 'getData33'])->name('getData33');
Route::get('/get-data-34', [ResponseForm3_4Controller::class, 'getData34'])->name('getData34');
Route::get('/get-data-35', [ResponseForm3_5Controller::class, 'getData35'])->name('getData35');
Route::get('/get-data-36', [ResponseForm3_6Controller::class, 'getData36'])->name('getData36');
Route::get('/get-data-37', [ResponseForm3_7Controller::class, 'getData37'])->name('getData37');
Route::get('/get-data-38', [ResponseForm3_8Controller::class, 'getData38'])->name('getData38');
Route::get('/get-data-381', [ResponseForm3_8_1Controller::class, 'getData381'])->name('getData381');
Route::get('/get-data-39', [ResponseForm3_9Controller::class, 'getData39'])->name('getData39');
Route::get('/get-data-310', [ResponseForm3_10Controller::class, 'getData310'])->name('getData310');
Route::get('/get-data-311', [ResponseForm3_11Controller::class, 'getData311'])->name('getData311');
Route::get('/get-data-312', [ResponseForm3_12Controller::class, 'getData312'])->name('getData312');
Route::get('/get-data-313', [ResponseForm3_13Controller::class, 'getData313'])->name('getData313');
Route::get('/get-data-314', [ResponseForm3_14Controller::class, 'getData314'])->name('getData314');
Route::get('/get-data-315', [ResponseForm3_15Controller::class, 'getData315'])->name('getData315');
Route::get('/get-data-316', [ResponseForm3_16Controller::class, 'getData316'])->name('getData316');
Route::get('/get-data-317', [ResponseForm3_17Controller::class, 'getData317'])->name('getData317');
Route::get('/get-data-318', [ResponseForm3_18Controller::class, 'getData318'])->name('getData318');
Route::get('/get-data-319', [ResponseForm3_19Controller::class, 'getData319'])->name('getData319');

Route::get('/get-form-data2', [DictaminatorForm2_Controller::class, 'getFormData2'])->name('getFormData2');
Route::get('/get-form-data', [DictaminatorFormsGroupsController::class, 'getDictaminadorData']);
Route::get('/get-data-resume', [ResumeController::class, 'getDataResume'])->name('get-data-resume');
Route::get('/get-evaluator-signature', [EvaluatorSignatureController1::class, 'getEvaluatorSignature'])->name('get-evaluator-signature');

Route::get('/get-dictaminators-responses', [ResponseJson::class, 'getDictaminatorResponses']);
Route::get('/get-dictaminators-responses-id', [ResponseJson::class, 'getDictaminatorResponsesId']);
Route::get('/fetch-convocatoria/{user_id}', [ResumenComisionController::class, 'fetchConvocatoria'])->name('fetch-convocatoria');
Route::get('/get-docentes-by-dictaminador', [DictaminatorForm2_Controller::class, 'getDocentesByDictaminador']);
Route::get('/get-user-id', [DictaminatorController::class, 'getUserId']);
Route::get('/general', [ReportsController::class, 'index'])->name('general');
Route::get('/show-profile', [ReportsController::class, 'showProfile'])->name('show-profile');
//Route::get('/perfil', [ProfileController::class, 'showProfile'])->name('perfil.show');

Route::get('/forms', [FormsController::class, 'showForms']);

Route::get('/formato-evaluacion/generate-json', [ResponseController::class, 'generateJson'])->name('generate-json');
Route::get('/json-generator', [ResponseJson::class, 'jsonGenerator'])->name('json-generator');

Route::post('/update-puntaje-maximo', [PuntajeMaximosController::class, 'updatePuntajeMaximo']);

Route::get('/form3_8_1', [PuntajeMaximosController::class, 'showForm3_8_1']);
Route::get('/formato-evaluacion/get-puntaje-maximo', [ResponseForm3_8_1Controller::class, 'getPuntajeMaximo']);
Route::get('/docencia', [ResponseForm3_8_1Controller::class, 'showForm3_8_1'])->name('showForm3_8_1');
Route::get('/formato-evaluacion/get-total-docencia', [DictaminatorForm3_1Controller::class, 'getTotalDocencia']);
Route::get('/test-event/{user_id}', function ($user_id) {
    event(new \App\Events\EvaluationCompleted($user_id));
    return 'Evento disparado para user_id: ' . $user_id;
});

    Route::get('/formato-evaluacion/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/formato-evaluacion/register', [AuthController::class, 'register'])->name('register');

Route::get('/formato-evaluacion/edit_delete_form/', [DynamicFormController::class, 'showDynamicForm'])->name('edit_delete_form');

// Ruta para cambiar el modo oscuro
Route::post('/toggle-dark-mode', [ThemeController::class, 'toggleDarkMode']);
//Route::resource('dynamic-forms', DynamicFormController::class);
Route::post('/dynamic-form/store', [DynamicFormController::class, 'store'])->name('dynamic-form.store');

Route::get('/formato-evaluacion/dynamic-form/{formName}', [DynamicFormController::class, 'getFormByName']);
    // Add this route with your other routes
//Route::get('/get-form-content/{selectedForm}', [DynamicFormController::class, 'getFormContent']);

Route::post('/formato-evaluacion/update-page-counter', function (Request $request) {
    try {
        $page = $request->input('page');
        \Log::info('Page counter received:', ['page' => $page]);
        session(['page_counter' => $page]);
        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        \Log::error('Error updating page counter: ' . $e->getMessage());
        return response()->json(['error' => 'Internal Server Error'], 500);
    }
});


Route::get('/formato-evaluacion//dynamic-form/columns/{formId}', [DynamicFormController::class, 'getColumns'])->name('dynamic-form.columns');
Route::get('/formato-evaluacion//form/edit/{form_name}', [DynamicFormController::class, 'edit'])->name('form.edit');
    Route::put('/forms/{id}', [DynamicFormController::class, 'update'])->name('forms.update');

    Route::delete('/forms/{id}', [DynamicFormController::class, 'destroy'])->name('forms.destroy');


    Route::get('/formato-evaluacion//get-form-content/{formId}', [DynamicFormController::class, 'showDynamicForm'])->name('get-form-content');

//Route::get('/get-form-data/{formType}', [DynamicFormController::class, 'getFormData']);
    Route::get('/formato-evaluacion//get-form-data/{formName}', [DynamicFormController::class, 'getFormData'])->where('formName', '.*');

});
Route::post('/logout', action: [SessionsController::class, 'logout'])->name('logout');

Route::get('/formato-evaluacion//test-dompdf', function () {
    try {
        $dompdf = new Dompdf();
        return 'Dompdf está disponible y funcionando.';
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});
