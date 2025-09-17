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


    <div class="bg-gray-50 text-black/50">
        <div class="relative min-h-screen flex flex-col items-center justify-center">
            @if (Route::has('login'))
                @if (Auth::check())
                    <x-nav-menu :user="Auth::user()" />
                @endif

                <header class="grid grid-cols-2 items-center gap-2 py-10 lg:grid-cols-3">
                        <x-general-header />
                    <div class="flex lg:justify-center lg:col-start-2"></div>
                    <nav class="-mx-3 flex flex-1 justify-end"></nav>
                </header>

            @endif
            </div>
@php
$userType = Auth::user()->user_type;
    @endphp
            </div>
    <div class="container mt-4 printButtonClass">

    @if ($userType != 'docente')
 <!-- Select para usuario con user_type vacío seleccionando dictaminadores -->
    <label for="dictaminadorSelect">Seleccionar Dictaminador:</label>
    <select id="dictaminadorSelect" class="form-select">
        <option value="">Seleccionar un dictaminador</option>
        <!-- Aquí se llenarán los dictaminadores con JavaScript -->
    </select>
    @endif
        </select>
                        <button type="submit">Mostrar Datos</button>
            </div>

    </form>
                <input type="hidden" name="dictaminador_id" value="{{ Auth::user()->id }}">
                <input type="hidden" name="user_id" value="">
                <input type="hidden" name="email" value="">
                <input type="hidden" name="user_type" value="">
    <div id="user-data">
        <!-- Aquí se mostrará la información del usuario -->
    </div>

            
</body>
<script>
    document.getElementById('filter-form').addEventListener('submit', async function (event) {
            event.preventDefault();

            const email = document.getElementById('user-email').value;

            try {
                const response = await fetch(`/show-profile?email=${email}`, {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    }
                });

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                const data = await response.json();

                if (data.error) {
                    document.getElementById('user-data').innerHTML = `<p>${data.error}</p>`;
                    return;
                }

                // Mostrar datos del usuario en la vista
                document.getElementById('user-data').innerHTML = `
                <h2>Datos del Usuario</h2>
                <p>Email: ${data.user.email || 'No disponible'}</p>
                <p>Nombre: ${data.user.name || 'No disponible'}</p>
                <p>Convocatoria: ${data.responseForm1.convocatoria || 'No disponible'}</p>
                <p>Comisión Actividad 1 Total: ${data.resumen_calidad_y_total.comision_actividad_1_total || 'No disponible'}</p>
                <p>Comisión Actividad 2 Total: ${data.resumen_calidad_y_total.comision_actividad_2_total || 'No disponible'}</p>
                <p>Comisión Actividad 3 Total: ${data.resumen_calidad_y_total.comision_actividad_3_total || 'No disponible'}</p>
                <p>Total Puntaje: ${data.resumen_calidad_y_total.total_puntaje || 'No disponible'}</p>
                <p>Mínima Calidad: ${data.resumen_calidad_y_total.minima_calidad || 'No disponible'}</p>
                <p>Mínima Total: ${data.resumen_calidad_y_total.minima_total || 'No disponible'}</p>
                <h2>Persona Evaluadora y Firma: </h2>
                <p>Persona Evaluadora: ${data.signature.evaluator_name_1 || 'No disponible'} 
                <span style="margin-left: 50px;">Firma:</span> ${data.signature.signature_path_1 ? `<img src="/storage/${data.signature.signature_path_1}" alt="Firma" style="max-width: 200px;"/>` : 'No disponible'}</p>
                <p>Persona Evaluadora: ${data.signature.evaluator_name_2 || 'No disponible'}
                <span style="margin-left: 50px;">Firma:</span> ${data.signature.signature_path_2 ? `<img src="/storage/${data.signature.signature_path_2}" alt="Firma" style="max-width: 200px;"/>` : 'No disponible'}
                </p>
                <p>Persona Evaluadora: ${data.signature.evaluator_name_3 || 'No disponible'}
                <span style="margin-left: 50px;">Firma:</span> ${data.signature.signature_path_3 ? `<img src="/storage/${data.signature.signature_path_3}" alt="Firma" style="max-width: 200px;"/>` : 'No disponible'}
                </p>
            `;

            } catch (error) {
                console.error('There was a problem with the fetch operation:', error);
                document.getElementById('user-data').innerHTML = `<p>Error al obtener datos del usuario.</p>`;
            }
        });


            document.addEventListener('DOMContentLoaded', async () => {

                const dictaminadorSelect = document.getElementById('dictaminadorSelect');

                // Current user type from the backend
                const userType = @json($userType);  // Get user type from backend
                // Fetch dictaminador options if user type is null or empty
                if (dictaminadorSelect && userType != 'docente') {
                    try {
                        const response = await fetch('/get-dictaminadores');
                        const dictaminadores = await response.json();

                        dictaminadores.forEach(dictaminador => {
                            const option = document.createElement('option');
                            option.value = dictaminador.id;  // Use dictaminador ID as value
                            option.dataset.email = dictaminador.email; // Store email in data attribute
                            option.textContent = dictaminador.email;
                            dictaminadorSelect.appendChild(option);
                        });

                        // Handle dictaminador selection change
                        dictaminadorSelect.addEventListener('change', async (event) => {
                            const dictaminadorId = event.target.value;
                            const email = event.target.options[event.target.selectedIndex].dataset.email;  // Get email from selected option

                            if (dictaminadorId) {
                                try {
                                    const response = await axios.get('/get-dictaminador-data', {
                                        params: { email: email, dictaminador_id: dictaminadorId }  // Send both ID and email
                                    });
                                    const data = response.data;

                                    document.querySelector('input[name="dictaminador_id"]').value = data.dictaminador.dictaminador_id || '0';
                                    document.querySelector('input[name="user_id"]').value = data.dictaminador.user_id || '';
                                    document.querySelector('input[name="email"]').value = data.dictaminador.email || '';
                                    document.querySelector('input[name="user_type"]').value = data.dictaminador.user_type || '';
                                    // Populate fields based on fetched data
                                    if (data.form4) {

                                        


                                    } else {
                                        console.error('No form data found for the selected dictaminador.');

                                        // Reset input values if no data found
                                        document.querySelector('input[name="dictaminador_id"]').value = '0';
                                        document.querySelector('input[name="user_id"]').value = '0';
                                        document.querySelector('input[name="email"]').value = '';
                                        document.querySelector('input[name="user_type"]').value = '';

                                       

                                    }
                                } catch (error) {
                                    console.error('Error fetching dictaminador data:', error);
                                }
                            }
                        });
                    } catch (error) {
                        console.error('Error fetching dictaminadores:', error);
                        alert('No se pudo cargar la lista de dictaminadores.');
                    }
                }
            });

    </script>
</script>

</html>