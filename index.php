<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Control de Clientes - Login</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #031a28 0%, #072130 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .login-container {
            width: 100%;
            max-width: 400px;
            background-color: #f9fafb;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 40px;
            border: 1px solid #d1d5db;
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-header h1 {
            color: #1f2937;
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .login-header p {
            color: #6b7280;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            color: #374151;
            font-weight: 500;
            margin-bottom: 8px;
            font-size: 14px;
        }

        input[type="text"],
        input[type="password"],
        input[type="email"] {
            width: 100%;
            padding: 12px 15px;
            background-color: #ffffff;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            color: #1f2937;
            transition: all 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="password"]:focus,
        input[type="email"]:focus {
            outline: none;
            border-color: #2b4b95;
            box-shadow: 0 0 0 3px rgba(43, 75, 149, 0.1);
            background-color: #ffffff;
        }

        input[type="text"]::placeholder,
        input[type="password"]::placeholder,
        input[type="email"]::placeholder {
            color: #9ca3af;
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #2b4b95 0%, #28427b 100%);
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(43, 75, 149, 0.2);
        }

        .btn-login:active {
            transform: translateY(0);
            box-shadow: 0 5px 15px rgba(43, 75, 149, 0.2);
        }

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .remember-forgot a {
            color: #2b4b95;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .remember-forgot a:hover {
            color: #28427b;
            text-decoration: underline;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            color: #6b7280;
            cursor: pointer;
        }

        input[type="checkbox"] {
            margin-right: 8px;
            cursor: pointer;
            accent-color: #2b4b95;
        }

        .error-message {
            color: #dc2626;
            font-size: 13px;
            margin-top: 5px;
            display: none;
        }

        .success-message {
            color: #16a34a;
            font-size: 13px;
            margin-top: 5px;
            display: none;
        }

        .form-footer {
            text-align: center;
            margin-top: 20px;
            color: #6b7280;
            font-size: 14px;
        }

        .form-footer a {
            color: #2b4b95;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .form-footer a:hover {
            color: #28427b;
            text-decoration: underline;
        }

        /* Responsive Design */
        @media (max-width: 600px) {
            .login-container {
                padding: 30px 20px;
            }

            .login-header h1 {
                font-size: 24px;
            }

            input[type="text"],
            input[type="password"],
            input[type="email"] {
                padding: 10px 12px;
                font-size: 16px;
            }

            .btn-login {
                padding: 10px;
                font-size: 14px;
            }
        }

        /* Accessibility */
        @media (prefers-reduced-motion: reduce) {
            * {
                animation: none !important;
                transition: none !important;
            }
        }

        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            body {
                background: linear-gradient(135deg, #031a28 0%, #072130 100%);
            }

            .login-container {
                background-color: #f9fafb;
                border-color: #d1d5db;
            }

            .login-header h1 {
                color: #1f2937;
            }

            .login-header p {
                color: #6b7280;
            }

            label {
                color: #374151;
            }

            input[type="text"],
            input[type="password"],
            input[type="email"] {
                background-color: #ffffff;
                border-color: #d1d5db;
                color: #1f2937;
            }

            input[type="text"]::placeholder,
            input[type="password"]::placeholder,
            input[type="email"]::placeholder {
                color: #9ca3af;
            }

            .remember-forgot {
                color: #6b7280;
            }

            .checkbox-label {
                color: #6b7280;
            }

            .form-footer {
                color: #6b7280;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>Control de Clientes</h1>
            <p>Inicia sesión en tu cuenta</p>
        </div>

        <form id="loginForm" method="POST" action="login.php">
            <div class="form-group">
                <label for="username">Usuario</label>
                <input type="text" id="username" name="username" placeholder="Ingresa tu usuario" required>
                <div class="error-message" id="usernameError"></div>
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" placeholder="Ingresa tu contraseña" required>
                <div class="error-message" id="passwordError"></div>
            </div>

            <div class="remember-forgot">
                <label class="checkbox-label">
                    <input type="checkbox" name="remember" id="remember">
                    Recuérdame
                </label>
                <a href="forgot-password.php">¿Olvidaste tu contraseña?</a>
            </div>

            <button type="submit" class="btn-login">Iniciar Sesión</button>

            <div class="error-message" id="formError"></div>
            <div class="success-message" id="formSuccess"></div>
        </form>

        <div class="form-footer">
            ¿No tienes cuenta? <a href="register.php">Regístrate aquí</a>
        </div>
    </div>

    <script>
        // Form validation and submission handling
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Clear previous messages
            document.getElementById('usernameError').style.display = 'none';
            document.getElementById('passwordError').style.display = 'none';
            document.getElementById('formError').style.display = 'none';
            document.getElementById('formSuccess').style.display = 'none';

            let isValid = true;
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value;

            // Validation
            if (username === '') {
                document.getElementById('usernameError').textContent = 'El usuario es requerido';
                document.getElementById('usernameError').style.display = 'block';
                isValid = false;
            }

            if (password === '') {
                document.getElementById('passwordError').textContent = 'La contraseña es requerida';
                document.getElementById('passwordError').style.display = 'block';
                isValid = false;
            }

            if (isValid) {
                // Submit the form
                this.submit();
            }
        });

        // Focus management for better UX
        document.getElementById('username').addEventListener('focus', function() {
            this.style.borderColor = '#2b4b95';
        });

        document.getElementById('password').addEventListener('focus', function() {
            this.style.borderColor = '#2b4b95';
        });

        document.getElementById('username').addEventListener('blur', function() {
            if (this.value === '') {
                this.style.borderColor = '#d1d5db';
            }
        });

        document.getElementById('password').addEventListener('blur', function() {
            if (this.value === '') {
                this.style.borderColor = '#d1d5db';
            }
        });
    </script>
</body>
</html>