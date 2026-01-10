<?php
declare(strict_types=1);

/**
 * REQUERIMIENTO:
 * - Mantener sesión activa aunque no uses el sistema.
 * - Solo se cierre al cerrar navegador/PC (cookie de sesión) o al cerrar sesión (logout).
 *
 * NOTA:
 * - El servidor puede limpiar sesiones por GC si gc_maxlifetime es bajo.
 * - Este archivo sube gc_maxlifetime y asegura cookie lifetime=0 (hasta cerrar navegador).
 * - Compatible con PHP antiguos (sin array en session_set_cookie_params).
 */

@ini_set('session.gc_maxlifetime', '604800'); // 7 días
@ini_set('session.cookie_lifetime', '0');     // hasta cerrar navegador

if (!headers_sent()) {
    $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
    $httponly = true;

    $params = session_get_cookie_params();
    $path = $params['path'] ?? '/';
    $domain = $params['domain'] ?? '';

    // Firma antigua compatible:
    @session_set_cookie_params(0, $path, $domain, $secure, $httponly);
}

if (session_status() !== PHP_SESSION_ACTIVE) {
    @session_start();
}

if (isset($_SESSION)) {
    $_SESSION['_last_seen'] = time();
}