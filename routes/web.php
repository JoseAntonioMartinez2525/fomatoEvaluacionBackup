<?php

use App\Http\Controllers\ConsolidatedResponseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocenteFormsController;
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
use App\Http\Controllers\WelcomeController;
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
use App\Http\Controllers\EvaluationDateController;
use App\Http\Controllers\FirmaDictaminadorController;
use App\Http\Controllers\PuntajeMaximosController;
use App\Http\Controllers\UserTimerController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Middleware\CheckTimer;
use App\Http\Middleware\VerifyAdminPrivileges;

Route::get('/forzar-error', function () {
    throw new \Exception("Este es un error de prueba");
});

Route::get('/', function () {
    return view('login');
});

Route::get('/formato-evaluacion/', [SessionsController::class, 'index'])->name('login');
Route::post('/login', [SessionsController::class, 'login'])->name('login.post');

// // Rutas para restablecer contraseña
// Route::get('/forgot-password', [PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
// Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
// Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
// Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');
 
Route::middleware(['auth'])->group(function (){
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('rules', function () {return view('rules'); })->name('rules');
     Route::get('/welcome', [DashboardController::class, 'index'])->name('welcome');
    Route::get('resumen', function () {return view('resumen'); })->name('resumen');
    Route::get('perfil', function () {return view('perfil'); })->name('perfil');
    Route::get('general', function () {return view('general');})->name('general');
    Route::get('tiempo', function () {return view('tiempo');})->name('tiempo');
    Route::get('fechas', function () {return view('/secretaria/fechas');})->name('fechas');
    Route::get('edit_delete_form', [DynamicFormController::class, 'showSecretaria'])->name('edit_delete_form_dynamic');

    Route::get('/get-first-non-numeric-value/{formId}', [DynamicFormController::class, 'getFirstNonNumericValue']);

    //formularios
    // Route::get('form2', function () {return view('form2'); })->name('form2');
    // Route::get('form2_2', function () {return view('form2_2'); })->name('form2_2');
    // Route::get('form3_1', function () {return view('form3_1'); })->name('form3_1');
    // Route::get('form3_2', function () {return view('form3_2'); })->name('form3_2');
    // Route::get('form3_3', function () {return view('form3_3'); })->name('form3_3');
    // Route::get('form3_4', function () { return view('form3_4'); })->name('form3_4');
    // Route::get('form3_5', function () {return view('form3_5'); })->name('form3_5');
    // Route::get('form3_6', function () {return view('form3_6'); })->name('form3_6');
    // Route::get('form3_7', function () {return view('form3_7'); })->name('form3_7');
    // Route::get('form3_8', function () {return view('form3_8'); })->name('form3_8');
    // Route::get('form3_8_1', function () {return view('form3_8_1'); })->name('form3_8_1');
    // Route::get('form3_9', function () {return view('form3_9'); })->name('form3_9');
    // Route::get('form3_10', function () {return view('form3_10'); })->name('form3_10');
    // Route::get('form3_11', function () {return view('form3_11'); })->name('form3_11');
    // Route::get('form3_12', function () {return view('form3_12'); })->name('form3_12');
    // Route::get('form3_13', function () {return view('form3_13'); })->name('form3_13');
    // Route::get('form3_14', function () {return view('form3_14'); })->name('form3_14');
    // Route::get('form3_15', function () {return view('form3_15'); })->name('form3_15');
    // Route::get('form3_16', function () {return view('form3_16'); })->name('form3_16');
    // Route::get('form3_17', function () {return view('form3_17'); })->name('form3_17');
    // Route::get('form3_18', function () {return view('form3_18'); })->name('form3_18');
    // Route::get('form3_19', function () {return view('form3_19'); })->name('form3_19');
    // Route::get('form4', function () {return view('form4'); })->name('form4');
    Route::get('form5', function () {return view('form5'); })->name('form5');
    Route::get('resumen_comision', function () {return view('resumen_comision'); })->name('resumen_comision');


    // Rutas para visualizar los formularios de evaluación con datos de un docente específico.
// El parámetro {teacher?} es opcional para permitir la carga de datos.
Route::middleware(['auth'])->group(function () {
    Route::get('/form2/{teacher?}', [DictaminatorForm2_Controller::class, 'showForm2'])->name('form2');
    Route::get('/form2_2/{teacher?}', [DictaminatorForm2_2Controller::class, 'showForm2_2'])->name('form2_2');
    Route::get('/form3_1/{teacher?}', [DictaminatorForm3_1Controller::class, 'showForm31NoSearch'])->name('form3_1');
    Route::get('/form3_2/{teacher?}', [DictaminatorForm3_2Controller::class, 'showForm32'])->name('form3_2');
    Route::get('/form3_3/{teacher?}', [DictaminatorForm3_3Controller::class, 'showForm33'])->name('form3_3');
    Route::get('/form3_4/{teacher?}', [DictaminatorForm3_4Controller::class, 'showForm34'])->name('form3_4');
    Route::get('/form3_5/{teacher?}', [DictaminatorForm3_5Controller::class, 'showForm35'])->name('form3_5');
    Route::get('/form3_6/{teacher?}', [DictaminatorForm3_6Controller::class, 'showForm36'])->name('form3_6');
    Route::get('/form3_7/{teacher?}', [DictaminatorForm3_7Controller::class, 'showForm37'])->name('form3_7');
    Route::get('/form3_8/{teacher?}', [DictaminatorForm3_8Controller::class, 'showForm38'])->name('form3_8');
    Route::get('/form3_8_1/{teacher?}', [DictaminatorForm3_8_1Controller::class, 'showForm381'])->name('form3_8_1');
    Route::get('/form3_9/{teacher?}', [DictaminatorForm3_9Controller::class, 'showForm39'])->name('form3_9');
    Route::get('/form3_10/{teacher?}', [DictaminatorForm3_10Controller::class, 'showForm310'])->name('form3_10');
    Route::get('/form3_11/{teacher?}', [DictaminatorForm3_11Controller::class, 'showForm311'])->name('form3_11');
    Route::get('/form3_12/{teacher?}', [DictaminatorForm3_12Controller::class, 'showForm312'])->name('form3_12');
    Route::get('/form3_13/{teacher?}', [DictaminatorForm3_13Controller::class, 'showForm313'])->name('form3_13');
    Route::get('/form3_14/{teacher?}', [DictaminatorForm3_14Controller::class, 'showForm314'])->name('form3_14');
    Route::get('/form3_15/{teacher?}', [DictaminatorForm3_15Controller::class, 'showForm315'])->name('form3_15');
    Route::get('/form3_16/{teacher?}', [DictaminatorForm3_16Controller::class, 'showForm316'])->name('form3_16');
    Route::get('/form3_17/{teacher?}', [DictaminatorForm3_17Controller::class, 'showForm317'])->name('form3_17');
    Route::get('/form3_18/{teacher?}', [DictaminatorForm3_18Controller::class, 'showForm318'])->name('form3_18');
    Route::get('/form3_19/{teacher?}', [DictaminatorForm3_19Controller::class, 'showForm319'])->name('form3_19');
});

    Route::get('/reporte_pdf', [App\Http\Controllers\DictaminatorController::class, 'generarPDF'])->name('reporte_pdf');
    // Route::get('comision_dictaminadora', function () {return view('comision_dictaminadora'); })->name('comision_dictaminadora');
    Route::get('dynamic_forms', function () {return view('dynamic_forms'); })->name('dynamic_forms');

    Route::get('/secretaria', [SecretariaController::class, 'showSecretaria'])->name('secretaria');


    Route::get('/show-all-users', [ProfileController::class, 'showAllUsers'])->name('show-all-users');
    Route::get('/get-docentes', [DictaminatorController::class, 'getDocentes'])->name('getDocentes');
    Route::get('/get-docente-data', [DictaminatorController::class, 'getDocenteData'])->name('getDocenteData');
    Route::get('/get-authenticated-docente-data', [DictaminatorController::class, 'getAuthenticatedDocenteData']);
    
    // Routes for viewing completed forms by docente
    Route::get('/docentes-asignados', [DocenteFormsController::class, 'index'])->name('docente.forms.index');
    Route::get('/docente-formularios/{docenteEmail}', [DocenteFormsController::class, 'show'])->name('docente.forms.show');
    //Route::get('/get-form-content/{form}', [FormContentController::class, 'getFormContent']);
    Route::get('/get-dictaminadores', [FormsController::class, 'getdictaminadores'])->name('getdictaminadores');
    Route::get('/form4/{teacher?}', [ConsolidatedResponseController::class, 'showResumen'])->name('form4');
    Route::get('/get-dictaminador-data', [FormsController::class, 'getDictaminadorData'])->name('getDictaminadorData');
    Route::get('otros_formularios', function () {return view('otros_formularios'); })->name('otros_formularios');
        Route::get('/get-docentes-otros-form', [DynamicFormController::class, 'getDocentesOtrosForm'])->name('get-docentes-otros-form');
    Route::get('/resumen-comision', [ResumenComisionController::class, 'getDictaminadorFinalData']);
    // routes/web.php

    Route::get('/dictaminador-final-data', [ResumenComisionController::class, 'getDictaminadorFinalData']);
    Route::get('/convocatoria/{dictaminadorId}', [DictaminatorController::class, 'getConvocatoria']);


    Route::get('/form3_1', [DictaminatorForm3_1Controller::class, 'showForm31']);



    // --- GRUPO DE RUTAS PARA DOCENTES PROTEGIDAS POR PERÍODO DE EVALUACIÓN ---
    Route::middleware([\App\Http\Middleware\CheckEvaluationPeriod::class])->group(function () {
        // Rutas GET para mostrar los formularios
        Route::get('docencia', function () {return view('docencia'); })->name('docencia');

        // Rutas POST para guardar los datos de los formularios
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
    });
    
    Route::post('/store-resume', [ResumeController::class, 'storeResume']);
    Route::post('/store-evaluator-signature', [EvaluatorSignatureController1::class, 'storeEvaluatorSignature'])->name('store-evaluator-signature');

    // Dictaminadores
    Route::post('/store-form2', [DictaminatorForm2_Controller::class, 'storeform2'])->name('form2.store')->withoutMiddleware('auth');
    Route::post('/store-form22', [DictaminatorForm2_2Controller::class, 'storeform22'])->name('form2_2.store')->withoutMiddleware('auth');
    Route::post('/store-form31', [DictaminatorForm3_1Controller::class, 'storeform31'])->name('form3_1.store')->withoutMiddleware('auth');
    Route::post('/store-form32', [DictaminatorForm3_2Controller::class, 'storeform32'])->name('form3_2.store')->withoutMiddleware('auth');
    Route::post('/store-form33', [DictaminatorForm3_3Controller::class, 'storeform33'])->name('form3_3.store')->withoutMiddleware('auth');
    Route::post('/store-form34', [DictaminatorForm3_4Controller::class, 'storeform34'])->name('form3_4.store')->withoutMiddleware('auth');
    Route::post('/store-form35', [DictaminatorForm3_5Controller::class, 'storeform35'])->name('form3_5.store')->withoutMiddleware('auth');
    Route::post('/store-form36', [DictaminatorForm3_6Controller ::class, 'storeform36'])->name('form3_6.store')->withoutMiddleware('auth');
    Route::post('/store-form37', [DictaminatorForm3_7Controller::class, 'storeform37'])->name('form3_7.store')->withoutMiddleware('auth');
    Route::post('/store-form38', [DictaminatorForm3_8Controller::class, 'storeform38'])->name('form3_8.store')->withoutMiddleware('auth');
    Route::get('/get-form38', [DictaminatorForm3_8Controller::class, 'getFormData38'])->name('form3_8.get')->withoutMiddleware('auth');
    Route::post('/store-form381', [DictaminatorForm3_8_1Controller::class, 'storeform381'])->name('form3_8_1.store')->withoutMiddleware('auth');
    Route::post('/store-form39', [DictaminatorForm3_9Controller::class, 'storeform39'])->name('form3_9.store_9')->withoutMiddleware('auth');
    Route::post('/store-form310', [DictaminatorForm3_10Controller::class, 'storeform310'])->name('form3_10.store_10')->withoutMiddleware('auth');
    Route::post('/store-form311', [DictaminatorForm3_11Controller::class, 'storeform311'])->name('form3_11.store_11')->withoutMiddleware('auth');
    Route::post('/store-form312', [DictaminatorForm3_12Controller::class, 'storeform312'])->name('form3_12.store_12')->withoutMiddleware('auth');
    Route::post('/store-form313', [DictaminatorForm3_13Controller::class, 'storeform313'])->name('form3_13.store_13')->withoutMiddleware('auth');
    Route::post('/store-form314', [DictaminatorForm3_14Controller::class, 'storeform314'])->name('form3_14.store_14')->withoutMiddleware('auth');
    Route::post('/store-form315', [DictaminatorForm3_15Controller::class, 'storeform315'])->name('form3_15.store_15')->withoutMiddleware('auth');
    Route::post('/store-form316', [DictaminatorForm3_16Controller::class, 'storeform316'])->name('form3_16.store_16')->withoutMiddleware('auth');
    Route::post('/store-form317', [DictaminatorForm3_17Controller::class, 'storeform317'])->name('form3_17.store_17')->withoutMiddleware('auth');
    Route::post('/store-form318', [DictaminatorForm3_18Controller::class, 'storeform318'])->name('form3_18.store_18')->withoutMiddleware('auth');
    Route::post('/store-form319', [DictaminatorForm3_19Controller::class, 'storeform319'])->name('form3_19.store_19')->withoutMiddleware('auth');
    Route::post('/store-dictaminator_signatures', [FirmaDictaminadorController::class, 'storeFirma'])->name('firmaDictaminador.store');    // Route::post('/formato-evaluacion/store-dictaminator-signature-secretaria', [FirmaDictaminadorController::class, 'storeFirmaSecretaria'])->name('store.dictaminator.signature.secretaria');
    Route::post('/store-signature-secretaria', [FirmaDictaminadorController::class, 'storeFirmaSecretaria'])->name('store.signature.secretaria');
    

        // 
    // Ruta PUT genérica para ACTUALIZAR cualquier formulario de dictaminador
    // El JavaScript ya construye la URL correctamente (ej: /update-form31)
    Route::put('/update-form{formIdentifier}', [DictaminatorController::class, 'updateForm'])
        ->name('dictaminator.form.update')
        ->withoutMiddleware('auth');
    
    Route::post('/update-form32', [DictaminatorForm3_2Controller::class, 'updateform32'])
        ->name('dictaminator.form.update32')
        ->withoutMiddleware('auth');

    Route::get('/formato-evaluacion/get-signatures', [FirmaDictaminadorController::class, 'getSignatures'])
     ->name('get.signatures');
    // Ruta para agregar un solo docente a un dictaminador
    Route::post('/agregar-docente/{dictaminador_id}', [DictaminatorForm2_Controller::class, 'agregarDocente'])
        ->name('agregar.docente');

    Route::post('/asignar-docentes/{dictaminador_id}', [DictaminatorForm2_Controller::class, 'asignarDocentes'])
        ->name('asignar.docentes');
    
        // GET → muestra formulario o panel según la firma
    Route::get('/firma-dictaminador', [FirmaDictaminadorController::class, 'index'])
        ->name('firmaDictaminador.index');

    Route::get('/dictaminador-signatures', [FirmaDictaminadorController::class, 'getFirmasPorDocente']);

    Route::get('/get-dictaminators-responses', [ResponseJson::class, 'getDictaminatorResponses']);
    Route::get('/get-dictaminators-responses-id', [ResponseJson::class, 'getDictaminatorResponsesId']);
    Route::get('/get-docentes-by-dictaminador', [DictaminatorController::class, 'getDocentesByDictaminador']);
    Route::get('/comision_dictaminadora', [FirmaDictaminadorController::class, 'showForm'])->name('comision_dictaminadora');
    Route::get('/get-user-id', [DictaminatorController::class, 'getUserId']);

    Route::post('/generate-pdf', [ResponseForm3_19Controller::class, 'generatePdf'])->name('generate.pdf');
    // Ruta para asignar varios docentes a un dictaminador


    Route::post('/update-form/{formId}', [DynamicFormController::class, 'updateFormData']);


    //Ruta para la tabla fragmentada de comision de formularios dinamicos
    Route::get('/get-teacher-form-data/{email}/{formName}', [DynamicFormController::class, 'getTeacherFormData']);
    Route::post('/update-commission-data/{formId}', [DynamicFormController::class, 'updateCommissionData']);

    //GET formularios utilizados por Docentes
    Route::get('/get-data1', [ResponseController::class, 'getData1'])->name('getData1');
    Route::get('/get-data2', [ResponseForm2Controller::class, 'getData2'])->name('getData2');
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


    Route::get('/get-form2', function () {return redirect()->route('getFormData2');});
    Route::get('/get-form-data2', [DictaminatorForm2_Controller::class, 'getFormData2'])->name('getFormData2');
    Route::get('/get-form-data22', [DictaminatorForm2_2Controller::class, 'getFormData22'])->name('getFormData22');
    Route::get('/get-form-data', [DictaminatorFormsGroupsController::class, 'getDictaminadorData']);
    Route::get('/get-data-resume', [ResumeController::class, 'getDataResume'])->name('get-data-resume');
    Route::get('/get-evaluator-signature', [EvaluatorSignatureController1::class, 'getEvaluatorSignature'])->name('get-evaluator-signature');


    Route::get('/fetch-convocatoria/{user_id}', [ResumenComisionController::class, 'fetchConvocatoria'])->name('fetch-convocatoria');

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
    Route::get('/get-total-docencia', [DictaminatorForm3_1Controller::class, 'getTotalDocencia'])->name('get-total-docencia');
    Route::get('/get-total-docencia-evaluar', [DictaminatorForm3_1Controller::class, 'getTotalDocenciaEvaluar'])->name('get-total-docencia-evaluar');
    Route::get('/test-event/{user_id}', function ($user_id) {
        event(new \App\Events\EvaluationCompleted($user_id));
        return 'Evento disparado para user_id: ' . $user_id;
    });

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');

    Route::get('/formato-evaluacion/edit_delete_form/', [DynamicFormController::class, 'showDynamicForm'])->name('edit_delete_form');

    // Ruta para cambiar el modo oscuro
    Route::post('/toggle-dark-mode', [ThemeController::class, 'toggleDarkMode'])->name('theme.toggle');
    //Route::resource('dynamic-forms', DynamicFormController::class);
    Route::post('/formato-evaluacion/dynamic-form/store', [DynamicFormController::class, 'store'])->name('dynamic-form.store');

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


    Route::get('/formato-evaluacion/dynamic-form/columns/{formId}', [DynamicFormController::class, 'getColumns'])->name('dynamic-form.columns');
    Route::get('/formato-evaluacion/form/edit/{form_name}', [DynamicFormController::class, 'edit'])->name('form.edit');
    Route::put('/forms/{id}', [DynamicFormController::class, 'update'])->name('forms.update');

    Route::delete('/forms/{id}', [DynamicFormController::class, 'destroy'])->name('forms.destroy');


    Route::get('/formato-evaluacion/get-form-content/{formId}', [DynamicFormController::class, 'showDynamicForm'])->name('get-form-content');

//Route::get('/get-form-data/{formType}', [DynamicFormController::class, 'getFormData']);
    Route::get('/formato-evaluacion/get-form-data/{formName}', [DynamicFormController::class, 'getFormData'])->where('formName', '.*');


});


