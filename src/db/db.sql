CREATE TABLE Game_Laberinto;

CREATE TABLE usuario (
    id_usuario INT PRIMARY KEY AUTO_INCREMENT,
    nombres VARCHAR(60) NOT NULL,
    correo VARCHAR(60) NOT NULL UNIQUE,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    contrasenia VARCHAR(255) NOT NULL,
    nivel INT NOT NULL DEFAULT 0,
    tiempo_jugado INT NOT NULL DEFAULT 0,
    puntaje INT NOT NULL DEFAULT 0
);

INSERT INTO usuario (nombres, correo, usuario, contrasenia, nivel, tiempo_jugado, puntaje) VALUES
('Juan Pérez', 'juan.perez@gmail.com', 'juanp', 'contrasenia123', 2, 100, 6000),
('María López', 'maria.lopez@gmail.com', 'maria123', 'passw0rd', 2, 80, 6000),
('Andrés García', 'andres.garcia@gmail.com', 'andresg', 'securePass1', 2, 74, 6000),
('Lucía Fernández', 'lucia.fernandez@gmail.com', 'luciaf', 'mypassword', 3, 200, 9000),
('Carlos Rodríguez', 'carlos.rodriguez@gmail.com', 'carloss', 'carlitos2023', 1, 30, 6000),
('Sofía González', 'sofia.gonzalez@gmail.com', 'sofig', 'sofiasuper', 2, 50, 6000),
('Pedro Martínez', 'pedro.martinez@gmail.com', 'pedrom', 'pedro2023', 2, 78, 6000),
('Ana Torres', 'ana.torres@gmail.com', 'anatorres', 'anapassword', 1, 49, 3000),
('Javier Sánchez', 'javier.sanchez@gmail.com', 'javiers', 'javier123', 1, 25, 3000);