<?php
/**
 * PropertyController
 * Handles property CRUD operations
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../helpers/functions.php';
require_once __DIR__ . '/../models/PropertyModel.php';
require_once __DIR__ . '/../models/ImageModel.php';

class PropertyController {
    private $propertyModel;
    private $imageModel;
    
    public function __construct($pdo) {
        $this->propertyModel = new PropertyModel($pdo);
        $this->imageModel = new ImageModel($pdo);
    }
    
    // List all properties
    public function index() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 20;
        
        // Build filters
        $filters = [];
        if (!empty($_GET['tipo'])) {
            $filters['tipo'] = sanitize($_GET['tipo']);
        }
        if (!empty($_GET['operacion'])) {
            $filters['operacion'] = sanitize($_GET['operacion']);
        }
        if (!empty($_GET['ciudad'])) {
            $filters['ciudad'] = sanitize($_GET['ciudad']);
        }
        if (!empty($_GET['precio_min'])) {
            $filters['precio_min'] = (float)$_GET['precio_min'];
        }
        if (!empty($_GET['precio_max'])) {
            $filters['precio_max'] = (float)$_GET['precio_max'];
        }
        if (!empty($_GET['habitaciones'])) {
            $filters['habitaciones'] = (int)$_GET['habitaciones'];
        }
        
        // Get properties
        $total = $this->propertyModel->count($filters);
        $pagination = paginate($total, $perPage, $page);
        $properties = $this->propertyModel->getAll($filters, $perPage, $pagination['offset']);
        
        // Add principal image to each property
        foreach ($properties as &$property) {
            $property['imagen_principal'] = $this->imageModel->getPrincipal($property['id']);
        }
        
        // Load view
        include __DIR__ . '/../../frontend/views/properties/list.php';
    }
    
    // Show single property
    public function show() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        if (!$id) {
            setFlash('error', 'Propiedad no encontrada');
            redirect('/?action=properties');
        }
        
        $property = $this->propertyModel->findById($id);
        
        if (!$property || !$property['activa']) {
            setFlash('error', 'Propiedad no encontrada');
            redirect('/?action=properties');
        }
        
        // Increment visits
        $this->propertyModel->incrementVisits($id);
        
        // Get images
        $images = $this->imageModel->getByProperty($id);
        
        // Load view
        include __DIR__ . '/../../frontend/views/properties/detail.php';
    }
    
    // Show create form
    public function create() {
        startSession();
        
        if (!isAuthenticated()) {
            setFlash('error', 'Debes iniciar sesión para crear una propiedad');
            redirect('/?action=login');
        }
        
        include __DIR__ . '/../../frontend/views/properties/create.php';
    }
    
    // Handle property creation
    public function store() {
        startSession();
        
        if (!isAuthenticated()) {
            redirect('/?action=login');
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/?action=property_create');
        }
        
        // Get data
        $data = [
            'usuario_id' => $_SESSION['user_id'],
            'titulo' => sanitize($_POST['titulo'] ?? ''),
            'descripcion' => sanitize($_POST['descripcion'] ?? ''),
            'tipo' => sanitize($_POST['tipo'] ?? 'piso'),
            'operacion' => sanitize($_POST['operacion'] ?? 'venta'),
            'precio' => (float)($_POST['precio'] ?? 0),
            'superficie' => !empty($_POST['superficie']) ? (float)$_POST['superficie'] : null,
            'habitaciones' => !empty($_POST['habitaciones']) ? (int)$_POST['habitaciones'] : null,
            'banos' => !empty($_POST['banos']) ? (int)$_POST['banos'] : null,
            'provincia' => sanitize($_POST['provincia'] ?? ''),
            'ciudad' => sanitize($_POST['ciudad'] ?? ''),
            'codigo_postal' => sanitize($_POST['codigo_postal'] ?? ''),
            'direccion' => sanitize($_POST['direccion'] ?? ''),
            'latitud' => !empty($_POST['latitud']) ? (float)$_POST['latitud'] : null,
            'longitud' => !empty($_POST['longitud']) ? (float)$_POST['longitud'] : null,
            'destacada' => isAdmin() ? (isset($_POST['destacada']) ? 1 : 0) : 0,
            'activa' => 1
        ];
        
        // Validation
        $errors = validateRequired(['titulo', 'tipo', 'operacion', 'precio', 'ciudad'], $data);
        
        if ($data['precio'] <= 0) {
            $errors['precio'] = 'El precio debe ser mayor a 0';
        }
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $_POST;
            redirect('/?action=property_create');
        }
        
        // Create property
        $propertyId = $this->propertyModel->create($data);
        
        if ($propertyId) {
            // Handle image uploads
            if (!empty($_FILES['images']['name'][0])) {
                $this->handleImageUploads($propertyId, $_FILES['images']);
            }
            
            setFlash('success', 'Propiedad creada exitosamente');
            redirect('/?action=property_detail&id=' . $propertyId);
        } else {
            setFlash('error', 'Error al crear la propiedad');
            redirect('/?action=property_create');
        }
    }
    
    // Show edit form
    public function edit() {
        startSession();
        
        if (!isAuthenticated()) {
            redirect('/?action=login');
        }
        
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $property = $this->propertyModel->findById($id);
        
        if (!$property) {
            setFlash('error', 'Propiedad no encontrada');
            redirect('/?action=properties');
        }
        
        // Check ownership (unless admin)
        if (!isAdmin() && $property['usuario_id'] != $_SESSION['user_id']) {
            setFlash('error', 'No tienes permiso para editar esta propiedad');
            redirect('/?action=properties');
        }
        
        $images = $this->imageModel->getByProperty($id);
        
        include __DIR__ . '/../../frontend/views/properties/edit.php';
    }
    
    // Handle property update
    public function update() {
        startSession();
        
        if (!isAuthenticated()) {
            redirect('/?action=login');
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/?action=properties');
        }
        
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        $property = $this->propertyModel->findById($id);
        
        if (!$property) {
            setFlash('error', 'Propiedad no encontrada');
            redirect('/?action=properties');
        }
        
        // Check ownership
        if (!isAdmin() && $property['usuario_id'] != $_SESSION['user_id']) {
            setFlash('error', 'No tienes permiso para editar esta propiedad');
            redirect('/?action=properties');
        }
        
        // Get data
        $data = [
            'titulo' => sanitize($_POST['titulo'] ?? ''),
            'descripcion' => sanitize($_POST['descripcion'] ?? ''),
            'tipo' => sanitize($_POST['tipo'] ?? 'piso'),
            'operacion' => sanitize($_POST['operacion'] ?? 'venta'),
            'precio' => (float)($_POST['precio'] ?? 0),
            'superficie' => !empty($_POST['superficie']) ? (float)$_POST['superficie'] : null,
            'habitaciones' => !empty($_POST['habitaciones']) ? (int)$_POST['habitaciones'] : null,
            'banos' => !empty($_POST['banos']) ? (int)$_POST['banos'] : null,
            'provincia' => sanitize($_POST['provincia'] ?? ''),
            'ciudad' => sanitize($_POST['ciudad'] ?? ''),
            'codigo_postal' => sanitize($_POST['codigo_postal'] ?? ''),
            'direccion' => sanitize($_POST['direccion'] ?? ''),
            'activa' => isset($_POST['activa']) ? 1 : 0
        ];
        
        if (isAdmin()) {
            $data['destacada'] = isset($_POST['destacada']) ? 1 : 0;
        }
        
        // Update
        if ($this->propertyModel->update($id, $data)) {
            setFlash('success', 'Propiedad actualizada correctamente');
        } else {
            setFlash('error', 'Error al actualizar la propiedad');
        }
        
        redirect('/?action=property_detail&id=' . $id);
    }
    
    // Handle property deletion
    public function delete() {
        startSession();
        
        if (!isAuthenticated()) {
            redirect('/?action=login');
        }
        
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        $property = $this->propertyModel->findById($id);
        
        if (!$property) {
            setFlash('error', 'Propiedad no encontrada');
            redirect('/?action=properties');
        }
        
        // Check ownership
        if (!isAdmin() && $property['usuario_id'] != $_SESSION['user_id']) {
            setFlash('error', 'No tienes permiso para eliminar esta propiedad');
            redirect('/?action=properties');
        }
        
        if ($this->propertyModel->delete($id)) {
            setFlash('success', 'Propiedad eliminada correctamente');
        } else {
            setFlash('error', 'Error al eliminar la propiedad');
        }
        
        redirect('/?action=properties');
    }
    
    // Helper: Handle image uploads
    private function handleImageUploads($propiedadId, $files) {
        $uploadDir = __DIR__ . '/../../public/uploads/properties/';
        
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $fileCount = count($files['name']);
        $orden = 0;
        
        for ($i = 0; $i < $fileCount; $i++) {
            if ($files['error'][$i] === UPLOAD_ERR_OK) {
                $file = [
                    'name' => $files['name'][$i],
                    'type' => $files['type'][$i],
                    'tmp_name' => $files['tmp_name'][$i],
                    'error' => $files['error'][$i],
                    'size' => $files['size'][$i]
                ];
                
                $uploadResult = uploadImage($file, $uploadDir);
                
                if ($uploadResult['success']) {
                    $this->imageModel->create([
                        'propiedad_id' => $propiedadId,
                        'ruta' => ASSETS_URL . '/public/uploads/properties/' . $uploadResult['filename'],
                        'nombre' => $files['name'][$i],
                        'orden' => $orden++,
                        'principal' => $i === 0 ? 1 : 0
                    ]);
                }
            }
        }
    }
}