// --- CORRECCIÓN DE RUTA ---
// Se cambia el nombre de la ruta para que coincida con la URL que el cliente construye: /get-form-data31
Route::get('/get-form-data31', [DictaminatorForm3_1Controller::class, 'getFormData31'])
    ->name('formato-evaluacion.get-form-data31');

Route::get('/get-form-data32', [DictaminatorForm3_2Controller::class, 'getFormData32']);

Route::get('/get-form-data33', [DictaminatorForm3_3Controller::class, 'getFormData33']);

Route::get('/get-form-data34', [DictaminatorForm3_4Controller::class, 'getFormData34']);

Route::get('/get-form-data35', [DictaminatorForm3_5Controller::class, 'getFormData35']);

Route::get('/get-form-data36', [DictaminatorForm3_6Controller::class, 'getFormData36']);

Route::get('/get-form-data37', [DictaminatorForm3_7Controller::class, 'getFormData37']);

Route::get('/get-form-data38', [DictaminatorForm3_8Controller::class, 'getFormData38']);

// Route::get('/get-form-data313', [DictaminatorForm3_13Controller::class, 'getFormData313']);

Route::get('/get-form-data381', [DictaminatorForm3_8_1Controller::class, 'getFormData381'])
    ->name('formato-evaluacion.get-form-data381');

