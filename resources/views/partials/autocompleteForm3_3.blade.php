<script>
(function () {
    const config = @json($config ?? []);

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

        // 1. Prioridad: Intentar como selector CSS completo.
        // Esto permite selectores explícitos como '#myId', '.myClass', '[name="myName"]'.
        try {
            const nodes = Array.from(document.querySelectorAll(selector));
            if (nodes.length) {
                nodes.forEach(n => assign(n, value));

                // Clonar si el selector es para una copia explícita o está en la lista blanca.
                if (selector.endsWith('_copy') && nodes.length > 0) {
                    // Lógica de clonación simple si es necesaria en el futuro.
                }
                return;
            }
        } catch (err) {
            console.warn(`Selector inválido en setValue: "${selector}"`);
            return; // Detener si el selector es inválido.
        }

        // 2. Si no se encontró nada y el selector es un nombre simple (sin '#', '.', etc.),
        // se asume que es un ID. Esta es la única estrategia de fallback.
        const simpleName = selector.match(/^[A-Za-z0-9_\-]+$/) ? selector : null;
        if (simpleName) {
            const elById = document.getElementById(simpleName);
            if (elById) {
                assign(elById, value);
            }
        }
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
                        const docentesResp = await fetch((config.docentesEndpoint) ? config.docentesEndpoint + '?search=' + encodeURIComponent(q) : `/formato-evaluacion/get-docentes?search=${encodeURIComponent(q)}`);
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
            try {
                const docenteDataEndpoint = config.docenteDataEndpoint || '/formato-evaluacion/get-docente-data';
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


                // Si hay convocatorias adicionales configuradas, poblarlas
                if (docenteData && docenteData.form1 && Array.isArray(config.convocatoriaSelectors)) {
                    config.convocatoriaSelectors.forEach(sel => setValue(sel, docenteData.form1.convocatoria || ''));
                }

                // Forzar actualización de footers/posición si la función fue expuesta
                if (typeof window.__docenteUpdateFooters === 'function') {
                    try { window.__docenteUpdateFooters(); } catch (e) { /* ignore */ }
                }               

             // Ejecutar para secretaria (userType ==='controlador') y para el tipo configurado de dictaminador
                // 🔹 CORRECCIÓN: Se activa si el usuario es'controlador' (cambiado a'controlador') O 'dictaminador', sin depender de la configuración del formulario.
                if (userType ==='controlador' || userType === 'dictaminador') {
                    try {
                        const dictRespUrl = config.dictEndpoint || '/formato-evaluacion/get-dictaminators-responses';
                        const resp = await fetch(dictRespUrl);
                        const dictData = await resp.json();
                        console.log('>> Dictaminadores:', dictData.form3_3);
                        console.log('>> Buscando dictaminador para email:', email);

                        const collectionKey = config.dictCollectionKey || config.formKey;
                        const collection = dictData[collectionKey] || [];
                        const selected = collection.find(r => r.email === email);
                        if (selected && config.dictMappings) {
                            console.log('>> Dictaminador encontrado:', selected);
                            Object.entries(config.dictMappings).forEach(([target, propPath]) => {
                                setValue(target, readProp(selected, propPath));
                            });

                            if (config.fillHiddenFromDict) {
                                Object.entries(config.fillHiddenFromDict).forEach(([inputName, propPath]) => {
                                    const input = document.querySelector(`input[name="${inputName}"]`);
                                    if (input) input.value = readProp(selected, propPath) ?? '';
                                });
                            }
                        } else if (config.resetOnNotFound && config.dictMappings) {
                            Object.keys(config.dictMappings).forEach(target => setValue(target, config.resetValues?.[target] ?? ''));
                        } else {
                            console.warn('>> No se encontró dictaminador para:', email);                            console.warn('>> Datos disponibles:', collection);
                       }
                  } catch (err) { console.error('Error fetching dictaminators responses:', err); }
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