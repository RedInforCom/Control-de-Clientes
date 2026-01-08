<?php
declare(strict_types=1);

session_start();
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'message' => 'No autorizado.']);
    exit;
}

require_once __DIR__ . '/../config/conexion.php';

$action = $_POST['action'] ?? '';

function json_fail(string $message, int $code = 400): void {
    http_response_code($code);
    echo json_encode(['ok' => false, 'message' => $message]);
    exit;
}

function json_ok(array $data = []): void {
    echo json_encode(array_merge(['ok' => true], $data));
    exit;
}

try {
    if ($action === 'get_admin_snapshot') {
        $stmt = $pdo->prepare("SELECT usuario FROM admin WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => (int)$_SESSION['usuario_id']]);
        $row = $stmt->fetch();

        if (!$row) json_fail('Administrador no encontrado.', 404);
        json_ok(['usuario' => $row['usuario']]);
    }

    if ($action === 'update_admin') {
        $new_usuario = trim((string)($_POST['usuario'] ?? ''));
        $new_password = trim((string)($_POST['contrasena'] ?? ''));

        if ($new_usuario === '' || $new_password === '') {
            json_fail('Completa usuario y contraseña.');
        }

        $stmt = $pdo->prepare("UPDATE admin SET usuario = :u, `contraseña` = :p WHERE id = :id");
        $stmt->execute([
            'u' => $new_usuario,
            'p' => hash('sha256', $new_password),
            'id' => (int)$_SESSION['usuario_id'],
        ]);

        $_SESSION['usuario'] = $new_usuario;
        json_ok(['message' => 'Guardado correctamente.']);
    }

    if ($action === 'reset_db') {
        $confirm = (string)($_POST['confirm'] ?? '');
        if ($confirm !== 'RESET') {
            json_fail('Confirmación inválida.');
        }

        // Detectar DB actual
        $dbName = $pdo->query('SELECT DATABASE() AS db')->fetch()['db'] ?? null;
        if (!$dbName) json_fail('No se pudo detectar la base de datos.', 500);

        // Solo tablas reales (no VIEWS)
        $stmt = $pdo->prepare("
            SELECT TABLE_NAME
            FROM INFORMATION_SCHEMA.TABLES
            WHERE TABLE_SCHEMA = :db
              AND TABLE_TYPE = 'BASE TABLE'
        ");
        $stmt->execute(['db' => $dbName]);
        $tables = array_map(fn($r) => $r['TABLE_NAME'], $stmt->fetchAll());

        // Excluir admin (case-insensitive)
        $tables = array_values(array_filter($tables, fn($t) => strtolower($t) !== 'admin'));

        // Desactivar FK
        try {
            $pdo->exec('SET FOREIGN_KEY_CHECKS=0');
        } catch (Throwable $e) {
            error_log('reset_db: cannot disable FK checks: ' . $e->getMessage());
            // seguimos igual (algunos hostings no permiten)
        }

        $truncated = 0;
        $deleted = 0;

        foreach ($tables as $t) {
            // Primero TRUNCATE (rápido)
            try {
                $pdo->exec("TRUNCATE TABLE `$t`");
                $truncated++;
                continue;
            } catch (Throwable $e) {
                error_log("reset_db: TRUNCATE failed for {$t}: " . $e->getMessage());
            }

            // Fallback: DELETE (más compatible)
            try {
                $pdo->exec("DELETE FROM `$t`");
                $deleted++;
            } catch (Throwable $e) {
                error_log("reset_db: DELETE failed for {$t}: " . $e->getMessage());
                // Si falla una tabla, devolvemos error claro
                try { $pdo->exec('SET FOREIGN_KEY_CHECKS=1'); } catch (Throwable $ignore) {}
                json_fail("No se pudo resetear la tabla: {$t}. Revisa permisos/llaves foráneas.", 500);
            }
        }

        // Reactivar FK
        try { $pdo->exec('SET FOREIGN_KEY_CHECKS=1'); } catch (Throwable $ignore) {}

        json_ok([
            'message' => "Base de datos reseteada correctamente (admin conservado). TRUNCATE: {$truncated}, DELETE: {$deleted}"
        ]);
    }

    json_fail('Acción no válida.', 404);
} catch (Throwable $e) {
    error_log('admin/actions.php error: ' . $e->getMessage());
    json_fail('Ocurrió un error. Intenta nuevamente.', 500);
}