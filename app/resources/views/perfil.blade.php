@php
$locale = app()->getLocale() ?: 'en';
$newLocale = str_replace('_', '-', $locale);
$logo = 'https://www.uabcs.mx/transparencia/assets/images/logo_uabcs.png';
@endphp
<!DOCTYPE html>
<html lang="{{ $newLocale }}">

<head>
    <link rel="icon" href="{{ $logo }}" type="image/png">
    <title>Evaluación docente</title> 
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <x-head-resources />

</head>

<body>
<div class="bg-gray-50 text-black/50">
        <x-general-header />
        <div class="relative min-h-screen flex flex-col items-center justify-center">
            @if (Route::has('login'))
                @if (Auth::check())
                    <x-nav-menu :user="Auth::user()" />
                @endif
    <header class="grid grid-cols-2 items-center gap-2 py-10 lg:grid-cols-3">
        <div class="flex lg:justify-center lg:col-start-2"></div>
        <nav class="-mx-3 flex flex-1 justify-end"></nav>
    </header>
                <form id="form6" method="GET" enctype="multipart/form-data"
                    onsubmit="event.preventDefault(); submitForm('/show-profile', 'form6');">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                    <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                    <h1>Reporte del Perfil de Usuario</h1>

                        <p>Email: 
                            <span id="email"></span>
                        </p>
                        <p>Usuario:
                            <span id="user"></span>
                        </p>
                        <p>Convocatoria:
                            <span id="convocatoria"></span>
                        </p>
                        <p>Comisión Actividad 1 Total: 
                            <span id="comision_actividad_1_total"></span>
                        </p>
                        <p>Comisión Actividad 2 Total: 
                            <span id="comision_actividad_2_total"></span>
                        </p>
                        <p>Comisión Actividad 3 Total: 
                            <span id="comision_actividad_3_total"></span>
                        </p>
                        <p>Total Puntaje: 
                            <span id="total_puntaje"></span>
                        </p>
                        <p>Mínima Calidad: 
                            <span id="minima_calidad"></span>
                        </p>
                        <p>Mínima Total: 
                            <span id="minima_total"></span>
                        </p>


                    <h1>Personas Evaluadoras y Firmas</h1>

                        <table>
                            <thead>
                                <tr>
                                <td>Nombre de la Persona Evaluadora:  <span id="evaluator_name_1"></span></td>    
                                <td><img id="signature_path_1" alt="Signature" style="max-width: 500px;"></td>
                            </tr>
                            </thead>
                            <thead>
                                <tr>
                                    <td>Nombre de la Persona Evaluadora: <span id="evaluator_name_2"></span></td>
                                    <td><img id="signature_path_2" alt="Signature" style="max-width: 500px;"></td>
                                </tr>
                            </thead>
                            <thead>
                                <tr>
                                    <td>Nombre de la Persona Evaluadora: <span id="evaluator_name_3"></span></td>
                                    <td><img id="signature_path_3" alt="Signature" style="max-width: 500px;"></td>
                                </tr>
                            </thead>

                        </table>
                        </form>
            @endif
</body>
<script>
             document.addEventListener('DOMContentLoaded', function () {
                    const userId = {{ auth()->user()->id }}; // Assuming you have user data

                async function fetchData(url, params = {}) {
                    const queryString = new URLSearchParams(params).toString();
                    const fullUrl = `${url}?${queryString}`;

                    try {
                        let response = await fetch(fullUrl, {
                            method: 'GET',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Content-Type': 'application/json',
                            }
                        });

                        console.log('Response from API:', response);
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }

                        let data = await response.json();
                        return data;
                    } catch (error) {
                        console.error('There was a problem with the fetch operation:', error);
                    }
                }
        async function loadAllData() {

            const userId = {{ auth()->user()->id }};
            const userEmail = "{{ auth()->user()->email }}";
            
            let dataResume = await fetchData('/get-data-resume', { user_id: userId });
            let dataSignature = await fetchData('/get-evaluator-signature', { user_id: userId , email: userEmail });
            let dataUser = await fetchData('/get-data1', { user_id: userId });

            // Populate labels with the retrieved data
            document.getElementById('email').innerText = dataResume ? dataResume.email : '';
            document.getElementById('user').innerText = dataUser ? dataUser.nombre : '';
            document.getElementById('convocatoria').innerText = dataUser ? dataUser.convocatoria : '';
            document.getElementById('comision_actividad_1_total').innerText = dataResume ? dataResume.comision_actividad_1_total : '';
            document.getElementById('comision_actividad_2_total').innerText = dataResume ? dataResume.comision_actividad_2_total : '';
            document.getElementById('comision_actividad_3_total').innerText = dataResume ? dataResume.comision_actividad_3_total : '';
            document.getElementById('total_puntaje').innerText = dataResume ? dataResume.total_puntaje : '';
            document.getElementById('minima_calidad').innerText = dataResume ? dataResume.minima_calidad : '';
            document.getElementById('minima_total').innerText = dataResume ? dataResume.minima_total : '';
            
            if (!dataSignature) {
                console.error('No dataSignature returned');
                return;
            }
            
            document.getElementById('evaluator_name_1').innerText = dataSignature.evaluator_name_1 || 'No evaluator name found';
            document.getElementById('signature_path_1').src = '/storage/' + (dataSignature.signature_path_1 || 'default.png');
            
            document.getElementById('evaluator_name_2').innerText = dataSignature.evaluator_name_2 || 'No evaluator name found';
            document.getElementById('signature_path_2').src = '/storage/' + (dataSignature.signature_path_2 || 'default.png');

            document.getElementById('evaluator_name_3').innerText = dataSignature.evaluator_name_3 || 'No evaluator name found';
            document.getElementById('signature_path_3').src = '/storage/' + (dataSignature.signature_path_3 || 'default.png');
            

        }

        loadAllData();
             });

             
                document.addEventListener('DOMContentLoaded', function () {
                    const userEmail = "{{ Auth::user()->email }}"; // Obtén el email del usuario desde Blade

                    // Verifica si el email es el que esperas
                    if (userEmail === 'joma_18@alu.uabcs.mx') {
                        // Muestra el enlace
                        document.getElementById('jsonDataLink').classList.remove('d-none');
                    }
                });
    
</script>

</html>

