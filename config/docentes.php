<?php

/**
 * Configuración de Docentes
 *
 * Estructura normalizada por email.
 * El email actúa como clave única del docente.
 * 
 * NOTA:
 * - Departamentos y áreas serán reemplazables por datos reales vía API
 */

return [

    'jperez@uabcs.mx' => [
        'nombre' => 'M.C Juan Carlos Pérez Concha',
        'departamento' => 'Ciencias Sociales y Juridicas',
        'area' => 'Ciencias Sociales y Juridicas',
    ],

    'iestrada@uabcs.mx' => [
        'nombre' => 'M.S.C. Italia Estrada Cota',
        'departamento' => 'Sistemas Computacionales',
        'area' => 'Sistemas Computacionales',
    ],

    'antoninaIv@uabcs.mx' => [
        'nombre' => 'Dra. Antonina Ivanova Boncheva',
        'departamento' => 'Ciencias Sociales y Humanidades',
        'area' => 'Humanidades',
    ],

    //Prueba 2, con la nueva arquitectura de configuración
        'javtandiaz@uabcs.mx' => [
        'nombre' => 'Dr. Javier Gaytan Diaz',
        'departamento' => 'Ciencias del Mar y de la Tierra',
        'area' => 'geologia',
    ],


];
