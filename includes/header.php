<?php
$has_renewal_alerts = $has_renewal_alerts ?? false;
?>
<header class="bg-white border-b border-gray-200">
    <div class="px-6 py-[0.3rem] flex items-center justify-between">
        <div>
            <div class="text-xl font-semibold text-gray-900">Control de Clientes</div>
            <div class="text-sm text-gray-500">Sistema de Gestión Profesional</div>
        </div>

        <div class="flex items-center gap-5">
            <!-- Notifications -->
            <button class="relative p-2 rounded-lg hover:bg-gray-100 transition" aria-label="Notificaciones">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 8a6 6 0 10-12 0c0 7-3 7-3 7h18s-3 0-3-7"></path>
                    <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                </svg>

                <?php if ($has_renewal_alerts): ?>
                    <span class="absolute top-2 right-2 w-2.5 h-2.5 rounded-full bg-red-500 ring-2 ring-white"></span>
                <?php endif; ?>
            </button>

            <!-- User dropdown -->
            <div class="relative">
                <button
                    type="button"
                    class="flex items-center gap-3 p-2 rounded-xl hover:bg-gray-100 transition"
                    aria-haspopup="menu"
                    aria-expanded="false"
                    data-user-menu-button
                >
                    <!-- User icon -->
                    <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M20 21v-1a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v1"></path>
                            <path d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8z"></path>
                        </svg>
                    </div>

                    <div class="leading-tight text-left">
                        <div class="text-sm font-semibold text-gray-900"><?php echo htmlspecialchars($usuario_layout); ?></div>
                        <div class="text-xs text-gray-500">Administrador</div>
                    </div>

                    <svg class="w-4 h-4 text-gray-500 ml-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M6 9l6 6 6-6"></path>
                    </svg>
                </button>

                <!-- Dropdown menu -->
                <div
                    class="hidden absolute right-0 mt-2 w-56 soft-card rounded-xl overflow-hidden z-30"
                    role="menu"
                    data-user-menu
                >
                    <!-- FIX HEADER: onclick directo + stopPropagation para que el click global no lo cierre antes -->
                    <a
                        href="#"
                        class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition"
                        role="menuitem"
                        onclick="event.preventDefault(); event.stopPropagation(); window.AdminModal?.open?.();"
                    >
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M12 20h9"></path>
                            <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"></path>
                        </svg>
                        Editar Administrador
                    </a>

                    <div class="h-px bg-gray-200"></div>

                    <a
                        href="/logout.php"
                        class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition"
                        role="menuitem"
                    >
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M10 17l5-5-5-5"></path>
                            <path d="M15 12H3"></path>
                            <path d="M21 3h-8a2 2 0 0 0-2 2v4"></path>
                            <path d="M11 15v4a2 2 0 0 0 2 2h8"></path>
                        </svg>
                        Cerrar Sesión
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
    (function () {
        const btn = document.querySelector('[data-user-menu-button]');
        const menu = document.querySelector('[data-user-menu]');
        if (!btn || !menu) return;

        function close() {
            menu.classList.add('hidden');
            btn.setAttribute('aria-expanded', 'false');
        }

        function toggle() {
            const isHidden = menu.classList.contains('hidden');
            if (isHidden) {
                menu.classList.remove('hidden');
                btn.setAttribute('aria-expanded', 'true');
            } else {
                close();
            }
        }

        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            toggle();
        });

        document.addEventListener('click', () => close());
        menu.addEventListener('click', (e) => e.stopPropagation());

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') close();
        });
    })();
</script>