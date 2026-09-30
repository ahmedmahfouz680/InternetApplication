CREATE DATABASE IF NOT EXISTS simple_crud CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE simple_crud;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(120) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO users (name, email) VALUES
('John Doe', 'john@example.com'),
('Sarah Smith', 'sarah@example.com');