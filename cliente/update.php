<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/session.php';
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'message' => 'No autenticado.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'message' => 'Método no permitido.']);
    exit;
}

require_once __DIR__ . '/../config/conexion.php';

$id = (int)($_POST['id'] ?? 0);
if ($id <= 0) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'message' => 'ID inválido.']);
    exit;
}

$contacto = trim((string)($_POST['contacto'] ?? ''));
$telefono = trim((string)($_POST['telefono'] ?? ''));
$dominio  = strtolower(trim((string)($_POST['dominio'] ?? '')));
$correo   = trim((string)($_POST['correo'] ?? ''));

$estado = trim((string)($_POST['estado'] ?? 'Activo'));
$fecha_creacion = trim((string)($_POST['fecha_creacion'] ?? ''));
$notas_generales = trim((string)($_POST['notas_generales'] ?? ''));

if ($contacto === '' || $telefono === '' || $dominio === '' || $correo === '') {
    http_response_code(400);
    echo json_encode(['ok' => false, 'message' => 'Debes completar Contacto, Teléfono, Dominio y Correo.']);
    exit;
}

if (!preg_match('/^\d+$/', $telefono)) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'message' => 'El teléfono solo debe contener números.']);
    exit;
}

if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'message' => 'El correo ingresado no es válido.']);
    exit;
}

// Dominio válido (igual que create.php)
if (strpos($dominio, '://') !== false || strpos($dominio, '/') !== false || strpos($dominio, ':') !== false) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'message' => 'Dominio inválido. No uses http/https, rutas ni puertos.']);
    exit;
}
if ($dominio === '' || $dominio[0] === '.' || substr($dominio, -1) === '.' || strpos($dominio, '.') === false) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'message' => 'Dominio inválido.']);
    exit;
}
$labels = explode('.', $dominio);
foreach ($labels as $label) {
    if ($label === '' || !preg_match('/^[a-z0-9-]+$/i', $label) || $label[0] === '-' || substr($label, -1) === '-') {
        http_response_code(400);
        echo json_encode(['ok' => false, 'message' => 'Dominio inválido.']);
        exit;
    }
}
$tld = $labels[count($labels) - 1];
if (!preg_match('/^[a-z]{2,24}$/i', $tld)) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'message' => 'Dominio inválido (TLD).']);
    exit;
}

$allowedEstados = ['Activo', 'Inactivo', 'Suspendido', 'En Revisión...'];
if (!in_array($estado, $allowedEstados, true)) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'message' => 'Estado inválido.']);
    exit;
}

try {
    // Update base fields (these exist)
    $stmt = $pdo->prepare("UPDATE clientes
                           SET contacto = :contacto,
                               telefono = :telefono,
                               dominio = :dominio,
                               correo = :correo,
                               estado = :estado
                           WHERE id = :id
                           LIMIT 1");
    $stmt->execute([
        'contacto' => $contacto,
        'telefono' => $telefono,
        'dominio' => $dominio,
        'correo' => $correo,
        'estado' => $estado,
        'id' => $id,
    ]);

    // Optional fields: try if columns exist
    if ($fecha_creacion !== '' || $notas_generales !== '') {
        try {
            $stmt2 = $pdo->prepare("UPDATE clientes
                                    SET fecha_creacion = :fecha_creacion,
                                        notas_generales = :notas_generales
                                    WHERE id = :id
                                    LIMIT 1");
            $stmt2->execute([
                'fecha_creacion' => $fecha_creacion !== '' ? $fecha_creacion : null,
                'notas_generales' => $notas_generales,
                'id' => $id,
            ]);
        } catch (Throwable $e2) {
            // ignore until DB has these columns
        }
    }

    echo json_encode(['ok' => true]);
} catch (Throwable $e) {
    error_log('Cliente update error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['ok' => false, 'message' => 'Error interno.']);
}