<?php
// Start session management
session_start();

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

        .glass-effect {
            background: #f9fafb;
            backdrop-filter: blur(10px);
            border: 1px solid #d1d5db;
        }

        .input-focus:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        }

        .btn-hover { transition: all 0.3s ease; }
        .btn-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(37, 99, 235, 0.35);
        }

        .password-toggle { cursor: pointer; user-select: none; }

        .logo-container { animation: float 3s ease-in-out infinite; }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        /* Iconos tipo “línea” como la imagen, color #9ca3af */
        .form-icon { color: #9ca3af; transition: all 0.3s ease; }
        .form-group:focus-within .form-icon { transform: scale(1.05); }

        .security-badge {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            border: 1px solid #2563eb;
        }

        .error-alert {
            background: rgba(239, 68, 68, 0.10);
            border-left: 4px solid #ef4444;
            color: #991b1b;
        }
        .success-alert {
            background: rgba(34, 197, 94, 0.10);
            border-left: 4px solid #22c55e;
            color: #166534;
        }

        .input-placeholder::placeholder { color: rgba(0, 0, 0, 0.45); }

        /* Labels: font-weight 500 */
        .label-500 { font-weight: 500; }

        /* Títulos en 600 (menos grueso) */
        .title-600 { font-weight: 600; }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Logo Container (logo al 60% de ancho) -->
        <div class="logo-container text-center mb-8">
            <img src="/assets/logo.webp" alt="INFORCOM Logo" class="mx-auto mb-4 object-contain" style="width:60%; height:auto;">
            <h1 class="text-3xl title-600 mt-2" style="color:#F9FAFB;">Control de Clientes</h1>
            <p class="text-gray-200 text-sm mt-2">Sistema de Gestión Profesional</p>
        </div>
        
        <!-- Main Card -->
        <div class="glass-effect rounded-xl shadow-2xl p-8 border border-opacity-20">
            <!-- Header (centrado) -->
            <div class="mb-8 text-center">
                <h2 class="text-2xl title-600 text-black">Inicio de Sesión</h2>
                <p class="text-gray-600 text-sm mt-2">Accede a tu cuenta para continuar</p>
            </div>
            
            <!-- Error Message -->
            <?php if (!empty($error_message)): ?>
            <div class="error-alert p-4 rounded-lg mb-6 text-sm" role="alert">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <span><?php echo htmlspecialchars($error_message); ?></span>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Success Message -->
            <?php if (!empty($success_message)): ?>
            <div class="success-alert p-4 rounded-lg mb-6 text-sm" role="alert">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span><?php echo htmlspecialchars($success_message); ?></span>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Login Form -->
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="space-y-5">
                <!-- Usuario Field -->
                <div class="form-group relative">
                    <label for="usuario" class="block text-sm text-black mb-2 label-500">Usuario</label>
                    <div class="relative flex items-center">
                        <svg class="form-icon absolute left-4 w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M20 21v-1a4 4 0 00-4-4H8a4 4 0 00-4 4v1" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M12 12a4 4 0 100-8 4 4 0 000 8z" />
                        </svg>

                        <input 
                            type="text" 
                            id="usuario" 
                            name="usuario" 
                            class="input-placeholder input-focus w-full bg-white border rounded-lg py-3 px-4 pl-12 text-black focus:outline-none transition-colors"
                            style="border-color:#d1d5db;"
                            placeholder="Ingresa tu usuario"
                            autocomplete="username"
                            required
                        >
                    </div>
                </div>
                
                <!-- Contraseña Field -->
                <div class="form-group relative">
                    <label for="contrasena" class="block text-sm text-black mb-2 label-500">Contraseña</label>
                    <div class="relative flex items-center">
                        <svg class="form-icon absolute left-4 w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M16 10V8a4 4 0 00-8 0v2" />
                            <rect x="5" y="10" width="14" height="11" rx="2" ry="2" stroke-width="1.7" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M12 15v2" />
                        </svg>

                        <input 
                            type="password" 
                            id="contrasena" 
                            name="contrasena" 
                            class="input-placeholder input-focus w-full bg-white border rounded-lg py-3 px-4 pl-12 pr-12 text-black focus:outline-none transition-colors"
                            style="border-color:#d1d5db;"
                            placeholder="Ingresa tu contraseña"
                            autocomplete="current-password"
                            required
                        >
                        <button 
                            type="button" 
                            class="password-toggle absolute right-4 text-gray-400 hover:text-gray-600 transition-colors"
                            onclick="togglePasswordVisibility()"
                            aria-label="Mostrar/Ocultar contraseña"
                        >
                            <svg id="eye-icon" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z" />
                                <circle cx="12" cy="12" r="3" stroke-width="1.7" />
                            </svg>
                        </button>
                    </div>
                </div>
                
                <!-- Submit Button -->
                <button 
                    type="submit" 
                    class="btn-hover w-full text-white font-bold py-3 px-4 rounded-lg mt-8 flex items-center justify-center gap-2 hover:shadow-lg transition-all"
                    style="background: linear-gradient(to right, #2563eb 0%, #1d4ed8 100%);"
                >
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M10 17l5-5-5-5" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M15 12H3" />
                    </svg>
                    Iniciar Sesión
                </button>
            </form>
        </div>
        
        <!-- Security Badge -->
        <div class="security-badge mt-6 rounded-lg p-4 flex items-center justify-center gap-2 text-sm">
            <svg class="w-5 h-5" style="color:#bfff00;" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5.293 7.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 5.414V15a1 1 0 11-2 0V5.414L6.707 7.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
            </svg>
            <span class="text-white">Conexión segura y encriptada</span>
            <svg class="w-5 h-5" style="color:#bfff00;" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M2.166 4.999a11.954 11.954 0 010 10.002 8 8 0 1013.668 0A11.954 11.954 0 012.166 5zm9.193 1.318a1 1 0 011.414 0l3.85 3.85a1 1 0 0 1-1.414 1.414L12 8.414l-2.636 2.636a1 1 0 01-1.414-1.414l3.85-3.85z" clip-rule="evenodd"></path>
            </svg>
        </div>
        
        <!-- Footer -->
        <p class="text-center text-gray-200 text-xs mt-6">
            © 2026 Control de Clientes. Todos los derechos reservados.
        </p>
    </div>
    
    <script>
        // Password visibility toggle
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('contrasena');
            const eyeIcon = document.getElementById('eye-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                // eye-off (línea)
                eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M3 3l18 18" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M10.5 10.5A2.8 2.8 0 0012 15a3 3 0 002.5-4.5" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M6.2 6.3C3.7 8.1 2 12 2 12s3.5 7 10 7c1.9 0 3.6-.6 5-1.4" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M14.7 14.7C13.9 15.5 13 15.9 12 15.9c-2.1 0-3.8-1.7-3.8-3.8 0-1 .4-1.9 1.2-2.7" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M12 5c6.5 0 10 7 10 7a17 17 0 01-3.3 4.6" />';
            } else {
                passwordInput.type = 'password';
                // eye (línea)
                eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z" /><circle cx="12" cy="12" r="3" stroke-width="1.7" />';
            }
        }
        
        // Clear password on page refresh for security
        window.addEventListener('beforeunload', function() {
            document.getElementById('contrasena').value = '';
        });
        
        // Add some interactivity to form groups on focus
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.parentElement.classList.add('scale-105');
                this.parentElement.parentElement.style.transition = 'transform 0.2s ease';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.parentElement.classList.remove('scale-105');
            });
        });
    </script>
</body>
</html>