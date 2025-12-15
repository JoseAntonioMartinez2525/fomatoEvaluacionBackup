@props(['formId', 'hasData' => false, 'userType' => null])

<button id="edit-btn-{{ $formId }}"
        class="edit-button printButtonClass".
        style="{{ $hasData ? 'display:block;' : 'display:none;' }}"
        type="button">
    Editar
</button>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const formId = @json($formId);
    const initialHasData = @json($hasData);
    const userType = @json($userType);
    // Elementos
    const editBtn = document.getElementById(`edit-btn-${formId}`);
    let submitBtn = document.querySelector(`#${formId} button[type="submit"], #${formId} input[type="submit"]`);
    if (!submitBtn) submitBtn = document.getElementById(`${formId}Button`);

    /**Verificar casos especiales form3_1, form3_2, form3_3,...,form3_19**/

    console.log('[edit-button] init', { formId, initialHasData, userType, foundEditBtn: !!editBtn, foundSubmitBtn: !!submitBtn, windowExisting: !!window.existingDictData });

    if (!editBtn) {
        console.warn('[edit-button] No se encontró editBtn en el DOM:', `edit-btn-${formId}`);
        return;
    }

    // helpers
    function showEdit() {
        editBtn.style.display = '';
        if (submitBtn) submitBtn.style.display = 'none';
    }
    function showSubmit() {
        editBtn.style.display = 'none';
        if (submitBtn) {
            submitBtn.textContent = 'Enviar';
            submitBtn.style.display = '';
        }
    }

    // --------------------------------------------------
    // Función que trae datos existentes del servidor
    // --------------------------------------------------
    async function getExistingData() {
        console.log('[edit-button] numericPart test:', formId, formId.replace('form','').replace('_',''));

        const form = document.getElementById(formId);
        if (!form) return null;
        const dictaminadorId = form.querySelector('input[name="dictaminador_id"]')?.value;
        let userId = form.querySelector('input[name="user_id"]')?.value;
        const email = form.querySelector('input[name="email"]')?.value || '';

        const numericPart = formId.replace('form', '').replace('_', '');;

        let url = `/formato-evaluacion/get-form${numericPart}?dictaminador_id=${dictaminadorId}&user_id=${userId}`;

        if (form.dataset.customUrl === "true") {
            // Para URLs custom, priorizamos el email si el user_id no está presente,
            // que es el caso cuando se selecciona un docente por primera vez.
            if (email && !userId) {
                url = `/formato-evaluacion/get-form-data${numericPart}?dictaminador_id=${dictaminadorId}&email=${encodeURIComponent(email)}`;
            } else {
                url = `/formato-evaluacion/get-form-data${numericPart}?dictaminador_id=${dictaminadorId}&user_id=${userId}`;
            }
        }

        try {
            const r = await fetch(url);
            const j = await r.json();
            return j.success ? j.data : null;
        } catch (err) {
            console.error('[edit-button] fetch error', err);
            return null;
        }
    }

    // --------------------------------------------------
    // Check inicial: prioridad
    // 1) si blade dice hasData true -> mostrar Edit
    // 2) si userType === 'secretaria' -> mostrar Edit (role rule)
    // 3) si window.existingDictData (llenado por docente-autocomplete) -> mostrar Edit
    // 4) si none -> llamar getExistingData() -> si existe -> mostrar Edit
    // --------------------------------------------------
    (async function initialCheck() {
        if (initialHasData) {
            console.log('[edit-button] initialHasData true -> showEdit');
            showEdit();
            return;
        }

        if (userType === 'secretaria') {
            console.log('[edit-button] userType secretaria -> showEdit');
            showEdit();
            return;
        }

        // Si docente-autocomplete ya cargó dictaminador y lo puso en window.existingDictData
        if (window.existingDictData) {
            console.log('[edit-button] window.existingDictData detected -> showEdit');
            showEdit();
            console.log("DICTAM ID:", dictaminadorId);
            console.log("USER ID:", userId);
            console.log("URL Construida:", url);
            return;
        }

        // Finalmente, intentar fetch (solo si tenemos dictaminador_id y user_id en DOM)
        const data = await getExistingData();
        console.log('[edit-button] getExistingData result', data);
        if (data) {
            showEdit();
        } else {
            showSubmit();
        }
    })();

    // --------------------------------------------------
    // Click editar (igual que antes)
    // --------------------------------------------------
    editBtn.addEventListener('click', async (e) => {
        e.preventDefault();    // ⛔ evita submit
        e.stopPropagation();   // ⛔ evita bubbling
        console.log('[edit-button] click editar');
        const data = await getExistingData();
        console.log('[edit-button] getExistingData result', data);
        if (!data) {
            // si no hay data, hacemos nada
            return;
        }
        // rellenar formulario si existen funciones globales que lo hagan (o reuse fill logic)
        // Aquí asumimos que docente-autocomplete ya puso los valores en DOM; si no, fillForm logic puede invocarse
        try {
            // Si existe una función genérica de llenado (no incluida por defecto), se puede llamar
            if (typeof window.__fillFormWithData === 'function') {
                window.__fillFormWithData(data, formId);
            } else {
                // fallback básico: rellenar inputs y spans
                const form = document.getElementById(formId);
                if (form) {
                    Object.keys(data).forEach(key => {
                        const el = form.querySelector(`[name="${key}"]`) || document.getElementById(key);
                        if (el) {
                            if (el.tagName === 'INPUT' || el.tagName === 'TEXTAREA' || el.tagName === 'SELECT') {
                                el.value = data[key] ?? '';
                            } else {
                                el.textContent = data[key] ?? '';
                            }
                        }
                    });
                }
            }
        } catch (e) {
            console.warn('[edit-button] fill fallback error', e);
        }

        editBtn.style.display = 'none';
        if (submitBtn) {
            submitBtn.textContent = 'Actualizar';
            submitBtn.style.display = '';
        }
    });

    // Reaccionar al evento que indica si se encontraron datos de evaluación.
    // Este evento será despachado por docente-autocomplete.blade.php
    document.addEventListener('evaluationDataLoaded', (e) => {
        const hasData = e.detail.hasData;
        console.log('[edit-button] evaluationDataLoaded event received', { hasData });
        if (hasData) {
            showEdit();
        } else {
            showSubmit();
        }
    });

});
</script>
