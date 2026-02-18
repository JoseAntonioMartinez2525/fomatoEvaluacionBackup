@php
$userType = Auth::user()->user_type ?? null;
$logo = 'https://www.uabcs.mx/transparencia/assets/images/logo_uabcs.png';

$docentePeriod = \DB::table('evaluation_dates')
->where('type', 'docentes_llenado')
->latest('id')
->first();

$isDocentePeriodFinished = $docentePeriod ? now()->gt(\Carbon\Carbon::parse($docentePeriod->end_date)->endOfDay()) :
false;
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="{{ $logo }}" type="image/png">
  <title>Evaluación docente</title>

  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
  <link href="{{ asset('css/print.css') }}" rel="stylesheet" type="text/css" media="print" />
  <link href="{{ asset('css/darkmode.css') }}" rel="stylesheet">
  <script src="https://kit.fontawesome.com/e72e299160.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
  </script>

  <script>
    window.isDarkModeGlobal = {{ $darkMode ?? false ? 'true' : 'false' }};
  </script>

  <style>
    body {
      margin-left: 200px;
      margin-bottom: 600px;
      display: flex;
      justify-content: space-between;
      background-color: #f4f4f4;
    }

    .enlaceSN {
      color: #4281a4;
    }

    .enlaceSN:hover {
      color: #086375;
    }

    nav {
      margin-left: -180px;
      padding-top: 50px;
      width: 300px;
      height: 2000px !important;
    }

    .deptos ul {
      list-style-type: none;
      padding: 0;
    }

    .deptos li {
      margin-bottom: 10px;
      margin-left: 20px;
      list-style-type: none;
      color: white;
    }

    table {
      border-collapse: collapse;
      min-width: 320px;
      border: 4px solid black !important;
      background: white;
      margin: 0;
    }

    .borderless th {
      border: none;
    }

    th,
    td {
      border: solid black;
      padding: 8px;
      width: 50px;
      text-align: center;
    }

    .table-container {
      display: flex;
      justify-content: center;
      align-items: flex-start;
      gap: 60px;
      max-width: fit-content;
      margin-top: 160px;
      margin-inline-start: -700px;
    }

    .table-container2 {
      margin-bottom: 100px;
      justify-content: space-between;
      max-width: fit-content;
      margin-inline-start: -43.9rem;
    }

    .nav-max-content {
      min-width: 313px;
      z-index: 2;
    }

    .content-area {
      flex: 1;
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      justify-content: flex-start;
      padding: 40px 0 0 16px;
      min-width: 0;
    }

    .nav-max-content a {
      color: white;
      font-size: larger;
    }

    .a-font-larger a {
      font-size: larger;
      color: white;
    }

    body.dark-mode nav.nav.flex-column {
      background: linear-gradient(90deg, rgb(14, 34, 69), rgb(13, 31, 63)) !important;
    }

    body.dark-mode nav.nav.flex-column a:hover {
      color: rgb(122, 164, 237);
    }

    .main-layout {
      display: flex;
      min-height: 100vh;
    }

    li.nav-item {
      margin-inline-start: 2rem;
    }
  </style>
</head>

<body class="font-sans antialiased {{ $bodyClass ?? 'light-mode' }}">

  @if (Route::has('login') && Auth::check())
  <x-nav-menu :user="Auth::user()" navClass="nav-max-content" emailClass="a-font-larger" :apply-date-rules="true">
    <div>
      <ul style="list-style: none;">
        @if ($userType === 'docente' || !$isDocentePeriodFinished)
        <li class="nav-item">
          <a class="nav-link active enlaceSN" style="width: 250px; font-size: 20px;" href="{{ route('welcome') }}"
            title="Formato de Evaluación docente">
            <i class="fa-solid fa-align-justify"></i>&nbspEvaluación
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link active enlaceSN" style="width: 200px;" href="{{ url('docencia') }}"
            title="Actividades 3. Calidad en la docencia">
            <i class="fas fa-chalkboard-teacher"></i>&nbspCalidad en la docencia
          </a>
        </li>
        @endif
      </ul>
    </div>
  </x-nav-menu>
  @endif

  <x-general-header />

  <div class="main-layout">
    <div class="content-area">

      <div class="table-container">
        <table class="table table-bordered" style="margin-top: 40px;">
          <thead>
            <tr class="borderless">
              <th style="border-left: solid 1px;">&nbsp;</th>
              <th>Artículo 10 REGLAMENTO PEDPD</th>
              <th style="border-right: solid 1px;">&nbsp;</th>
            </tr>
            <tr>
              <th>PUNTUACIÓN TOTAL MÍNIMA</th>
              <th>&nbsp;</th>
              <th>NIVEL</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>301</td>
              <td>377.99</td>
              <td>I</td>
            </tr>

            @php
            $minima = [378, 455.99, 456, 533.99, 534, 611.99, 612, 689.99, 690, 767.99, 768, 845.99, 846, 923.99, 924,
            1000];
            $nivel = ['II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX'];
            @endphp

            @for ($i = 0; $i < count($minima); $i +=2) <tr>
              <td>{{ $minima[$i] }}</td>
              <td>{{ $minima[$i + 1] }}</td>
              <td>{{ $nivel[$i / 2] }}</td>
              </tr>
              @endfor
          </tbody>
        </table>
      </div>

      <table class="table table-bordered table-container2" style="margin-top: 40px;">
        <thead>
          <tr>
            <th>PUNTUACIÓN MÍNIMA DE CALIDAD</th>
            <th>NIVEL</th>
          </tr>
        </thead>
        <tbody>
          @php
          $puntuacion_minima = [210, 265, 320, 375, 430, 485, 540, 595, 650];
          $puntuacion_maxima = [264.99, 319.99, 374.99, 429.99, 484.99, 539.99, 594.99, 649.99, 704];
          $nivel = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX'];
          @endphp

          @for ($i = 0; $i < count($puntuacion_minima); $i++) <tr>
            <td>{{ $puntuacion_minima[$i] }} - {{ $puntuacion_maxima[$i] }}</td>
            <td>{{ $nivel[$i] }}</td>
            </tr>
            @endfor
        </tbody>
      </table>

    </div>
  </div>

  <script>
    const A40 = 6.25;
    const B56 = 17;
    const B57 = 50;
    const variables = {};
    const variablesMultiplicadas = {};

    for (let i = 40; i <= 55; i++) {
      variables[`B${i}`] = i - 39;
      variablesMultiplicadas[`C${i}`] = A40 * variables[`B${i}`];
    }

    const C56 = B56 * A40;
    const C57 = B57 * A40;
    console.log(variablesMultiplicadas);
  </script>

</body>

</html>