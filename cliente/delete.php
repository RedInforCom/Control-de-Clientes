<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/session.php';
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'message' => 'No autorizado.']);
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

try {
    // Verificar existe (para evitar "ok" falso)
    $stmt = $pdo->prepare("SELECT id, nombre_cliente FROM clientes WHERE id = :id LIMIT 1");
    $stmt->execute(['id' => $id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        http_response_code(404);
        echo json_encode(['ok' => false, 'message' => 'Cliente no encontrado.']);
        exit;
    }

    // Si en el futuro hay tablas relacionadas, aquí se borran primero
    // o se usa FK con ON DELETE CASCADE.

    $stmt = $pdo->prepare("DELETE FROM clientes WHERE id = :id");
    $stmt->execute(['id' => $id]);

    echo json_encode(['ok' => true]);
} catch (Throwable $e) {
    error_log('cliente/delete.php error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['ok' => false, 'message' => 'Error interno.']);
}