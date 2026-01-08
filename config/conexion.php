<?php
declare(strict_types=1);

define('DB_HOST', 'localhost');
define('DB_NAME', 'zqgikadc_clientesinforcom'); // <-- ESTA ES LA CORRECTA
define('DB_USER', 'zqgikadc_admin');
define('DB_PASS', 'aBjar1BKI4sW');
define('DB_PORT', 3306);

$dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';port=' . DB_PORT . ';charset=utf8mb4';

$pdo = new PDO($dsn, DB_USER, DB_PASS, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
]);