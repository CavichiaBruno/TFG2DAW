<?php
/**
 * BlogController
 * Handles blog post operations
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../helpers/functions.php';
require_once __DIR__ . '/../models/BlogModel.php';

class BlogController {
    private $blogModel;
    
    public function __construct($pdo) {
        $this->blogModel = new BlogModel($pdo);
    }
    
    // List all blog posts
    public function index() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 12;
        
        $total = $this->blogModel->count(true);
        $pagination = paginate($total, $perPage, $page);
        $posts = $this->blogModel->getAll(true, $perPage, $pagination['offset']);
        
        include __DIR__ . '/../../frontend/views/blog/list.php';
    }
    
    // Show single blog post
    public function show() {
        $slug = isset($_GET['slug']) ? sanitize($_GET['slug']) : '';
        
        if (!$slug) {
            setFlash('error', 'Artículo no encontrado');
            redirect('/?action=blog');
        }
        
        $post = $this->blogModel->findBySlug($slug);
        
        if (!$post || !$post['publicado']) {
            setFlash('error', 'Artículo no encontrado');
            redirect('/?action=blog');
        }
        
        include __DIR__ . '/../../frontend/views/blog/detail.php';
    }
    
    // Admin: Create post form
    public function create() {
        startSession();
        
        if (!isAdmin()) {
            setFlash('error', 'Acceso denegado');
            redirect('/');
        }
        
        include __DIR__ . '/../../frontend/views/blog/create.php';
    }
    
    // Admin: Store new post
    public function store() {
        startSession();
        
        if (!isAdmin()) {
            redirect('/');
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/?action=blog_create');
        }
        
        $titulo = sanitize($_POST['titulo'] ?? '');
        $contenido = $_POST['contenido'] ?? ''; // Don't sanitize HTML content
        $extracto = sanitize($_POST['extracto'] ?? '');
        $publicado = isset($_POST['publicado']) ? 1 : 0;
        
        // Generate slug
        $slug = generateSlug($titulo);
        $slugCounter = 1;
        while ($this->blogModel->slugExists($slug)) {
            $slug = generateSlug($titulo) . '-' . $slugCounter++;
        }
        
        // Handle image upload
        $imagen = null;
        if (!empty($_FILES['imagen']['name'])) {
            $uploadResult = uploadImage($_FILES['imagen'], __DIR__ . '/../../public/uploads/blog/');
            if ($uploadResult['success']) {
                $imagen = '/public/uploads/blog/' . $uploadResult['filename'];
            }
        }
        
        $data = [
            'usuario_id' => $_SESSION['user_id'],
            'titulo' => $titulo,
            'slug' => $slug,
            'contenido' => $contenido,
            'extracto' => $extracto,
            'imagen' => $imagen,
            'publicado' => $publicado
        ];
        
        $errors = validateRequired(['titulo', 'contenido'], $data);
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            redirect('/?action=blog_create');
        }
        
        $postId = $this->blogModel->create($data);
        
        if ($postId) {
            setFlash('success', 'Artículo creado correctamente');
            $post = $this->blogModel->findById($postId);
            redirect('/?action=blog_detail&slug=' . $post['slug']);
        } else {
            setFlash('error', 'Error al crear el artículo');
            redirect('/?action=blog_create');
        }
    }
    
    // Admin: Edit post form
    public function edit() {
        startSession();
        
        if (!isAdmin()) {
            redirect('/');
        }
        
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $post = $this->blogModel->findById($id);
        
        if (!$post) {
            setFlash('error', 'Artículo no encontrado');
            redirect('/?action=blog');
        }
        
        include __DIR__ . '/../../frontend/views/blog/edit.php';
    }
    
    // Admin: Update post
    public function update() {
        startSession();
        
        if (!isAdmin()) {
            redirect('/');
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/?action=blog');
        }
        
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        $post = $this->blogModel->findById($id);
        
        if (!$post) {
            setFlash('error', 'Artículo no encontrado');
            redirect('/?action=blog');
        }
        
        $data = [
            'titulo' => sanitize($_POST['titulo'] ?? ''),
            'contenido' => $_POST['contenido'] ?? '',
            'extracto' => sanitize($_POST['extracto'] ?? ''),
            'publicado' => isset($_POST['publicado']) ? 1 : 0
        ];
        
        // Handle image upload
        if (!empty($_FILES['imagen']['name'])) {
            $uploadResult = uploadImage($_FILES['imagen'], __DIR__ . '/../../public/uploads/blog/');
            if ($uploadResult['success']) {
                $data['imagen'] = '/public/uploads/blog/' . $uploadResult['filename'];
            }
        }
        
        if ($this->blogModel->update($id, $data)) {
            setFlash('success', 'Artículo actualizado correctamente');
            redirect('/?action=blog_detail&slug=' . $post['slug']);
        } else {
            setFlash('error', 'Error al actualizar el artículo');
            redirect('/?action=blog_edit&id=' . $id);
        }
    }
    
    // Admin: Delete post
    public function delete() {
        startSession();
        
        if (!isAdmin()) {
            redirect('/');
        }
        
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        
        if ($this->blogModel->delete($id)) {
            setFlash('success', 'Artículo eliminado correctamente');
        } else {
            setFlash('error', 'Error al eliminar el artículo');
        }
        
        redirect('/?action=blog');
    }
}
