<?php
session_start();

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
try {
    $total_clientes = (int)$pdo->query('SELECT COUNT(*) AS c FROM clientes')->fetch()['c'];
} catch (Throwable $e) {
    $total_clientes = 0;
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

            <!-- CAMBIO: abrir modal Crear Cliente -->
            <button
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-white font-semibold shadow-sm"
                style="background: linear-gradient(to right, #2563eb 0%, #1d4ed8 100%);"
                type="button"
                onclick="event.preventDefault(); if (window.ClientModal && typeof ClientModal.open==='function') { ClientModal.open(); } else { console.warn('ClientModal no definido'); }"
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
        </div>
    </div>

    <?php require_once __DIR__ . '/../includes/footer.php'; ?>
</main>

<?php require_once __DIR__ . '/../includes/modals.php'; ?>
<?php require_once __DIR__ . '/../includes/layout_end.php'; ?>