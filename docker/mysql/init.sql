-- Initialize database for Collectorate Library
CREATE DATABASE IF NOT EXISTS collectorate_library CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Create user if not exists
CREATE USER IF NOT EXISTS 'laravel'@'%' IDENTIFIED BY 'laravel_password';
GRANT ALL PRIVILEGES ON collectorate_library.* TO 'laravel'@'%';
FLUSH PRIVILEGES;

-- Use the database
USE collectorate_library;

-- Set timezone
SET time_zone = '+00:00';
