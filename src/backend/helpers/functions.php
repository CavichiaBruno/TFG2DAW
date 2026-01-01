<?php
/**
 * Helper Functions
 * Security, validation, and utility functions
 */

// Session management
function startSession() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

// Authentication check
function isAuthenticated() {
    startSession();
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

// Get current user
function getCurrentUser() {
    startSession();
    return $_SESSION ?? null;
}

// Check user role
function hasRole($role) {
    startSession();
    if (!isset($_SESSION['rol_nombre'])) {
        return false;
    }
    return $_SESSION['rol_nombre'] === $role;
}

// Check if user is admin
function isAdmin() {
    return hasRole('admin');
}

// Redirect helper
function redirect($path) {
    if (strpos($path, '/') === 0) {
        // If path starts with /, prepend BASE_URL (unless it already contains it)
        if (strpos($path, BASE_URL) !== 0) {
            $path = BASE_URL . $path;
        }
    }
    header("Location: $path");
    exit;
}

// Sanitize input
function sanitize($data) {
    if (is_array($data)) {
        return array_map('sanitize', $data);
    }
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// Validate email
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

// Validate required fields
function validateRequired($fields, $data) {
    $errors = [];
    foreach ($fields as $field) {
        if (!isset($data[$field]) || empty(trim($data[$field]))) {
            $errors[$field] = "El campo $field es obligatorio";
        }
    }
    return $errors;
}

// Generate CSRF token
function generateCsrfToken() {
    startSession();
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Verify CSRF token
function verifyCsrfToken($token) {
    startSession();
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Format price
function formatPrice($price) {
    return number_format($price, 0, ',', '.') . ' €';
}

// Generate slug
function generateSlug($text) {
    $text = strtolower($text);
    $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
    $text = preg_replace('/[\s-]+/', '-', $text);
    return trim($text, '-');
}

// Date format helper
function formatDate($date) {
    return date('d/m/Y', strtotime($date));
}

// Pagination helper
function paginate($total, $perPage, $currentPage) {
    $totalPages = ceil($total / $perPage);
    $currentPage = max(1, min($currentPage, $totalPages));
    $offset = ($currentPage - 1) * $perPage;
    
    return [
        'total' => $total,
        'perPage' => $perPage,
        'currentPage' => $currentPage,
        'totalPages' => $totalPages,
        'offset' => $offset,
        'hasNext' => $currentPage < $totalPages,
        'hasPrev' => $currentPage > 1
    ];
}

// Flash message system
function setFlash($type, $message) {
    startSession();
    $_SESSION['flash'][$type] = $message;
}

function getFlash($type) {
    startSession();
    if (isset($_SESSION['flash'][$type])) {
        $message = $_SESSION['flash'][$type];
        unset($_SESSION['flash'][$type]);
        return $message;
    }
    return null;
}

function hasFlash($type) {
    startSession();
    return isset($_SESSION['flash'][$type]);
}

// File upload helper
function uploadImage($file, $uploadDir = '../public/uploads/') {
    $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
    $maxSize = 5 * 1024 * 1024; // 5MB
    
    if (!isset($file['error']) || is_array($file['error'])) {
        return ['success' => false, 'error' => 'Error en la carga del archivo'];
    }
    
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'error' => 'Error al subir el archivo'];
    }
    
    if ($file['size'] > $maxSize) {
        return ['success' => false, 'error' => 'El archivo es demasiado grande (máx. 5MB)'];
    }
    
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($file['tmp_name']);
    
    if (!in_array($mimeType, $allowedTypes)) {
        return ['success' => false, 'error' => 'Tipo de archivo no permitido'];
    }
    
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '_' . time() . '.' . $extension;
    $destination = $uploadDir . $filename;
    
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        return ['success' => false, 'error' => 'Error al mover el archivo'];
    }
    
    return ['success' => true, 'filename' => $filename, 'path' => $destination];
}

// Response helper (for AJAX)
function jsonResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}
