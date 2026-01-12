<?php
require_once __DIR__ . '/../config/session.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: /');
    exit();
}

$page_title = 'Dashboard - Control de Clientes';
$active_menu = 'dashboard';

require_once __DIR__ . '/../config/conexion.php';

$usuario = $_SESSION['usuario'] ?? 'Administrador';

// Por ahora manual (luego lo conectamos a alertas_renovacion)
$has_renewal_alerts = false;

$total_clientes = 0;
$clientes_activos = 0;
$renovaciones = 0; // placeholder
$ingresos_mes = 0.00; // placeholder

$clientes = [];

try {
    $total_clientes = (int)$pdo->query("SELECT COUNT(*) AS c FROM clientes")->fetch()['c'];
    $clientes_activos = (int)$pdo->query("SELECT COUNT(*) AS c FROM clientes WHERE estado = 'Activo'")->fetch()['c'];

    $stmt = $pdo->query("SELECT id, nombre_cliente, contacto, telefono, dominio, estado
                         FROM clientes
                         ORDER BY id DESC
                         LIMIT 500");
    $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Throwable $e) {
    $clientes = [];
}
?>
<?php require_once __DIR__ . '/../includes/layout_start.php'; ?>
<?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

<main class="flex-1 flex flex-col min-h-screen">
    <?php require_once __DIR__ . '/../includes/header.php'; ?>

    <div class="p-6 flex-1">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Dashboard</h1>
                <p class="text-sm text-gray-600">Bienvenido, <?php echo htmlspecialchars($usuario); ?></p>
            </div>

            <button
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-white font-semibold shadow-sm"
                style="background: linear-gradient(to right, #2563eb 0%, #1d4ed8 100%);"
                type="button"
                onclick="event.preventDefault(); window.ClientModal?.open?.();"
            >
                <!-- Heroicon: user-plus (outline) -->
                <svg data-heroicon="user-plus" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.5V18a2.25 2.25 0 00-2.25-2.25h-7.5A2.25 2.25 0 003 18v1.5" />
                  <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 6.75a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" d="M22.5 12.75h-3m1.5-1.5v3" />
                </svg>
                Crear Cliente
            </button>
        </div>

        <!-- Métricas -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
            <div class="soft-card rounded-xl p-5 flex items-center justify-between">
                <div>
                    <div class="text-sm text-gray-500">Total Clientes</div>
                    <div class="text-3xl font-semibold text-gray-900"><?php echo (int)$total_clientes; ?></div>
                </div>
                <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: rgba(37,99,235,0.12);">
                    <!-- Heroicon: user-group (outline) -->
                    <svg data-heroicon="user-group" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-[#2563eb]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-4-4h-1" />
                      <path stroke-linecap="round" stroke-linejoin="round" d="M7 20H2v-2a4 4 0 014-4h1" />
                      <path stroke-linecap="round" stroke-linejoin="round" d="M7 8a4 4 0 100-8 4 4 0 000 8z" />
                      <path stroke-linecap="round" stroke-linejoin="round" d="M17 8a4 4 0 100-8 4 4 0 000 8z" />
                    </svg>
                </div>
            </div>

            <div class="soft-card rounded-xl p-5 flex items-center justify-between">
                <div>
                    <div class="text-sm text-gray-500">Clientes Activos</div>
                    <div class="text-3xl font-semibold text-gray-900"><?php echo (int)$clientes_activos; ?></div>
                </div>
                <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: rgba(34,197,94,0.14);">
                    <!-- Heroicon: check (outline) -->
                    <svg data-heroicon="check" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-[#16a34a]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                </div>
            </div>

            <div class="soft-card rounded-xl p-5 flex items-center justify-between">
                <div>
                    <div class="text-sm text-gray-500">Renovaciones</div>
                    <div class="text-3xl font-semibold text-gray-900"><?php echo (int)$renovaciones; ?></div>
                </div>
                <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: rgba(249,115,22,0.14);">
                    <!-- Heroicon: arrow-path (outline) -->
                    <svg data-heroicon="arrow-path" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-[#f97316]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M17.834 6.166A7.501 7.501 0 006.166 17.834" />
                      <path stroke-linecap="round" stroke-linejoin="round" d="M17.834 6.166h-3.684m3.684 0V9.85" />
                      <path stroke-linecap="round" stroke-linejoin="round" d="M6.166 17.834h3.684m-3.684 0v-3.684" />
                    </svg>
                </div>
            </div>

            <div class="soft-card rounded-xl p-5 flex items-center justify-between">
                <div>
                    <div class="text-sm text-gray-500">Ingresos del mes</div>
                    <div class="text-3xl font-semibold text-gray-900">S/ <?php echo number_format((float)$ingresos_mes, 2); ?></div>
                </div>
                <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: rgba(168,85,247,0.14);">
                    <!-- Heroicon: currency-dollar (outline) -->
                    <svg data-heroicon="currency-dollar" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-[#a855f7]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M12 2v20" />
                      <path stroke-linecap="round" stroke-linejoin="round" d="M9 7.5a3 3 0 013-3c1.657 0 3 1.343 3 3s-1.343 3-3 3-3 1.343-3 3 1.343 3 3 3c1.657 0 3 1.343 3 3" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- (resto del archivo igual) -->
        <style>
            .action-icon{
                border:0 !important;
                width:2.25rem;
                height:2.25rem;
                display:inline-flex;
                align-items:center;
                justify-content:center;
                background: transparent;
                transition: background-color .15s ease;
            }

            .action-icon svg{
                width:1.10rem !important;
                height:1.10rem !important;
                stroke: currentColor !important;
            }

            .action-icon--green{ color:#16a34a !important; }
            .action-icon--blue{ color:#2563eb !important; }
            .action-icon--orange{ color:#f97316 !important; }
            .action-icon--red{ color:#ef4444 !important; }

            .action-icon:hover{ background: rgba(17,24,39,0.06); }
            .action-icon--green:hover{ background: rgba(34,197,94,0.12); }
            .action-icon--blue:hover{ background: rgba(37,99,235,0.10); }
            .action-icon--orange:hover{ background: rgba(249,115,22,0.12); }
            .action-icon--red:hover{ background: rgba(239,68,68,0.10); }
        </style>

        <div class="soft-card overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-200">
                <div class="flex flex-col md:flex-row md:items-center gap-3">
                    <div class="flex-1 relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                            <!-- Heroicon: magnifying-glass (outline) -->
                            <svg data-heroicon="magnifying-glass" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M6.75 11.25a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0z" />
                            </svg>
                        </span>
                        <input
                            id="clientSearchInput"
                            class="w-full pl-11 pr-3 py-[0.55rem] border border-gray-300 focus:outline-none"
                            style="background:#F9FAFB;"
                            placeholder="Buscar cliente, contacto o dominio..."
                        />
                    </div>

                    <select
                        id="clientStatusSelect"
                        class="w-full md:w-52 px-3 py-[0.55rem] border border-gray-300 focus:outline-none"
                        style="background:#F9FAFB;"
                    >
                        <option value="">Todos los estados</option>
                        <option value="Activo">Activo</option>
                        <option value="Inactivo">Inactivo</option>
                        <option value="Suspendido">Suspendido</option>
                        <option value="En Revisión...">En Revisión...</option>
                    </select>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="text-left px-5 py-3 font-semibold">CLIENTE</th>
                            <th class="text-left px-5 py-3 font-semibold">CONTACTO</th>
                            <th class="text-left px-5 py-3 font-semibold">TELÉFONO</th>
                            <th class="text-left px-5 py-3 font-semibold">DOMINIO</th>
                            <th class="text-left px-5 py-3 font-semibold">ESTADO</th>
                            <th class="text-center px-5 py-3 font-semibold">ACCIONES</th>
                        </tr>
                    </thead>

                    <tbody id="clientsTbody" class="divide-y divide-gray-200">
                    <?php if (!$clientes): ?>
                        <tr>
                            <td colspan="6" class="px-5 py-6 text-gray-500">
                                No hay clientes aún. Crea el primero con “Crear Cliente”.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($clientes as $c): ?>
                            <?php
                                $id = (int)$c['id'];
                                $nombre = (string)($c['nombre_cliente'] ?? '');
                                $contacto = (string)($c['contacto'] ?? '');
                                $telefono = (string)($c['telefono'] ?? '');
                                $dominio = (string)($c['dominio'] ?? '');
                                $estado = (string)($c['estado'] ?? 'Activo');

                                $tel_digits = preg_replace('/\D+/', '', $telefono);
                                $wa_link = $tel_digits !== '' ? ('https://wa.me/' . $tel_digits) : '';

                                $estadoClass = 'bg-gray-100 text-gray-700';
                                if ($estado === 'Activo') $estadoClass = 'bg-green-100 text-green-700';
                                if ($estado === 'Inactivo') $estadoClass = 'bg-gray-200 text-gray-700';
                                if ($estado === 'Suspendido') $estadoClass = 'bg-red-100 text-red-700';
                                if ($estado === 'En Revisión...') $estadoClass = 'bg-yellow-100 text-yellow-700';

                                $searchHaystack = strtolower($nombre . ' ' . $contacto . ' ' . $telefono . ' ' . $dominio);
                            ?>
                            <tr class="bg-white"
                                data-search="<?php echo htmlspecialchars($searchHaystack, ENT_QUOTES, 'UTF-8'); ?>"
                                data-estado="<?php echo htmlspecialchars($estado, ENT_QUOTES, 'UTF-8'); ?>">
                                <td class="px-5 py-2 font-semibold text-gray-900 whitespace-nowrap">
                                    <?php echo htmlspecialchars($nombre); ?>
                                </td>

                                <td class="px-5 py-2 text-gray-700 whitespace-nowrap">
                                    <?php echo htmlspecialchars($contacto); ?>
                                </td>

                                <td class="px-5 py-2 text-gray-700 whitespace-nowrap">
                                    <?php echo htmlspecialchars($telefono); ?>
                                </td>

                                <td class="px-5 py-2 whitespace-nowrap">
                                    <?php if ($dominio !== ''): ?>
                                        <a class="text-blue-600 hover:underline inline-flex items-center gap-1"
                                           href="https://<?php echo htmlspecialchars($dominio); ?>"
                                           target="_blank" rel="noopener">
                                            <?php echo htmlspecialchars($dominio); ?>
                                            <!-- Heroicon: arrow-top-right-on-square (outline) -->
                                            <svg data-heroicon="arrow-top-right-on-square" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-current" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                              <path stroke-linecap="round" stroke-linejoin="round" d="M14.25 6.75H6.75A2.25 2.25 0 004.5 9v8.25A2.25 2.25 0 006.75 19.5h8.25a2.25 2.25 0 002.25-2.25V9.75" />
                                              <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 3h4.5v4.5M21 3l-9 9" />
                                            </svg>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-gray-400">—</span>
                                    <?php endif; ?>
                                </td>

                                <td class="px-5 py-2 whitespace-nowrap">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold <?php echo $estadoClass; ?>">
                                        <?php echo htmlspecialchars($estado); ?>
                                    </span>
                                </td>

                                <td class="px-5 py-2 whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-1">
                                        <a class="action-icon action-icon--green"
                                           href="<?php echo htmlspecialchars($wa_link ?: '#'); ?>"
                                           target="<?php echo $wa_link ? '_blank' : '_self'; ?>"
                                           onclick="<?php echo $wa_link ? '' : "alert('No hay teléfono'); return false;"; ?>"
                                           title="WhatsApp">
                                            <!-- Heroicon: phone (outline) used as WhatsApp substitute -->
                                            <svg data-heroicon="phone" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                              <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75A2.25 2.25 0 014.5 4.5h2.25a.75.75 0 01.75.75v2.25a.75.75 0 01-.2.52l-1.2 1.2a11.25 11.25 0 005.3 5.3l1.2-1.2a.75.75 0 01.52-.2H19.5a.75.75 0 01.75.75V19.5A2.25 2.25 0 0118 21.75H6.75A2.25 2.25 0 014.5 19.5V6.75z" />
                                            </svg>
                                        </a>

                                        <a class="action-icon action-icon--blue"
                                           href="/cliente/ficha.php?id=<?php echo $id; ?>"
                                           title="Ver ficha">
                                            <!-- Heroicon: eye (outline) -->
                                            <svg data-heroicon="eye" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                              <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12c0 2.47 3.76 7.5 9.75 7.5s9.75-5.03 9.75-7.5S17.74 4.5 12 4.5 2.25 9.53 2.25 12z" />
                                              <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </a>

                                        <a class="action-icon action-icon--orange"
                                           href="/cliente/ficha.php?id=<?php echo $id; ?>&edit=1"
                                           title="Editar ficha">
                                            <!-- Heroicon: pencil (outline) -->
                                            <svg data-heroicon="pencil" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                              <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 3.487a2.25 2.25 0 013.182 3.182l-9.014 9.014a4.5 4.5 0 01-1.897 1.13l-3.379 1.013 1.014-3.38a4.5 4.5 0 011.13-1.897l9.014-9.014z" />
                                              <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 19.5H6.75A2.25 2.25 0 014.5 17.25V6.75A2.25 2.25 0 016.75 4.5h5.379" />
                                            </svg>
                                        </a>

                                        <button type="button"
                                                class="action-icon action-icon--red"
                                                title="Eliminar"
                                                onclick="ClientsUI.deleteClient(<?php echo (int)$id; ?>, '<?php echo htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8'); ?>', this)">
                                            <!-- Heroicon: trash (outline) -->
                                            <svg data-heroicon="trash" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                              <path stroke-linecap="round" stroke-linejoin="round" d="M6 7.5h12M8.25 7.5V6a2.25 2.25 0 012.25-2.25h3.0A2.25 2.25 0 0116.5 6v1.5M8.25 7.5v12A2.25 2.25 0 0010.5 21.75h3.0A2.25 2.25 0 0015.75 19.5v-12" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div id="clientsEmptyState" class="hidden px-5 py-6 text-gray-500 border-t border-gray-200">
                No se encontraron clientes con esos filtros.
            </div>
        </div>
    </div>

    <?php require_once __DIR__ . '/../includes/footer.php'; ?>
</main>

<?php require_once __DIR__ . '/../includes/modals.php'; ?>
<?php require_once __DIR__ . '/../includes/layout_end.php'; ?>

<script>
(function () {
    const input = document.getElementById('clientSearchInput');
    const select = document.getElementById('clientStatusSelect');
    const tbody = document.getElementById('clientsTbody');
    const empty = document.getElementById('clientsEmptyState');

    if (!input || !select || !tbody) return;

    const rows = Array.from(tbody.querySelectorAll('tr[data-search]'));

    function applyFilters() {
        const q = (input.value || '').trim().toLowerCase();
        const st = (select.value || '').trim();

        let visible = 0;

        rows.forEach(row => {
            const hay = row.getAttribute('data-search') || '';
            const estado = row.getAttribute('data-estado') || '';

            const matchText = q === '' ? true : hay.includes(q);
            const matchEstado = st === '' ? true : (estado === st);

            const show = matchText && matchEstado;
            row.style.display = show ? '' : 'none';
            if (show) visible++;
        });

        if (empty) empty.classList.toggle('hidden', visible !== 0);
    }

    let t = null;
    input.addEventListener('input', () => {
        clearTimeout(t);
        t = setTimeout(applyFilters, 80);
    });

    select.addEventListener('change', applyFilters);
    applyFilters();
})();
</script>