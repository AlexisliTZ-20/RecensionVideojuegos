USE videojuegos;

CREATE TABLE games (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    developer VARCHAR(255) NOT NULL,
    publisher VARCHAR(255) NOT NULL,
    image VARCHAR(255) NOT NULL,
    video VARCHAR(255) NOT NULL,
    screenshots TEXT NOT NULL
);

CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    game_id INT NOT NULL,
    username VARCHAR(255) NOT NULL,
    recommendation BOOLEAN NOT NULL,
    review_text TEXT NOT NULL,
    FOREIGN KEY (game_id) REFERENCES games(id)
);

CREATE TABLE tags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

CREATE TABLE game_tags (
    game_id INT NOT NULL,
    tag_id INT NOT NULL,
    FOREIGN KEY (game_id) REFERENCES games(id),
    FOREIGN KEY (tag_id) REFERENCES tags(id)
);
