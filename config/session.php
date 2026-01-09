<?php
declare(strict_types=1);

/**
 * Objetivo:
 * - Mantener la sesión activa mientras el navegador siga abierto.
 * - Que solo se cierre al cerrar el navegador/PC o al hacer logout.
 *
 * Claves:
 * - Cookie de sesión sin expiración (lifetime=0 => "session cookie").
 * - Aumentar gc_maxlifetime para que el servidor no limpie la sesión por inactividad.
 */

$cookieParams = session_get_cookie_params();

session_set_cookie_params([
    'lifetime' => 0, // hasta cerrar navegador
    'path' => $cookieParams['path'] ?? '/',
    'domain' => $cookieParams['domain'] ?? '',
    'secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
    'httponly' => true,
    'samesite' => 'Lax',
]);

// Evitar que el GC borre sesiones "por inactividad" muy rápido (7 días)
@ini_set('session.gc_maxlifetime', '604800');
@ini_set('session.cookie_lifetime', '0');

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Marca actividad (no cierra por tiempo; solo ayuda al GC)
$_SESSION['_last_seen'] = time();