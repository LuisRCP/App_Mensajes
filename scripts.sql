CREATE DATABASE chat CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE chat;

CREATE TABLE persona(
    personaId INT AUTO_INCREMENT PRIMARY KEY,
    persona_Nombre VARCHAR(100) NOT NULL,
    persona_ApellidoPaterno VARCHAR(100) NOT NULL,
    persona_ApellidoMaterno VARCHAR(100) NOT NULL
);

CREATE TABLE usuario(
    usuarioId INT AUTO_INCREMENT PRIMARY KEY,
    usuario_Correo VARCHAR(150) NOT NULL UNIQUE,
    usuario_Clave VARCHAR(255) NOT NULL,
    personaId INT NOT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_usuario_persona
        FOREIGN KEY (personaId)
        REFERENCES persona(personaId)
        ON DELETE CASCADE
);

CREATE TABLE conversacion(
    conversacionId INT AUTO_INCREMENT PRIMARY KEY,
    usuario1_id INT NOT NULL,
    usuario2_id INT NOT NULL,
    creada_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_conv_user1
        FOREIGN KEY (usuario1_id) REFERENCES usuario(usuarioId)
        ON DELETE CASCADE,

    CONSTRAINT fk_conv_user2
        FOREIGN KEY (usuario2_id) REFERENCES usuario(usuarioId)
        ON DELETE CASCADE
);

CREATE TABLE mensaje(
    mensajeId INT AUTO_INCREMENT PRIMARY KEY,

    conversacionId INT NOT NULL,
    emisor_id INT NOT NULL,

    mensaje_contenido LONGTEXT NOT NULL,

    tipo ENUM('texto','imagen') DEFAULT 'texto',

    leido TINYINT DEFAULT 0,

    fecha_enviado TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_msg_conv
        FOREIGN KEY (conversacionId)
        REFERENCES conversacion(conversacionId)
        ON DELETE CASCADE,

    CONSTRAINT fk_msg_emisor
        FOREIGN KEY (emisor_id)
        REFERENCES usuario(usuarioId)
        ON DELETE CASCADE
);

CREATE TABLE archivo(
    archivoId INT AUTO_INCREMENT PRIMARY KEY,
    nombre_original VARCHAR(255),
    nombre_cifrado VARCHAR(255),
    ruta_archivo VARCHAR(255),
    fecha_subida TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

ALTER TABLE mensaje
ADD archivoId INT NULL,
ADD CONSTRAINT fk_msg_archivo
FOREIGN KEY (archivoId)
REFERENCES archivo(archivoId)
ON DELETE SET NULL;

CREATE INDEX idx_conv ON mensaje(conversacionId);
CREATE INDEX idx_fecha ON mensaje(fecha_enviado);
CREATE INDEX idx_leido ON mensaje(leido);

ALTER TABLE conversacion
ADD CONSTRAINT unique_conversacion UNIQUE(usuario1_id, usuario2_id);

ALTER TABLE mensaje
ADD leido_en TIMESTAMP NULL;

CREATE INDEX idx_conv_fecha 
ON mensaje(conversacionId, fecha_enviado);

ALTER TABLE archivo
ADD mime_type VARCHAR(100),
ADD tamaño_bytes INT;

ALTER TABLE conversacion
ADD ultimo_mensaje_id INT NULL,
ADD actualizado_en TIMESTAMP NULL;