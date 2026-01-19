<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DocenteFormsController extends Controller
{
    /**
     * Display a list of all docentes assigned to the current dictaminador
     */
    public function index()
    {
        // Get all docentes assigned to current dictaminador
        // $docentes = DB::table('dictaminador_docente')
        //     ->where('dictaminador_id', Auth::id())
        //     ->join('users', 'dictaminador_docente.docente_id', '=', 'users.id')
        //     ->select('users.id', 'users.name', 'users.email')
        //     ->distinct()
        // Se modifica para listar todos los docentes del sistema, permitiendo evaluar a cualquiera
        $docentes = DB::table('users')
            ->where('user_type', 'docente')
            ->select('id', 'name', 'email')
            ->orderBy('name')
            ->get();

        return view('dictaminador.docentes_list', compact('docentes'));
    }

    /**
     * Display all completed forms for a specific docente
     */
    public function show($docenteEmail)
    {
        // Verify this docente is assigned to current dictaminador

        // $isAssigned = DB::table('dictaminador_docente')
        //     ->where('dictaminador_id', Auth::id())
        //     ->where('docente_email', $docenteEmail)
        //     ->exists();
        // // Se elimina la restricción de asignación previa para permitir que el dictaminador
        // // pueda acceder a los formularios de cualquier docente listado.

        // if (!$isAssigned) {
        //     abort(403, 'No tiene permiso para ver los formularios de este docente.');
        // }

        // Se elimina la restricción de asignación previa para permitir que el dictaminador
        // pueda acceder a los formularios de cualquier docente listado.

        // Get docente information
        $docente = DB::table('users')
            ->where('email', $docenteEmail)
            ->first();

        // Define all form tables and their display names
        $formTables = [
            'form2' => [
                'table' => 'users_response_form2',
                'name' => '1. Permanencia en las actividades de la docencia',
                'route' => 'getData2',
                'view_route' => 'form2'
            ],
            'form2_2' => [
                'table' => 'users_response_form2_2',
                'name' => '2. Dedicación en el desempeño docente',
                'route' => 'getData22',
                'view_route' => 'form2_2'
            ],
            'form3_1' => [
                'table' => 'users_response_form3_1',
                'name' => '3.1 Participación en actividades de diseño curricular',
                'route' => 'getData31',
                'view_route' => 'form3_1'
            ],
            'form3_2' => [
                'table' => 'users_response_form3_2',
                'name' => '3.2 Calidad del desempeño docente evaluada por el alumnado',
                'route' => 'getData32',
                'view_route' => 'form3_2'
            ],
            'form3_3' => [
                'table' => 'users_response_form3_3',
                'name' => '3.3 Publicaciones relacionadas con la docencia',
                'route' => 'getData33',
                'view_route' => 'form3_3'
            ],
            'form3_4' => [
                'table' => 'users_response_form3_4',
                'name' => '3.4 Distinciones académicas recibidas por el docente',
                'route' => 'getData34',
                'view_route' => 'form3_4'
            ],
            'form3_5' => [
                'table' => 'users_response_form3_5',
                'name' => '3.5 Asistencia, puntualidad y permanencia en el desempeño docente',
                'route' => 'getData35',
                'view_route' => 'form3_5'
            ],
            'form3_6' => [
                'table' => 'users_response_form3_6',
                'name' => '3.6 Capacitación y actualización pedagógica recibida',
                'route' => 'getData36',
                'view_route' => 'form3_6'
            ],
            'form3_7' => [
                'table' => 'users_response_form3_7',
                'name' => '3.7 Cursos de actualización disciplinaria',
                'route' => 'getData37',
                'view_route' => 'form3_7'
            ],
            'form3_8' => [
                'table' => 'users_response_form3_8',
                'name' => '3.8 Impartición de cursos, diplomados, seminarios',
                'route' => 'getData38',
                'view_route' => 'form3_8'
            ],
            'form3_8_1' => [
                'table' => 'users_response_form3_8_1',
                'name' => '3.8.1 RSU',
                'route' => 'getData381',
                'view_route' => 'form3_8_1'
            ],
            'form3_9' => [
                'table' => 'users_response_form3_9',
                'name' => '3.9 Trabajos dirigidos para la titulación',
                'route' => 'getData39',
                'view_route' => 'form3_9'
            ],
            'form3_10' => [
                'table' => 'users_response_form3_10',
                'name' => '3.10 Tutorías a estudiantes',
                'route' => 'getData310',
                'view_route' => 'form3_10'
            ],
            'form3_11' => [
                'table' => 'users_response_form3_11',
                'name' => '3.11 Asesoría a estudiantes',
                'route' => 'getData311',
                'view_route' => 'form3_11'
            ],
            'form3_12' => [
                'table' => 'users_response_form3_12',
                'name' => '3.12 Publicaciones de investigación',
                'route' => 'getData312',
                'view_route' => 'form3_12'
            ],
            'form3_13' => [
                'table' => 'users_response_form3_13',
                'name' => '3.13 Proyectos académicos de investigación',
                'route' => 'getData313',
                'view_route' => 'form3_13'
            ],
            'form3_14' => [
                'table' => 'users_response_form3_14',
                'name' => '3.14 Participación como ponente en congresos',
                'route' => 'getData314',
                'view_route' => 'form3_14'
            ],
            'form3_15' => [
                'table' => 'users_response_form3_15',
                'name' => '3.15 Registro de patentes y productos de investigación',
                'route' => 'getData315',
                'view_route' => 'form3_15'
            ],
            'form3_16' => [
                'table' => 'users_response_form3_16',
                'name' => '3.16 Actividades de arbitraje y edición',
                'route' => 'getData316',
                'view_route' => 'form3_16'
            ],
            'form3_17' => [
                'table' => 'users_response_form3_17',
                'name' => '3.17 Proyectos académicos de extensión',
                'route' => 'getData317',
                'view_route' => 'form3_17'
            ],
            'form3_18' => [
                'table' => 'users_response_form3_18',
                'name' => '3.18 Organización de congresos institucionales',
                'route' => 'getData318',
                'view_route' => 'form3_18'
            ],
            'form3_19' => [
                'table' => 'users_response_form3_19',
                'name' => '3.19 Participación en cuerpos colegiados',
                'route' => 'getData319',
                'view_route' => 'form3_19'
            ],
            //Resumen
                'form4' => [
                'table' => 'users_final_resume',
                'name' => 'Resumen comision',
                'route' => 'getDictaminadorFinalData',
                'view_route' => 'form4'
            ]
        ];

        $completedForms = [];

        // Check each form table for completed forms
        foreach ($formTables as $formKey => $formInfo) {
            $formData = DB::table($formInfo['table'])
                ->where('email', $docenteEmail)
                ->first();

            if ($formData) {
                $completedForms[] = [
                    'form_key' => $formKey,
                    'form_name' => $formInfo['name'],
                    'completed_at' => $formData->created_at ?? null,
                    'updated_at' => $formData->updated_at ?? null,
                    'route' => isset($formInfo['view_route']) ? route($formInfo['view_route'], ['teacher' => $docenteEmail]) : '#',
                    'status' => 'completed'
                ];
            }
        }

        // Check dynamic forms
        // $dynamicForms = DB::table('dynamic_form_combined')
        //     ->where('email', $docenteEmail)
        //     ->get();

        // foreach ($dynamicForms as $dynamicForm) {
        //     $completedForms[] = [
        //         'form_key' => 'dynamic_' . $dynamicForm->id,
        //         'form_name' => $dynamicForm->form_type ?? 'Formulario Dinámico',
        //         'completed_at' => $dynamicForm->created_at ?? null,
        //         'updated_at' => $dynamicForm->updated_at ?? null,
        //         'route' => '#', // Dynamic forms route would need to be defined
        //         'status' => 'completed'
        //     ];
        // }

        // Sort by completion date (most recent first)
        usort($completedForms, function($a, $b) {
            return strtotime($b['completed_at']) - strtotime($a['completed_at']);
        });

        return view('dictaminador.docente_forms', compact('docente', 'completedForms', 'docenteEmail'));
    }
}