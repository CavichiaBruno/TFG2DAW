<?php
/**
 * AdminController
 * Handles admin dashboard and management
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../helpers/functions.php';
require_once __DIR__ . '/../models/PropertyModel.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/BlogModel.php';

class AdminController {
    private $propertyModel;
    private $userModel;
    private $blogModel;
    
    public function __construct($pdo) {
        $this->propertyModel = new PropertyModel($pdo);
        $this->userModel = new UserModel($pdo);
        $this->blogModel = new BlogModel($pdo);
    }
    
    public function dashboard() {
        startSession();
        
        if (!isAdmin()) {
            setFlash('error', 'Acceso denegado');
            redirect('/');
        }
        
        // Stats
        $stats = [
            'properties' => $this->propertyModel->count([]),
            'users' => $this->userModel->count(),
            'posts' => $this->blogModel->count(false) // Count all, including unpublished
        ];
        
        // Recent items
        $recentProperties = $this->propertyModel->getAll([], 5, 0);
        $recentUsers = $this->userModel->getAll(5, 0);
        $recentPosts = $this->blogModel->getAll(false, 5, 0);
        
        include __DIR__ . '/../../frontend/views/admin/dashboard.php';
    }
}
