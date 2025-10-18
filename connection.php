<?php
// Database connection using PDO
// Edit the following constants to match your local environment

define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'inventory');
define('DB_USER', 'root');
define('DB_PASS', '');

date_default_timezone_set('UTC');

function getPDO() {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'DB connection failed: ' . $e->getMessage()]);
            exit;
        }
    }
    return $pdo;
}

// Backwards compatibility: some older files expect a $conn variable
// Assign it to the PDO instance returned by getPDO()
try {
    if (!isset($conn) || $conn === null) {
        $conn = getPDO();
    }
} catch (Exception $e) {
    // If connection fails, let including script handle it (or catch the exception)
}

// SQL to create the products table (run once in your MySQL):
/*
CREATE DATABASE IF NOT EXISTS elog_inventory CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE elog_inventory;

CREATE TABLE IF NOT EXISTS products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  category VARCHAR(255) DEFAULT '',
  state VARCHAR(100) DEFAULT '',
  quantity INT DEFAULT 0,
  transit INT DEFAULT 0,
  price DECIMAL(12,2) DEFAULT 0.00,
  description TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
*/
