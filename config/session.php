<?php
declare(strict_types=1);

/**
 * Sesión persistente mientras el navegador esté abierto:
 * - cookie lifetime = 0 (se borra al cerrar navegador)
 * - gc_maxlifetime alto para que el servidor no la limpie por inactividad
 *
 * Compatible con PHP antiguos (sin session_set_cookie_params por array).
 */

@ini_set('session.gc_maxlifetime', '604800'); // 7 días
@ini_set('session.cookie_lifetime', '0');     // hasta cerrar navegador

$secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
$httponly = true;

$params = session_get_cookie_params();
$path = $params['path'] ?? '/';
$domain = $params['domain'] ?? '';

// Firma antigua (compatible):
session_set_cookie_params(0, $path, $domain, $secure, $httponly);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$_SESSION['_last_seen'] = time();
