</div> <!-- cierre .flex min-h-screen -->

<script>
(function () {
    function show(el) {
        if (!el) return;
        el.classList.remove('hidden');
        el.classList.add('flex');
    }
    function hide(el) {
        if (!el) return;
        el.classList.add('hidden');
        el.classList.remove('flex');
    }

    // =========================
    // AdminModal (RESTORE)
    // =========================
    const adminModal = document.getElementById('adminModal');

    const aAlert = document.getElementById('adminModalAlert');
    const aAlertText = document.getElementById('adminModalAlertText');

    const aCurrentUser = document.getElementById('adminCurrentUser');

    const aNewUser = document.getElementById('adminNewUser');
    const aNewPass = document.getElementById('adminNewPass');
    const aSaveBtn = document.getElementById('adminSaveBtn');
    const aPassEye = document.getElementById('adminPassEye');

    function aSetAlert(type, msg) {
        if (!aAlert || !aAlertText) return;
        aAlertText.textContent = msg;
        aAlert.classList.remove('hidden');

        aAlert.classList.remove('border-red-200', 'bg-red-50', 'text-red-700');
        aAlert.classList.remove('border-green-200', 'bg-green-50', 'text-green-700');

        if (type === 'success') aAlert.classList.add('border-green-200', 'bg-green-50', 'text-green-700');
        else aAlert.classList.add('border-red-200', 'bg-red-50', 'text-red-700');
    }

    function aClearAlert() {
        if (!aAlert || !aAlertText) return;
        aAlert.classList.add('hidden');
        aAlertText.textContent = '';
    }

    function aLoadSnapshot() {
        if (!aCurrentUser) return;

        aCurrentUser.textContent = '...';

        fetch('/admin/actions.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ action: 'get_admin_snapshot' }).toString()
        })
        .then(r => r.json())
        .then(data => {
            if (!data.ok) throw new Error(data.message || 'Error');
            aCurrentUser.textContent = data.usuario || '—';
        })
        .catch(err => {
            aCurrentUser.textContent = '—';
            aSetAlert('error', err.message || 'No se pudo cargar el administrador.');
        });
    }

    function aSetSaving(isSaving) {
        if (!aSaveBtn) return;
        if (isSaving) {
            aSaveBtn.disabled = true;
            aSaveBtn.classList.add('opacity-70');
        } else {
            aSaveBtn.disabled = false;
            aSaveBtn.classList.remove('opacity-70');
        }
    }

    function aOpen() {
        if (!adminModal) {
            console.warn('[AdminModal] No existe #adminModal');
            return;
        }

        aClearAlert();
        aLoadSnapshot();

        if (aNewUser) aNewUser.value = '';
        if (aNewPass) {
            aNewPass.value = '';
            aNewPass.type = 'password';
        }
        if (aPassEye) {
            aPassEye.innerHTML = '<path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"></path><circle cx="12" cy="12" r="3"></circle>';
        }

        show(adminModal);
    }

    function aClose() {
        if (!adminModal) return;
        hide(adminModal);
    }

    function aToggleNewPassword() {
        if (!aNewPass) return;

        if (aNewPass.type === 'password') {
            aNewPass.type = 'text';
            if (aPassEye) aPassEye.innerHTML = '<path d="M3 3l18 18"></path><path d="M6.2 6.3C3.7 8.1 2 12 2 12s3.5 7 10 7c1.9 0 3.6-.6 5-1.4"></path>';
        } else {
            aNewPass.type = 'password';
            if (aPassEye) aPassEye.innerHTML = '<path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"></path><circle cx="12" cy="12" r="3"></circle>';
        }
    }

    function aSubmit(e) {
        if (e && typeof e.preventDefault === 'function') e.preventDefault();
        aClearAlert();

        const u = (aNewUser?.value || '').trim();
        const p = (aNewPass?.value || '').trim();

        if (!u || !p) {
            aSetAlert('error', 'Debes completar el nuevo usuario y la nueva contraseña.');
            return false;
        }

        aSetSaving(true);

        fetch('/admin/actions.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ action: 'update_admin', usuario: u, contrasena: p }).toString()
        })
        .then(r => r.json())
        .then(data => {
            if (!data.ok) throw new Error(data.message || 'Error');
            aSetAlert('success', 'Guardado correctamente. Cerrando sesión...');
            setTimeout(() => {
                aClose();
                window.location.href = '/logout.php';
            }, 1800);
        })
        .catch(err => aSetAlert('error', err.message || 'No se pudo guardar.'))
        .finally(() => aSetSaving(false));

        return false;
    }

    function aConfirmResetDb() {
        aClearAlert();

        const ok = window.confirm('¿Seguro que deseas resetear la Base de Datos? Esta acción NO se puede deshacer.');
        if (!ok) return;

        const val = window.prompt('Escribe RESET para confirmar:', '');
        if ((val || '').trim() !== 'RESET') {
            aSetAlert('error', 'Confirmación inválida. Debes escribir RESET.');
            return;
        }

        aSetAlert('success', 'Reseteando base de datos...');

        fetch('/admin/actions.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ action: 'reset_db', confirm: 'RESET' }).toString()
        })
        .then(r => r.json())
        .then(data => {
            if (!data.ok) throw new Error(data.message || 'Error');
            window.location.href = '/dashboard/';
        })
        .catch(err => aSetAlert('error', err.message || 'No se pudo resetear la BD.'));
    }

    window.AdminModal = window.AdminModal || {};
    window.AdminModal.open = aOpen;
    window.AdminModal.close = aClose;
    window.AdminModal.submit = aSubmit;
    window.AdminModal.toggleNewPassword = aToggleNewPassword;
    window.AdminModal.clearAlert = aClearAlert;
    window.AdminModal.confirmResetDb = aConfirmResetDb;

    // =========================
    // ClientModal (CREAR CLIENTE)
    // =========================
    const clientModal = document.getElementById('clientModal');

    const cAlert = document.getElementById('clientModalAlert');
    const cAlertText = document.getElementById('clientModalAlertText');

    const cName = document.getElementById('clientName');
    const cContact = document.getElementById('clientContact');
    const cPhone = document.getElementById('clientPhone');
    const cDomain = document.getElementById('clientDomain');
    const cEmail = document.getElementById('clientEmail');

    const cCreateBtn = document.getElementById('clientCreateBtn');

    function cSetAlert(type, msg) {
        if (!cAlert || !cAlertText) return;
        cAlertText.textContent = msg;
        cAlert.classList.remove('hidden');

        cAlert.classList.remove('border-red-200', 'bg-red-50', 'text-red-700');
        cAlert.classList.remove('border-green-200', 'bg-green-50', 'text-green-700');

        if (type === 'success') cAlert.classList.add('border-green-200', 'bg-green-50', 'text-green-700');
        else cAlert.classList.add('border-red-200', 'bg-red-50', 'text-red-700');
    }

    function cClearAlert() {
        if (!cAlert || !cAlertText) return;
        cAlert.classList.add('hidden');
        cAlertText.textContent = '';
    }

    function cSetSaving(isSaving) {
        if (!cCreateBtn) return;
        if (isSaving) {
            cCreateBtn.disabled = true;
            cCreateBtn.classList.add('opacity-70');
        } else {
            cCreateBtn.disabled = false;
            cCreateBtn.classList.remove('opacity-70');
        }
    }

    function onlyDigits(v) { return (v || '').replace(/\D+/g, ''); }
    function normalizeDomain(v) { return (v || '').trim().toLowerCase(); }

    function isValidDomain(domain) {
        if (!domain) return false;
        if (domain.includes('://')) return false;
        if (domain.includes('/')) return false;
        if (domain.includes(':')) return false;
        if (domain.startsWith('.')) return false;
        if (domain.endsWith('.')) return false;
        if (!domain.includes('.')) return false;

        const labels = domain.split('.');
        for (const l of labels) {
            if (!l) return false;
            if (!/^[a-z0-9-]+$/i.test(l)) return false;
            if (l.startsWith('-') || l.endsWith('-')) return false;
        }

        const tld = labels[labels.length - 1];
        if (!/^[a-z]{2,24}$/i.test(tld)) return false;
        return true;
    }

    function isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/i.test(email || '');
    }

    function cOpen() {
        if (!clientModal) return;
        cClearAlert();

        if (cName) cName.value = '';
        if (cContact) cContact.value = '';
        if (cPhone) cPhone.value = '';
        if (cDomain) cDomain.value = '';
        if (cEmail) cEmail.value = '';

        show(clientModal);
        setTimeout(() => cName && cName.focus(), 0);
    }

    function cClose() {
        if (!clientModal) return;
        hide(clientModal);
    }

    async function cSubmit(e) {
        if (e && typeof e.preventDefault === 'function') e.preventDefault();
        cClearAlert();

        const cliente = (cName?.value || '').trim();
        const contacto = (cContact?.value || '').trim();
        const telefonoRaw = (cPhone?.value || '').trim();
        const telefono = onlyDigits(telefonoRaw);
        const dominio = normalizeDomain(cDomain?.value || '');
        const correo = (cEmail?.value || '').trim();

        if (!cliente || !contacto || !telefono || !dominio || !correo) {
            cSetAlert('error', 'Debes completar todos los campos.');
            return false;
        }

        if (!/^\d+$/.test(telefono)) {
            cSetAlert('error', 'El teléfono solo debe contener números.');
            return false;
        }
        if (cPhone && cPhone.value !== telefono) cPhone.value = telefono;

        if (!isValidDomain(dominio)) {
            cSetAlert('error', 'Dominio inválido. Ejemplos: vancouver.edu.pe, app.midominio.com, dominio.com.pe');
            return false;
        }

        if (!isValidEmail(correo)) {
            cSetAlert('error', 'Correo inválido. Ej: usuario@dominio.com');
            return false;
        }

        cSetSaving(true);

        try {
            const res = await fetch('/cliente/create.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({ cliente, contacto, telefono, dominio, correo }).toString()
            });

            const data = await res.json().catch(() => ({ ok: false, message: 'Respuesta inválida del servidor.' }));

            if (!res.ok || !data.ok) {
                if (res.status === 409 && data && data.existing_id) {
                    cSetAlert('success', 'Ya existe. Abriendo ficha...');
                    window.location.href = '/cliente/ficha.php?id=' + encodeURIComponent(data.existing_id) + '&edit=1';
                    return false;
                }
                cSetAlert('error', data.message || 'No se pudo crear el cliente.');
                return false;
            }

            if (!data.id) {
                cSetAlert('error', 'No se recibió el ID del cliente.');
                return false;
            }

            cSetAlert('success', 'Cliente creado. Abriendo ficha...');
            window.location.href = '/cliente/ficha.php?id=' + encodeURIComponent(data.id) + '&edit=1';
            return false;
        } catch (err) {
            cSetAlert('error', 'Error de red. Intenta de nuevo.');
            return false;
        } finally {
            cSetSaving(false);
        }
    }

    if (cPhone) {
        cPhone.addEventListener('input', () => {
            const cleaned = onlyDigits(cPhone.value);
            if (cPhone.value !== cleaned) cPhone.value = cleaned;
        });
        cPhone.addEventListener('paste', (e) => {
            e.preventDefault();
            const text = (e.clipboardData || window.clipboardData).getData('text') || '';
            cPhone.value = onlyDigits(text);
        });
    }

    window.ClientModal = window.ClientModal || {};
    window.ClientModal.open = cOpen;
    window.ClientModal.close = cClose;
    window.ClientModal.submit = cSubmit;
    window.ClientModal.clearAlert = cClearAlert;

    // =========================
    // Catalogs (CRUD por modal)
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

    const catState = {}; // {lista: {editId:number}}

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

    async function catApi(lista, action, payload) {
        const body = new URLSearchParams(Object.assign({ action, lista }, payload || {}));
        const res = await fetch('/config/catalogos.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=utf-8' },
            body: body.toString()
        });
        const data = await res.json().catch(() => ({ ok: false, message: 'Respuesta inválida.' }));
        if (!res.ok || !data.ok) throw new Error(data.message || 'Error');
        return data;
    }

    function escapeHtml(s) {
        return String(s)
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }

    function catRow(lista, item) {
        const precio = Number(item.precio || 0).toFixed(2);
        return `
            <tr class="bg-white">
                <td class="px-4 py-2 font-semibold text-gray-900">${escapeHtml(item.nombre || '')}</td>
                <td class="px-4 py-2 text-gray-700">S/ ${precio}</td>
                <td class="px-4 py-2">
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

    function catReset(lista) {
        catState[lista] = catState[lista] || { editId: 0 };
        catState[lista].editId = 0;
        const { inNombre, inPrecio } = catEls(lista);
        if (inNombre) inNombre.value = '';
        if (inPrecio) inPrecio.value = '';
        catClearAlert(lista);
    }

    async function catSave(lista) {
        catState[lista] = catState[lista] || { editId: 0 };
        const editId = catState[lista].editId || 0;
        const { inNombre, inPrecio } = catEls(lista);

        const nombre = (inNombre?.value || '').trim();
        const precio = (inPrecio?.value || '').trim();

        if (!nombre) { catSetAlert(lista, 'error', 'Nombre requerido.'); return; }

        try {
            if (editId > 0) {
                await catApi(lista, 'update', { id: String(editId), nombre, precio });
                catSetAlert(lista, 'success', 'Actualizado.');
            } else {
                await catApi(lista, 'create', { nombre, precio });
                catSetAlert(lista, 'success', 'Creado.');
            }
            catReset(lista);
            await catLoad(lista);
        } catch (e) {
            catSetAlert(lista, 'error', e.message || 'Error');
        }
    }

    async function catDelete(lista, id) {
        const ok = window.confirm('¿Eliminar este registro?');
        if (!ok) return;
        try {
            await catApi(lista, 'delete', { id: String(id) });
            await catLoad(lista);
        } catch (e) {
            catSetAlert(lista, 'error', e.message || 'Error');
        }
    }

    function catOpen(lista) {
        const modal = catModalByList[lista];
        if (!modal) return;
        catReset(lista);
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
    window.Catalogs.reset = catReset;

    // Sanitizar inputs precio (solo dígitos y punto)
    Object.keys(catModalByList).forEach(lista => {
        const { inPrecio, btnGuardar } = catEls(lista);
        if (inPrecio) {
            inPrecio.addEventListener('input', () => {
                inPrecio.value = inPrecio.value.replace(/[^\d.]/g, '');
            });
        }
        if (btnGuardar) {
            btnGuardar.addEventListener('click', (e) => {
                e.preventDefault();
                catSave(lista);
            });
        }
    });

    // =========================
    // Close buttons + actions
    // =========================
    document.addEventListener('click', (e) => {
        const adminX = e.target.closest('[data-admin-x]');
        if (adminX) { e.preventDefault(); window.AdminModal.close(); return; }

        const clientX = e.target.closest('[data-client-x]');
        if (clientX) { e.preventDefault(); window.ClientModal.close(); return; }

        // cerrar catalogos
        const catX = e.target.closest('[data-cat-x]');
        if (catX) {
            e.preventDefault();
            const lista = catX.getAttribute('data-cat-x') || '';
            if (lista) catClose(lista);
            return;
        }

        // editar item en tabla
        const editBtn = e.target.closest('[data-cat-edit]');
        if (editBtn) {
            e.preventDefault();
            const v = editBtn.getAttribute('data-cat-edit') || '';
            const [lista, idStr] = v.split(':');
            const id = parseInt(idStr || '0', 10) || 0;
            if (!lista || !id) return;

            catState[lista] = catState[lista] || { editId: 0 };
            catState[lista].editId = id;

            const tr = editBtn.closest('tr');
            const tds = tr ? tr.querySelectorAll('td') : null;
            const nombre = tds && tds[0] ? (tds[0].textContent || '').trim() : '';
            const precioText = tds && tds[1] ? (tds[1].textContent || '') : '';
            const precio = precioText.replace('S/', '').trim();

            const { inNombre, inPrecio } = catEls(lista);
            if (inNombre) inNombre.value = nombre;
            if (inPrecio) inPrecio.value = precio;
            return;
        }

        // eliminar item
        const delBtn = e.target.closest('[data-cat-del]');
        if (delBtn) {
            e.preventDefault();
            const v = delBtn.getAttribute('data-cat-del') || '';
            const [lista, idStr] = v.split(':');
            const id = parseInt(idStr || '0', 10) || 0;
            if (!lista || !id) return;
            catDelete(lista, id);
            return;
        }

        // open handlers
        const adminTrigger = e.target.closest('[data-open-admin-modal]');
        if (adminTrigger) { e.preventDefault(); window.AdminModal.open(); return; }

        const clientTrigger = e.target.closest('[data-open-client-modal]');
        if (clientTrigger) { e.preventDefault(); window.ClientModal.open(); return; }

        // abrir catálogos desde sidebar
        const catTrigger = e.target.closest('[data-open-catalog]');
        if (catTrigger) {
            e.preventDefault();
            const lista = catTrigger.getAttribute('data-open-catalog') || '';
            if (lista) catOpen(lista);
            return;
        }
    });
})();
</script>

</body>
</html>