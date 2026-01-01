<?php
/**
 * FavoritoModel
 * Database operations for favoritos table
 */

class FavoritoModel {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    // Add to favorites
    public function add($usuarioId, $propiedadId) {
        $sql = "INSERT INTO favoritos (usuario_id, propiedad_id) 
                VALUES (:usuario_id, :propiedad_id)";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                ':usuario_id' => $usuarioId,
                ':propiedad_id' => $propiedadId
            ]);
        } catch (PDOException $e) {
            // Duplicate entry (already favorite)
            if ($e->getCode() == 23000) {
                return false;
            }
            throw $e;
        }
    }
    
    // Remove from favorites
    public function remove($usuarioId, $propiedadId) {
        $sql = "DELETE FROM favoritos 
                WHERE usuario_id = :usuario_id AND propiedad_id = :propiedad_id";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':usuario_id' => $usuarioId,
            ':propiedad_id' => $propiedadId
        ]);
    }
    
    // Check if property is favorite
    public function isFavorite($usuarioId, $propiedadId) {
        $sql = "SELECT COUNT(*) as count FROM favoritos 
                WHERE usuario_id = :usuario_id AND propiedad_id = :propiedad_id";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':usuario_id' => $usuarioId,
            ':propiedad_id' => $propiedadId
        ]);
        
        $result = $stmt->fetch();
        return $result['count'] > 0;
    }
    
    // Get user favorites
    public function getUserFavorites($usuarioId, $limit = 50, $offset = 0) {
        $sql = "SELECT p.*, f.created_at as favorito_fecha 
                FROM favoritos f 
                INNER JOIN propiedad p ON f.propiedad_id = p.id 
                WHERE f.usuario_id = :usuario_id 
                ORDER BY f.created_at DESC 
                LIMIT :limit OFFSET :offset";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':usuario_id', $usuarioId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    // Count user favorites
    public function countUserFavorites($usuarioId) {
        $sql = "SELECT COUNT(*) as total FROM favoritos WHERE usuario_id = :usuario_id";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':usuario_id' => $usuarioId]);
        
        $result = $stmt->fetch();
        return $result['total'];
    }
}
