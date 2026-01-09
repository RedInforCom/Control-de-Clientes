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
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M20 8v6"></path>
                    <path d="M23 11h-6"></path>
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
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                </div>
            </div>

            <div class="soft-card rounded-xl p-5 flex items-center justify-between">
                <div>
                    <div class="text-sm text-gray-500">Clientes Activos</div>
                    <div class="text-3xl font-semibold text-gray-900"><?php echo (int)$clientes_activos; ?></div>
                </div>
                <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: rgba(34,197,94,0.14);">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 6 9 17l-5-5"></path>
                    </svg>
                </div>
            </div>

            <div class="soft-card rounded-xl p-5 flex items-center justify-between">
                <div>
                    <div class="text-sm text-gray-500">Renovaciones</div>
                    <div class="text-3xl font-semibold text-gray-900"><?php echo (int)$renovaciones; ?></div>
                </div>
                <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: rgba(249,115,22,0.14);">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 12a9 9 0 1 1-3-6.7"></path>
                        <path d="M21 3v7h-7"></path>
                    </svg>
                </div>
            </div>

            <div class="soft-card rounded-xl p-5 flex items-center justify-between">
                <div>
                    <div class="text-sm text-gray-500">Ingresos del mes</div>
                    <div class="text-3xl font-semibold text-gray-900"><?php echo number_format((float)$ingresos_mes, 2); ?></div>
                </div>
                <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: rgba(168,85,247,0.14);">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="#a855f7" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 1v22"></path>
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7H14a3.5 3.5 0 0 1 0 7H6"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Lista de clientes + filtros -->
        <div class="soft-card rounded-xl overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-200">
                <div class="flex flex-col md:flex-row md:items-center gap-3">
                    <div class="flex-1 relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8"></circle>
                                <path d="m21 21-4.3-4.3"></path>
                            </svg>
                        </span>
                        <input
                            id="clientSearchInput"
                            class="w-full pl-11 pr-3 py-[0.55rem] border border-gray-300 focus:outline-none"
                            style="border-radius:0.35rem; background:#F9FAFB;"
                            placeholder="Buscar cliente, contacto o dominio..."
                        />
                    </div>

                    <select
                        id="clientStatusSelect"
                        class="w-full md:w-52 px-3 py-[0.55rem] border border-gray-300 focus:outline-none"
                        style="border-radius:0.35rem; background:#F9FAFB;"
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
                            <th class="text-left px-5 py-3 font-semibold">ACCIONES</th>
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
                                <td class="px-5 py-4 font-semibold text-gray-900 whitespace-nowrap">
                                    <?php echo htmlspecialchars($nombre); ?>
                                </td>

                                <td class="px-5 py-4 text-gray-700 whitespace-nowrap">
                                    <?php echo htmlspecialchars($contacto); ?>
                                </td>

                                <td class="px-5 py-4 text-gray-700 whitespace-nowrap">
                                    <?php echo htmlspecialchars($telefono); ?>
                                </td>

                                <td class="px-5 py-4 whitespace-nowrap">
                                    <?php if ($dominio !== ''): ?>
                                        <a class="text-blue-600 hover:underline inline-flex items-center gap-1"
                                           href="https://<?php echo htmlspecialchars($dominio); ?>"
                                           target="_blank" rel="noopener">
                                            <?php echo htmlspecialchars($dominio); ?>
                                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                                <path d="M15 3h6v6"></path>
                                                <path d="M10 14 21 3"></path>
                                            </svg>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-gray-400">—</span>
                                    <?php endif; ?>
                                </td>

                                <td class="px-5 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold <?php echo $estadoClass; ?>">
                                        <?php echo htmlspecialchars($estado); ?>
                                    </span>
                                </td>

                                <td class="px-5 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <!-- WhatsApp -->
                                        <a href="<?php echo htmlspecialchars($wa_link ?: '#'); ?>"
                                           target="<?php echo $wa_link ? '_blank' : '_self'; ?>"
                                           onclick="<?php echo $wa_link ? '' : "alert('No hay teléfono'); return false;"; ?>"
                                           class="w-9 h-9 rounded-lg border border-gray-200 bg-white hover:bg-gray-50 flex items-center justify-center"
                                           title="WhatsApp">
                                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M21 11.5a8.5 8.5 0 0 1-12.5 7.6L3 21l1.9-5.5A8.5 8.5 0 1 1 21 11.5z"></path>
                                            </svg>
                                        </a>

                                        <!-- Ver ficha -->
                                        <a href="/cliente/ficha.php?id=<?php echo $id; ?>"
                                           class="w-9 h-9 rounded-lg border border-gray-200 bg-white hover:bg-gray-50 flex items-center justify-center"
                                           title="Ver ficha">
                                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"></path>
                                                <circle cx="12" cy="12" r="3"></circle>
                                            </svg>
                                        </a>

                                        <!-- Editar ficha -->
                                        <a href="/cliente/ficha.php?id=<?php echo $id; ?>&edit=1"
                                           class="w-9 h-9 rounded-lg border border-gray-200 bg-white hover:bg-gray-50 flex items-center justify-center"
                                           title="Editar ficha">
                                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M12 20h9"></path>
                                                <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"></path>
                                            </svg>
                                        </a>

                                        <!-- Eliminar -->
                                        <button type="button"
                                                class="w-9 h-9 rounded-lg border border-gray-200 bg-white hover:bg-red-50 flex items-center justify-center"
                                                title="Eliminar"
                                                onclick="ClientsUI.deleteClient(<?php echo (int)$id; ?>, '<?php echo htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8'); ?>', this)">
                                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
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

window.ClientsUI = window.ClientsUI || {};
window.ClientsUI.deleteClient = async function (id, nombre, btnEl) {
    const ok = confirm(`Vas a eliminar al Cliente ${nombre} ¿Estás seguro?`);
    if (!ok) return;

    if (btnEl) {
        btnEl.disabled = true;
        btnEl.classList.add('opacity-60');
    }

    try {
        const res = await fetch('/cliente/delete.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ id: String(id) }).toString()
        });

        const data = await res.json().catch(() => ({ ok: false, message: 'Respuesta inválida del servidor.' }));

        if (!res.ok || !data.ok) {
            alert(data.message || 'No se pudo eliminar el cliente.');
            return;
        }

        const row = btnEl ? btnEl.closest('tr') : null;
        if (row) row.remove();

        // Re-aplicar filtros y mostrar empty state si corresponde
        const ev = new Event('input');
        document.getElementById('clientSearchInput')?.dispatchEvent(ev);
    } catch (e) {
        alert('Error de red. Intenta de nuevo.');
    } finally {
        if (btnEl) {
            btnEl.disabled = false;
            btnEl.classList.remove('opacity-60');
        }
    }
};
</script>
