CREATE DATABASE bd_futebol;

CREATE TABLE posicoes(
    id INT AUTO_INCREMENT PRIMARY KEY,
    posicao VARCHAR(50),
    created_at DATETIME DEFAULT now()
);

INSERT INTO `posicoes`(`posicao`) 
VALUES ("Goleiro"),
("Zagueiro"),
("Meio-Campo"),
("Atacante"),
("Lateral-esquerdo"),
("Lateral-direito");

