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

        // reset form (sin tocar tu diseño)
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
        // Requiere tu modal global existente (AppModal). Si no existe, avisa.
        if (!window.AppModal || typeof window.AppModal.open !== 'function') {
            aSetAlert('error', 'No se encontró el modal de confirmación (AppModal). Verifica que exista #globalModal y su JS.');
            return;
        }

        aClose();

        window.AppModal.open({
            type: 'danger',
            title: 'Confirmar reseteo',
            subtitle: 'Esta acción no se puede deshacer',
            body:
                '<div class="text-sm text-gray-700 space-y-2">' +
                    '<p class="font-semibold text-gray-900">¿Seguro que deseas resetear la Base de Datos?</p>' +
                    '<p>Se eliminarán clientes, servicios, pagos, etc. <span class="font-semibold">El administrador no se borrará</span>.</p>' +
                    '<p class="text-xs text-gray-500">Escribe <span class="font-semibold">RESET</span> para confirmar:</p>' +
                    '<input id="resetConfirmInput" class="w-full mt-1 px-3 py-[0.45rem] border border-gray-300" style="border-radius:0.3rem; background:#F9FAFB;" placeholder="RESET" />' +
                '</div>',
            primaryText: 'Sí, resetear',
            primaryIcon: (window.AppModal.ICONS && window.AppModal.ICONS.danger) ? window.AppModal.ICONS.danger : '',
            returnTo: function(){ aOpen(); },
            onPrimary: function(closeGlobal, setGlobalAlert){
                const val = (document.getElementById('resetConfirmInput')?.value || '').trim();
                if (val !== 'RESET') {
                    setGlobalAlert('error', 'Confirmación inválida. Debes escribir RESET.');
                    return;
                }

                fetch('/admin/actions.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams({ action: 'reset_db', confirm: 'RESET' }).toString()
                })
                .then(r => r.json())
                .then(data => {
                    if (!data.ok) throw new Error(data.message || 'Error');
                    closeGlobal();
                    window.location.reload();
                })
                .catch(err => setGlobalAlert('error', err.message || 'No se pudo resetear la BD.'));
            }
        });
    }

    window.AdminModal = window.AdminModal || {};
    window.AdminModal.open = aOpen;
    window.AdminModal.close = aClose;
    window.AdminModal.submit = aSubmit;
    window.AdminModal.toggleNewPassword = aToggleNewPassword;
    window.AdminModal.clearAlert = aClearAlert;
    window.AdminModal.confirmResetDb = aConfirmResetDb;

    // =========================
    // ClientModal (deja lo que ya tienes)
    // =========================
    window.ClientModal = window.ClientModal || {};
    window.ClientModal.open = function () {
        const el = document.getElementById('clientModal');
        if (!el) {
            console.warn('[ClientModal] No existe #clientModal');
            return;
        }
        show(el);
    };
    window.ClientModal.close = function () {
        const el = document.getElementById('clientModal');
        if (!el) return;
        hide(el);
    };

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
    // Open handlers (header/sidebar)
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