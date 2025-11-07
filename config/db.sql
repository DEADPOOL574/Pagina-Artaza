-- Crear base y tablas básicas
CREATE DATABASE IF NOT EXISTS artaza_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE artaza_db;

-- Usuarios
CREATE TABLE IF NOT EXISTS usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(120) NOT NULL,
  email VARCHAR(180) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Noticias
CREATE TABLE IF NOT EXISTS noticias (
  id INT AUTO_INCREMENT PRIMARY KEY,
  titulo VARCHAR(200) NOT NULL,
  contenido TEXT NOT NULL,
  categoria ENUM('anime','videojuegos','novedades') DEFAULT 'novedades',
  imagen_url VARCHAR(400) NULL,
  creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


