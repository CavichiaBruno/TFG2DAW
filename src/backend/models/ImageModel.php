<?php
/**
 * ImageModel
 * Database operations for imagen table
 */

class ImageModel {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    // Create new image
    public function create($data) {
        $sql = "INSERT INTO imagen (propiedad_id, ruta, nombre, orden, principal) 
                VALUES (:propiedad_id, :ruta, :nombre, :orden, :principal)";
        
        $stmt = $this->pdo->prepare($sql);
        
        $result = $stmt->execute([
            ':propiedad_id' => $data['propiedad_id'],
            ':ruta' => $data['ruta'],
            ':nombre' => $data['nombre'] ?? null,
            ':orden' => $data['orden'] ?? 0,
            ':principal' => $data['principal'] ?? 0
        ]);
        
        return $result ? $this->pdo->lastInsertId() : false;
    }
    
    // Get images by property
    public function getByProperty($propiedadId) {
        $sql = "SELECT * FROM imagen 
                WHERE propiedad_id = :propiedad_id 
                ORDER BY principal DESC, orden ASC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':propiedad_id' => $propiedadId]);
        
        return $stmt->fetchAll();
    }
    
    // Get principal image
    public function getPrincipal($propiedadId) {
        $sql = "SELECT * FROM imagen 
                WHERE propiedad_id = :propiedad_id AND principal = 1 
                LIMIT 1";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':propiedad_id' => $propiedadId]);
        
        return $stmt->fetch();
    }
    
    // Update image
    public function update($id, $data) {
        $fields = [];
        $params = [':id' => $id];
        
        if (isset($data['ruta'])) {
            $fields[] = "ruta = :ruta";
            $params[':ruta'] = $data['ruta'];
        }
        if (isset($data['nombre'])) {
            $fields[] = "nombre = :nombre";
            $params[':nombre'] = $data['nombre'];
        }
        if (isset($data['orden'])) {
            $fields[] = "orden = :orden";
            $params[':orden'] = $data['orden'];
        }
        if (isset($data['principal'])) {
            $fields[] = "principal = :principal";
            $params[':principal'] = $data['principal'];
        }
        
        if (empty($fields)) {
            return false;
        }
        
        $sql = "UPDATE imagen SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        
        return $stmt->execute($params);
    }
    
    // Set as principal (unset others first)
    public function setPrincipal($id, $propiedadId) {
        // Unset all principal images for this property
        $sql1 = "UPDATE imagen SET principal = 0 WHERE propiedad_id = :propiedad_id";
        $stmt1 = $this->pdo->prepare($sql1);
        $stmt1->execute([':propiedad_id' => $propiedadId]);
        
        // Set new principal
        $sql2 = "UPDATE imagen SET principal = 1 WHERE id = :id";
        $stmt2 = $this->pdo->prepare($sql2);
        
        return $stmt2->execute([':id' => $id]);
    }
    
    // Delete image
    public function delete($id) {
        $sql = "DELETE FROM imagen WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    // Get image by ID
    public function findById($id) {
        $sql = "SELECT * FROM imagen WHERE id = :id LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
}
