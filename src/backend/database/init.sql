-- Database: iberpiso
-- Real Estate Portal Database Schema

CREATE DATABASE IF NOT EXISTS iberpiso CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE iberpiso;

-- Table: rol
CREATE TABLE IF NOT EXISTS rol (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    descripcion TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: usuario
CREATE TABLE IF NOT EXISTS usuario (
    id INT PRIMARY KEY AUTO_INCREMENT,
    rol_id INT NOT NULL DEFAULT 2,
    nombre VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100),
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    telefono VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (rol_id) REFERENCES rol(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX idx_email (email),
    INDEX idx_rol (rol_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: propiedad
CREATE TABLE IF NOT EXISTS propiedad (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT NOT NULL,
    titulo VARCHAR(200) NOT NULL,
    descripcion TEXT,
    tipo ENUM('casa', 'piso', 'terreno', 'local', 'oficina', 'garaje', 'otro') NOT NULL DEFAULT 'piso',
    operacion ENUM('venta', 'alquiler') NOT NULL DEFAULT 'venta',
    precio DECIMAL(12, 2) NOT NULL,
    superficie DECIMAL(8, 2),
    habitaciones INT,
    banos INT,
    provincia VARCHAR(100),
    ciudad VARCHAR(100),
    codigo_postal VARCHAR(10),
    direccion VARCHAR(255),
    latitud DECIMAL(10, 8),
    longitud DECIMAL(11, 8),
    visitas INT DEFAULT 0,
    destacada BOOLEAN DEFAULT FALSE,
    activa BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuario(id) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_tipo (tipo),
    INDEX idx_operacion (operacion),
    INDEX idx_precio (precio),
    INDEX idx_ciudad (ciudad),
    INDEX idx_destacada (destacada),
    INDEX idx_activa (activa)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: imagen
CREATE TABLE IF NOT EXISTS imagen (
    id INT PRIMARY KEY AUTO_INCREMENT,
    propiedad_id INT NOT NULL,
    ruta VARCHAR(255) NOT NULL,
    nombre VARCHAR(255),
    orden INT DEFAULT 0,
    principal BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (propiedad_id) REFERENCES propiedad(id) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_propiedad (propiedad_id),
    INDEX idx_principal (principal)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: favoritos
CREATE TABLE IF NOT EXISTS favoritos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT NOT NULL,
    propiedad_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuario(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (propiedad_id) REFERENCES propiedad(id) ON DELETE CASCADE ON UPDATE CASCADE,
    UNIQUE KEY unique_favorito (usuario_id, propiedad_id),
    INDEX idx_usuario (usuario_id),
    INDEX idx_propiedad (propiedad_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: mensaje
CREATE TABLE IF NOT EXISTS mensaje (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT,
    propiedad_id INT,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    telefono VARCHAR(20),
    asunto VARCHAR(200),
    mensaje TEXT NOT NULL,
    leido BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuario(id) ON DELETE SET NULL ON UPDATE CASCADE,
    FOREIGN KEY (propiedad_id) REFERENCES propiedad(id) ON DELETE SET NULL ON UPDATE CASCADE,
    INDEX idx_leido (leido),
    INDEX idx_propiedad (propiedad_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: blog
CREATE TABLE IF NOT EXISTS blog (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT NOT NULL,
    titulo VARCHAR(200) NOT NULL,
    slug VARCHAR(250) UNIQUE,
    contenido TEXT NOT NULL,
    extracto TEXT,
    imagen VARCHAR(255),
    publicado BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuario(id) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_slug (slug),
    INDEX idx_publicado (publicado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default roles
INSERT INTO rol (nombre, descripcion) VALUES
('admin', 'Administrador del sistema con acceso completo'),
('usuario', 'Usuario registrado con acceso limitado'),
('agente', 'Agente inmobiliario con permisos de gestión de propiedades')
ON DUPLICATE KEY UPDATE descripcion = VALUES(descripcion);

-- Insert default admin user
-- Password: admin123 (hashed with PHP password_hash using PASSWORD_DEFAULT)
INSERT INTO usuario (rol_id, nombre, apellidos, email, password, telefono) VALUES
(1, 'Administrador', 'Sistema', 'admin@iberpiso.com', '$2y$12$dvN4DrrL.xJavnZDT4ngruOJFad8pOeZWuKJeJovGrqAQvjeAmg5.', '600000000')
ON DUPLICATE KEY UPDATE password = VALUES(password);
