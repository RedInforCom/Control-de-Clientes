<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/session.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: /');
    exit();
}

require_once __DIR__ . '/../config/conexion.php';

$id = (int)($_GET['id'] ?? 0);
$edit = (int)($_GET['edit'] ?? 0) === 1;

if ($id <= 0) {
    http_response_code(400);
    echo "ID inválido";
    exit;
}

$stmt = $pdo->prepare("SELECT id, nombre_cliente, contacto, telefono, dominio, correo, estado
                       FROM clientes
                       WHERE id = :id
                       LIMIT 1");
$stmt->execute(['id' => $id]);
$cliente = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$cliente) {
    http_response_code(404);
    echo "Cliente no encontrado";
    exit;
}

$page_title = 'Ficha Cliente - Control de Clientes';
$active_menu = 'clientes';
$usuario = $_SESSION['usuario'] ?? 'Administrador';
$has_renewal_alerts = false;
?>
<?php require_once __DIR__ . '/../includes/layout_start.php'; ?>
<?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

<main class="flex-1 flex flex-col min-h-screen">
    <?php require_once __DIR__ . '/../includes/header.php'; ?>

    <div class="p-6 flex-1">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900"><?php echo htmlspecialchars((string)$cliente['nombre_cliente']); ?></h1>
                <p class="text-sm text-gray-600">Ficha del cliente</p>
            </div>

            <?php if (!$edit): ?>
                <a href="/cliente/ficha.php?id=<?php echo (int)$cliente['id']; ?>&edit=1"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-white font-semibold shadow-sm"
                   style="background: linear-gradient(to right, #2563eb 0%, #1d4ed8 100%);">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 20h9"></path>
                        <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"></path>
                    </svg>
                    Editar
                </a>
            <?php endif; ?>
        </div>

        <div class="soft-card rounded-xl p-5">
            <?php if ($edit): ?>
                <form class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-800">Cliente</label>
                        <input value="<?php echo htmlspecialchars((string)$cliente['nombre_cliente']); ?>" disabled
                               class="w-full mt-1 px-3 py-[0.45rem] border border-gray-300"
                               style="border-radius:0.3rem; background:#F9FAFB;" />
                        <div class="text-xs text-gray-500 mt-1">El nombre del cliente no se edita aquí (por ahora).</div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-800">Dominio</label>
                        <input value="<?php echo htmlspecialchars((string)$cliente['dominio']); ?>" disabled
                               class="w-full mt-1 px-3 py-[0.45rem] border border-gray-300"
                               style="border-radius:0.3rem; background:#F9FAFB;" />
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-800">Contacto</label>
                        <input value="<?php echo htmlspecialchars((string)$cliente['contacto']); ?>"
                               class="w-full mt-1 px-3 py-[0.45rem] border border-gray-300"
                               style="border-radius:0.3rem; background:#F9FAFB;" />
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-800">Teléfono</label>
                        <div class="flex gap-2">
                            <input value="<?php echo htmlspecialchars((string)$cliente['telefono']); ?>"
                                   class="w-full mt-1 px-3 py-[0.45rem] border border-gray-300"
                                   style="border-radius:0.3rem; background:#F9FAFB;" />
                            <?php
                                $tel = preg_replace('/\D+/', '', (string)$cliente['telefono']);
                                $wa = $tel !== '' ? ('https://wa.me/' . $tel) : '#';
                            ?>
                            <a class="mt-1 px-3 py-[0.45rem] border border-gray-300 bg-white hover:bg-gray-50 transition"
                               style="border-radius:0.3rem;"
                               href="<?php echo htmlspecialchars($wa); ?>"
                               target="<?php echo $tel !== '' ? '_blank' : '_self'; ?>"
                               onclick="<?php echo $tel !== '' ? '' : "alert('No hay teléfono'); return false;"; ?>">
                                WhatsApp
                            </a>
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-800">Correo</label>
                        <input value="<?php echo htmlspecialchars((string)$cliente['correo']); ?>"
                               class="w-full mt-1 px-3 py-[0.45rem] border border-gray-300"
                               style="border-radius:0.3rem; background:#F9FAFB;" />
                    </div>

                    <div class="md:col-span-2 pt-2 flex gap-3">
                        <a href="/cliente/ficha.php?id=<?php echo (int)$cliente['id']; ?>"
                           class="px-4 py-[0.45rem] border border-blue-200 bg-blue-50 text-blue-700 hover:bg-blue-100 transition inline-flex items-center justify-center gap-2"
                           style="border-radius:0.3rem;">
                            Cancelar
                        </a>

                        <button type="button"
                                class="px-4 py-[0.45rem] text-white font-semibold"
                                style="border-radius:0.3rem; background: linear-gradient(to right, #2563eb 0%, #1d4ed8 100%);"
                                onclick="alert('Guardar ficha lo hacemos después');">
                            Guardar
                        </button>
                    </div>
                </form>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <div class="text-xs uppercase tracking-wider text-gray-500">Contacto</div>
                        <div class="text-gray-900 font-semibold"><?php echo htmlspecialchars((string)$cliente['contacto']); ?></div>
                    </div>
                    <div>
                        <div class="text-xs uppercase tracking-wider text-gray-500">Teléfono</div>
                        <div class="text-gray-900 font-semibold"><?php echo htmlspecialchars((string)$cliente['telefono']); ?></div>
                    </div>
                    <div>
                        <div class="text-xs uppercase tracking-wider text-gray-500">Dominio</div>
                        <div class="text-gray-900 font-semibold"><?php echo htmlspecialchars((string)$cliente['dominio']); ?></div>
                    </div>
                    <div>
                        <div class="text-xs uppercase tracking-wider text-gray-500">Correo</div>
                        <div class="text-gray-900 font-semibold"><?php echo htmlspecialchars((string)$cliente['correo']); ?></div>
                    </div>
                    <div>
                        <div class="text-xs uppercase tracking-wider text-gray-500">Estado</div>
                        <div class="text-gray-900 font-semibold"><?php echo htmlspecialchars((string)($cliente['estado'] ?? 'Activo')); ?></div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php require_once __DIR__ . '/../includes/footer.php'; ?>
</main>

<?php require_once __DIR__ . '/../includes/modals.php'; ?>
<?php require_once __DIR__ . '/../includes/layout_end.php'; ?>