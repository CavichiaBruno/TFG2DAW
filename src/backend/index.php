<?php
/**
 * Main Entry Point
 * Single entry point for the application with routing
 */

// Error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session
session_start();

// Load dependencies
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/helpers/functions.php';

// Load controllers
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/PropertyController.php';
require_once __DIR__ . '/controllers/UserController.php';
require_once __DIR__ . '/controllers/BlogController.php';
require_once __DIR__ . '/controllers/MessageController.php';
require_once __DIR__ . '/controllers/AdminController.php';

// Get action from URL
$action = isset($_GET['action']) ? sanitize($_GET['action']) : 'home';

// Initialize controllers
$authController = new AuthController($pdo);
$propertyController = new PropertyController($pdo);
$userController = new UserController($pdo);
$blogController = new BlogController($pdo);
$messageController = new MessageController($pdo);
$adminController = new AdminController($pdo);

// Routing
switch ($action) {
    // Home
    case 'home':
        include __DIR__ . '/../frontend/views/home.php';
        break;
    
    // Authentication
    case 'login':
        $authController->showLogin();
        break;
    
    case 'login_post':
        $authController->login();
        break;
    
    case 'register':
        $authController->showRegister();
        break;
    
    case 'register_post':
        $authController->register();
        break;
    
    case 'logout':
        $authController->logout();
        break;
    
    // Properties
    case 'properties':
        $propertyController->index();
        break;
    
    case 'property_detail':
        $propertyController->show();
        break;
    
    case 'property_create':
        $propertyController->create();
        break;
    
    case 'property_store':
        $propertyController->store();
        break;
    
    case 'property_edit':
        $propertyController->edit();
        break;
    
    case 'property_update':
        $propertyController->update();
        break;
    
    case 'property_delete':
        $propertyController->delete();
        break;
    
    // User
    case 'profile':
        $userController->profile();
        break;
    
    case 'profile_update':
        $userController->updateProfile();
        break;
    
    case 'favorite_toggle':
        $userController->toggleFavorite();
        break;
    
    // Blog
    case 'blog':
        $blogController->index();
        break;
    
    case 'blog_detail':
        $blogController->show();
        break;
    
    case 'blog_create':
        $blogController->create();
        break;
    
    case 'blog_store':
        $blogController->store();
        break;
    
    case 'blog_edit':
        $blogController->edit();
        break;
    
    case 'blog_update':
        $blogController->update();
        break;
    
    case 'blog_delete':
        $blogController->delete();
        break;
    
    // Messages
    case 'contact':
        include __DIR__ . '/../frontend/views/contact.php';
        break;
    
    case 'message_send':
        $messageController->send();
        break;
    
    // Admin - Users
    case 'admin_users':
        $userController->adminListUsers();
        break;
    
    case 'admin_user_create':
        $userController->adminCreateUser();
        break;
        
    case 'admin_user_store':
        $userController->adminStoreUser();
        break;
        
    case 'admin_user_edit':
        $userController->adminEditUser();
        break;
        
    case 'admin_user_update':
        $userController->adminUpdateUser();
        break;
    
    case 'admin_user_delete':
        $userController->adminDeleteUser();
        break;
    
    // Admin - Messages
    case 'admin_messages':
        $messageController->adminList();
        break;
    
    case 'admin_message_view':
        $messageController->adminView();
        break;
    
    case 'admin_message_delete':
        $messageController->adminDelete();
        break;
    
    // Admin - Dashboard
    case 'admin_dashboard':
        $adminController->dashboard();
        break;
    
    // 404
    default:
        http_response_code(404);
        include __DIR__ . '/../frontend/views/404.php';
        break;
}
