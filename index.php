<?php
// Start session management
require_once __DIR__ . '/config/session.php';

/**
 * Load a Font Awesome "light" SVG file (from your uploaded package) and normalize it:
 * - Removes width/height attributes so CSS can control sizing
 * - Ensures preserveAspectRatio is present
 * - Removes XML prolog if present
 *
 * Returns SVG string or empty on failure.
 */
function load_light_svg_normalized(string $basename): string {
    $dir = __DIR__ . '/assets/fontawesome/svgs/light';
    $file = $dir . '/' . $basename . '.svg';
    if (!is_readable($file)) return '';

    $svg = file_get_contents($file);
    if ($svg === false) return '';

    // Remove XML prolog
    $svg = preg_replace('/<\\?xml.*?\\?>/s', '', $svg);

    // Remove width / height attributes
    $svg = preg_replace('/\\s(width|height)=\"[^\"]*\"/i', '', $svg);

    // Ensure preserveAspectRatio is present
    if (!preg_match('/preserveAspectRatio=/i', $svg)) {
        $svg = preg_replace('/<svg([^>]*)>/i', '<svg$1 preserveAspectRatio="xMidYMid meet">', $svg, 1);
    }

    // Make sure viewBox exists (if not, we can't do much — return as-is)
    if (!preg_match('/viewBox=/i', $svg)) {
        // leave it — ideally your FA svgs already have viewBox
    }

    // Optional: remove inline fill/stroke colors to let currentColor work
    // (only remove fill/stroke attributes if they are static values, not "currentColor")
    $svg = preg_replace('/\\sfill=\"(?!currentColor)[^\"]*\"/i', '', $svg);
    $svg = preg_replace('/\\sstroke=\"(?!currentColor)[^\"]*\"/i', '', $svg);

    return trim($svg);
}

// Preload inline SVG for lock (to avoid clipping issues) and some other critical icons if available
$inline_lock = load_light_svg_normalized('lock');        // try lock.svg
if (!$inline_lock) $inline_lock = load_light_svg_normalized('lock-closed'); // fallback name

$inline_eye = load_light_svg_normalized('eye');
$inline_eye_slash = load_light_svg_normalized('eye-slash'); // fallback names may vary

$inline_arrow = load_light_svg_normalized('arrow-right');
if (!$inline_arrow) $inline_arrow = load_light_svg_normalized('chevron-right');

$inline_shield = load_light_svg_normalized('shield-check');
$inline_check = load_light_svg_normalized('check-circle');

// Messages
$error_message = '';
$success_message = '';

