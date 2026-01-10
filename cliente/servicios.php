<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/session.php';
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'message' => 'No autorizado.']);
    exit;
}

require_once __DIR__ . '/../config/conexion.php';

$action = (string)($_POST['action'] ?? '');
$cliente_id = (int)($_POST['cliente_id'] ?? 0);

function json_fail(string $message, int $code = 400): void {
    http_response_code($code);
    echo json_encode(['ok' => false, 'message' => $message]);
    exit;
}
function json_ok(array $data = []): void {
    echo json_encode(array_merge(['ok' => true], $data));
    exit;
}

if ($cliente_id <= 0) json_fail('cliente_id inválido.');

function get_price(PDO $pdo, string $table, string $idCol, int $id, string $priceCol): float {
    $stmt = $pdo->prepare("SELECT {$priceCol} AS precio FROM {$table} WHERE {$idCol} = :id LIMIT 1");
    $stmt->execute(['id' => $id]);
    $row = $stmt->fetch();
    return $row ? (float)$row['precio'] : 0.0;
}

try {
    if ($action === 'get') {
        $out = [
            'hosting' => null,
            'dominio' => null,
            'correo' => null,
            'web' => null,
            'grafico' => null,
            'otro' => null,
        ];

        $stmt = $pdo->prepare("SELECT id, plan_id, fecha_contratacion, precio
                               FROM servicios_hosting
                               WHERE cliente_id = :id
                               ORDER BY id DESC LIMIT 1");
        $stmt->execute(['id' => $cliente_id]);
        $out['hosting'] = $stmt->fetch() ?: null;

        $stmt = $pdo->prepare("SELECT id, tld_id, registrante_id, fecha_contratacion, precio
                               FROM servicios_dominios
                               WHERE cliente_id = :id
                               ORDER BY id DESC LIMIT 1");
        $stmt->execute(['id' => $cliente_id]);
        $out['dominio'] = $stmt->fetch() ?: null;

        $stmt = $pdo->prepare("SELECT id, tipo_id, fecha_contratacion, precio
                               FROM servicios_correo
                               WHERE cliente_id = :id
                               ORDER BY id DESC LIMIT 1");
        $stmt->execute(['id' => $cliente_id]);
        $out['correo'] = $stmt->fetch() ?: null;

        $stmt = $pdo->prepare("SELECT id, tipo_id, fecha_contratacion, precio_total
                               FROM servicios_diseno_web
                               WHERE cliente_id = :id
                               ORDER BY id DESC LIMIT 1");
        $stmt->execute(['id' => $cliente_id]);
        $out['web'] = $stmt->fetch() ?: null;

        $stmt = $pdo->prepare("SELECT id, tipo_id, fecha_contratacion, precio_total
                               FROM servicios_diseno_grafico
                               WHERE cliente_id = :id
                               ORDER BY id DESC LIMIT 1");
        $stmt->execute(['id' => $cliente_id]);
        $out['grafico'] = $stmt->fetch() ?: null;

        try {
            $stmt = $pdo->prepare("SELECT id, tipo_id, fecha_contratacion, precio
                                   FROM servicios_otro
                                   WHERE cliente_id = :id
                                   ORDER BY id DESC LIMIT 1");
            $stmt->execute(['id' => $cliente_id]);
            $out['otro'] = $stmt->fetch() ?: null;
        } catch (Throwable $e) {
            $out['otro'] = null;
        }

        json_ok(['data' => $out]);
    }

    if ($action === 'save_hosting') {
        $plan_id = (int)($_POST['plan_id'] ?? 0);
        $fecha = trim((string)($_POST['fecha'] ?? date('Y-m-d')));
        $precio = trim((string)($_POST['precio'] ?? ''));

        if ($plan_id <= 0) json_fail('Plan inválido.');

        $auto = get_price($pdo, 'planes_hosting', 'id', $plan_id, 'precio');
        $precioF = ($precio === '' ? $auto : (float)$precio);

        $stmt = $pdo->prepare("INSERT INTO servicios_hosting (cliente_id, plan_id, fecha_contratacion, precio)
                               VALUES (:c, :p, :f, :pr)");
        $stmt->execute(['c' => $cliente_id, 'p' => $plan_id, 'f' => $fecha, 'pr' => $precioF]);
        json_ok();
    }

    if ($action === 'save_dominio') {
        $tld_id = (int)($_POST['tld_id'] ?? 0);
        $registrante_id = (int)($_POST['registrante_id'] ?? 0);
        $fecha = trim((string)($_POST['fecha'] ?? date('Y-m-d')));
        $precio = trim((string)($_POST['precio'] ?? ''));

        if ($tld_id <= 0) json_fail('TLD inválido.');
        if ($registrante_id <= 0) json_fail('Registrante inválido.');

        $autoTld = get_price($pdo, 'tld_dominios', 'id', $tld_id, 'precio');
        $autoReg = get_price($pdo, 'registrantes', 'id', $registrante_id, 'precio');

        $auto = $autoTld + $autoReg;
        $precioF = ($precio === '' ? $auto : (float)$precio);

        $stmt = $pdo->prepare("INSERT INTO servicios_dominios (cliente_id, dominio_nombre, tld_id, registrante_id, fecha_contratacion, precio)
                               VALUES (:c, :dn, :t, :r, :f, :pr)");
        $stmt->execute([
            'c' => $cliente_id,
            'dn' => '',
            't' => $tld_id,
            'r' => $registrante_id,
            'f' => $fecha,
            'pr' => $precioF,
        ]);
        json_ok();
    }

    if ($action === 'save_correo') {
        $tipo_id = (int)($_POST['tipo_id'] ?? 0);
        $fecha = trim((string)($_POST['fecha'] ?? date('Y-m-d')));
        $precio = trim((string)($_POST['precio'] ?? ''));

        if ($tipo_id <= 0) json_fail('Tipo inválido.');

        $auto = get_price($pdo, 'tipos_correo', 'id', $tipo_id, 'precio');
        $precioF = ($precio === '' ? $auto : (float)$precio);

        $stmt = $pdo->prepare("INSERT INTO servicios_correo (cliente_id, tipo_id, fecha_contratacion, precio)
                               VALUES (:c, :t, :f, :pr)");
        $stmt->execute(['c' => $cliente_id, 't' => $tipo_id, 'f' => $fecha, 'pr' => $precioF]);
        json_ok();
    }

    if ($action === 'save_web') {
        $tipo_id = (int)($_POST['tipo_id'] ?? 0);
        $fecha = trim((string)($_POST['fecha'] ?? date('Y-m-d')));
        $precio = trim((string)($_POST['precio'] ?? ''));

        if ($tipo_id <= 0) json_fail('Tipo inválido.');

        $auto = get_price($pdo, 'tipos_diseno_web', 'id', $tipo_id, 'precio');
        $precioF = ($precio === '' ? $auto : (float)$precio);

        $stmt = $pdo->prepare("INSERT INTO servicios_diseno_web (cliente_id, tipo_id, fecha_contratacion, precio_total, precio_adelanto, precio_saldo)
                               VALUES (:c, :t, :f, :pt, 0, :ps)");
        $stmt->execute(['c' => $cliente_id, 't' => $tipo_id, 'f' => $fecha, 'pt' => $precioF, 'ps' => $precioF]);
        json_ok();
    }

    if ($action === 'save_grafico') {
        $tipo_id = (int)($_POST['tipo_id'] ?? 0);
        $fecha = trim((string)($_POST['fecha'] ?? date('Y-m-d')));
        $precio = trim((string)($_POST['precio'] ?? ''));

        if ($tipo_id <= 0) json_fail('Tipo inválido.');

        $auto = get_price($pdo, 'tipos_diseno_grafico', 'id', $tipo_id, 'precio');
        $precioF = ($precio === '' ? $auto : (float)$precio);

        $stmt = $pdo->prepare("INSERT INTO servicios_diseno_grafico (cliente_id, tipo_id, fecha_contratacion, precio_total, precio_adelanto, precio_saldo)
                               VALUES (:c, :t, :f, :pt, 0, :ps)");
        $stmt->execute(['c' => $cliente_id, 't' => $tipo_id, 'f' => $fecha, 'pt' => $precioF, 'ps' => $precioF]);
        json_ok();
    }

    if ($action === 'save_otro') {
        $tipo_id = (int)($_POST['tipo_id'] ?? 0);
        $fecha = trim((string)($_POST['fecha'] ?? date('Y-m-d')));
        $precio = trim((string)($_POST['precio'] ?? ''));

        if ($tipo_id <= 0) json_fail('Tipo inválido.');

        $auto = get_price($pdo, 'tipos_otro', 'id', $tipo_id, 'precio');
        $precioF = ($precio === '' ? $auto : (float)$precio);

        $stmt = $pdo->prepare("INSERT INTO servicios_otro (cliente_id, tipo_id, fecha_contratacion, precio, notas)
                               VALUES (:c, :t, :f, :pr, '')");
        $stmt->execute(['c' => $cliente_id, 't' => $tipo_id, 'f' => $fecha, 'pr' => $precioF]);
        json_ok();
    }

    json_fail('Acción inválida.');
} catch (Throwable $e) {
    error_log('cliente/servicios error: ' . $e->getMessage());
    json_fail('Error interno.', 500);
}