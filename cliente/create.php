<?php
declare(strict_types=1);
session_start();
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'message' => 'Método no permitido.']);
    exit;
}

require_once __DIR__ . '/../config/conexion.php';

// Datos del cliente
$cliente = trim((string)($_POST['cliente'] ?? ''));
$contacto = trim((string)($_POST['contacto'] ?? ''));
$telefono = trim((string)($_POST['telefono'] ?? ''));
$dominio = trim((string)($_POST['dominio'] ?? ''));

// Validaciones básicas
if ($cliente === '' || $dominio === '') {
    http_response_code(400);
    echo json_encode(['ok' => false, 'message' => 'Los campos Cliente y Dominio son obligatorios.']);
    exit;
}

// Validación de dominio
if (!preg_match('/^[a-z0-9.-]+\.[a-z]{2,10}$/i', $dominio)) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'message' => 'El dominio ingresado no es válido.']);
    exit;
}

try {
    // Validar unicidad
    $stmt = $pdo->prepare("SELECT COUNT(*) AS c FROM clientes WHERE LOWER(cliente) = LOWER(:cliente) OR LOWER(dominio) = LOWER(:dominio)");
    $stmt->execute(['cliente' => $cliente, 'dominio' => $dominio]);
    $exists = (int)$stmt->fetch()['c'];

    if ($exists > 0) {
        http_response_code(409);
        echo json_encode(['ok' => false, 'message' => 'El cliente o dominio ya existe.']);
        exit;
    }

    // Insertar cliente
    $stmt = $pdo->prepare("INSERT INTO clientes (cliente, contacto, telefono, dominio) VALUES (:cliente, :contacto, :telefono, :dominio)");
    $stmt->execute(['cliente' => $cliente, 'contacto' => $contacto, 'telefono' => $telefono, 'dominio' => $dominio]);
    $id = (int)$pdo->lastInsertId();

    if ($id <= 0) {
        throw new Exception('No se pudo crear el cliente.');
    }

    echo json_encode(['ok' => true, 'id' => $id]);
} catch (Throwable $e) {
    error_log('Cliente creation error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['ok' => false, 'message' => 'Error interno.']);
}