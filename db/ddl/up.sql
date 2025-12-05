CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(250) NOT NULL,
    password BINARY(60) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    is_admin BOOL DEFAULT FALSE,
    address TEXT,
    telephone VARCHAR(16),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);