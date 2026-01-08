<?php
declare(strict_types=1);
session_start();

require_once __DIR__ . '/../config/conexion.php';

$id = (int)($_GET['id'] ?? 0);
$edit = (int)($_GET['edit'] ?? 0) === 1;

if ($id <= 0) {
    http_response_code(400);
    echo "ID inválido";
    exit;
}

$stmt = $pdo->prepare("SELECT id, cliente, contacto, telefono, dominio FROM clientes WHERE id = :id LIMIT 1");
$stmt->execute(['id' => $id]);
$cliente = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$cliente) {
    http_response_code(404);
    echo "Cliente no encontrado";
    exit;
}

// Si vienes del modal (edit=1) entras a editar directo.
// Si no, entras a solo ver.
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Ficha Cliente</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
<div class="max-w-4xl mx-auto p-6">
    <div class="bg-white shadow-sm border border-gray-200" style="border-radius:0.3rem;">
        <div class="px-5 py-4 flex items-center justify-between border-b border-gray-200">
            <div>
                <div class="text-lg font-semibold text-gray-900"><?php echo htmlspecialchars((string)$cliente['cliente']); ?></div>
                <div class="text-sm text-gray-500">Ficha del cliente</div>
            </div>

            <?php if (!$edit): ?>
                <a href="/cliente/ficha.php?id=<?php echo (int)$cliente['id']; ?>&edit=1"
                   class="px-4 py-[0.45rem] text-white font-semibold inline-flex items-center justify-center gap-2"
                   style="border-radius:0.3rem; background: linear-gradient(to right, #2563eb 0%, #1d4ed8 100%);">
                    Editar
                </a>
            <?php endif; ?>
        </div>

        <div class="p-5">
            <?php if ($edit): ?>
                <!-- Modo editar (por ahora básico) -->
                <form method="post" action="#" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-800">Cliente</label>
                        <input value="<?php echo htmlspecialchars((string)$cliente['cliente']); ?>" disabled
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
                            <a class="mt-1 px-3 py-[0.45rem] border border-gray-300 bg-white hover:bg-gray-50"
                               style="border-radius:0.3rem;"
                               href="#"
                               onclick="alert('Botón WhatsApp se conecta después'); return false;">
                                WhatsApp
                            </a>
                        </div>
                    </div>

                    <div class="md:col-span-2 pt-2 flex gap-3">
                        <a href="/cliente/ficha.php?id=<?php echo (int)$cliente['id']; ?>"
                           class="px-4 py-[0.45rem] border border-gray-300 text-gray-700 hover:bg-gray-50"
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
                <!-- Modo ver -->
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
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
