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

$cliente = null;
try {
    $stmt = $pdo->prepare("SELECT id, nombre_cliente, contacto, telefono, dominio, correo, estado, fecha_creacion, notas_adicionales
                           FROM clientes
                           WHERE id = :id
                           LIMIT 1");
    $stmt->execute(['id' => $id]);
    $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (Throwable $e) {
    $stmt = $pdo->prepare("SELECT id, nombre_cliente, contacto, telefono, dominio, correo, estado, fecha_creacion
                           FROM clientes
                           WHERE id = :id
                           LIMIT 1");
    $stmt->execute(['id' => $id]);
    $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
    $cliente['notas_adicionales'] = $cliente['notas_adicionales'] ?? '';
}

if (!$cliente) {
    http_response_code(404);
    echo "Cliente no encontrado";
    exit;
}

$page_title = 'Ficha Cliente - Control de Clientes';
$active_menu = 'clientes';
$usuario = $_SESSION['usuario'] ?? 'Administrador';
$has_renewal_alerts = false;

$telDigits = preg_replace('/\D+/', '', (string)($cliente['telefono'] ?? ''));
$waLink = $telDigits !== '' ? ('https://wa.me/' . $telDigits) : '#';

$dominio = (string)($cliente['dominio'] ?? '');
$dominioLink = $dominio !== '' ? ('https://' . $dominio) : '#';

$estado = (string)($cliente['estado'] ?? 'Activo');

$fecha_creacion_value = !empty($cliente['fecha_creacion']) ? (string)$cliente['fecha_creacion'] : '';
$notas_value = (string)($cliente['notas_adicionales'] ?? '');
$readonly = !$edit;
?>
<?php require_once __DIR__ . '/../includes/layout_start.php'; ?>
<?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

<main class="flex-1 flex flex-col min-h-screen">
    <?php require_once __DIR__ . '/../includes/header.php'; ?>

    <div class="p-6 flex-1">
        <div class="flex items-start justify-between gap-4 mb-6">
            <div class="flex items-start gap-3">
                <a href="/clientes.php" class="mt-1 text-gray-500 hover:text-gray-800 transition" title="Volver">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M15 18l-6-6 6-6"></path>
                    </svg>
                </a>

                <div>
                    <h1 class="text-2xl font-semibold text-gray-900"><?php echo htmlspecialchars((string)$cliente['nombre_cliente']); ?></h1>
                    <p class="text-sm text-gray-600">Ficha completa del cliente</p>
                </div>
            </div>

            <!-- ✅ botones pequeños con texto + icono -->
            <div class="flex items-center gap-2">
                <a href="<?php echo htmlspecialchars($waLink); ?>"
                   target="<?php echo $telDigits !== '' ? '_blank' : '_self'; ?>"
                   onclick="<?php echo $telDigits !== '' ? '' : "alert('No hay teléfono'); return false;"; ?>"
                   class="inline-flex items-center gap-2 px-3 py-[0.35rem] text-white font-semibold shadow-sm"
                   style="background:#16a34a;"
                   title="WhatsApp">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 11.5a8.5 8.5 0 0 1-12.5 7.6L3 21l1.9-5.5A8.5 8.5 0 1 1 21 11.5z"></path>
                    </svg>
                    WhatsApp
                </a>

                <a href="<?php echo htmlspecialchars($dominioLink); ?>"
                   target="<?php echo $dominio !== '' ? '_blank' : '_self'; ?>"
                   onclick="<?php echo $dominio !== '' ? '' : "alert('No hay dominio'); return false;"; ?>"
                   class="inline-flex items-center gap-2 px-3 py-[0.35rem] text-white font-semibold shadow-sm"
                   style="background:#2563eb;"
                   title="Web">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="M2 12h20"></path>
                        <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                    </svg>
                    Web
                </a>

                <?php if (!$edit): ?>
                    <a href="/cliente/ficha.php?id=<?php echo (int)$cliente['id']; ?>&edit=1"
                       class="inline-flex items-center gap-2 px-3 py-[0.35rem] text-white font-semibold shadow-sm"
                       style="background:#f97316;"
                       title="Editar">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 20h9"></path>
                            <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"></path>
                        </svg>
                        Editar
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- ✅ cambios SOLO de ficha: labels normales + padding input-field -->
        <style>
            .field-label{
                display:block;
                font-size: 0.9rem;
                color:#374151;
                font-weight: 600;
                margin-bottom: 0.35rem;
            }
            .input-field{
                padding: 0.45rem 0.75rem !important;
                padding-left: 2.5rem !important;
            }
        </style>

        <form id="clienteFichaForm" class="space-y-6" onsubmit="return false;">
            <input type="hidden" id="fichaId" value="<?php echo (int)$cliente['id']; ?>" />

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Datos básicos -->
                <div class="soft-card overflow-hidden panel-blue lg:col-span-2">
                    <div class="panel-head">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-1a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v1"></path>
                            <path d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8z"></path>
                        </svg>
                        Datos Básicos del Cliente
                    </div>

                    <div class="panel-body">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="field-label">Cliente / Empresa</label>
                                <div class="input-wrap readonly">
                                    <span class="input-ico">
                                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M3 21h18"></path>
                                            <path d="M5 21V7l8-4 6 4v14"></path>
                                            <path d="M9 9h1"></path><path d="M9 13h1"></path><path d="M9 17h1"></path>
                                            <path d="M14 9h1"></path><path d="M14 13h1"></path><path d="M14 17h1"></path>
                                        </svg>
                                    </span>
                                    <input class="input-field" value="<?php echo htmlspecialchars((string)$cliente['nombre_cliente']); ?>" disabled />
                                </div>
                            </div>

                            <div>
                                <label class="field-label">Contacto</label>
                                <div class="input-wrap <?php echo $readonly ? 'readonly' : ''; ?>">
                                    <span class="input-ico">
                                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M20 21v-1a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v1"></path>
                                            <path d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8z"></path>
                                        </svg>
                                    </span>
                                    <input id="contacto" class="input-field" value="<?php echo htmlspecialchars((string)$cliente['contacto']); ?>" <?php echo $readonly ? 'disabled' : ''; ?> />
                                </div>
                            </div>

                            <div>
                                <label class="field-label">Teléfono</label>
                                <div class="input-wrap <?php echo $readonly ? 'readonly' : ''; ?>">
                                    <span class="input-ico">
                                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.08 4.18 2 2 0 0 1 4.06 2h3a2 2 0 0 1 2 1.72c.12.86.31 1.7.57 2.5a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.58-1.09a2 2 0 0 1 2.11-.45c.8.26 1.64.45 2.5.57A2 2 0 0 1 22 16.92z"></path>
                                        </svg>
                                    </span>
                                    <input id="telefono" class="input-field" value="<?php echo htmlspecialchars((string)$cliente['telefono']); ?>" <?php echo $readonly ? 'disabled' : 'inputmode="numeric"'; ?> />
                                </div>
                            </div>

                            <div>
                                <label class="field-label">Dominio</label>
                                <div class="input-wrap <?php echo $readonly ? 'readonly' : ''; ?>">
                                    <span class="input-ico">
                                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <path d="M2 12h20"></path>
                                            <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                                        </svg>
                                    </span>
                                    <input id="dominio" class="input-field" value="<?php echo htmlspecialchars((string)$cliente['dominio']); ?>" <?php echo $readonly ? 'disabled' : ''; ?> />
                                </div>
                            </div>

                            <div class="md:col-span-2">
                                <label class="field-label">Correo</label>
                                <div class="input-wrap <?php echo $readonly ? 'readonly' : ''; ?>">
                                    <span class="input-ico">
                                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M4 4h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z"></path>
                                            <path d="m22 6-10 7L2 6"></path>
                                        </svg>
                                    </span>
                                    <input id="correo" type="email" class="input-field" value="<?php echo htmlspecialchars((string)$cliente['correo']); ?>" <?php echo $readonly ? 'disabled' : ''; ?> />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Estado -->
                <div class="soft-card overflow-hidden panel-purple">
                    <div class="panel-head">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="M12 8h.01"></path>
                            <path d="M11 12h1v4h1"></path>
                        </svg>
                        Estado y Metadata
                    </div>

                    <div class="panel-body space-y-5">
                        <div>
                            <label class="field-label">Estado General</label>
                            <div class="input-wrap <?php echo $readonly ? 'readonly' : ''; ?>">
                                <span class="input-ico">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 2v20"></path>
                                        <path d="M2 12h20"></path>
                                    </svg>
                                </span>
                                <?php if ($edit): ?>
                                    <select id="estado" class="input-field" <?php echo $readonly ? 'disabled' : ''; ?>>
                                        <?php foreach (['Activo','Suspendido','Inactivo','En Revisión'] as $opt): ?>
                                            <option value="<?php echo htmlspecialchars($opt); ?>" <?php echo $estado === $opt ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($opt); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                <?php else: ?>
                                    <input class="input-field" value="<?php echo htmlspecialchars($estado); ?>" disabled />
                                <?php endif; ?>
                            </div>
                        </div>

                        <div>
                            <label class="field-label">Fecha de Creación</label>
                            <div class="input-wrap <?php echo $readonly ? 'readonly' : ''; ?>">
                                <span class="input-ico">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="4" width="18" height="18" rx="2"></rect>
                                        <path d="M16 2v4"></path>
                                        <path d="M8 2v4"></path>
                                        <path d="M3 10h18"></path>
                                    </svg>
                                </span>
                                <input id="fecha_creacion" class="input-field" value="<?php echo htmlspecialchars($fecha_creacion_value); ?>" <?php echo $readonly ? 'disabled' : ''; ?> />
                            </div>
                        </div>

                        <div>
                            <label class="field-label">Notas Generales</label>
                            <textarea id="notas_adicionales" class="textarea-field" <?php echo $readonly ? 'disabled' : ''; ?>><?php echo htmlspecialchars($notas_value); ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Servicios -->
            <div class="soft-card overflow-hidden panel-green">
                <div class="panel-head">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                        <path d="M12 22V12"></path>
                        <path d="m3.3 7 8.7 5 8.7-5"></path>
                    </svg>
                    Servicios Contratados
                </div>

                <div class="panel-body">
                    <div class="text-sm text-gray-600 text-center py-10">
                        Esta sección se implementará próximamente
                    </div>
                </div>
            </div>

            <!-- ✅ Panel nuevo abajo con botones (solo edit) -->
            <?php if ($edit): ?>
                <div class="soft-card overflow-hidden">
                    <div class="panel-body">
                        <div class="grid grid-cols-2 gap-3">
                            <a href="/cliente/ficha.php?id=<?php echo (int)$cliente['id']; ?>"
                               class="w-full px-4 py-[0.45rem] border border-blue-200 bg-blue-50 text-blue-700 hover:bg-blue-100 transition inline-flex items-center justify-center gap-2"
                               style="border-radius:0.3rem;">
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="#1d4ed8" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M18 6L6 18"></path>
                                    <path d="M6 6l12 12"></path>
                                </svg>
                                Cancelar
                            </a>

                            <button type="button"
                                    class="w-full px-4 py-[0.45rem] text-white font-semibold inline-flex items-center justify-center gap-2"
                                    style="border-radius:0.3rem; background: linear-gradient(to right, #2563eb 0%, #1d4ed8 100%);"
                                    onclick="FichaCliente.save()">
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                                    <path d="M17 21v-8H7v8"></path>
                                    <path d="M7 3v5h8"></path>
                                </svg>
                                Guardar Cambios
                            </button>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </form>
    </div>

    <?php require_once __DIR__ . '/../includes/footer.php'; ?>
</main>

<?php require_once __DIR__ . '/../includes/modals.php'; ?>
<?php require_once __DIR__ . '/../includes/layout_end.php'; ?>

<script>
window.FichaCliente = window.FichaCliente || {};

window.FichaCliente.save = async function () {
    const id = document.getElementById('fichaId')?.value || '';
    const contacto = document.getElementById('contacto')?.value || '';
    const telefono = document.getElementById('telefono')?.value || '';
    const dominio = document.getElementById('dominio')?.value || '';
    const correo = document.getElementById('correo')?.value || '';
    const estado = document.getElementById('estado')?.value || 'Activo';
    const fecha_creacion = document.getElementById('fecha_creacion')?.value || '';
    const notas_adicionales = document.getElementById('notas_adicionales')?.value || '';

    const body = new URLSearchParams({
        id,
        contacto,
        telefono,
        dominio,
        correo,
        estado,
        fecha_creacion,
        notas_adicionales
    });

    try {
        const res = await fetch('/cliente/update.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded; charset=utf-8'},
            body: body.toString()
        });

        const data = await res.json().catch(() => ({ ok: false, message: 'Respuesta inválida del servidor.' }));

        if (!res.ok || !data.ok) {
            alert(data.message || 'No se pudo guardar.');
            return;
        }

        window.location.href = '/cliente/ficha.php?id=' + encodeURIComponent(id);
    } catch (e) {
        alert('Error de red. Intenta de nuevo.');
    }
};

(function () {
    const phone = document.getElementById('telefono');
    if (!phone) return;
    phone.addEventListener('input', () => {
        phone.value = phone.value.replace(/[^\d]/g, '');
    });
})();
</script>