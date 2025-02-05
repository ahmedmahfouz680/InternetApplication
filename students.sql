-- Create the database
CREATE DATABASE IF NOT EXISTS students;

-- Use the database
USE students;

-- Create the student table
CREATE TABLE IF NOT EXISTS student (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);
