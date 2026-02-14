<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'sap_erp');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if not exists
$conn->query("CREATE DATABASE IF NOT EXISTS " . DB_NAME);
$conn->select_db(DB_NAME);

// Users Table
$conn->query("CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Products Table (Materials Management)
$conn->query("CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    sku VARCHAR(50) UNIQUE,
    category VARCHAR(50),
    price DECIMAL(10, 2),
    stock INT DEFAULT 0,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)");

// Sales Table (Sales & Distribution)
$conn->query("CREATE TABLE IF NOT EXISTS sales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT,
    quantity INT,
    total_price DECIMAL(10, 2),
    sale_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id)
)");

// Employees Table (HCM)
$conn->query("CREATE TABLE IF NOT EXISTS employees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE,
    department VARCHAR(50),
    salary DECIMAL(10, 2),
    joining_date DATE
)");

// Finance Table (FI)
$conn->query("CREATE TABLE IF NOT EXISTS finance_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    description VARCHAR(255),
    amount DECIMAL(10, 2),
    type ENUM('INCOME', 'EXPENSE'),
    log_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Default Admin
$admin_pass = password_hash('admin123', PASSWORD_DEFAULT);
$conn->query("INSERT IGNORE INTO users (username, password, role) VALUES ('admin', '$admin_pass', 'admin')");

// Seed Sample Data if empty
$check = $conn->query("SELECT id FROM products LIMIT 1");
if ($check->num_rows == 0) {
    $conn->query("INSERT INTO products (name, sku, category, price, stock) VALUES 
    ('Brake Disc', 'BD-9901', 'Automotive', 150.00, 45),
    ('Control Unit', 'CU-2022', 'Electronics', 1200.00, 12),
    ('Standard Bolt', 'SB-002', 'Hardware', 0.50, 500)");

    $conn->query("INSERT INTO employees (full_name, email, department, salary, joining_date) VALUES 
    ('John Doe', 'john.doe@enterprise.com', 'Finance', 75000, '2023-01-15'),
    ('Jane Smith', 'jane.s@enterprise.com', 'IT', 95000, '2022-11-20')");

    $conn->query("INSERT INTO finance_logs (description, amount, type) VALUES 
    ('Initial Capital Injection', 500000.00, 'INCOME'),
    ('Office Rent Q1', 5000.00, 'EXPENSE')");
}

// $conn->close();
?>