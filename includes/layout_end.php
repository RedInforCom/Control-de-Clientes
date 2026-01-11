</div> <!-- cierre .flex min-h-screen -->

<script>
(function () {
    function show(el) { if (!el) return; el.classList.remove('hidden'); el.classList.add('flex'); }
    function hide(el) { if (!el) return; el.classList.add('hidden'); el.classList.remove('flex'); }

    // =========================
    // Catalogs (solo muestro bloque completo aquí, Admin/Client se mantienen en tu archivo si ya están)
    // =========================
    const catModalByList = {
        'planes_hosting': document.getElementById('modalPlanesHosting'),
        'tld_dominios': document.getElementById('modalTldDominios'),
        'registrantes': document.getElementById('modalRegistrantes'),
        'tipos_correo': document.getElementById('modalTiposCorreo'),
        'tipos_diseno_web': document.getElementById('modalTiposDisenoWeb'),
        'tipos_diseno_grafico': document.getElementById('modalTiposDisenoGrafico'),
        'tipos_otro': document.getElementById('modalTiposOtro')
    };

    const catRequiredLabel = {
        'planes_hosting': 'Nombre del plan requerido.',
        'tld_dominios': 'Dominio/TLD requerido.',
        'registrantes': 'Registrante requerido.',
        'tipos_correo': 'Tipo de correo requerido.',
        'tipos_diseno_web': 'Tipo de diseño web requerido.',
        'tipos_diseno_grafico': 'Tipo de diseño gráfico requerido.',
        'tipos_otro': 'Tipo requerido.'
    };

    const catDeleteLabel = {
        'planes_hosting': 'el Plan de Hosting',
        'tld_dominios': 'el Dominio/TLD',
        'registrantes': 'el Registrante',
        'tipos_correo': 'el Tipo de Correo',
        'tipos_diseno_web': 'el Tipo de Diseño Web',
        'tipos_diseno_grafico': 'el Tipo de Diseño Gráfico',
        'tipos_otro': 'el ítem'
    };

    function catEls(lista) {
        return {
            modal: catModalByList[lista],
            alert: document.getElementById('alert_' + lista),
            inNombre: document.getElementById('in_' + lista + '_nombre'),
            inPrecio: document.getElementById('in_' + lista + '_precio'),
            btnGuardar: document.getElementById('btn_' + lista + '_guardar'),
            tbody: document.getElementById('tb_' + lista),
        };
    }

    function catSetAlert(lista, type, msg) {
        const el = catEls(lista).alert;
        if (!el) return;
        el.textContent = msg;
        el.classList.remove('hidden');
        el.classList.remove('border-red-200', 'bg-red-50', 'text-red-700');
        el.classList.remove('border-green-200', 'bg-green-50', 'text-green-700');
        if (type === 'success') el.classList.add('border-green-200', 'bg-green-50', 'text-green-700');
        else el.classList.add('border-red-200', 'bg-red-50', 'text-red-700');
    }

    function catClearAlert(lista) {
        const el = catEls(lista).alert;
        if (!el) return;
        el.classList.add('hidden');
        el.textContent = '';
    }

    function cleanMoney(v) {
        let s = String(v ?? '').trim();
        s = s.replace(/^S\/\s*/i, '');
        s = s.replaceAll(',', '.');
        s = s.replace(/[^\d.]/g, '');
        const parts = s.split('.');
        if (parts.length > 2) s = parts[0] + '.' + parts.slice(1).join('');
        return s;
    }
    function formatMoney2(v) {
        const n = parseFloat(cleanMoney(v));
        if (Number.isNaN(n)) return '0.00';
        return n.toFixed(2);
    }
    function escapeHtml(s) {
        return String(s)
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }

    async function catApi(lista, action, payload) {
        const body = new URLSearchParams(Object.assign({ action, lista }, payload || {}));
        const res = await fetch('/api/catalogos.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=utf-8' },
            body: body.toString()
        });

        const raw = await res.text();
        let data = null;
        try { data = JSON.parse(raw); }
        catch (e) { throw new Error('Respuesta inválida del servidor. Detalle: ' + raw.slice(0, 180)); }

        if (!res.ok || !data.ok) throw new Error(data.message || 'Error');
        return data;
    }

    function catRow(lista, item) {
        const precio = Number(item.precio || 0).toFixed(2);
        return `
            <tr class="bg-white" data-cat-row="${lista}:${item.id}">
                <td class="px-4 cat-td font-semibold text-gray-900">${escapeHtml(item.nombre || '')}</td>
                <td class="px-4 cat-td text-gray-700">S/ ${precio}</td>
                <td class="px-4 cat-td">
                    <div class="flex items-center justify-center gap-2">
                        <button type="button" class="action-icon action-icon--orange" data-cat-edit="${lista}:${item.id}" title="Editar">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 20h9"></path>
                                <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"></path>
                            </svg>
                        </button>
                        <button type="button" class="action-icon action-icon--red" data-cat-del="${lista}:${item.id}" title="Eliminar">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 6h18"></path>
                                <path d="M8 6V4h8v2"></path>
                                <path d="M19 6l-1 14H6L5 6"></path>
                                <path d="M10 11v6"></path>
                                <path d="M14 11v6"></path>
                            </svg>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    }

    function catInlineEditRowHtml(lista, id, nombre, precio) {
        const nameSafe = escapeHtml(nombre || '');
        const precioStr = formatMoney2(precio || '0');
        return `
            <tr class="bg-white" data-cat-row="${lista}:${id}" data-cat-editing="1">
                <td class="px-4 cat-td">
                    <input data-cat-edit-nombre class="w-full px-2 py-[0.35rem] border border-gray-300 focus:outline-none"
                           style="background:#F9FAFB;" value="${nameSafe}">
                </td>

                <td class="px-4 cat-td">
                    <div class="relative">
                        <span class="absolute left-2 top-1/2 -translate-y-1/2 font-extrabold text-gray-500">S/</span>
                        <input data-cat-edit-precio class="w-full pl-10 pr-2 py-[0.35rem] border border-gray-300 focus:outline-none"
                               style="background:#F9FAFB;" value="${precioStr}" inputmode="decimal">
                    </div>
                </td>

                <td class="px-4 cat-td">
                    <div class="flex items-center justify-center gap-2">
                        <button type="button" class="action-icon action-icon--green" data-cat-save-inline="${lista}:${id}" title="Guardar">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 6L9 17l-5-5"></path>
                            </svg>
                        </button>
                        <button type="button" class="action-icon" data-cat-cancel-inline="${lista}:${id}" title="Cancelar">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 6L6 18"></path>
                                <path d="M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    }

    async function catLoad(lista) {
        const { tbody } = catEls(lista);
        if (!tbody) return;
        catClearAlert(lista);

        const data = await catApi(lista, 'list');
        const items = data.items || [];
        tbody.innerHTML = items.length
            ? items.map(i => catRow(lista, i)).join('')
            : `<tr><td colspan="3" class="px-4 py-5 text-gray-500">No hay registros.</td></tr>`;
    }

    function catResetCreateInputs(lista) {
        const { inNombre, inPrecio } = catEls(lista);
        if (inNombre) inNombre.value = '';
        if (inPrecio) inPrecio.value = '0.00';
    }

    async function catCreate(lista) {
        const { inNombre, inPrecio } = catEls(lista);
        const nombre = (inNombre?.value || '').trim();
        const precio = cleanMoney(formatMoney2(inPrecio?.value || ''));

        if (!nombre) {
            catSetAlert(lista, 'error', catRequiredLabel[lista] || 'Campo requerido.');
            return;
        }

        try {
            await catApi(lista, 'create', { nombre, precio });
            catSetAlert(lista, 'success', 'Creado.');
            catResetCreateInputs(lista);
            await catLoad(lista);
        } catch (e) {
            catSetAlert(lista, 'error', e.message || 'Error');
        }
    }

    async function catUpdateInline(lista, id, tr) {
        const inN = tr.querySelector('[data-cat-edit-nombre]');
        const inP = tr.querySelector('[data-cat-edit-precio]');
        const nombre = (inN?.value || '').trim();
        const precio = cleanMoney(formatMoney2(inP?.value || ''));

        if (!nombre) {
            catSetAlert(lista, 'error', catRequiredLabel[lista] || 'Campo requerido.');
            return;
        }

        try {
            await catApi(lista, 'update', { id: String(id), nombre, precio });
            catSetAlert(lista, 'success', 'Actualizado.');
            await catLoad(lista);
        } catch (e) {
            catSetAlert(lista, 'error', e.message || 'Error');
        }
    }

    async function catDelete(lista, id, nombre) {
        const tipo = catDeleteLabel[lista] || 'el elemento';
        const label = nombre ? `¿Quieres eliminar ${tipo} "${nombre}"?` : `¿Quieres eliminar ${tipo}?`;
        const ok = window.confirm(label);
        if (!ok) return;

        try {
            await catApi(lista, 'delete', { id: String(id) });
            catSetAlert(lista, 'success', 'Eliminado.');
            await catLoad(lista);
        } catch (e) {
            catSetAlert(lista, 'error', e.message || 'Error');
        }
    }

    function catOpen(lista) {
        const modal = catModalByList[lista];
        if (!modal) return;
        catResetCreateInputs(lista);
        catClearAlert(lista);
        show(modal);
        catLoad(lista).catch(() => {});
    }

    function catClose(lista) {
        const modal = catModalByList[lista];
        if (!modal) return;
        hide(modal);
    }

    window.Catalogs = window.Catalogs || {};
    window.Catalogs.open = catOpen;
    window.Catalogs.close = catClose;
    window.Catalogs.load = catLoad;
    window.Catalogs.reset = catResetCreateInputs;

    // input precio superior (crear)
    Object.keys(catModalByList).forEach(lista => {
        const { inPrecio, btnGuardar } = catEls(lista);

        if (inPrecio) {
            inPrecio.addEventListener('input', () => {
                inPrecio.value = inPrecio.value.replace(/[^\d.,S\/\s]/g, '');
            });
            inPrecio.addEventListener('blur', () => {
                inPrecio.value = formatMoney2(inPrecio.value);
            });
            if (!inPrecio.value) inPrecio.value = '0.00';
        }

        if (btnGuardar) {
            btnGuardar.addEventListener('click', (e) => {
                e.preventDefault();
                catCreate(lista);
            });
        }
    });

    // ✅ Enter = guardar inline (nombre/precio)
    function attachInlineEnterHandlers(tr, lista, id) {
        const inN = tr.querySelector('[data-cat-edit-nombre]');
        const inP = tr.querySelector('[data-cat-edit-precio]');
        function onKey(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                catUpdateInline(lista, id, tr);
            }
        }
        if (inN) inN.addEventListener('keydown', onKey);
        if (inP) inP.addEventListener('keydown', onKey);

        if (inP) {
            inP.addEventListener('input', () => {
                inP.value = inP.value.replace(/[^\d.,S\/\s]/g, '');
            });
            inP.addEventListener('blur', () => {
                inP.value = formatMoney2(inP.value);
            });
        }
    }

    // Delegación clicks
    document.addEventListener('click', (e) => {
        const catX = e.target.closest('[data-cat-x]');
        if (catX) {
            e.preventDefault();
            const lista = catX.getAttribute('data-cat-x') || '';
            if (lista) catClose(lista);
            return;
        }

        const catTrigger = e.target.closest('[data-open-catalog]');
        if (catTrigger) {
            e.preventDefault();
            const lista = catTrigger.getAttribute('data-open-catalog') || '';
            if (lista) catOpen(lista);
            return;
        }

        const editBtn = e.target.closest('[data-cat-edit]');
        if (editBtn) {
            e.preventDefault();
            const v = editBtn.getAttribute('data-cat-edit') || '';
            const [lista, idStr] = v.split(':');
            const id = parseInt(idStr || '0', 10) || 0;
            if (!lista || !id) return;

            const tr = editBtn.closest('tr');
            if (!tr) return;

            const tds = tr.querySelectorAll('td');
            const nombre = tds && tds[0] ? (tds[0].textContent || '').trim() : '';
            const precioText = tds && tds[1] ? (tds[1].textContent || '').trim() : '';
            const precio = formatMoney2(precioText);

            tr.outerHTML = catInlineEditRowHtml(lista, id, nombre, precio);

            // enganchar enter a la nueva fila
            const newTr = document.querySelector(`tr[data-cat-row="${lista}:${id}"][data-cat-editing="1"]`);
            if (newTr) {
                attachInlineEnterHandlers(newTr, lista, id);
                const inN = newTr.querySelector('[data-cat-edit-nombre]');
                if (inN) setTimeout(() => inN.focus(), 0);
            }
            return;
        }

        const cancelInline = e.target.closest('[data-cat-cancel-inline]');
        if (cancelInline) {
            e.preventDefault();
            const v = cancelInline.getAttribute('data-cat-cancel-inline') || '';
            const [lista] = v.split(':');
            if (!lista) return;
            catLoad(lista).catch(() => {});
            return;
        }

        const saveInline = e.target.closest('[data-cat-save-inline]');
        if (saveInline) {
            e.preventDefault();
            const v = saveInline.getAttribute('data-cat-save-inline') || '';
            const [lista, idStr] = v.split(':');
            const id = parseInt(idStr || '0', 10) || 0;
            if (!lista || !id) return;

            const tr = saveInline.closest('tr');
            if (!tr) return;

            catUpdateInline(lista, id, tr);
            return;
        }

        const delBtn = e.target.closest('[data-cat-del]');
        if (delBtn) {
            e.preventDefault();
            const v = delBtn.getAttribute('data-cat-del') || '';
            const [lista, idStr] = v.split(':');
            const id = parseInt(idStr || '0', 10) || 0;
            if (!lista || !id) return;

            const tr = delBtn.closest('tr');
            const tds = tr ? tr.querySelectorAll('td') : null;
            const nombre = tds && tds[0] ? (tds[0].textContent || '').trim() : '';

            catDelete(lista, id, nombre);
            return;
        }
    });
})();
</script>

</body>
</html>