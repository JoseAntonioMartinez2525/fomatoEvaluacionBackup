<?php

/**
 * Configuración de Docentes
 * 
 * IMPORTANTE: Todos los arrays (emails, nombres, departamentos, areas) deben tener
 * el MISMO número de elementos y estar ALINEADOS por índice.
 * 
 * Ejemplo:
 * - emails[0] debe corresponder a nombres[0], departamentos[0], areas[0]
 * - emails[1] debe corresponder a nombres[1], departamentos[1], areas[1]
 * - etc.
 * 
 * Si un docente no tiene un valor específico para algún campo, use una cadena vacía ''
 * pero NO omita el elemento del array.
 */

return [
    'emails' => [
        // Agrega aquí los correos que actuarán como docentes para pruebas
        'jperez@uabcs.mx',          // Índice 0
        'iestrada@uabcs.mx',        // Índice 1
        'antoninaIv@uabcs.mx',      // Índice 2
        // ... duplicado de dictaminadores o correos específicos de prueba
    ],
    
    'nombres' => [
        'M.C Juan Carlos Pérez Concha',        // Índice 0 - corresponde a jperez@uabcs.mx
        'M.S.C. Italia Estrada Cota',          // Índice 1 - corresponde a iestrada@uabcs.mx
        'Dra. Antonina Ivanova Boncheva',      // Índice 2 - corresponde a antoninaIv@uabcs.mx
        // ...
    ],

    /*Departamento Académico*/
    'departamentos' => [
        'Ciencias Sociales y Juridicas',       // Índice 0 - corresponde a jperez@uabcs.mx
        'Sistemas Computacionales',            // Índice 1 - corresponde a iestrada@uabcs.mx
        'Ciencias Sociales y Humanidades',     // Índice 2 - corresponde a antoninaIv@uabcs.mx
        // ...
    ],

    /*Área de Conocimiento*/ 
    'areas' => [
        'Ciencias Sociales y Juridicas',       // Índice 0 - corresponde a jperez@uabcs.mx
        'Sistemas Computacionales',            // Índice 1 - corresponde a iestrada@uabcs.mx
        'Humanidades',                         // Índice 2 - corresponde a antoninaIv@uabcs.mx
        // ...
    ],

];
