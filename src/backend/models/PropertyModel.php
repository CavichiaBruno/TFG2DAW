<?php
/**
 * PropertyModel
 * Database operations for propiedad table
 */

class PropertyModel {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    // Create new property
    public function create($data) {
        $sql = "INSERT INTO propiedad (
                    usuario_id, titulo, descripcion, tipo, operacion, precio, 
                    superficie, habitaciones, banos, provincia, ciudad, 
                    codigo_postal, direccion, latitud, longitud, destacada, activa
                ) VALUES (
                    :usuario_id, :titulo, :descripcion, :tipo, :operacion, :precio, 
                    :superficie, :habitaciones, :banos, :provincia, :ciudad, 
                    :codigo_postal, :direccion, :latitud, :longitud, :destacada, :activa
                )";
        
        $stmt = $this->pdo->prepare($sql);
        
        $result = $stmt->execute([
            ':usuario_id' => $data['usuario_id'],
            ':titulo' => $data['titulo'],
            ':descripcion' => $data['descripcion'] ?? null,
            ':tipo' => $data['tipo'] ?? 'piso',
            ':operacion' => $data['operacion'] ?? 'venta',
            ':precio' => $data['precio'],
            ':superficie' => $data['superficie'] ?? null,
            ':habitaciones' => $data['habitaciones'] ?? null,
            ':banos' => $data['banos'] ?? null,
            ':provincia' => $data['provincia'] ?? null,
            ':ciudad' => $data['ciudad'] ?? null,
            ':codigo_postal' => $data['codigo_postal'] ?? null,
            ':direccion' => $data['direccion'] ?? null,
            ':latitud' => $data['latitud'] ?? null,
            ':longitud' => $data['longitud'] ?? null,
            ':destacada' => $data['destacada'] ?? 0,
            ':activa' => $data['activa'] ?? 1
        ]);
        
        return $result ? $this->pdo->lastInsertId() : false;
    }
    
    // Get property by ID
    public function findById($id) {
        $sql = "SELECT p.*, u.nombre as usuario_nombre, u.email as usuario_email, u.telefono as usuario_telefono 
                FROM propiedad p 
                LEFT JOIN usuario u ON p.usuario_id = u.id 
                WHERE p.id = :id LIMIT 1";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        
        return $stmt->fetch();
    }
    
    // Get all properties with filters
    public function getAll($filters = [], $limit = 20, $offset = 0) {
        $sql = "SELECT p.*, u.nombre as usuario_nombre 
                FROM propiedad p 
                LEFT JOIN usuario u ON p.usuario_id = u.id 
                WHERE 1=1";
        
        $params = [];
        
        if (!empty($filters['tipo'])) {
            $sql .= " AND p.tipo = :tipo";
            $params[':tipo'] = $filters['tipo'];
        }
        
        if (!empty($filters['operacion'])) {
            $sql .= " AND p.operacion = :operacion";
            $params[':operacion'] = $filters['operacion'];
        }
        
        if (!empty($filters['ciudad'])) {
            $sql .= " AND p.ciudad LIKE :ciudad";
            $params[':ciudad'] = '%' . $filters['ciudad'] . '%';
        }
        
        if (!empty($filters['provincia'])) {
            $sql .= " AND p.provincia LIKE :provincia";
            $params[':provincia'] = '%' . $filters['provincia'] . '%';
        }
        
        if (!empty($filters['precio_min'])) {
            $sql .= " AND p.precio >= :precio_min";
            $params[':precio_min'] = $filters['precio_min'];
        }
        
        if (!empty($filters['precio_max'])) {
            $sql .= " AND p.precio <= :precio_max";
            $params[':precio_max'] = $filters['precio_max'];
        }
        
        if (!empty($filters['habitaciones'])) {
            $sql .= " AND p.habitaciones >= :habitaciones";
            $params[':habitaciones'] = $filters['habitaciones'];
        }
        
        if (isset($filters['activa'])) {
            $sql .= " AND p.activa = :activa";
            $params[':activa'] = $filters['activa'];
        } else {
            $sql .= " AND p.activa = 1";
        }
        
        if (isset($filters['destacada']) && $filters['destacada']) {
            $sql .= " AND p.destacada = 1";
        }
        
        $sql .= " ORDER BY p.destacada DESC, p.created_at DESC LIMIT :limit OFFSET :offset";
        
        $stmt = $this->pdo->prepare($sql);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    // Count properties with filters
    public function count($filters = []) {
        $sql = "SELECT COUNT(*) as total FROM propiedad WHERE 1=1";
        $params = [];
        
        if (!empty($filters['tipo'])) {
            $sql .= " AND tipo = :tipo";
            $params[':tipo'] = $filters['tipo'];
        }
        
        if (!empty($filters['operacion'])) {
            $sql .= " AND operacion = :operacion";
            $params[':operacion'] = $filters['operacion'];
        }
        
        if (isset($filters['activa'])) {
            $sql .= " AND activa = :activa";
            $params[':activa'] = $filters['activa'];
        } else {
            $sql .= " AND activa = 1";
        }
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        
        return $result['total'];
    }
    
    // Update property
    public function update($id, $data) {
        $fields = [];
        $params = [':id' => $id];
        
        $allowedFields = [
            'titulo', 'descripcion', 'tipo', 'operacion', 'precio', 
            'superficie', 'habitaciones', 'banos', 'provincia', 'ciudad', 
            'codigo_postal', 'direccion', 'latitud', 'longitud', 'destacada', 'activa'
        ];
        
        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $fields[] = "$field = :$field";
                $params[":$field"] = $data[$field];
            }
        }
        
        if (empty($fields)) {
            return false;
        }
        
        $sql = "UPDATE propiedad SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        
        return $stmt->execute($params);
    }
    
    // Delete property
    public function delete($id) {
        $sql = "DELETE FROM propiedad WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    // Increment visit counter
    public function incrementVisits($id) {
        $sql = "UPDATE propiedad SET visitas = visitas + 1 WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    // Get properties by user
    public function getByUser($userId, $limit = 50, $offset = 0) {
        $sql = "SELECT * FROM propiedad 
                WHERE usuario_id = :usuario_id 
                ORDER BY created_at DESC 
                LIMIT :limit OFFSET :offset";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':usuario_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    // Get featured properties
    public function getFeatured($limit = 6) {
        $sql = "SELECT * FROM propiedad 
                WHERE destacada = 1 AND activa = 1 
                ORDER BY RAND() 
                LIMIT :limit";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
}
