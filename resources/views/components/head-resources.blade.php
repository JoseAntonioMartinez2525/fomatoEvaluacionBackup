<!-- resources/views/components/head-resources.blade.php -->

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

<link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
<script src="https://kit.fontawesome.com/e72e299160.js" crossorigin="anonymous"></script>
<link href="{{ asset('css/app.css') }}" rel="stylesheet">
<link href="{{ asset('css/form3.css') }}" rel="stylesheet">
<link href="{{ asset('css/signatures.css') }}" rel="stylesheet">
<link href="{{ asset('css/print.css') }}" rel="stylesheet" type="text/css" media="print" />
<link href="{{ asset('css/darkmode.css') }}" rel="stylesheet">
<link href="{{ asset('css/search_bar.css') }}" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="{{ asset('js/subtotales.js') }}"></script>
<script src="{{ asset('js/dark_mode.js') }}"></script>
<script src="{{ asset('js/comisiones.js') }}"></script>
<script src="{{ asset('js/minimos.js') }}"></script>
<script src="{{ asset('js/privileges.js') }}"></script>
<script src="{{ asset('js/puntajeMaximo3_8_1.js') }}"></script>
<script src="{{ asset('js/messages.js') }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/3.0.3/polyfills.umd.js" integrity="sha512-zuET/f9xlS8Jf7OZXI9LwjJ96UHzXpELg5JMtyhpwNnsj3yq6ZJzhmB0++LxOPZNf5fu9AC53soGtlKwgeA2Pg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/react/17.0.2/umd/react.development.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/react-dom/17.0.2/umd/react-dom.development.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<!-- jQuery (requerido por Select2) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Select2  JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

{{-- bootstrap --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

<script>
window.isDarkModeGlobal = {{ $darkMode ?? false ? 'true' : 'false' }};
</script>