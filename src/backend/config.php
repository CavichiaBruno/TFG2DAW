<?php
/**
 * Database Configuration
 * PDO connection for MySQL/MariaDB
 * Supports both Docker (env vars) and local XAMPP (defaults)
 */

// Database credentials - use env vars if available (Docker), otherwise defaults (XAMPP)
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_NAME', getenv('DB_NAME') ?: 'iberpiso');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_CHARSET', 'utf8mb4');

// Base URL for the application
// In Docker: app is at root (/), locally: at /TFG2DAW/
$isDocker = getenv('DB_HOST') !== false;
define('BASE_URL', $isDocker ? '/src/backend' : '/TFG2DAW/src/backend');
define('ASSETS_URL', $isDocker ? '/src' : '/TFG2DAW/src');

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
