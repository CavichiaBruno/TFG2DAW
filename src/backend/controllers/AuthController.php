<?php
/**
 * AuthController
 * Handles authentication: login, register, logout
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../helpers/functions.php';
require_once __DIR__ . '/../models/UserModel.php';

class AuthController {
    private $userModel;
    
    public function __construct($pdo) {
        $this->userModel = new UserModel($pdo);
    }
    
    // Display login form
    public function showLogin() {
        startSession();
        
        // Redirect if already authenticated
        if (isAuthenticated()) {
            redirect('/');
        }
        
        include __DIR__ . '/../../frontend/components/login-page/login-page-component.php';
    }
    
    // Handle login
    public function login() {
        startSession();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/?action=login');
        }
        
        $email = sanitize($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        
        // Validation
        $errors = [];
        
        if (empty($email)) {
            $errors[] = 'El email es obligatorio';
        } elseif (!validateEmail($email)) {
            $errors[] = 'El email no es válido';
        }
        
        if (empty($password)) {
            $errors[] = 'La contraseña es obligatoria';
        }
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            redirect('/?action=login');
        }
        
        // Find user
        $user = $this->userModel->findByEmail($email);
        
        if (!$user || !$this->userModel->verifyPassword($password, $user['password'])) {
            setFlash('error', 'Credenciales incorrectas');
            redirect('/?action=login');
        }
        
        // Create session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_nombre'] = $user['nombre'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['rol_id'] = $user['rol_id'];
        $_SESSION['rol_nombre'] = $user['rol_nombre'];
        
        setFlash('success', 'Bienvenido, ' . $user['nombre']);
        redirect('/');
    }
    
    // Display register form
    public function showRegister() {
        startSession();
        
        // Redirect if already authenticated
        if (isAuthenticated()) {
            redirect('/');
        }
        
        include __DIR__ . '/../../frontend/components/register-page/register-page-component.php';
    }
    
    // Handle registration
    public function register() {
        startSession();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/?action=register');
        }
        
        $nombre = sanitize($_POST['nombre'] ?? '');
        $apellidos = sanitize($_POST['apellidos'] ?? '');
        $email = sanitize($_POST['email'] ?? '');
        $telefono = sanitize($_POST['telefono'] ?? '');
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';
        
        // Validation
        $errors = [];
        
        if (empty($nombre)) {
            $errors[] = 'El nombre es obligatorio';
        }
        
        if (empty($email)) {
            $errors[] = 'El email es obligatorio';
        } elseif (!validateEmail($email)) {
            $errors[] = 'El email no es válido';
        } elseif ($this->userModel->emailExists($email)) {
            $errors[] = 'El email ya está registrado';
        }
        
        if (empty($password)) {
            $errors[] = 'La contraseña es obligatoria';
        } elseif (strlen($password) < 6) {
            $errors[] = 'La contraseña debe tener al menos 6 caracteres';
        } elseif ($password !== $passwordConfirm) {
            $errors[] = 'Las contraseñas no coinciden';
        }
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $_POST;
            redirect('/?action=register');
        }
        
        // Create user
        $result = $this->userModel->create([
            'nombre' => $nombre,
            'apellidos' => $apellidos,
            'email' => $email,
            'telefono' => $telefono,
            'password' => $password,
            'rol_id' => 2 // Default: usuario
        ]);
        
        if ($result) {
            setFlash('success', 'Registro exitoso. Ya puedes iniciar sesión.');
            redirect('/?action=login');
        } else {
            setFlash('error', 'Error al registrar el usuario');
            redirect('/?action=register');
        }
    }
    
    // Handle logout
    public function logout() {
        startSession();
        
        // Destroy session
        $_SESSION = [];
        
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params['path'], $params['domain'],
                $params['secure'], $params['httponly']
            );
        }
        
        session_destroy();
        
        setFlash('success', 'Sesión cerrada correctamente');
        redirect('/');
    }
}
