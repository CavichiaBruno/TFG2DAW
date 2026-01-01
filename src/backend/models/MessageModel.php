<?php
/**
 * MessageModel
 * Database operations for mensaje table
 */

class MessageModel {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    // Create new message
    public function create($data) {
        $sql = "INSERT INTO mensaje (usuario_id, propiedad_id, nombre, email, telefono, asunto, mensaje) 
                VALUES (:usuario_id, :propiedad_id, :nombre, :email, :telefono, :asunto, :mensaje)";
        
        $stmt = $this->pdo->prepare($sql);
        
        return $stmt->execute([
            ':usuario_id' => $data['usuario_id'] ?? null,
            ':propiedad_id' => $data['propiedad_id'] ?? null,
            ':nombre' => $data['nombre'],
            ':email' => $data['email'],
            ':telefono' => $data['telefono'] ?? null,
            ':asunto' => $data['asunto'] ?? null,
            ':mensaje' => $data['mensaje']
        ]);
    }
    
    // Get all messages
    public function getAll($limit = 50, $offset = 0) {
        $sql = "SELECT m.*, u.nombre as usuario_nombre, p.titulo as propiedad_titulo 
                FROM mensaje m 
                LEFT JOIN usuario u ON m.usuario_id = u.id 
                LEFT JOIN propiedad p ON m.propiedad_id = p.id 
                ORDER BY m.leido ASC, m.created_at DESC 
                LIMIT :limit OFFSET :offset";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    // Count messages
    public function count($unreadOnly = false) {
        $sql = "SELECT COUNT(*) as total FROM mensaje";
        
        if ($unreadOnly) {
            $sql .= " WHERE leido = 0";
        }
        
        $stmt = $this->pdo->query($sql);
        $result = $stmt->fetch();
        
        return $result['total'];
    }
    
    // Mark as read
    public function markAsRead($id) {
        $sql = "UPDATE mensaje SET leido = 1 WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    // Find by ID
    public function findById($id) {
        $sql = "SELECT m.*, u.nombre as usuario_nombre, p.titulo as propiedad_titulo 
                FROM mensaje m 
                LEFT JOIN usuario u ON m.usuario_id = u.id 
                LEFT JOIN propiedad p ON m.propiedad_id = p.id 
                WHERE m.id = :id LIMIT 1";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        
        return $stmt->fetch();
    }
    
    // Delete message
    public function delete($id) {
        $sql = "DELETE FROM mensaje WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    // Get messages by property
    public function getByProperty($propiedadId) {
        $sql = "SELECT * FROM mensaje 
                WHERE propiedad_id = :propiedad_id 
                ORDER BY created_at DESC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':propiedad_id' => $propiedadId]);
        
        return $stmt->fetchAll();
    }
}
