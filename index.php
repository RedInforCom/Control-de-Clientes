<?php
// Start session management
require_once __DIR__ . '/config/session.php';

// Initialize error and success messages
$error_message = '';
$success_message = '';

// Handle POST request for login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input
    $usuario = isset($_POST['usuario']) ? trim($_POST['usuario']) : '';
    $contrasena = isset($_POST['contrasena']) ? trim($_POST['contrasena']) : '';
    
    // Validate inputs
    if (empty($usuario) || empty($contrasena)) {
        $error_message = 'Por favor, complete todos los campos.';
    } else {
        try {
            // PDO connection moved out of index.php
            require_once __DIR__ . '/config/conexion.php';
            
            // Use the real table: admin + real column name: `contraseña`
            $query = 'SELECT id, usuario, `contraseña` FROM admin WHERE usuario = :usuario LIMIT 1';
            $stmt = $pdo->prepare($query);
            $stmt->execute(['usuario' => $usuario]);
            $user = $stmt->fetch();
            
            // Verify user exists and password matches (sha256)
            if ($user && hash('sha256', $contrasena) === $user['contraseña']) {
                // Set session variables
                $_SESSION['usuario_id'] = $user['id'];
                $_SESSION['usuario'] = $user['usuario'];
                $_SESSION['login_time'] = time();
                
                $success_message = '¡Bienvenido! Redirigiendo...';
                header('Refresh: 1; url=/dashboard/');
            } else {
                $error_message = 'Usuario o contraseña incorrectos.';
            }
            
            $pdo = null;
        } catch (PDOException $e) {
            $error_message = 'Error de conexión a la base de datos. Por favor, intente más tarde.';
            error_log('Database Error: ' . $e->getMessage());
        } catch (Exception $e) {
            $error_message = 'Ha ocurrido un error inesperado. Por favor, intente nuevamente.';
            error_log('General Error: ' . $e->getMessage());
        }
    }
}

// Check if user is already logged in
if (isset($_SESSION['usuario_id'])) {
    header('Location: /dashboard/');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistema de Control de Clientes - Acceso Seguro">
    <meta name="robots" content="noindex, nofollow">
    <title>Control de Clientes - Inicio de Sesión</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }

        /* Fondo solicitado */
        .gradient-bg {
            background: linear-gradient(0deg, #081228 0%, #020B31 100%);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
        }
        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
    </style>
</head>
<!-- (resto del archivo tal cual lo tienes actualmente) -->