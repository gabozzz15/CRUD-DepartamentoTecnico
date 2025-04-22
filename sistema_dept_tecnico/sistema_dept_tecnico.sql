-- Base de datos: sistema_dept_tecnico
-- REALIZADO POR GABRIEL BASTARDO
-- https://github.com/gabozzz15

CREATE DATABASE IF NOT EXISTS sistema_dept_tecnico;
USE sistema_dept_tecnico;

CREATE TABLE usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE tecnicos (
    id_tecnico INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    cedula VARCHAR(20) UNIQUE,
    telefono VARCHAR(20),
    correo VARCHAR(100),
    estado ENUM('activo', 'inactivo') DEFAULT 'activo' -- Por si tiene vacaciones o esta de baja
);

CREATE TABLE equipos (
    id_equipo INT AUTO_INCREMENT PRIMARY KEY,
    tipo ENUM('pc', 'laptop', 'impresora', 'router', 'otro') NOT NULL,
    marca VARCHAR(100),
    descripcion VARCHAR(255), -- Especifique si viene con mouse, teclado, etc en caso de PC
    departamento VARCHAR(100),
    );


CREATE TABLE ordenes_servicio (
    id_orden INT AUTO_INCREMENT PRIMARY KEY,
    id_tecnico INT NOT NULL,
    id_equipo INT NOT NULL,
    descripcion_falla VARCHAR(255),
    estado ENUM('pendiente', 'reparado', 'entregado', 'no_reparable') DEFAULT 'pendiente',
    fecha_entrada TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_reparacion TIMESTAMP NULL,
    fecha_entrega TIMESTAMP NULL,
    FOREIGN KEY (id_tecnico) REFERENCES tecnicos(id_tecnico) ON DELETE SET NULL,
    FOREIGN KEY (id_equipo) REFERENCES equipos(id_equipo) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS configuraciones_usuario (
    usuario_id INT PRIMARY KEY,
    notif_email BOOLEAN DEFAULT 1,
    notif_sistema BOOLEAN DEFAULT 1,
    modo_oscuro BOOLEAN DEFAULT 0,
    tamano_fuente ENUM('small', 'medium', 'large') DEFAULT 'medium',
    idioma ENUM('es', 'en') DEFAULT 'es',
    FOREIGN KEY (usuario_id) REFERENCES usuario(id) ON DELETE CASCADE
);


INSERT INTO tecnicos (nombre, apellido, cedula, telefono, correo) 
VALUES ('Carlos', 'Gomez', '5675675', '04121525555', 'carlos.gomez@gmail.com');

INSERT INTO equipos (tipo, marca, descripcion, departamento) 
VALUES 
('pc', 'HP', 'PC de escritorio con monitor Dell 24", teclado Logitech K120, mouse HP, 8GB RAM, 256GB SSD', 'Contabilidad'),
('laptop', 'Dell', 'Laptop con cargador, 16GB RAM, 512GB SSD, pantalla táctil', 'Gerencia'),
('impresora', 'Epson', 'Impresora multifuncional con sistema de tinta continua', 'Administración');

INSERT INTO ordenes_servicio (id_tecnico, id_equipo, descripcion_falla) 
VALUES 
(1, 1, 'La PC no enciende, posible problema con la fuente de poder'),
(1, 2, 'Laptop tarda mucho en prender, posible falta de espacio o de RAM');




