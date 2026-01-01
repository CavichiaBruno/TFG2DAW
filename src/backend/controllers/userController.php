<?php
/**
 * UserController
 * Handles user management (admin)
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../helpers/functions.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/PropertyModel.php';
require_once __DIR__ . '/../models/FavoritoModel.php';

class UserController {
    private $userModel;
    private $propertyModel;
    private $favoritoModel;
    
    public function __construct($pdo) {
        $this->userModel = new UserModel($pdo);
        $this->propertyModel = new PropertyModel($pdo);
        $this->favoritoModel = new FavoritoModel($pdo);
    }
    
    // User profile
    public function profile() {
        startSession();
        
        if (!isAuthenticated()) {
            redirect('/?action=login');
        }
        
        $userId = $_SESSION['user_id'];
        $user = $this->userModel->findById($userId);
        $properties = $this->propertyModel->getByUser($userId);
        $favorites = $this->favoritoModel->getUserFavorites($userId);
        
        include __DIR__ . '/../../frontend/views/user/profile.php';
    }
    
    // Update profile
    public function updateProfile() {
        startSession();
        
        if (!isAuthenticated()) {
            redirect('/?action=login');
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/?action=profile');
        }
        
        $userId = $_SESSION['user_id'];
        
        $data = [
            'nombre' => sanitize($_POST['nombre'] ?? ''),
            'apellidos' => sanitize($_POST['apellidos'] ?? ''),
            'email' => sanitize($_POST['email'] ?? ''),
            'telefono' => sanitize($_POST['telefono'] ?? '')
        ];
        
        // Validation
        $errors = validateRequired(['nombre', 'email'], $data);
        
        if (!validateEmail($data['email'])) {
            $errors['email'] = 'Email no válido';
        } elseif ($this->userModel->emailExists($data['email'], $userId)) {
            $errors['email'] = 'El email ya está en uso';
        }
        
        // Update password if provided
        if (!empty($_POST['password'])) {
            if ($_POST['password'] !== $_POST['password_confirm']) {
                $errors['password'] = 'Las contraseñas no coinciden';
            } elseif (strlen($_POST['password']) < 6) {
                $errors['password'] = 'La contraseña debe tener al menos 6 caracteres';
            } else {
                $data['password'] = $_POST['password'];
            }
        }
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            redirect('/?action=profile');
        }
        
        if ($this->userModel->update($userId, $data)) {
            // Update session
            $_SESSION['user_nombre'] = $data['nombre'];
            $_SESSION['user_email'] = $data['email'];
            
            setFlash('success', 'Perfil actualizado correctamente');
        } else {
            setFlash('error', 'Error al actualizar el perfil');
        }
        
        redirect('/?action=profile');
    }
    
    // Toggle favorite
    public function toggleFavorite() {
        startSession();
        
        if (!isAuthenticated()) {
            jsonResponse(['success' => false, 'message' => 'No autenticado'], 401);
        }
        
        $propiedadId = isset($_POST['propiedad_id']) ? (int)$_POST['propiedad_id'] : 0;
        $userId = $_SESSION['user_id'];
        
        if (!$propiedadId) {
            jsonResponse(['success' => false, 'message' => 'ID inválido']);
        }
        
        $isFavorite = $this->favoritoModel->isFavorite($userId, $propiedadId);
        
        if ($isFavorite) {
            $this->favoritoModel->remove($userId, $propiedadId);
            jsonResponse(['success' => true, 'action' => 'removed']);
        } else {
            $this->favoritoModel->add($userId, $propiedadId);
            jsonResponse(['success' => true, 'action' => 'added']);
        }
    }
    
    // Admin: List all users
    public function adminListUsers() {
        startSession();
        
        if (!isAdmin()) {
            setFlash('error', 'Acceso denegado');
            redirect('/');
        }
        
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 30;
        
        $total = $this->userModel->count();
        $pagination = paginate($total, $perPage, $page);
        $users = $this->userModel->getAll($perPage, $pagination['offset']);
        
        include __DIR__ . '/../../frontend/views/admin/users/list.php';
    }

    // Admin: Create user form
    public function adminCreateUser() {
        startSession();
        if (!isAdmin()) {
            redirect('/');
        }
        include __DIR__ . '/../../frontend/views/admin/users/create.php';
    }

    // Admin: Store user
    public function adminStoreUser() {
        startSession();
        if (!isAdmin()) redirect('/');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('/?action=admin_user_create');
        
        $data = [
            'nombre' => sanitize($_POST['nombre'] ?? ''),
            'apellidos' => sanitize($_POST['apellidos'] ?? ''),
            'email' => sanitize($_POST['email'] ?? ''),
            'password' => $_POST['password'] ?? '',
            'telefono' => sanitize($_POST['telefono'] ?? ''),
            'rol_id' => (int)($_POST['rol_id'] ?? 2)
        ];
        
        $errors = validateRequired(['nombre', 'email', 'password'], $data);
        
        if (!validateEmail($data['email'])) {
            $errors['email'] = 'Email no válido';
        } elseif ($this->userModel->emailExists($data['email'])) {
            $errors['email'] = 'El email ya está en uso';
        }
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $data;
            redirect('/?action=admin_user_create');
        }
        
        if ($this->userModel->create($data)) {
            setFlash('success', 'Usuario creado correctamente');
            redirect('/?action=admin_users');
        } else {
            setFlash('error', 'Error al crear usuario');
            redirect('/?action=admin_user_create');
        }
    }

    // Admin: Edit user form
    public function adminEditUser() {
        startSession();
        if (!isAdmin()) redirect('/');
        
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $user = $this->userModel->findById($id);
        
        if (!$user) {
            setFlash('error', 'Usuario no encontrado');
            redirect('/?action=admin_users');
        }
        
        include __DIR__ . '/../../frontend/views/admin/users/edit.php';
    }

    // Admin: Update user
    public function adminUpdateUser() {
        startSession();
        if (!isAdmin()) redirect('/');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('/?action=admin_users');
        
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        
        $data = [
            'nombre' => sanitize($_POST['nombre'] ?? ''),
            'apellidos' => sanitize($_POST['apellidos'] ?? ''),
            'email' => sanitize($_POST['email'] ?? ''),
            'telefono' => sanitize($_POST['telefono'] ?? ''),
            'rol_id' => (int)($_POST['rol_id'] ?? 2)
        ];
        
        if (!empty($_POST['password'])) {
            $data['password'] = $_POST['password'];
        }
        
        $errors = validateRequired(['nombre', 'email'], $data);
        
        if (!validateEmail($data['email'])) {
            $errors['email'] = 'Email no válido';
        } elseif ($this->userModel->emailExists($data['email'], $id)) {
            $errors['email'] = 'El email ya está en uso';
        }
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            redirect('/?action=admin_user_edit&id=' . $id);
        }
        
        if ($this->userModel->update($id, $data)) {
            setFlash('success', 'Usuario actualizado correctamente');
            redirect('/?action=admin_users');
        } else {
            setFlash('error', 'Error al actualizar usuario');
            redirect('/?action=admin_user_edit&id=' . $id);
        }
    }
    
    // Admin: Delete user
    public function adminDeleteUser() {
        startSession();
        
        if (!isAdmin()) {
            redirect('/');
        }
        
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        
        if ($id == $_SESSION['user_id']) {
            setFlash('error', 'No puedes eliminar tu propia cuenta');
            redirect('/?action=admin_users');
        }
        
        if ($this->userModel->delete($id)) {
            setFlash('success', 'Usuario eliminado correctamente');
        } else {
            setFlash('error', 'Error al eliminar el usuario');
        }
        
        redirect('/?action=admin_users');
    }
}
