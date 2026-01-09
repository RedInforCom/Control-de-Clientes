<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/session.php';
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'message' => 'Método no permitido.']);
    exit;
}

require_once __DIR__ . '/../config/conexion.php';

$nombre_cliente = trim((string)($_POST['cliente'] ?? ''));
$contacto       = trim((string)($_POST['contacto'] ?? ''));
$telefono       = trim((string)($_POST['telefono'] ?? ''));
$dominio        = strtolower(trim((string)($_POST['dominio'] ?? '')));
$correo         = trim((string)($_POST['correo'] ?? ''));

if ($nombre_cliente === '' || $contacto === '' || $telefono === '' || $dominio === '' || $correo === '') {
    http_response_code(400);
    echo json_encode(['ok' => false, 'message' => 'Debes completar todos los campos.']);
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

// Dominio válido
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

try {
    // Buscar si existe por nombre_cliente o dominio y devolver su ID
    $stmt = $pdo->prepare("SELECT id, nombre_cliente, dominio
                           FROM clientes
                           WHERE LOWER(nombre_cliente) = LOWER(:nombre_cliente)
                              OR LOWER(dominio) = LOWER(:dominio)
                           LIMIT 1");
    $stmt->execute(['nombre_cliente' => $nombre_cliente, 'dominio' => $dominio]);
    $existing = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existing) {
        http_response_code(409);
        echo json_encode([
            'ok' => false,
            'message' => 'El cliente o dominio ya existe.',
            'existing_id' => (int)$existing['id']
        ]);
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO clientes (nombre_cliente, contacto, telefono, dominio, correo)
                           VALUES (:nombre_cliente, :contacto, :telefono, :dominio, :correo)");
    $stmt->execute([
        'nombre_cliente' => $nombre_cliente,
        'contacto' => $contacto,
        'telefono' => $telefono,
        'dominio' => $dominio,
        'correo' => $correo,
    ]);

    $id = (int)$pdo->lastInsertId();
    echo json_encode(['ok' => true, 'id' => $id]);
} catch (Throwable $e) {
    error_log('Cliente creation error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['ok' => false, 'message' => 'Error interno.']);
}