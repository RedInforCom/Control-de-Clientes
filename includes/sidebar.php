<aside class="sidebar-bg w-72 text-white flex flex-col sticky top-0 h-screen overflow-y-auto">
    <div class="p-6 border-b border-white/10 flex items-center justify-center">
        <img src="/assets/logo.webp" alt="INFORCOM" class="object-contain" style="width:80%; height:auto;">
    </div>

    <?php
        $active_menu_value = ($active_menu ?? '');
        $clientes_open = ($active_menu_value === 'clientes');

        $current_path = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: '';
        $is_ver_clientes = ($current_path === '/clientes.php');
    ?>

    <nav class="px-4 py-4 space-y-1 text-sm">
        <a href="/dashboard/" class="flex items-center gap-3 px-3 py-[0.35rem] <?php echo ($active_menu_value === 'dashboard') ? 'active-item' : 'hover:bg-white/5'; ?> transition">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="var(--muted)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 12l9-9 9 9"></path>
                <path d="M9 21V9h6v12"></path>
            </svg>
            <span class="font-medium">Dashboard</span>
        </a>

        <div>
            <button type="button"
                class="w-full flex items-center gap-3 px-3 py-[0.35rem] <?php echo $clientes_open ? 'active-item' : 'hover:bg-white/5'; ?> transition"
                data-accordion-button="clientes"
                aria-expanded="<?php echo $clientes_open ? 'true' : 'false'; ?>"
            >
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="var(--muted)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
                <span class="font-medium">Clientes</span>
                <svg class="w-4 h-4 ml-auto text-white/70 transition-transform" data-accordion-chevron="clientes" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 9l6 6 6-6"></path>
                </svg>
            </button>

            <div class="overflow-hidden max-h-0 transition-[max-height] duration-300 ease-in-out" data-accordion-panel="clientes">
                <div class="mt-1 ml-6 space-y-1 border-l border-white/10 pl-4">
                    <a href="/clientes.php"
                       class="flex items-center gap-2 px-2 py-[0.35rem] transition <?php echo $is_ver_clientes ? 'text-white bg-white/5' : 'text-white/80 hover:text-white hover:bg-white/5'; ?>">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="var(--muted)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M8 6h13"></path>
                            <path d="M8 12h13"></path>
                            <path d="M8 18h13"></path>
                            <path d="M3 6h.01"></path>
                            <path d="M3 12h.01"></path>
                            <path d="M3 18h.01"></path>
                        </svg>
                        Ver Clientes
                    </a>

                    <a href="#"
                       class="flex items-center gap-2 px-2 py-[0.35rem] text-white/80 hover:text-white hover:bg-white/5 transition"
                       onclick="event.preventDefault(); window.ClientModal?.open?.();">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="var(--muted)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 5v14"></path>
                            <path d="M5 12h14"></path>
                        </svg>
                        Crear Cliente
                    </a>
                </div>
            </div>
        </div>

        <div class="mt-2">
            <button type="button"
                class="w-full flex items-center gap-3 px-3 py-[0.35rem] hover:bg-white/5 transition"
                data-accordion-button="config"
                aria-expanded="false"
            >
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="var(--muted)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 1v3"></path>
                    <path d="M12 20v3"></path>
                    <path d="M4.22 4.22l2.12 2.12"></path>
                    <path d="M17.66 17.66l2.12 2.12"></path>
                    <path d="M1 12h3"></path>
                    <path d="M20 12h3"></path>
                    <path d="M4.22 19.78l2.12-2.12"></path>
                    <path d="M17.66 6.34l2.12-2.12"></path>
                    <circle cx="12" cy="12" r="4"></circle>
                </svg>
                <span class="font-medium">Configuración</span>
                <svg class="w-4 h-4 ml-auto text-white/70 transition-transform" data-accordion-chevron="config" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 9l6 6 6-6"></path>
                </svg>
            </button>

            <div class="overflow-hidden max-h-0 transition-[max-height] duration-300 ease-in-out" data-accordion-panel="config">
                <div class="mt-1 ml-6 space-y-1 border-l border-white/10 pl-4">
                    <a href="#" class="flex items-center gap-2 px-2 py-[0.35rem] text-white/80 hover:text-white hover:bg-white/5 transition">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="var(--muted)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 1v22"></path><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7H14"></path>
                        </svg>
                        Planes Hosting
                    </a>
                    <a href="#" class="flex items-center gap-2 px-2 py-[0.35rem] text-white/80 hover:text-white hover:bg-white/5 transition">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="var(--muted)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle><path d="M2 12h20"></path>
                        </svg>
                        Dominios TLD
                    </a>
                    <a href="#" class="flex items-center gap-2 px-2 py-[0.35rem] text-white/80 hover:text-white hover:bg-white/5 transition">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="var(--muted)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        Dominios Registrante
                    </a>
                    <a href="#" class="flex items-center gap-2 px-2 py-[0.35rem] text-white/80 hover:text-white hover:bg-white/5 transition">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="var(--muted)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 4h16v16H4z"></path><path d="M4 9h16"></path>
                        </svg>
                        Tipo de Correo
                    </a>
                    <a href="#" class="flex items-center gap-2 px-2 py-[0.35rem] text-white/80 hover:text-white hover:bg-white/5 transition">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="var(--muted)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 20h9"></path><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"></path>
                        </svg>
                        Diseño Web
                    </a>
                    <a href="#" class="flex items-center gap-2 px-2 py-[0.35rem] text-white/80 hover:text-white hover:bg-white/5 transition">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="var(--muted)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a7.97 7.97 0 0 0 .1-6"></path>
                        </svg>
                        Diseño Gráfico
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="mt-auto border-t border-white/10" style="padding:0.5rem 1rem 0.5rem 1rem;">
        <a
            href="#"
            class="flex items-center gap-3 px-3 py-[0.35rem] hover:bg-white/5 transition"
            data-open-admin-modal
        >
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="var(--muted)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 20h9"></path>
                <path d="M16.5 3.5a2.1 2 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"></path>
            </svg>
            <span class="font-medium">Editar Administrador</span>
        </a>
    </div>

    <script>
        (function () {
            const buttons = document.querySelectorAll('[data-accordion-button]');
            if (!buttons.length) return;

            const keepOpenKey = 'clientes';
            const keepOpenBtn = document.querySelector('[data-accordion-button="clientes"]');
            const keepClientesOpen = keepOpenBtn && keepOpenBtn.getAttribute('aria-expanded') === 'true';

            function closePanel(key) {
                if (keepClientesOpen && key === keepOpenKey) return; // ✅ no cerrar clientes si está activo por navegación

                const panel = document.querySelector(`[data-accordion-panel="${key}"]`);
                const chevron = document.querySelector(`[data-accordion-chevron="${key}"]`);
                const btn = document.querySelector(`[data-accordion-button="${key}"]`);
                if (!panel || !btn) return;

                panel.style.maxHeight = '0px';
                btn.setAttribute('aria-expanded', 'false');
                if (chevron) chevron.style.transform = 'rotate(0deg)';
            }

            function openPanel(key) {
                const panel = document.querySelector(`[data-accordion-panel="${key}"]`);
                const chevron = document.querySelector(`[data-accordion-chevron="${key}"]`);
                const btn = document.querySelector(`[data-accordion-button="${key}"]`);
                if (!panel || !btn) return;

                panel.style.maxHeight = panel.scrollHeight + 'px';
                btn.setAttribute('aria-expanded', 'true');
                if (chevron) chevron.style.transform = 'rotate(180deg)';
            }

            buttons.forEach(btn => {
                const key = btn.getAttribute('data-accordion-button');
                const panel = document.querySelector(`[data-accordion-panel="${key}"]`);
                const chevron = document.querySelector(`[data-accordion-chevron="${key}"]`);
                if (!panel) return;

                panel.style.maxHeight = '0px';
                if (chevron) chevron.style.transform = 'rotate(0deg)';

                if (btn.getAttribute('aria-expanded') === 'true') {
                    openPanel(key);
                }

                btn.addEventListener('click', () => {
                    const expanded = btn.getAttribute('aria-expanded') === 'true';
                    if (expanded) {
                        closePanel(key);
                    } else {
                        buttons.forEach(b => {
                            const k = b.getAttribute('data-accordion-button');
                            if (k !== key) closePanel(k);
                        });
                        openPanel(key);
                    }
                });
            });

            window.addEventListener('resize', () => {
                buttons.forEach(btn => {
                    const key = btn.getAttribute('data-accordion-button');
                    const panel = document.querySelector(`[data-accordion-panel="${key}"]`);
                    if (!panel) return;
                    if (btn.getAttribute('aria-expanded') === 'true') {
                        panel.style.maxHeight = panel.scrollHeight + 'px';
                    }
                });
            });
        })();
    </script>
</aside>