Route::get('/get-form-data39', [DictaminatorForm3_9Controller::class, 'getFormData39']);
Route::get('/get-form-data310', [DictaminatorForm3_10Controller::class, 'getFormData310']);
Route::get('/get-form-data311', [DictaminatorForm3_11Controller::class, 'getFormData311']);
Route::get('/get-form-data312', [DictaminatorForm3_12Controller::class, 'getFormData312']);
Route::get('/get-form-data313', [DictaminatorForm3_13Controller::class, 'getFormData313']);
Route::get('/get-form-data314', [DictaminatorForm3_14Controller::class, 'getFormData314']);
Route::get('/get-form-data315', [DictaminatorForm3_15Controller::class, 'getFormData315']);
Route::get('/get-form-data316', [DictaminatorForm3_16Controller::class, 'getFormData316']);
Route::get('/get-form-data317', [DictaminatorForm3_17Controller::class, 'getFormData317']);
Route::get('/get-form-data318', [DictaminatorForm3_18Controller::class, 'getFormData318']);
Route::get('/get-form-data319', [DictaminatorForm3_19Controller::class, 'getFormData319']);

    // Route::prefix('formato-evaluacion')
    // ->name('formato-evaluacion.')
    // ->group(function () {

    //     foreach ([2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19] as $n) {

    //         $controller = "App\\Http\\Controllers\\DictaminatorForm3_{$n}Controller";

    //         Route::get("/get-form-data3{$n}", function (\Illuminate\Http\Request $request) use ($controller) {
    //             $controller = app($controller);
    //             return $controller->getForm3Data($request);
    //         })->name("get-form-data3{$n}");
    //     }
    // });

