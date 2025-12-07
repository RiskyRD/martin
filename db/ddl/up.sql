CREATE DATABASE IF NOT EXISTS martin;
USE martin;

-- USERS TABLE
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(250) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    is_admin BOOL DEFAULT FALSE,
    address TEXT,
    telephone VARCHAR(16),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- PRODUCTS TABLE
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(250) NOT NULL,
    price INT NOT NULL DEFAULT 0,
    stock INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- TRANSACTIONS TABLE
CREATE TABLE IF NOT EXISTS transactions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  user_id INT NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- TRANSACTION DETAILS TABLE
CREATE TABLE IF NOT EXISTS transaction_details (
  id INT AUTO_INCREMENT PRIMARY KEY,
  transaction_id INT NOT NULL,
  product_id INT NOT NULL,
  amount INT NOT NULL,
  FOREIGN KEY (transaction_id) REFERENCES transactions(id) ON DELETE CASCADE,
  FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Insert bcrypt-hashed password for "12345678"
INSERT INTO users (id, name, password, email, is_admin, address, telephone) VALUES
(1, 'admin', '$2y$10$7OLS/DMewv9T4/hskpYJQ.3QMbWyS.6tENnSORAWs42uopjIPdBqq', 'admin@example.com', 1, NULL, NULL),
(2, 'risky', '$2y$10$7OLS/DMewv9T4/hskpYJQ.3QMbWyS.6tENnSORAWs42uopjIPdBqq', 'risky@example.com', 0, 'Blahbatuh', '085776728231'),
(3, 'lela', '$2y$10$7OLS/DMewv9T4/hskpYJQ.3QMbWyS.6tENnSORAWs42uopjIPdBqq', 'lela@example.com', 0, 'Sukawati', '082340688585'),
(4, 'kesava', '$2y$10$7OLS/DMewv9T4/hskpYJQ.3QMbWyS.6tENnSORAWs42uopjIPdBqq', 'kesava@example.com', 0, 'Denpasar', '082147700295');

-- PRODUCTS
INSERT INTO products (id, name, price, stock) VALUES
(1, 'Roti Tawar', 15000, 30),
(2, 'Mie Instan', 3500, 100),
(3, 'Air Mineral 600ml', 5000, 80),
(4, 'Teh Botol', 7000, 50),
(5, 'Pulpen Biru', 4000, 60),
(6, 'Buku Tulis', 6000, 40),
(7, 'Headset', 85000, 20),
(8, 'Charger HP', 65000, 15);

-- TRANSACTIONS (simple timestamps only)
INSERT INTO transactions (id, user_id) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 2);

-- TRANSACTION DETAILS
INSERT INTO transaction_details (transaction_id, product_id, amount) VALUES
(1, 1, 2),
(1, 3, 1),
(1, 5, 3),
(2, 2, 5),
(3, 4, 2),
(3, 6, 1),
(4, 7, 1),
(4, 1, 1),
(4, 8, 2);
