<?php
/**
 * UserModel
 * Database operations for usuario table
 */

class UserModel {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    // Create new user (register)
    public function create($data) {
        $sql = "INSERT INTO usuario (rol_id, nombre, apellidos, email, password, telefono) 
                VALUES (:rol_id, :nombre, :apellidos, :email, :password, :telefono)";
        
        $stmt = $this->pdo->prepare($sql);
        
        return $stmt->execute([
            ':rol_id' => $data['rol_id'] ?? 2, // Default: usuario
            ':nombre' => $data['nombre'],
            ':apellidos' => $data['apellidos'] ?? null,
            ':email' => $data['email'],
            ':password' => password_hash($data['password'], PASSWORD_DEFAULT),
            ':telefono' => $data['telefono'] ?? null
        ]);
    }
    
    // Find user by email
    public function findByEmail($email) {
        $sql = "SELECT u.*, r.nombre as rol_nombre 
                FROM usuario u 
                LEFT JOIN rol r ON u.rol_id = r.id 
                WHERE u.email = :email LIMIT 1";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':email' => $email]);
        
        return $stmt->fetch();
    }
    
    // Find user by ID
    public function findById($id) {
        $sql = "SELECT u.*, r.nombre as rol_nombre 
                FROM usuario u 
                LEFT JOIN rol r ON u.rol_id = r.id 
                WHERE u.id = :id LIMIT 1";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        
        return $stmt->fetch();
    }
    
    // Get all users (admin)
    public function getAll($limit = 50, $offset = 0) {
        $sql = "SELECT u.*, r.nombre as rol_nombre 
                FROM usuario u 
                LEFT JOIN rol r ON u.rol_id = r.id 
                ORDER BY u.created_at DESC 
                LIMIT :limit OFFSET :offset";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    // Count total users
    public function count() {
        $sql = "SELECT COUNT(*) as total FROM usuario";
        $stmt = $this->pdo->query($sql);
        $result = $stmt->fetch();
        return $result['total'];
    }
    
    // Update user
    public function update($id, $data) {
        $fields = [];
        $params = [':id' => $id];
        
        if (isset($data['nombre'])) {
            $fields[] = "nombre = :nombre";
            $params[':nombre'] = $data['nombre'];
        }
        if (isset($data['apellidos'])) {
            $fields[] = "apellidos = :apellidos";
            $params[':apellidos'] = $data['apellidos'];
        }
        if (isset($data['email'])) {
            $fields[] = "email = :email";
            $params[':email'] = $data['email'];
        }
        if (isset($data['telefono'])) {
            $fields[] = "telefono = :telefono";
            $params[':telefono'] = $data['telefono'];
        }
        if (isset($data['rol_id'])) {
            $fields[] = "rol_id = :rol_id";
            $params[':rol_id'] = $data['rol_id'];
        }
        if (isset($data['password'])) {
            $fields[] = "password = :password";
            $params[':password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        if (empty($fields)) {
            return false;
        }
        
        $sql = "UPDATE usuario SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        
        return $stmt->execute($params);
    }
    
    // Delete user
    public function delete($id) {
        $sql = "DELETE FROM usuario WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    // Check if email exists
    public function emailExists($email, $excludeId = null) {
        $sql = "SELECT COUNT(*) as count FROM usuario WHERE email = :email";
        
        if ($excludeId) {
            $sql .= " AND id != :id";
        }
        
        $stmt = $this->pdo->prepare($sql);
        $params = [':email' => $email];
        
        if ($excludeId) {
            $params[':id'] = $excludeId;
        }
        
        $stmt->execute($params);
        $result = $stmt->fetch();
        
        return $result['count'] > 0;
    }
    
    // Verify password
    public function verifyPassword($plainPassword, $hashedPassword) {
        return password_verify($plainPassword, $hashedPassword);
    }
}
