<?php

namespace App\Http\Controllers;
use App\Models\DynamicForm;
use App\Models\DynamicFormCommission;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\DynamicFormItem;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;// Corrected line without extraneous character
use App\Models\DictaminatorsResponseForm3_8_1;
use App\Models\UsersResponseForm3_8_1;
use App\Models\DynamicFormResponse;

class DynamicFormController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'form_name' => 'required|string|max:255',
                'puntaje_maximo' => 'required|numeric|min:0',
                'table_data' => 'required|array',
                'user_id' => 'required|integer',
                'email' => 'required|email',
                'user_type' => 'nullable|string',
                'column_names' => 'required|array',
                'acreditacion' => 'nullable|string',
                'filas' => 'required|integer|min:0',
                'columnas' => 'required|integer|min:0',
            ]);

            // Extraer form_type del form_name. Ej: "3.20 Convenios" -> "3.20"
            $formName = trim($validatedData['form_name']);
            // Regex mejorado para capturar prefijos numéricos incluso con espacios (ej: "3. 20")
            preg_match('/^[0-9.]+(\s*[0-9.]+)?/', $formName, $matches);
            // Si se encuentra, se limpia cualquier punto al final. Si no, es null.
            $formType = !empty($matches[0]) ? rtrim(str_replace(' ', '', $matches[0]), '.') : null;

            // 1. Preparar la estructura del formulario (columnas)
            $frontendColumnNames = $validatedData['column_names'];
            // El orden de las columnas fijas y dinámicas es crucial
            $orderedColumnNames = array_merge(['Actividad'], $frontendColumnNames, ['Puntaje a evaluar', 'Puntaje de la Comisión Dictaminadora', 'Observaciones']);

            $formStructure = [];
            foreach ($orderedColumnNames as $columnName) {
                $sanitizedColumnName = Str::slug($columnName, '_');
                $formStructure[] = [
                    'name' => $columnName,
                    'key' => $sanitizedColumnName,
                    // Se pueden agregar metadatos adicionales aquí en el futuro, como 'type' => 'text'
                ];
            }

            // 2. Preparar los datos del formulario (filas)
            $formData = [];

        foreach ($validatedData['table_data'] as $row) {

            // 🟢 Caso moderno: ya viene semántico (asociativo)
            if (is_array($row) && !array_is_list($row)) {
                $formData[] = $row;
                continue;
            }

            // 🟡 Caso legado: array indexado
            $normalized = [];
            foreach ($formStructure as $i => $column) {
                $normalized[$column['key']] = $row[$i] ?? '';
            }

            $formData[] = $normalized;
        }


            // 3. Guardar en la base de datos usando el modelo Eloquent
            $form = DynamicForm::create([
                'user_id' => $validatedData['user_id'],
                'email' => $validatedData['email'],
                'user_type' => $validatedData['user_type'] ?? null,
                'form_name' => $validatedData['form_name'],
                'form_type' => $formType,
                'puntaje_maximo' => $validatedData['puntaje_maximo'],
                'acreditacion' => $validatedData['acreditacion'] ?? null,
                'filas' => $validatedData['filas'],
                'columnas' => $validatedData['columnas'],
                'form_structure' => $formStructure, // Pasar array directo (el modelo hace cast)
                'form_data' => $formData,           // Pasar array directo (el modelo hace cast)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Formulario guardado exitosamente.',
                'form_id' => $form->id
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Error de validación al guardar el formulario:', ['errors' => $e->errors(), 'request' => $request->all()]);
            return response()->json(['success' => false, 'message' => 'Error de validación.', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Error al guardar el formulario: ' . $e->getMessage(), ['request' => $request->all()]);
            return response()->json(['success' => false, 'message' => 'Error interno del servidor al guardar el formulario: ' . $e->getMessage()], 500);
        }
    }
    

    // Método para recuperar el formulario del usuario
    public function getFormByName($formName)
    {
        $form = DynamicForm::where('form_name', $formName)->first();

        if ($form) {
            return response()->json([
                'success' => true,
                'form' => $form,
            ]);
        } else {
            return response()->json(['success' => false, 'message' => 'Formulario no encontrado.']);
        }
    }

    public function calculateScore($activity)
    {
        // Ejemplo de cálculo dinámico
        $score = $activity['base_score'] * $activity['weight'];
        return $score;
    }

    public function showDynamicForm(Request $request)
    {
        \Log::info('Accessing showDynamicForm', [
            'user' => Auth::user(),
            'session' => $request->session()->all()
        ]);

        Log::info('User attempting to access edit_delete_form', [
            'user_id' => Auth::id(),
            'user_type' => Auth::user()->user_type
        ]);


        // Verify user is authenticated and has correct type
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (Auth::user()->user_type !== '') {
            Log::warning('Unauthorized user type attempted to access edit_delete_form');
            return redirect()->route('login');
        }

        $form = \DB::table('dynamic_forms')->first();
        if (!$form) {
            return redirect()->route('edit_delete_form')->with('error', 'Formulario no encontrado.');
        }
        /* Se usaba esto con tablas pivote:
        $columns, $values, $items y sus relaciones con dynamic_forms
        Obtener las columnas y valores para este formulario
        // $columns = \DB::table('dynamic_form_columns')->where('dynamic_form_id', $form->id)->get();
        // $values = \DB::table('dynamic_form_items')->where('dynamic_form_id', $form->id)->get();
        */
        // Fetch all forms from the database
        $forms = DynamicForm::all();

        // Check if there are any forms
        if ($forms->isEmpty()) {
            return redirect()->route('secretaria')
                ->with('message', 'No hay formularios disponibles. Por favor, cree un nuevo formulario.');

        }

        return view('edit_delete_form', [
            'form' => $form,
            // 'columns' => $columns,
            // 'values' => $values,
            'forms' => $forms // Pass the forms to the view
        ]);



    }
    public function showSecretaria()
    {
        $forms = DynamicForm::all(); // Fetch all forms from the database
        return view('secretaria', compact('forms')); // Pass the forms to the view

    }

    // public function edit($formName, $columnId)
    // {
    //     $form = DynamicForm::with(['columns', 'values'])
    //         ->where('form_name', $formName)
    //         ->firstOrFail();

    //     $column = $form->columns->where('id', $columnId)->firstOrFail();
    //     $value = $form->values->where('dynamic_form_column_id', $columnId)->first();


    //     dd($form, $column, $value); // Esto mostrará los datos en la pantalla
    //     return view('edit_delete_form', compact('form', 'column', 'value'));
    // }


    public function update(Request $request, $id)
    {
        $form = DynamicForm::find($id);

        if (!$form) {
            return response()->json(['success' => false, 'message' => 'Formulario no encontrado'], 404);
        }

        \Log::info('Actualizando Formulario con estructura JSON:', $request->all());

        try {
            // La validación ahora espera la estructura de datos completa
            $validatedData = $request->validate([
                'form_name' => 'required|string|max:255',
                'puntaje_maximo' => 'required|numeric|min:0',
                'acreditacion' => 'nullable|string',
                'form_data' => 'required|array', // Espera el array de objetos de fila
                'form_structure' => 'sometimes|array', // Opcional para actualizar la estructura
            ]);

            // Actualizar las propiedades principales
            $form->form_name = $validatedData['form_name'];
            $form->puntaje_maximo = $validatedData['puntaje_maximo'];
            $form->acreditacion = $validatedData['acreditacion'] ?? $form->acreditacion;

            // Actualizar los datos JSON
            $form->form_data = $validatedData['form_data'];
            $form->form_data = json_encode($validatedData['form_data']);
            
            // Opcionalmente, actualizar la estructura si se proporciona
            if (isset($validatedData['form_structure'])) {
                $form->form_structure = $validatedData['form_structure'];
                $form->form_structure = json_encode($validatedData['form_structure']);
            }

            // Actualizar contadores de filas/columnas
            $form->filas = count($validatedData['form_data']);
            $form->columnas = isset($validatedData['form_data'][0]) ? count((array)$validatedData['form_data'][0]) : 0;

            $form->save();

            return response()->json([
                'success' => true,
                'message' => 'Formulario actualizado exitosamente.',
                'form' => $form,
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Error de validación al actualizar el formulario:', ['errors' => $e->errors(), 'request' => $request->all()]);
            return response()->json(['success' => false, 'message' => 'Error de validación.', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Error al actualizar el formulario: ' . $e->getMessage(), ['request' => $request->all()]);
            return response()->json(['success' => false, 'message' => 'Error interno del servidor al actualizar el formulario: ' . $e->getMessage()], 500);
        }
    }


    public function destroy($formId)
    {
        // ... (el método destroy no necesita cambios)
    }


    public function getFormData($formName)
    {
        // Agregar logs para depuración
        \Log::info('Buscando formulario con nombre:', ['formName' => $formName]);

        // Usar Eloquent con los casts del modelo
        $form = DynamicForm::where('form_name', $formName)->first();

        if ($form) {
            // 'form_structure' y 'form_data' ya son arrays gracias al casting del modelo
            $columns = $form->form_structure;
            $values = $form->form_data;

            // Extraer actividades (primera columna)
            $activityColumnKey = $columns[0]['key'] ?? null;
            $activities = [];
            if ($activityColumnKey && is_array($values)) {
                $activities = array_column($values, $activityColumnKey);
            }

            \Log::info('Datos del formulario:', [
                'form_name' => $formName,
                'columns_count' => count($columns),
                'values_rows' => count($values),
                'puntaje_maximo' => $form->puntaje_maximo,
                'acreditacion' => $form->acreditacion ?? 'No encontrado',
                'filas' => $form->filas,
                'columnas' => $form->columnas,
            ]);

            // El frontend necesitará adaptarse a esta nueva estructura.
            // 'columns' es ahora la estructura, 'values' son los datos de las filas.
            return response()->json([
                'success' => true,
                'form_id' => $form->id,
                'columns' => $columns, // Esta es la definición de la estructura
                'values' => $values,   // Estos son los datos de las filas
                'puntaje_maximo' => $form->puntaje_maximo,
                'acreditacion' => $form->acreditacion,
                'activities' => $activities,
                'filas' => $form->filas,
                'columnas' => $form->columnas,
            ]);
        } else {
            \Log::info('Formulario no encontrado para:', ['formName' => $formName]);
            return response()->json(['success' => false, 'message' => 'Formulario no encontrado.']);
        }
    }


    public function getFormId($formName)
    {   // Extract only numbers and dots from formName using regex
        $formId = preg_replace('/[^0-9.]/', '', $formName);

        if (!$formId) {
            return response()->json([
                'success' => false,
                'message' => 'Formulario no encontrado.'
            ]);
        }

        return $formId;
    }

    //transfer the update functionality, directly 
    protected function checkAndUpdateForm($formName, $data = [], $action = 'update')
    {

        // Add logging
        \Log::info("Checking and updating form: {$formName}, Action: {$action}");
        try {
            // Add your conditions here if needed
            if (isset($data['user_type']) && $data['user_type'] === '') {
                \Log::debug("Executing Artisan command for form update");

                $exitCode = Artisan::call('form:update', [
                    'action' => $action,
                    'formName' => $formName,
                    '--data' => $action === 'update' ? [json_encode($data)] : []
                ]);
                if ($exitCode !== 0) {
                    throw new \Exception("Artisan command failed with exit code: {$exitCode}");
                }
            }
        } catch (\Exception $e) {
            \Log::error("Error in checkAndUpdateForm: " . $e->getMessage());
            throw $e;
        }
    }

    public function loadFormView(Request $request, $formType)
    {
        $user = Auth::user();
        $targetUser = $user;

        // Si se proporciona un email (caso secretaria/dictaminador), buscamos ese usuario
        if ($request->has('email')) {
            $email = $request->input('email');
            $foundUser = User::where('email', $email)->first();
            if ($foundUser) {
                $targetUser = $foundUser;
            }
        }

        // Lógica centralizada para obtener convocatoria y periodo
        $form1 = \App\Models\UsersResponseForm1::where('user_id', $targetUser->id)->first();
        $convocatoria = ($form1 && $form1->convocatoria) ? $form1->convocatoria : 'Convocatoria no asignada';
        $periodo = ($form1 && $form1->periodo) ? $form1->periodo : (\App\Models\UsersResponseForm1::calculateCurrentPeriod() ?? 'Periodo no definido');

        // Variables adicionales para compatibilidad con las vistas
        $convocatoria2 = $convocatoria;
        $periodo2 = $periodo;
        $teacherEmailFromUrl = $request->input('email');

        // Data to pass to the view
        $viewData = compact('convocatoria', 'periodo', 'convocatoria2', 'periodo2', 'teacherEmailFromUrl');

        // Specific logic for form3_8_1 to define $mostrarSoloSpan
        if ($formType === 'form3_8_1') {
            // This logic is from PuntajeMaximosController to fix the undefined variable
            $existenDatosDictaminador = DictaminatorsResponseForm3_8_1::exists();
            $existenDatosDocente = UsersResponseForm3_8_1::exists();
            $viewData['mostrarSoloSpan'] = $existenDatosDictaminador || $existenDatosDocente;
        }
        else {
            $viewData['mostrarSoloSpan'] = false; // Default value for other forms
        }

        // Esta función maneja los formularios estáticos
        return view($formType, $viewData);
    }

    public function getDynamicFormForSecretaria($formName)
    {
        // Esta función es similar a getFormData pero con algunas modificaciones para secretaria
        $form = DynamicForm::where('form_name', $formName)->first();
        if ($form) {
            return response()->json([
                'success' => true,
                'columns' => $form->form_structure, // Desde JSON
                'values' => $form->form_data,       // Desde JSON
                'puntaje_maximo' => $form->puntaje_maximo,
                'acreditacion' => $form->acreditacion,
            ]);
        } else {
            return response()->json(['success' => false, 'message' => 'Formulario no encontrado.']);
        }
    }



    public function getDocentesOtrosForm()
    {
        $docentes = User::where('user_type', 'docente')->get(['id', 'email']);
        return response()->json($docentes);
    }

    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * Obtiene los datos de un formulario específico para un docente
     * 
     * Este método carga los datos de un formulario dinámico junto con
     * cualquier evaluación de comisión existente para un docente específico
     */
    public function updateCommissionData(Request $request, $formId)
    {
        try {
            $form = DynamicForm::findOrFail($formId);
            $evaluator = Auth::user();

            // Validar los datos enviados
            $validatedData = $request->validate([
                'rows' => 'required|array',
                'rows.*.row_identifier' => 'required|string',
                'rows.*.puntaje_comision' => 'nullable|numeric',
                'rows.*.puntaje_input_values' => 'nullable|string', // Validación para puntaje_input_values
                'rows.*.observaciones' => 'nullable|string',
                'user_id' => 'required|integer',
                'email' => 'required|email',
                'user_type' => 'required|string',
            ]);

            // Obtener el ID del docente a partir del email proporcionado
            $docente = User::where('email', $validatedData['email'])->first();
            if (!$docente) {
                return response()->json(['success' => false, 'message' => 'Docente no encontrado.'], 404);
            }
            $docenteId = $docente->id;

            foreach ($validatedData['rows'] as $row) {
                DynamicFormCommission::updateOrCreate(
                    [
                        'dynamic_form_id' => $formId,
                        'row_identifier' => $row['row_identifier'],
                        'email_docente' => $validatedData['email'],
                    ],
                    [
                        'user_id' => $docenteId, // Guardar el ID del docente
                        'user_type' => 'docente', // El tipo de usuario al que pertenece la evaluación
                        'puntaje_comision' => $row['puntaje_comision'] ?? null,
                        'puntaje_input_values' => $row['puntaje_input_values'] ?? null, // Guardar puntaje_input_values
                        'observaciones' => $row['observaciones'] ?? null,
                        'evaluador_id' => $evaluator ? $evaluator->id : null,
                        'evaluador_email' => $evaluator ? $evaluator->email : null,
                    ]
                );
            }

            // Obtener la respuesta original del docente para actualizarla con los datos de la comisión
            $response = DynamicFormResponse::where('dynamic_form_id', $formId)
                ->where('user_id', $docenteId)
                ->first();

            if ($response) {
                $responseData = $response->data;
                $structure = $form->form_structure;
                if (is_string($structure)) {
                    $structure = json_decode($structure, true) ?? [];
                }

                // Identificar las claves dinámicas para el puntaje de comisión y observaciones
                $structureCollection = collect($structure);
                $commissionScoreCol = $structureCollection->firstWhere('name', 'Puntaje de la Comisión Dictaminadora');
                $observationsCol = $structureCollection->firstWhere('name', 'Observaciones');
                
                $commissionScoreKey = $commissionScoreCol['key'] ?? 'puntaje_de_la_comision_dictaminadora';
                $observationsKey = $observationsCol['key'] ?? 'observaciones';

                // Mapear los datos enviados por el dictaminador para un acceso rápido
                $submittedDataMap = collect($validatedData['rows'])->keyBy('row_identifier');

                // Actualizar el array 'rows' en la data de la respuesta
                if (isset($responseData['rows']) && is_array($responseData['rows'])) {
                    foreach ($responseData['rows'] as $index => &$row) {
                        if ($submittedDataMap->has($index)) {
                            $submittedRow = $submittedDataMap->get($index);
                            
                            // Asignar el puntaje y las observaciones de la comisión a las claves correspondientes
                            $row[$commissionScoreKey] = $submittedRow['puntaje_comision'] ?? ($row[$commissionScoreKey] ?? '');
                            $row[$observationsKey] = $submittedRow['observaciones'] ?? ($row[$observationsKey] ?? '');
                        }
                    }
                }

                // Guardar la data actualizada y la información del evaluador en la respuesta principal
                $response->data = $responseData;
                $response->evaluador_id = $evaluator ? $evaluator->id : null;
                $response->evaluador_email = $evaluator ? $evaluator->email : null;
                $response->save();
            }

            return response()->json(['success' => true, 'message' => 'Datos actualizados correctamente.']);
        } catch (\Exception $e) {
            \Log::error('Error al actualizar datos de comisión: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Obtiene los datos de un formulario específico para un docente
     */
    public function getTeacherFormData($email, $formName)
    {
        // Verificar si el email es válido
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return response()->json(['success' => false, 'message' => 'Email inválido']);
        }

        try {
            // Buscar el formulario
            $form = DynamicForm::where('form_name', $formName)->first();
            if (!$form) {
                return response()->json(['success' => false, 'message' => 'Formulario no encontrado']);
            }

            // Buscar información del docente
            $docente = User::where('email', $email)
                ->where('user_type', 'docente')
                ->first();
            if (!$docente) {
                return response()->json(['success' => false, 'message' => 'Docente no encontrado']);
            }

            // Obtener las columnas y valores del formulario desde los campos JSON
            $columns = $form->form_structure;
            $values = $form->form_data;

            // Obtener datos de comisión existentes para este docente y formulario
            $commissionData = DynamicFormCommission::where('dynamic_form_id', $form->id)
                ->where('email_docente', $email)
                ->get();

            // Registrar la operación en los logs para debugging
            \Log::info('Datos cargados para el docente y formulario', [
                'docente' => $email,
                'form_name' => $formName,
                'commission_data_count' => $commissionData->count(),
                'commission_data' => $commissionData->toArray() // Mostrar los datos para debugging
            ]);

            return response()->json([
                'success' => true,
                'form_id' => $form->id,
                'columns' => $columns,
                'values' => $values,
                'commission_data' => $commissionData,
                'puntaje_maximo' => $form->puntaje_maximo,
                'acreditacion' => $form->acreditacion,
                'teacher' => [
                    'id' => $docente->id,
                    'name' => $docente->name,
                    'email' => $docente->email
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error al obtener datos del formulario para docente: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Muestra una vista genérica para un formulario dinámico por su nombre.
     */
//     public function showDynamicFormByName($form_name)
//     {
//         $form = DynamicForm::where('form_name', $form_name)->first();

//         if (!$form) {
//             abort(404, 'Formulario no encontrado.');
//         }

//  // Cargar todos los formularios de sección 3
//     $dynamicForms = DynamicForm::where('form_type', 'like', '3.%')->get();
//     $staticFormTypes = [ '3.1', '3.2', '3.3', '3.4', '3.5', '3.6', '3.7', '3.8', '3.8.1', '3.9', 
//     '3.10', '3.11', '3.12', '3.13', '3.14', '3.15', '3.16', '3.17', '3.18', '3.19'];
//     $staticStepCount = 20; // igual que en tu blade

//     return view('docencia', [
//         'currentForm' => $form,
//         'dynamicForms' => $dynamicForms,
//         'staticFormTypes' => $staticFormTypes,
//         'staticStepCount' => $staticStepCount
//     ]);
//     }

/** @var \App\Models\User $user */

public function showDynamicFormByName(Request $request, $form_name)
{
    $form = DynamicForm::where('form_name', $form_name)->firstOrFail();
    
    if (is_string($form->form_structure)) {
        $form->form_structure = json_decode($form->form_structure, true) ?? [];
    }
    if (is_string($form->form_data)) {
        $form->form_data = json_decode($form->form_data, true) ?? [];
    }

    $user = Auth::user();

        // Obtener convocatoria y periodo para el footer
        $form1 = \App\Models\UsersResponseForm1::where('user_id', $user->id)->first();
        $convocatoria = ($form1 && $form1->convocatoria) ? $form1->convocatoria : 'Convocatoria no asignada';
        $periodo = ($form1 && $form1->periodo) ? $form1->periodo : (\App\Models\UsersResponseForm1::calculateCurrentPeriod() ?? 'Periodo no definido');

    $currentResponse = DynamicFormResponse::where('dynamic_form_id', $form->id)
        ->where('user_id', $user->id)
        ->first();

    // Si existe una respuesta, usar el array 'rows'. Si no, usar los datos base del formulario.
    $renderData = ($currentResponse && isset($currentResponse->data['rows']))
        ? $currentResponse->data['rows']
        : $form->form_data;

    if (is_string($renderData)) {
        $renderData = json_decode($renderData, true) ?? [];
    }
    if (!is_array($renderData)) $renderData = [];

    // ✅ Normalizar formulario actual
    $normalizedData = [];

    foreach ($renderData as $row) {
        $normalizedRow = [];

        foreach ($form->form_structure as $column) {
            $key = $column['key'];
            $normalizedRow[$key] = $row[$key] ?? '';
        }

        $normalizedData[] = $normalizedRow;
    }

    $renderData = $normalizedData;

    // ✅ Formularios dinámicos sección 3
    $dynamicForms = DynamicForm::where(function($query) {
        $query->where('form_type', 'like', '3.%')
              ->orWhere('form_name', 'like', '3.%');
    })->get();

    $renderDataByForm = [];

    foreach ($dynamicForms as $df) {
        if (is_string($df->form_structure)) {
            $df->form_structure = json_decode($df->form_structure, true) ?? [];
        }
        if (is_string($df->form_data)) {
            $df->form_data = json_decode($df->form_data, true) ?? [];
        }

        $dfResponse = DynamicFormResponse::where('dynamic_form_id', $df->id)
            ->where('user_id', $user->id)
            ->first();

        $data = ($dfResponse && isset($dfResponse->data['rows']))
            ? $dfResponse->data['rows']
            : $df->form_data;

        if (is_string($data)) {
            $data = json_decode($data, true) ?? [];
        }
        if (!is_array($data)) $data = [];

        // ✅ Normalizar formularios dinámicos
        $normalizedDynamicData = [];

        foreach ($data as $row) {
            $normalizedRow = [];

            foreach ($df->form_structure as $column) {
                $key = $column['key'];
                $normalizedRow[$key] = $row[$key] ?? '';
            }

            $normalizedDynamicData[] = $normalizedRow;
        }

        $renderDataByForm[$df->id] = $normalizedDynamicData;
    }

    $staticFormTypes = [
        '3.1','3.2','3.3','3.4','3.5','3.6','3.7','3.8','3.8.1','3.9',
        '3.10','3.11','3.12','3.13','3.14','3.15','3.16','3.17','3.18','3.19'
    ];


    /* 
        $dynamicFormTypes= [];
    una pila no estatica de dynamicFormTypes, el length sera en base al numero de registros de dynamic_forms de la base de datos, 
    probablemente con un count() pero realizando un evento, trigger o transaccion desde la base de datos
    */

    $staticStepCount = 20;

    $view = $request->query('view') === 'single'
        ? 'dynamic_form_display'
        : 'docencia';

    return view($view, compact(
        'form',
        'dynamicForms',
        'staticFormTypes',
        'staticStepCount',
        'renderData',
        'renderDataByForm',
        'convocatoria',
        'periodo'
    ));
}

/**
 * Guarda la respuesta de un docente para un formulario dinámico.
 */
public function saveResponse(Request $request)
{
    $validator = Validator::make($request->all(), [
        'form_id' => 'required|exists:dynamic_forms,id',
        'data' => 'required|array',
    ]);

    if ($validator->fails()) {
        return response()->json(['success' => false, 'message' => 'Datos inválidos.', 'errors' => $validator->errors()], 422);
    }

    $validatedData = $validator->validated();
    $user = Auth::user();

    try {
        $form = DynamicForm::findOrFail($validatedData['form_id']);
        $structure = $form->form_structure;
        $maxScore = $form->puntaje_maximo;

        $submittedData = $validatedData['data'];
        $processedData = [];
        $totalScore = 0;

        // Determinar la clave de la columna de puntaje
        $scoreKey = 'puntaje_a_evaluar'; // default
        $structureCollection = collect($structure);
        $scoreColumn = $structureCollection->firstWhere('group', 'evaluacion');
        if ($scoreColumn && isset($scoreColumn['key'])) {
            $scoreKey = $scoreColumn['key'];
        }

        // Procesar los datos enviados y calcular el puntaje total
        foreach ($submittedData as $row) {
            $normalizedRow = [];
            foreach ($structure as $column) {
                $key = $column['key'];
                // Asegurar que todas las claves de la estructura existan en la fila guardada
                $normalizedRow[$key] = $row[$key] ?? '';
            }
            
            // Sumar el puntaje de la fila al total
            if (isset($normalizedRow[$scoreKey]) && is_numeric($normalizedRow[$scoreKey])) {
                $totalScore += (float)$normalizedRow[$scoreKey];
            }

            $processedData[] = $normalizedRow;
        }

        // Aplicar el puntaje máximo al total
        $cappedTotalScore = min($totalScore, $maxScore);

        // Estructura final a guardar en el campo 'data'
        $dataToSave = [
            'rows' => $processedData,
            '_total_score' => $cappedTotalScore,
            '_comision_total_score' => $totalScore,
        ];

        // Guardar o actualizar la respuesta en la base de datos
        DynamicFormResponse::updateOrCreate(
            ['dynamic_form_id' => $form->id, 'user_id' => $user->id],
            ['user_type' => $user->user_type, 'data' => $dataToSave]
        );

        return response()->json(['success' => true, 'message' => 'Respuesta guardada correctamente.']);

    } catch (\Exception $e) {
        Log::error('Error al guardar respuesta de formulario dinámico: ' . $e->getMessage(), ['request' => $request->all(), 'user_id' => $user->id]);
        return response()->json(['success' => false, 'message' => 'Ocurrió un error interno al guardar la respuesta.'], 500);
    }
}

    /**
     * Obtiene todos los formularios dinámicos y sus puntajes de comisión para un usuario.
     */
    public function getAllDynamicFormsWithScores(Request $request)
    {
        $email = $request->query('email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json([]);
        }

        $forms = DynamicForm::all();
        $results = [];

        foreach ($forms as $form) {
            $commissionScore = DynamicFormCommission::where('dynamic_form_id', $form->id)
                ->where('email_docente', $email)
                ->sum('puntaje_comision');

            $results[] = [
                'id' => $form->id,
                'form_name' => $form->form_name,
                'puntaje_maximo' => $form->puntaje_maximo,
                'score' => $commissionScore
            ];
        }

        return response()->json($results);
    }

}