<?php
/**
 * BlogModel
 * Database operations for blog table
 */

class BlogModel {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    // Create new blog post
    public function create($data) {
        $sql = "INSERT INTO blog (usuario_id, titulo, slug, contenido, extracto, imagen, publicado) 
                VALUES (:usuario_id, :titulo, :slug, :contenido, :extracto, :imagen, :publicado)";
        
        $stmt = $this->pdo->prepare($sql);
        
        $result = $stmt->execute([
            ':usuario_id' => $data['usuario_id'],
            ':titulo' => $data['titulo'],
            ':slug' => $data['slug'],
            ':contenido' => $data['contenido'],
            ':extracto' => $data['extracto'] ?? null,
            ':imagen' => $data['imagen'] ?? null,
            ':publicado' => $data['publicado'] ?? 0
        ]);
        
        return $result ? $this->pdo->lastInsertId() : false;
    }
    
    // Find by ID
    public function findById($id) {
        $sql = "SELECT b.*, u.nombre as autor_nombre 
                FROM blog b 
                LEFT JOIN usuario u ON b.usuario_id = u.id 
                WHERE b.id = :id LIMIT 1";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        
        return $stmt->fetch();
    }
    
    // Find by slug
    public function findBySlug($slug) {
        $sql = "SELECT b.*, u.nombre as autor_nombre 
                FROM blog b 
                LEFT JOIN usuario u ON b.usuario_id = u.id 
                WHERE b.slug = :slug LIMIT 1";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':slug' => $slug]);
        
        return $stmt->fetch();
    }
    
    // Get all posts
    public function getAll($publicadoOnly = true, $limit = 20, $offset = 0) {
        $sql = "SELECT b.*, u.nombre as autor_nombre 
                FROM blog b 
                LEFT JOIN usuario u ON b.usuario_id = u.id";
        
        if ($publicadoOnly) {
            $sql .= " WHERE b.publicado = 1";
        }
        
        $sql .= " ORDER BY b.created_at DESC LIMIT :limit OFFSET :offset";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    // Count posts
    public function count($publicadoOnly = true) {
        $sql = "SELECT COUNT(*) as total FROM blog";
        
        if ($publicadoOnly) {
            $sql .= " WHERE publicado = 1";
        }
        
        $stmt = $this->pdo->query($sql);
        $result = $stmt->fetch();
        
        return $result['total'];
    }
    
    // Update post
    public function update($id, $data) {
        $fields = [];
        $params = [':id' => $id];
        
        $allowedFields = ['titulo', 'slug', 'contenido', 'extracto', 'imagen', 'publicado'];
        
        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $fields[] = "$field = :$field";
                $params[":$field"] = $data[$field];
            }
        }
        
        if (empty($fields)) {
            return false;
        }
        
        $sql = "UPDATE blog SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        
        return $stmt->execute($params);
    }
    
    // Delete post
    public function delete($id) {
        $sql = "DELETE FROM blog WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    // Check if slug exists
    public function slugExists($slug, $excludeId = null) {
        $sql = "SELECT COUNT(*) as count FROM blog WHERE slug = :slug";
        
        if ($excludeId) {
            $sql .= " AND id != :id";
        }
        
        $stmt = $this->pdo->prepare($sql);
        $params = [':slug' => $slug];
        
        if ($excludeId) {
            $params[':id'] = $excludeId;
        }
        
        $stmt->execute($params);
        $result = $stmt->fetch();
        
        return $result['count'] > 0;
    }
}
