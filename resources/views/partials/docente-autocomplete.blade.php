<script>
(function () {
    const config = @json($config ?? []);
    // Preferir window.ENDPOINTS si existe
    const endpoints = window.ENDPOINTS || {};

    // util: leer propiedad segura por path "a.b.c"
    function readProp(obj, path) {
        if (!obj || !path) return undefined;
        return path.split('.').reduce((acc, p) => (acc && acc[p] !== undefined) ? acc[p] : undefined, obj);
    }

    // util: setea valor en id, selector, name o en múltiples elementos (soporta copias *_copy)
    function setValue(selector, value) {
        if (!selector) return;

        // helper para asignar a un elemento DOM según tipo
        function assign(el, v) {
            if (!el) return;
            const tag = el.tagName;
            if (tag === 'INPUT' || tag === 'TEXTAREA' || tag === 'SELECT') {
                el.value = v ?? '';
            } else {
                el.textContent = (v ?? '');
            }
        }

        // 1) intentar selector CSS tal cual (permite '#id', '.class', 'span[name="x"]', etc.)
        try {
            const nodes = Array.from(document.querySelectorAll(selector));
            if (nodes.length) {
                nodes.forEach(n => assign(n, value));
                return;
            }
        } catch (err) {
            // selector inválido, se seguirá intentando otras estrategias
        }

        // 2 si selector parece un identificador simple (sin .#[]), intentar id, name y clases
        const simpleName = selector.match(/^[A-Za-z0-9_\-]+$/) ? selector : null;

       if (simpleName) {
            const byId = document.getElementById(simpleName);
            if (byId) assign(byId, value);

            // Decide si clonamos: SOLO si no está dentro de una tabla (evita duplicar <td>)
            const copyId = `${simpleName}_copy`;
            let byIdCopy = document.getElementById(copyId);

            const isInsideTable = byId && !!byId.closest('table');
            const explicitCopySelectorProvided = selector.endsWith('_copy') || ['convocatoria'].includes(simpleName) || (typeof config.cloneWhitelist !== 'undefined' && Array.isArray(config.cloneWhitelist) && config.cloneWhitelist.includes(simpleName));
            if (!byIdCopy && byId && (!isInsideTable || explicitCopySelectorProvided)) {
                try {
                    byIdCopy = byId.cloneNode(true);
                    byIdCopy.id = copyId;
                    if (byId.parentNode) byId.parentNode.insertBefore(byIdCopy, byId.nextSibling);
                } catch (e) {
                    byIdCopy = null;
                }
            }
            if (byIdCopy) assign(byIdCopy, value);

            // elements by name attribute
            const byName = document.querySelectorAll(`[name="${simpleName}"]`);
            if (byName.length) Array.from(byName).forEach(n => assign(n, value));

            // classes .name and .name_copy (no cloning here)
            const byClass = document.querySelectorAll(`.${simpleName}`);
            if (byClass.length) Array.from(byClass).forEach(n => assign(n, value));
            const byClassCopy = document.querySelectorAll(`.${simpleName}_copy`);
            if (byClassCopy.length) Array.from(byClassCopy).forEach(n => assign(n, value));

            return;
        }

        // 3) fallback: intentar como selector con name=... (por si pasaron 'prom90_100' como name)
        const byNameFallback = document.querySelectorAll(`[name="${selector}"]`);
        if (byNameFallback.length) {
            Array.from(byNameFallback).forEach(n => assign(n, value));
            return;
        }

        // último recurso: tratar selector como id
        const lastId = document.getElementById(selector);
        if (lastId) assign(lastId, value);
    }

   //Nueva función que además busca copias con índices _0, _1, _2...
    function setValueWithCopies(selector, value) {
        // Aplica al original
        setValue(selector, value);

        // Aplica a las copias numeradas si existen
        for (let i = 0; i <= 5; i++) {
            const copySelector = `${selector}_${i}`;
            const el = document.querySelector(copySelector.startsWith('#') ? copySelector : `#${copySelector}`);
            if (el) setValue(copySelector, value);
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.getElementById(config.searchInputId || 'docenteSearch');
        const suggestionsBox = document.getElementById(config.suggestionsBoxId || 'docenteSuggestions');
        const hiddenEmail = document.getElementById(config.hiddenEmailId || 'selectedDocenteEmail');

        const userType = @json($userType ?? '');
        let debounceTimer;

        // Autocomplete (fetch)
        if (searchInput && suggestionsBox) {
            searchInput.addEventListener('input', function () {
                const q = this.value.trim();
                clearTimeout(debounceTimer);
                if (q.length < (config.minChars || 2)) { suggestionsBox.style.display = 'none'; return; }
                debounceTimer = setTimeout(async () => {
                    try {
                        // Usar endpoint global si existe, si no fallback
                        const docentesEndpoint = endpoints.getDictaminatorsResponses || config.docentesEndpoint || (config.baseUrl ? config.baseUrl + '/get-docentes' : '/get-docentes');
                        const docentesResp = await fetch(docentesEndpoint + '?search=' + encodeURIComponent(q));
                        const docentes = await docentesResp.json();
                        suggestionsBox.innerHTML = '';
                        if (Array.isArray(docentes) && docentes.length) {
                            docentes.forEach(d => {
                                const li = document.createElement('li');
                                li.className = config.suggestionClass || 'list-group-item list-group-item-action';
                                li.innerHTML = `<strong>${d.nombre}</strong><br><small>${d.email}</small>`;
                                li.addEventListener('click', () => {
                                    searchInput.value = `${d.nombre} (${d.email})`;
                                    if (hiddenEmail) hiddenEmail.value = d.email;
                                    suggestionsBox.style.display = 'none';
                                    document.dispatchEvent(new CustomEvent('docenteSelected', { detail: d }));
                                });
                                suggestionsBox.appendChild(li);
                            });
                            suggestionsBox.style.display = 'block';
                        } else {
                            suggestionsBox.style.display = 'none';
                        }
                    } catch (err) { console.error('Error buscando docentes:', err); }
                }, config.debounceMs || 300);
            });
        }

        document.addEventListener('click', (e) => {
            if (!e.target.closest('#' + (config.searchInputId || 'docenteSearch')) && !e.target.closest('#' + (config.suggestionsBoxId || 'docenteSuggestions'))) {
                const sb = document.getElementById(config.suggestionsBoxId || 'docenteSuggestions');
                if (sb) sb.style.display = 'none';
            }
        });

        // --- LÓGICA DE CARGA AUTOMÁTICA ---
        // Si se ha preseleccionado un email desde la vista (pasado por la URL),
        // disparamos el evento 'docenteSelected' para cargar sus datos automáticamente.
        if (config.preselectedEmail) {
            // Usamos setTimeout para asegurar que el DOM esté completamente listo
            // antes de disparar el evento. Esto resuelve problemas de "race condition"
            // donde el script se ejecuta antes de que los elementos del formulario existan.
            setTimeout(() => {
                console.log('Docente preseleccionado por URL:', config.preselectedEmail);
                const preselectedDocente = { email: config.preselectedEmail };
                // Disparamos el evento para que se carguen los datos.
                document.dispatchEvent(new CustomEvent('docenteSelected', { detail: preselectedDocente }));
            }, 0); // Un retardo de 0ms es suficiente para moverlo al final de la cola de ejecución.
        }

        // Manejo cuando se selecciona docente: usa axios para /get-docente-data por defecto
        document.addEventListener('docenteSelected', async (e) => {
            const docente = e.detail;
            // Si la vista que incluye este parcial necesita manejar el evento por su cuenta, puede pasar este flag.
            if (config.skipAutoFetch) return;

            const email = docente.email;
            selectedEmail = email; // actualizar variable global

             // --- NUEVO BLOQUE: hidratar scores desde endpoint backend ---
                try {
                    // Tomar user_id si existe en un input oculto
                    const userIdInput = document.querySelector(`input[name="user_id"]`);
                    const userId = userIdInput ? userIdInput.value : null;

                    if (userId) {
                        const resp = await fetch(`/docencia-scores?user_id=${userId}`);
                        if (!resp.ok) throw new Error('Error al obtener docencia-scores');

                        const scoresData = await resp.json();

                        // Hidratar window.data con scores reales
                        window.data = window.data || {};
                        for (let i = 1; i <= 19; i++) {
                            const key = `score3_${i}`;
                            window.data[key] = Number(scoresData[key]) || 0;
                        }

                        // docencia total
                        window.data['docencia'] = Number(scoresData.docencia) || 0;
                        window.data.__mode = 'edit';

                        // Proyectar scores en el DOM
                        renderScoresFromData(window.data);

                        const docenciaEl = document.getElementById('docencia');
                        const docencia2El = document.getElementById('docencia2');
                        if (docenciaEl) docenciaEl.textContent = window.data['docencia'] || '0';
                        if (docencia2El) docencia2El.textContent = window.data['docencia'] || '0';

                    }
                } catch (err) {
                    console.error('Error cargando docencia-scores:', err);
                }
                // --- FIN BLOQUE NUEVO (solo scores) ---
            try {
                // Usar endpoint global si existe, si no fallback
                const docenteDataEndpoint = endpoints.getDocenteData || config.docenteDataEndpoint || (config.baseUrl ? config.baseUrl + '/get-docente-data' : '/get-docente-data');
                // se usa axios (por preferencia del proyecto)
                const axiosResp = await axios.get(docenteDataEndpoint, { params: { email } });
                const docenteData = axiosResp.data;
                console.log('Datos recibidos:', docenteData);


                // actualizar convocatoria si existe form1
                if (docenteData && docenteData.form1) {
                    const conv = document.getElementById('convocatoria');
                    if (conv) conv.textContent = docenteData.form1.convocatoria || '';
                }

                // llenar mappings desde la parte del form (config.formKey por ejemplo "form3_2")
                const formBlock = readProp(docenteData, config.formKey || 'form3_2');
                if (formBlock && config.docenteMappings) {
                    Object.entries(config.docenteMappings).forEach(([target, propPath]) => {
                        setValue(target, readProp(formBlock, propPath));
                    });
                }

                // llenar inputs ocultos comunes
                if (formBlock && config.fillHiddenFrom) {
                    Object.entries(config.fillHiddenFrom).forEach(([inputName, propPath]) => {
                        const input = document.querySelector(`input[name="${inputName}"]`);
                        if (input) input.value = readProp(formBlock, propPath) ?? '';
                    });
                }

                // --- respaldo: si el email no se llenó, tomarlo directamente del docente seleccionado o del nivel raíz ---
                if (config.fillHiddenFrom && config.fillHiddenFrom.email) {
                    const emailInput = document.querySelector(`input[name="email"]`);
                    if (emailInput && !emailInput.value) {
                        emailInput.value = docente.email || readProp(docenteData, 'email') || '';
                        console.log('Email de respaldo asignado:', emailInput.value);
                    }
                }

                // --- respaldo: si el user_id no se llenó, tomarlo del docente ---
                const userIdInput = document.querySelector(`input[name="user_id"]`);
                if (userIdInput && !userIdInput.value) {
                    userIdInput.value = readProp(docenteData, 'docente.id') || '';
                    console.log('User ID de respaldo asignado:', userIdInput.value);
                }


                // Si hay convocatorias adicionales configuradas, poblarlas
                if (docenteData && docenteData.form1 && Array.isArray(config.convocatoriaSelectors)) {
                    config.convocatoriaSelectors.forEach(sel => setValue(sel, docenteData.form1.convocatoria || ''));
                }

                // Forzar actualización de footers/posición si la función fue expuesta
                if (typeof window.__docenteUpdateFooters === 'function') {
                    try { window.__docenteUpdateFooters(); } catch (e) { /* ignore */ }
                }               

             // Ejecutar para secretaria (userType === 'secretaria') y para el tipo configurado de dictaminador
                    // --- CARGA DE RESPUESTA DE DICTAMINADOR ---
                    if (config.dictEndpoint) {
                        try {
                            // Usar endpoint global si existe, si no fallback
                            const dictEndpoint = endpoints.getDictaminatorsResponses || config.dictEndpoint || (config.baseUrl ? config.baseUrl + '/get-dictaminators-responses' : '/get-dictaminators-responses');
                            const dictRespUrl = `${dictEndpoint}?email=${email}`;
                            const resp = await fetch(dictRespUrl);
                            const dictData = await resp.json();

                            const collectionKey = config.dictCollectionKey || config.formKey;
                            const collection = dictData[collectionKey] || [];

                                    // Determinar el MODO de selección segun el formulario
                                    let mode = "byEmail"; // por defecto

                                    // Formularios especiales → usar el primer registro
                                    const specialForms = [
                                        "form3_11","form3_12","form3_13","form3_14","form3_15",
                                        "form3_16","form3_17","form3_18","form3_19",
                                         "form3_1", "form3_9"
                                    ];

                                    if (specialForms.includes(config.formKey)) {
                                        mode = "first";
                                    }
                                    // else if (mode === "dictaminadorById") {
                                    //     return collection.find(r => r.dictaminador_id == AUTH_ID);
                                    // }

                                    // función auxiliar
                                    function getDictaminadorRecord(collection, email, mode) {
                                        if (mode === "byEmail") {
                                            return collection.find(r => r.email === email) || null;
                                        }
                                        if (mode === "first") {
                                            return Array.isArray(collection) && collection.length > 0
                                                ? collection[0]
                                                : null;
                                        }
                                        return null;
                                    }

                            // buscar respuesta del dictaminador
                            const selected = getDictaminadorRecord(collection, email, mode);

                            // Set global flag for existing data
                            window.existingDictData = selected;

                            // Notificar a otros componentes (como edit-button) si se encontraron datos.
                            document.dispatchEvent(new CustomEvent('evaluationDataLoaded', {
                                detail: { hasData: !!selected }
                            }));

                            if (selected && config.dictMappings) {
                                Object.entries(config.dictMappings).forEach(([target, propPath]) => {
                                    setValue(target, readProp(selected, propPath));
                                });



                                if (config.fillHiddenFromDict) {
                                    Object.entries(config.fillHiddenFromDict).forEach(([inputName, propPath]) => {
                                        const input = document.querySelector(`input[name="${inputName}"]`);
                                        if (input) input.value = readProp(selected, propPath) ?? '';
                                    });
                                }
                            } else {
                                console.warn(`>> No hay dictaminador (${mode}) para este docente:`, email);
                            }

                        } catch (err) {
                            console.error('Error trayendo dictaminador:', err);
                        }
                    }

                

            } catch (err) {
                console.error('Error al obtener datos del docente:', err);
            }
        });

            (function registerPrintFooterLogic() {
                const pairs = config.printPagePairs || [[3,4]]; // default pairs
                const pagesSelector = ".page-break";

                function updateFooters() {
                    const pages = document.querySelectorAll(pagesSelector);
                    if (!pages || pages.length === 0) return;

                    // global footers (fallback)
                    const globalFirst = document.querySelector('.first-page-footer');
                    const globalSecond = document.querySelector('.second-page-footer');

                    // reset globals
                    if (globalFirst) globalFirst.style.display = 'none';
                    if (globalSecond) globalSecond.style.display = 'none';

                    pages.forEach(page => {
                        const pageNumber = page.getAttribute('data-page');

                        // check each configured pair
                        pairs.forEach(pair => {
                            const a = String(pair[0]);
                            const b = String(pair[1]);

                            // footers inside the page (preferred)
                            const firstFooter = page.querySelector('.first-page-footer') || globalFirst;
                            const secondFooter = page.querySelector('.second-page-footer') || globalSecond;

                            if (pageNumber === a) {
                                if (firstFooter) firstFooter.style.display = 'table-footer-group';
                                if (secondFooter) secondFooter.style.display = 'none';
                            } else if (pageNumber === b) {
                                if (firstFooter) firstFooter.style.display = 'none';
                                if (secondFooter) secondFooter.style.display = 'table-footer-group';
                            }
                        });
                    });
                }

                // Exponer la función para forzar actualización desde fuera del IIFE
                window.__docenteUpdateFooters = updateFooters;
                // run immediately if printing now
                if (window.matchMedia('print').matches) updateFooters();

                // run before print
                window.addEventListener('beforeprint', updateFooters);

                // optional: reset/hide after print
                window.addEventListener('afterprint', () => {
                    const globalFirst = document.querySelector('.first-page-footer');
                    const globalSecond = document.querySelector('.second-page-footer');
                    if (globalFirst) globalFirst.style.display = '';
                    if (globalSecond) globalSecond.style.display = '';
                });
            })();

            
    });
})();
</script>
