<?php

return [
    'emails' => [
        'bramirez@uabcs.mx',
        'jperez@uabcs.mx',
        'sandoval@uabcs.mx',
        'mcoronado@uabcs.mx',
        'isaias@uabcs.mx', 
        'mpina@uabcs.mx',
        'mycortes@uabcs.mx',
        'imaz@uabcs.mx',
        'gbrabata@uabcs.mx',
        'rdeluna@uabcs.mx',
        'abeltran@uabcs.mx',
        'iestrada@uabcs.mx',
        'rborquez@uabcs.mx',
        'brendaran@uabcs.mx',  //pruebas


    ],
    'nombres' => [
        'Dra. Brenda Elizabeth Ramírez Díaz',
        'M.C Juan Carlos Pérez Concha',
        'Dr. Jesús Andrés Sandoval Bringas',
        'Dr. Manuel Arturo Coronado García',
        'Dr. Francisco Isaías Ruiz Ceseña',
        'Dra. Marta Piña Zentella',
        'Dra. Mara Yadira Cortés Martínez',
        'Dr. Miguel Ángel Imaz Lamadrid',
        'Dra. Georgina Brabata Domínguez',
        'Dr. Rafael de Luna de la Peña',
        'Dr. Félix Alfredo Beltrán Morales',
        'M.S.C. Italia Estrada Cota',
        'Dr. Ricardo Bórquez Reyes',

        //Pruebas
        'Dra. Brenda Díaz Romo'  

    ],

    /*Departamentos y/o areas */
    'departamentos' => [
        'Ciencias Sociales y Juridicas',
        'Ciencias Sociales y Juridicas',
        'Sistemas Computacionales',
        'Ciencias Sociales y Juridicas',
        'Ciencias Sociales y Juridicas',
        'Ciencia animal y Conservación del habitat', 
        'Ciencias de la tierra', 
        'Ciencias Marinas y Costeras',
        'Humanidades',
        'Humanidades',
        'Ingenieria en Pesquerias',
        'Sistemas Computacionales',
        'Ingenieria en Pesquerias',
        'Ciencias Sociales y Juridicas', // Ejemplo para el último email (brendaran@uabcs.mx)

    ],
];



// 'email' => [
//     'required',
//     'string',
//     'email',
//     'max:255',
//     'unique:users,email',
//     function ($attribute, $value, $fail) {
//         if (!str_ends_with($value, '@uabcs.mx')) {
//             $fail('El correo debe pertenecer al dominio @uabcs.mx');
//         }
//     },
// ],
