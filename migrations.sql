# criação das tabelas

CREATE TABLE pokemon(
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255)
);

CREATE TABLE type_pokemon(
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255)
);

CREATE TABLE pokemon_type_pokemon(
    pokemon_id INT,
    type_id INT
)