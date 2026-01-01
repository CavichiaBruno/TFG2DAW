<?php
/**
 * Database Configuration
 * PDO connection for MySQL/MariaDB
 */

// Database credentials
define('DB_HOST', 'localhost');
define('DB_NAME', 'iberpiso');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Base URL for the application
define('BASE_URL', '/TFG2DAW/src/backend');
define('ASSETS_URL', '/TFG2DAW/src');

// PDO options for security and error handling
$pdoOptions = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

// Create PDO connection
try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $pdoOptions);
} catch (PDOException $e) {
    // Log error securely (no details exposed)
    error_log("Database connection failed: " . $e->getMessage());
    die("Error de conexión a la base de datos. Por favor, inténtelo más tarde.");
}

return $pdo;
