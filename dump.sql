CREATE DATABASE IF NOT EXISTS babado_total
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE babado_total;

CREATE TABLE usuarios (

    id INT AUTO_INCREMENT PRIMARY KEY,

    nome VARCHAR(100) NOT NULL,

    email VARCHAR(150) NOT NULL UNIQUE,

    senha VARCHAR(255) NOT NULL

);

CREATE TABLE noticias (

    id INT AUTO_INCREMENT PRIMARY KEY,

    titulo VARCHAR(255) NOT NULL,

    noticia TEXT NOT NULL,

    data DATETIME DEFAULT CURRENT_TIMESTAMP,

    autor INT NOT NULL,

    imagem VARCHAR(255),

    CONSTRAINT fk_autor
        FOREIGN KEY (autor)
        REFERENCES usuarios(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE

);

CREATE TABLE comentarios (

    id INT AUTO_INCREMENT PRIMARY KEY,

    noticia_id INT NOT NULL,

    usuario_id INT NOT NULL,

    comentario TEXT NOT NULL,

    data DATETIME DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_comentario_noticia
        FOREIGN KEY (noticia_id)
        REFERENCES noticias(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_comentario_usuario
        FOREIGN KEY (usuario_id)
        REFERENCES usuarios(id)
        ON DELETE CASCADE

);

CREATE TABLE likes_noticia (

    id INT AUTO_INCREMENT PRIMARY KEY,

    noticia_id INT NOT NULL,

    usuario_id INT NOT NULL,

    data DATETIME DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_like_noticia
        FOREIGN KEY (noticia_id)
        REFERENCES noticias(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_like_usuario
        FOREIGN KEY (usuario_id)
        REFERENCES usuarios(id)
        ON DELETE CASCADE,

    UNIQUE (noticia_id, usuario_id)

);