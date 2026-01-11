<?php
require_once __DIR__ . '/../config/session.php';

$usuario_layout = $_SESSION['usuario'] ?? 'Administrador';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Control de Clientes - Sistema de Gestión Profesional</title>
    <link rel="icon" type="image/webp" href="/assets/favicon.webp">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root{
            --bg1:#081228;
            --bg2:#020B31;
            --accent:#bfff00;
            --muted:#9ca3af;

            /* ✅ estándar solicitado */
            --r: 0.3rem;
        }

        /* ✅ Forzar border-radius 0.3rem en TODO el sistema */
        :where(
            button, a,
            input, select, textarea,
            .soft-card,
            [class*="rounded"]
        ){
            border-radius: var(--r) !important;
        }

        .sidebar-bg{
            background: linear-gradient(180deg, rgba(8,18,40,1) 0%, rgba(2,11,49,1) 100%);
        }
        .app-bg{ background: #f3f4f6; }
        .soft-card{
            background: #ffffff;
            border: 1px solid rgba(229,231,235,1);
            box-shadow: 0 8px 18px rgba(0,0,0,0.06);
        }
        .active-item{
            background: rgba(37,99,235,0.18);
            border: 1px solid rgba(37,99,235,0.25);
        }
        .accent-dot{ background: var(--accent); }
        .modal-backdrop{ background: rgba(2,11,49,0.65); }

        /* Focus profesional (sin borde grueso) */
        :where(input, select, textarea){
            transition: box-shadow .15s ease, border-color .15s ease, background-color .15s ease;
        }
        :where(input, select, textarea):focus{
            outline: none !important;
            border-color: rgba(37,99,235,.55) !important;
            box-shadow: 0 0 0 3px rgba(37,99,235,.12) !important;
            background-color: #ffffff !important;
        }

        /* ✅ Botones/iconos en acciones: sin borde cuadrado, hover con color */
        .action-icon{
            width: 2.25rem;
            height: 2.25rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: transparent;
            border: 0;
            color: #6b7280; /* gray-500 */
            transition: background-color .15s ease, color .15s ease;
        }
        .action-icon:hover{
            background: rgba(17,24,39,0.06);
            color: #111827; /* gray-900 */
        }
        .action-icon--blue:hover{ background: rgba(37,99,235,0.10); color:#2563eb; }
        .action-icon--green:hover{ background: rgba(34,197,94,0.12); color:#16a34a; }
        .action-icon--orange:hover{ background: rgba(249,115,22,0.12); color:#f97316; }
        .action-icon--red:hover{ background: rgba(239,68,68,0.10); color:#ef4444; }

        /* SVG fixes: evita recorte y define clase icon-svg (no rompe diseño) */
        svg { overflow: visible !important; }
        .icon-svg {
          width: 1.25rem;
          height: 1.25rem;
          display: inline-block;
          flex: 0 0 auto;
          vertical-align: middle;
        }
        /* evita que el stroke escale si alguna regla lo fuerza */
        .icon-svg * { vector-effect: non-scaling-stroke; }
    </style>
</head>
<body class="min-h-screen app-bg">
<div class="flex min-h-screen">