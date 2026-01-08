<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

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
    </style>
</head>
<body class="min-h-screen app-bg">
<div class="flex min-h-screen">
