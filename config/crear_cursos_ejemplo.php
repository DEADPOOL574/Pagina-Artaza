<?php
require_once __DIR__ . '/db.php';

$mysqli->query("CREATE TABLE IF NOT EXISTS cursos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  titulo VARCHAR(200) NOT NULL,
  descripcion TEXT NOT NULL,
  categoria ENUM('anime','videojuegos','programacion','otros') DEFAULT 'otros',
  imagen_url VARCHAR(400) NULL,
  duracion VARCHAR(50) NULL,
  nivel ENUM('principiante','intermedio','avanzado') DEFAULT 'principiante',
  creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  actualizado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$cursos = [
  ['Curso de Introducción al Anime', 'Historia, géneros y producciones destacadas del anime moderno.', 'anime', 'https://images.unsplash.com/photo-1558981403-c5f9899a28bc?w=900', '3 semanas', 'principiante'],
  ['Game Design 101', 'Conceptos fundamentales de diseño de videojuegos y prototipado.', 'videojuegos', 'https://images.unsplash.com/photo-1542751371-adc38448a05e?w=900', '4 semanas', 'intermedio'],
  ['PHP y MySQL desde cero', 'Construcción de sitios dinámicos con PHP y MySQL.', 'programacion', 'https://images.unsplash.com/photo-1518779578993-ec3579fee39f?w=900', '20 horas', 'principiante']
];

$stmt = $mysqli->prepare("INSERT INTO cursos (titulo, descripcion, categoria, imagen_url, duracion, nivel) VALUES (?,?,?,?,?,?)");
foreach ($cursos as $c) {
  [$t,$d,$cat,$img,$dur,$niv] = $c;
  $stmt->bind_param('ssssss', $t,$d,$cat,$img,$dur,$niv);
  $stmt->execute();
}

echo '<h2>Se cargaron cursos de ejemplo.</h2><p><a href="/ArtazaFinal/Pagina-Prin/Cursos/cursos.php">Ver cursos</a></p>';
