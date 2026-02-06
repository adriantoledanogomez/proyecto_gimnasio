CREATE DATABASE IF NOT EXISTS soc_incidencias CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE soc_incidencias;

CREATE TABLE IF NOT EXISTS usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS incidentes (
    id_incidente INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(150) NOT NULL,
    descripcion TEXT NOT NULL,
    severidad ENUM('Baja','Media','Alta','Crítica') NOT NULL,
    estado ENUM('Abierto','En investigación','Mitigado','Cerrado') NOT NULL,
    origen VARCHAR(100) NOT NULL,
    fecha_reporte DATETIME NOT NULL
);

CREATE TABLE IF NOT EXISTS activos (
    id_activo INT AUTO_INCREMENT PRIMARY KEY,
    nombre_activo VARCHAR(120) NOT NULL,
    tipo VARCHAR(80) NOT NULL,
    criticidad ENUM('Baja','Media','Alta','Crítica') NOT NULL,
    propietario VARCHAR(120) NOT NULL
);

CREATE TABLE IF NOT EXISTS analistas (
    id_analista INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(120) NOT NULL,
    nivel ENUM('L1','L2','L3') NOT NULL,
    turno ENUM('Mañana','Tarde','Noche') NOT NULL
);

CREATE TABLE IF NOT EXISTS asignaciones (
    id_asignacion INT AUTO_INCREMENT PRIMARY KEY,
    id_incidente INT NOT NULL,
    id_analista INT NOT NULL,
    comentario TEXT NOT NULL,
    fecha_asignacion DATETIME NOT NULL,
    CONSTRAINT fk_asig_incidente FOREIGN KEY (id_incidente) REFERENCES incidentes(id_incidente) ON DELETE CASCADE,
    CONSTRAINT fk_asig_analista FOREIGN KEY (id_analista) REFERENCES analistas(id_analista) ON DELETE CASCADE
);

INSERT INTO usuarios (nombre, usuario, password_hash)
VALUES ('Administrador SOC', 'admin', '$2y$12$QSmUPLeFoQPPzJ68XRqbzuoIVKxe2m4NcpLCfuLRuIp4WzOq7fNNa')
ON DUPLICATE KEY UPDATE nombre=VALUES(nombre);
