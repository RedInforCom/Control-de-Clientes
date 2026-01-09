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

    // ✅ CAMBIO ÚNICO: Resetear BD sin AppModal + redirigir al dashboard
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
            // Importante: evita quedarte en ficha sin cliente
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

    function onlyDigits(v) {
        return (v || '').replace(/\D+/g, '');
    }

    function normalizeDomain(v) {
        return (v || '').trim().toLowerCase();
    }

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
        if (!clientModal) {
            console.warn('[ClientModal] No existe #clientModal');
            return;
        }
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
                body: new URLSearchParams({
                    cliente,
                    contacto,
                    telefono,
                    dominio,
                    correo
                }).toString()
            });

            const data = await res.json().catch(() => ({ ok: false, message: 'Respuesta inválida del servidor.' }));

            if (!res.ok || !data.ok) {
                // ✅ Si ya existe, igual enviamos a la ficha en modo editar
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

    // filtro telefono
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
    // Close buttons
    // =========================
    document.addEventListener('click', (e) => {
        const adminX = e.target.closest('[data-admin-x]');
        if (adminX) {
            e.preventDefault();
            window.AdminModal.close();
            return;
        }

        const clientX = e.target.closest('[data-client-x]');
        if (clientX) {
            e.preventDefault();
            window.ClientModal.close();
            return;
        }
    });

    // =========================
    // Open handlers (header/sidebar/center)
    // =========================
    document.addEventListener('click', (e) => {
        const adminTrigger = e.target.closest('[data-open-admin-modal]');
        if (adminTrigger) {
            e.preventDefault();
            window.AdminModal.open();
            return;
        }

        const clientTrigger = e.target.closest('[data-open-client-modal]');
        if (clientTrigger) {
            e.preventDefault();
            window.ClientModal.open();
            return;
        }
    });
})();
</script>

</body>
</html>