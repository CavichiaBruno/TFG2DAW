<?php
/**
 * MessageController
 * Handles contact messages
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../helpers/functions.php';
require_once __DIR__ . '/../models/MessageModel.php';

class MessageController {
    private $messageModel;
    
    public function __construct($pdo) {
        $this->messageModel = new MessageModel($pdo);
    }
    
    // Send message (contact form)
    public function send() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/');
        }
        
        startSession();
        
        $data = [
            'nombre' => sanitize($_POST['nombre'] ?? ''),
            'email' => sanitize($_POST['email'] ?? ''),
            'telefono' => sanitize($_POST['telefono'] ?? ''),
            'asunto' => sanitize($_POST['asunto'] ?? ''),
            'mensaje' => sanitize($_POST['mensaje'] ?? ''),
            'propiedad_id' => !empty($_POST['propiedad_id']) ? (int)$_POST['propiedad_id'] : null,
            'usuario_id' => isAuthenticated() ? $_SESSION['user_id'] : null
        ];
        
        // Validation
        $errors = validateRequired(['nombre', 'email', 'mensaje'], $data);
        
        if (!validateEmail($data['email'])) {
            $errors['email'] = 'Email no válido';
        }
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $_POST;
            
            $redirect = !empty($data['propiedad_id']) 
                ? '/?action=property_detail&id=' . $data['propiedad_id'] 
                : '/?action=contact';
            
            redirect($redirect);
        }
        
        if ($this->messageModel->create($data)) {
            setFlash('success', 'Mensaje enviado correctamente. Te contactaremos pronto.');
        } else {
            setFlash('error', 'Error al enviar el mensaje');
        }
        
        $redirect = !empty($data['propiedad_id']) 
            ? '/?action=property_detail&id=' . $data['propiedad_id'] 
            : '/?action=contact';
        
        redirect($redirect);
    }
    
    // Admin: List messages
    public function adminList() {
        startSession();
        
        if (!isAdmin()) {
            setFlash('error', 'Acceso denegado');
            redirect('/');
        }
        
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 30;
        
        $total = $this->messageModel->count();
        $pagination = paginate($total, $perPage, $page);
        $messages = $this->messageModel->getAll($perPage, $pagination['offset']);
        
        include __DIR__ . '/../../frontend/views/admin/messages.php';
    }
    
    // Admin: View message
    public function adminView() {
        startSession();
        
        if (!isAdmin()) {
            redirect('/');
        }
        
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $message = $this->messageModel->findById($id);
        
        if (!$message) {
            setFlash('error', 'Mensaje no encontrado');
            redirect('/?action=admin_messages');
        }
        
        // Mark as read
        $this->messageModel->markAsRead($id);
        
        include __DIR__ . '/../../frontend/views/admin/message-detail.php';
    }
    
    // Admin: Delete message
    public function adminDelete() {
        startSession();
        
        if (!isAdmin()) {
            redirect('/');
        }
        
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        
        if ($this->messageModel->delete($id)) {
            setFlash('success', 'Mensaje eliminado correctamente');
        } else {
            setFlash('error', 'Error al eliminar el mensaje');
        }
        
        redirect('/?action=admin_messages');
    }
}
