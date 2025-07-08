-- Create the database
CREATE DATABASE IF NOT EXISTS employee_management;
USE employee_management;

-- Create the employees table
CREATE TABLE IF NOT EXISTS employees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    age INT,
    address VARCHAR(255),
    department VARCHAR(100)
);
