<?php
declare(strict_types=1);

require_once __DIR__ . '/session.php';
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'message' => 'No autorizado.']);
    exit;
}

require_once __DIR__ . '/conexion.php';

$action = (string)($_POST['action'] ?? '');
$lista  = (string)($_POST['lista'] ?? '');

function json_fail(string $message, int $code = 400): void {
    http_response_code($code);
    echo json_encode(['ok' => false, 'message' => $message]);
    exit;
}
function json_ok(array $data = []): void {
    echo json_encode(array_merge(['ok' => true], $data));
    exit;
}

$map = [
    'planes_hosting' => ['table' => 'planes_hosting', 'name' => 'nombre', 'price' => 'precio'],
    'tld_dominios' => ['table' => 'tld_dominios', 'name' => 'tld', 'price' => 'precio'],
    'registrantes' => ['table' => 'registrantes', 'name' => 'nombre', 'price' => 'precio'],
    'tipos_correo' => ['table' => 'tipos_correo', 'name' => 'nombre', 'price' => 'precio'],
    'tipos_diseno_web' => ['table' => 'tipos_diseno_web', 'name' => 'nombre', 'price' => 'precio'],
    'tipos_diseno_grafico' => ['table' => 'tipos_diseno_grafico', 'name' => 'nombre', 'price' => 'precio'],
    'tipos_otro' => ['table' => 'tipos_otro', 'name' => 'nombre', 'price' => 'precio'],
];

if (!isset($map[$lista])) {
    json_fail('Lista inválida.');
}

$cfg = $map[$lista];
$table = $cfg['table'];
$nameCol = $cfg['name'];
$priceCol = $cfg['price'];

try {
    if ($action === 'list') {
        $stmt = $pdo->query("SELECT id, {$nameCol} AS nombre, {$priceCol} AS precio, activo
                             FROM {$table}
                             ORDER BY id DESC");
        json_ok(['items' => $stmt->fetchAll()]);
    }

    if ($action === 'create') {
        $nombre = trim((string)($_POST['nombre'] ?? ''));
        $precio = (string)($_POST['precio'] ?? '0');

        if ($nombre === '') {
            $msgs = [
                'planes_hosting' => 'Nombre del plan requerido.',
                'tld_dominios' => 'Dominio/TLD requerido.',
                'registrantes' => 'Registrante requerido.',
                'tipos_correo' => 'Tipo de correo requerido.',
                'tipos_diseno_web' => 'Tipo de diseño web requerido.',
                'tipos_diseno_grafico' => 'Tipo de diseño gráfico requerido.',
                'tipos_otro' => 'Tipo requerido.',
            ];
            json_fail($msgs[$lista] ?? 'Campo requerido.');
        }
        $precioF = (float)$precio;
        if ($precioF < 0) json_fail('Precio inválido.');

        $stmt = $pdo->prepare("INSERT INTO {$table} ({$nameCol}, {$priceCol}, activo) VALUES (:n, :p, 1)");
        $stmt->execute(['n' => $nombre, 'p' => $precioF]);
        json_ok(['id' => (int)$pdo->lastInsertId()]);
    }

    if ($action === 'update') {
        $id = (int)($_POST['id'] ?? 0);
        $nombre = trim((string)($_POST['nombre'] ?? ''));
        $precio = (string)($_POST['precio'] ?? '0');

        if ($id <= 0) json_fail('ID inválido.');
        if ($nombre === '') {
            $msgs = [
                'planes_hosting' => 'Nombre del plan requerido.',
                'tld_dominios' => 'Dominio/TLD requerido.',
                'registrantes' => 'Registrante requerido.',
                'tipos_correo' => 'Tipo de correo requerido.',
                'tipos_diseno_web' => 'Tipo de diseño web requerido.',
                'tipos_diseno_grafico' => 'Tipo de diseño gráfico requerido.',
                'tipos_otro' => 'Tipo requerido.',
            ];
            json_fail($msgs[$lista] ?? 'Campo requerido.');
        }

        $precioF = (float)$precio;
        if ($precioF < 0) json_fail('Precio inválido.');

        $stmt = $pdo->prepare("UPDATE {$table} SET {$nameCol} = :n, {$priceCol} = :p WHERE id = :id LIMIT 1");
        $stmt->execute(['n' => $nombre, 'p' => $precioF, 'id' => $id]);
        json_ok();
    }

    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) json_fail('ID inválido.');

        $stmt = $pdo->prepare("DELETE FROM {$table} WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        json_ok();
    }

    if ($action === 'get_price') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) json_fail('ID inválido.');

        $stmt = $pdo->prepare("SELECT {$priceCol} AS precio FROM {$table} WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        if (!$row) json_fail('No encontrado.', 404);
        json_ok(['precio' => (float)$row['precio']]);
    }

    json_fail('Acción inválida.');
} catch (Throwable $e) {
    error_log('config/catalogos error: ' . $e->getMessage());
    json_fail('Error interno.', 500);
}