Route::get('/docencia-scores?user_id=${userId}', [ResponseJson::class, 'getDocenciaScoresByUser']);

// Route::get('/get-form38', [DictaminatorForm3_8Controller::class, 'getFormData38'])->name('form3_8.get');
Route::post('/logout', action: [SessionsController::class, 'logout'])->name('logout');

Route::get('/formato-evaluacion/test-dompdf', function () {
    try {
        $dompdf = new Dompdf();
        return 'Dompdf está disponible y funcionando.';
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});

Route::middleware(['auth'])->group(function () {
    Route::get('/timer', [UserTimerController::class, 'getTimer'])->name('timer.get');
    Route::post('/timer/update', [UserTimerController::class, 'updateTimer'])->name('timer.update');
    Route::post('/timer/extend/{userId}', [UserTimerController::class, 'extendTimer'])->name('timer.extend'); // Admin
});




Route::post('admin-reset-timer', [DictaminatorController::class, 'adminResetTimer'])
    ->middleware([VerifyAdminPrivileges::class])
    ->name('admin.reset.timer');

    Route::get('/resumen-comision/firmas', [ResumenComisionController::class, 'getFirmasYResumen'])
    ->middleware(['auth'])
    ->name('resumenComision.firmas');

Route::post('/evaluation-dates/docentes-llenado', [EvaluationDateController::class, 'storeDocentesLlenado']);
Route::post('/evaluation-dates/docentes-evaluacion', [EvaluationDateController::class, 'storeDocentesEvaluacion']);
Route::post('/evaluation-dates/evaluadores-captura', [EvaluationDateController::class, 'storeEvaluadoresCaptura']);
Route::get('/evaluation-dates', [EvaluationDateController::class, 'getFechas']);
Route::post('/formato-evaluacion/update-periods', [ResumenComisionController::class, 'updatePeriods'])->middleware('auth');
Route::post('/formato-evaluacion/update-convocatoria', [ResumenComisionController::class, 'updateConvocatoria'])->middleware('auth');
Route::get('/evaluation-dates/history', [ResumenComisionController::class, 'getEvaluationDatesHistory'])->middleware('auth');

for ($i = 1; $i <= 19; $i++) {
    Route::get("/get-form3{$i}", [ 
        '\App\Http\Controllers\DictaminatorForm3_'.$i.'Controller',
        'getFormData3'.$i
    ])->name("form3_{$i}.get")->withoutMiddleware('auth');
}

//Excel
Route::get('users/export/', [UserController::class, 'export'])->name('users.export');