// Handle login form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = isset($_POST['usuario']) ? trim($_POST['usuario']) : '';
    $contrasena = isset($_POST['contrasena']) ? trim($_POST['contrasena']) : '';

    if ($usuario === '' || $contrasena === '') {
        $error_message = 'Por favor, complete todos los campos.';
    } else {
        try {
            require_once __DIR__ . '/config/conexion.php';
            $query = 'SELECT id, usuario, `contraseña` FROM admin WHERE usuario = :usuario LIMIT 1';
            $stmt = $pdo->prepare($query);
            $stmt->execute(['usuario' => $usuario]);
            $user = $stmt->fetch();

            if ($user && hash('sha256', $contrasena) === $user['contraseña']) {
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

// If already logged in, redirect
if (isset($_SESSION['usuario_id'])) {
    header('Location: /dashboard/');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<meta name="description" content="Sistema de Control de Clientes - Acceso Seguro" />
<title>Control de Clientes - Inicio de Sesión</title>

<!-- Tailwind (CDN) -->
<script src="https://cdn.tailwindcss.com"></script>

<!-- Favicon -->
<link rel="icon" type="image/webp" href="/assets/favicon.webp">

<!-- Font Awesome Pro (local JS) -->
<script src="/assets/fontawesome/js/all.min.js" defer></script>

<style>
  :root{
    --icon-size: 1.25rem; /* default icon size (20px) */
    --icon-size-sm: 1.125rem; /* 18px */
  }

  /* Responsive adjustments */
  @media (max-width:420px){
    :root { --icon-size: var(--icon-size-sm); }
  }

  *{box-sizing:border-box}
  body{font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif; margin:0}

  .gradient-bg{
    background: linear-gradient(0deg,#081228 0%, #020B31 100%);
    min-height:100vh; display:flex; align-items:center; justify-content:center; padding:1rem;
    background-size:400% 400%; animation: gradient 15s ease infinite;
  }
  @keyframes gradient{0%{background-position:0 50%}50%{background-position:100% 50%}100%{background-position:0 50%}}

  .glass-effect{
    background:#f9fafb; border:1px solid #d1d5db; border-radius:1rem; padding:2rem; box-shadow:0 20px 40px rgba(2,6,23,0.5);
    backdrop-filter: blur(8px);
  }

  .title-600{font-weight:600}
  .label-500{font-weight:500}

  /* Icon sizing helpers (Font Awesome injected SVGs inherit font-size) */
  .fa-light, .fa-regular, .fa-solid { font-size: var(--icon-size); line-height:1; display:inline-flex; align-items:center; justify-content:center; width: var(--icon-size); height: var(--icon-size); }
  .fa-icon { display:inline-flex; align-items:center; justify-content:center; width: var(--icon-size); height: var(--icon-size); font-size: var(--icon-size); }

  /* Inline SVG wrappers */
  .svg-icon { display:inline-block; width: var(--icon-size); height: var(--icon-size); vertical-align:middle; line-height:0; }
  .svg-icon svg { width:100%; height:100%; display:block; overflow: visible !important; vector-effect: non-scaling-stroke; transform-box: fill-box; shape-rendering: geometricPrecision; }

  /* Prevent clipping: allow children SVGs to overflow container (some icons draw slightly outside viewBox) */
  .form-icon, .fa-icon, .svg-icon { overflow: visible; }

  /* Keep stroke/fill controlled by color */
  .form-icon { color: #9ca3af; }
  .password-toggle { color:#9ca3af; }
  .security-badge { background: linear-gradient(135deg,#2563eb 0%,#1d4ed8 100%); border:1px solid #2563eb; display:flex; align-items:center; justify-content:center; gap:0.5rem; padding:0.75rem 1rem; border-radius:0.5rem; }

  .security-badge .svg-icon, .security-badge .fa-icon { color: #bfff00; }

  /* Alerts */
  .error-alert { background: rgba(239,68,68,0.10); border-left:4px solid #ef4444; color:#991b1b; padding:1rem; border-radius:0.5rem; }
  .success-alert { background: rgba(34,197,94,0.10); border-left:4px solid #22c55e; color:#166534; padding:1rem; border-radius:0.5rem; }

  /* Inputs/buttons */
  input { font-size: 0.95rem; }
  .input-focus:focus { outline: none; border-color:#2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.12); }

  /* Button with icon */
  .btn-with-icon { display:inline-flex; align-items:center; gap:0.5rem; justify-content:center; }

  /* Small accessibility tweak */
  .visually-hidden { position:absolute!important; height:1px; width:1px; overflow:hidden; clip:rect(1px,1px,1px,1px); white-space:nowrap; }
</style>
</head>
<body class="gradient-bg">
  <div class="w-full max-w-md">
    <div class="text-center mb-8">
      <img src="/assets/logo.webp" alt="INFORCOM Logo" class="mx-auto mb-4 object-contain" style="width:60%; height:auto;">
      <h1 class="text-3xl title-600 mt-2" style="color:#F9FAFB;">Control de Clientes</h1>
      <p class="text-gray-200 text-sm mt-2">Sistema de Gestión Profesional</p>
    </div>

    <div class="glass-effect">
      <div class="mb-8 text-center">
        <h2 class="text-2xl title-600 text-black">Inicio de Sesión</h2>
        <p class="text-gray-600 text-sm mt-2">Accede a tu cuenta para continuar</p>
      </div>

      <?php if (!empty($error_message)): ?>
        <div class="error-alert mb-6" role="alert">
          <div class="flex items-center gap-3">
            <div style="color:#991b1b;">
              <?php
                // Prefer OA inline check (regular/light SVG) else FA class (light)
                if ($inline_lock) {
                  // use an "error" svg if available, else use inline lock as visual marker
                  echo '<span class="svg-icon" aria-hidden="true">' . $inline_lock . '</span>';
                } else {
                  echo '<i class="fa-light fa-circle-xmark fa-icon" aria-hidden="true"></i>';
                }
              ?>
            </div>
            <div><?php echo htmlspecialchars($error_message); ?></div>
          </div>
        </div>
      <?php endif; ?>

      <?php if (!empty($success_message)): ?>
        <div class="success-alert mb-6" role="status">
          <div class="flex items-center gap-3">
            <div style="color:#166534;">
              <?php
                if ($inline_check) {
                  echo '<span class="svg-icon" aria-hidden="true">' . $inline_check . '</span>';
                } else {
                  echo '<i class="fa-light fa-circle-check fa-icon" aria-hidden="true"></i>';
                }
              ?>
            </div>
            <div><?php echo htmlspecialchars($success_message); ?></div>
          </div>
        </div>
      <?php endif; ?>

      <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="space-y-5">
        <div class="form-group relative">
          <label for="usuario" class="block text-sm text-black mb-2 label-500">Usuario</label>
          <div class="relative flex items-center">
            <div class="form-icon absolute left-4 w-5 h-5" aria-hidden="true" style="width:var(--icon-size); height:var(--icon-size);">
              <!-- use FA light class (will be injected as SVG) -->
              <i class="fa-light fa-user fa-icon" aria-hidden="true"></i>
            </div>

            <input id="usuario" name="usuario" type="text" autocomplete="username"
              class="input-placeholder input-focus w-full bg-white border rounded-lg py-3 px-4 pl-12 text-black"
              placeholder="Ingresa tu usuario" style="border-color:#d1d5db;" required>
          </div>
        </div>

        <div class="form-group relative">
          <label for="contrasena" class="block text-sm text-black mb-2 label-500">Contraseña</label>
          <div class="relative flex items-center">
            <div class="form-icon absolute left-4 w-5 h-5" aria-hidden="true" style="width:var(--icon-size); height:var(--icon-size);">
              <!-- Lock: use inline SVG from light set to avoid clipping. Fallback to fa-light if file missing -->
              <?php
                if ($inline_lock) {
                  echo '<span class="svg-icon" aria-hidden="true" id="lockInline">' . $inline_lock . '</span>';
                } else {
                  echo '<i class="fa-light fa-lock fa-icon" aria-hidden="true"></i>';
                }
              ?>
            </div>

            <input id="contrasena" name="contrasena" type="password" autocomplete="current-password"
              class="input-placeholder input-focus w-full bg-white border rounded-lg py-3 px-4 pl-12 pr-12 text-black"
              placeholder="Ingresa tu contraseña" style="border-color:#d1d5db;" required>

            <button type="button" id="pwd-toggle" class="password-toggle absolute right-4" aria-label="Mostrar contraseña" aria-pressed="false" style="color:#9ca3af;">
              <!-- Eye: prefer inline (light) if available; fallback to fa-light -->
              <?php
                if ($inline_eye) {
                  echo '<span id="eyeVisible" class="svg-icon" aria-hidden="true">' . $inline_eye . '</span>';
                } else {
                  echo '<i id="eyeVisibleFallback" class="fa-light fa-eye fa-icon" aria-hidden="true"></i>';
                }
                if ($inline_eye_slash) {
                  echo '<span id="eyeHidden" class="svg-icon" aria-hidden="true" style="display:none;">' . $inline_eye_slash . '</span>';
                } else {
                  echo '<i id="eyeHiddenFallback" class="fa-light fa-eye-slash fa-icon" aria-hidden="true" style="display:none;"></i>';
                }
              ?>
            </button>
          </div>
        </div>

        <button type="submit" class="btn-with-icon btn-hover w-full text-white font-bold py-3 px-4 rounded-lg mt-4" style="background:linear-gradient(to right,#2563eb 0%,#1d4ed8 100%);">
          <!-- Submit icon: prefer inline arrow from light set -->
          <?php
            if ($inline_arrow) {
              echo '<span class="svg-icon" aria-hidden="true" style="color:#ffffff; margin-right:8px;">' . $inline_arrow . '</span>';
            } else {
              echo '<i class="fa-light fa-arrow-right fa-icon" style="color:#ffffff; margin-right:8px;" aria-hidden="true"></i>';
            }
          ?>
          <span>Iniciar Sesión</span>
        </button>
      </form>
    </div>

    <div class="security-badge mt-6">
      <?php
        if ($inline_shield) {
          echo '<span class="svg-icon" aria-hidden="true" style="color:#bfff00;">' . $inline_shield . '</span>';
        } else {
          echo '<i class="fa-light fa-shield-alt fa-icon" style="color:#bfff00;" aria-hidden="true"></i>';
        }
      ?>
      <span class="text-white">Conexión segura y encriptada</span>
        <?php
          // Icono temático: candado (inline si existe en light, si no fallback a fa-light fa-lock)
          if (!empty($inline_lock)) {
              echo '<span class="svg-icon" aria-hidden="true" style="color:#bfff00;">' . $inline_lock . '</span>';
          } else {
              echo '<i class="fa-light fa-lock fa-icon" style="color:#bfff00;" aria-hidden="true"></i>';
          }
        ?>
    </div>

    <p class="text-center text-gray-200 text-xs mt-6">© 2026 Control de Clientes. Todos los derechos reservados.</p>
  </div>

<script>
(function () {
  // Toggle password visibility: supports inline SVG fallbacks and FA-injected <i> fallbacks.
  const btn = document.getElementById('pwd-toggle');
  const inp = document.getElementById('contrasena');
  if (!btn || !inp) return;

  const eyeVisible = document.getElementById('eyeVisible');
  const eyeHidden = document.getElementById('eyeHidden');
  const eyeVisibleFallback = document.getElementById('eyeVisibleFallback');
  const eyeHiddenFallback = document.getElementById('eyeHiddenFallback');

  btn.addEventListener('click', function () {
    const showing = inp.type === 'text';
    if (!showing) {
      inp.type = 'text';
      // show eye-off, hide eye
      if (eyeVisible && eyeHidden) {
        eyeVisible.style.display = 'none';
        eyeHidden.style.display = '';
      } else if (eyeVisibleFallback && eyeHiddenFallback) {
        eyeVisibleFallback.style.display = 'none';
        eyeHiddenFallback.style.display = '';
      } else {
        // If using FA-injected <i>, attempt class swap on the fallback element
        if (eyeVisibleFallback) {
          eyeVisibleFallback.classList.remove('fa-eye');
          eyeVisibleFallback.classList.add('fa-eye-slash');
        }
      }
      btn.setAttribute('aria-pressed', 'true');
      btn.setAttribute('aria-label', 'Ocultar contraseña');
    } else {
      inp.type = 'password';
      if (eyeVisible && eyeHidden) {
        eyeVisible.style.display = '';
        eyeHidden.style.display = 'none';
      } else if (eyeVisibleFallback && eyeHiddenFallback) {
        eyeVisibleFallback.style.display = '';
        eyeHiddenFallback.style.display = 'none';
      } else {
        if (eyeVisibleFallback) {
          eyeVisibleFallback.classList.remove('fa-eye-slash');
          eyeVisibleFallback.classList.add('fa-eye');
        }
      }
      btn.setAttribute('aria-pressed', 'false');
      btn.setAttribute('aria-label', 'Mostrar contraseña');
    }
  });

  // Ensure responsive sizing: if user resizes, icons using font-size will scale
  // (No extra JS required; CSS uses variables and media queries.)

  // Clear password on page refresh
  window.addEventListener('beforeunload', function () {
    if (inp) inp.value = '';
  });

  // Small focus animations (cosmetic)
  document.querySelectorAll('input').forEach(input => {
    input.addEventListener('focus', function () {
      this.parentElement.parentElement.classList.add('scale-105');
      this.parentElement.parentElement.style.transition = 'transform 0.18s ease';
    });
    input.addEventListener('blur', function () {
      this.parentElement.parentElement.classList.remove('scale-105');
    });
  });
})();
</script>
</body>
</html>