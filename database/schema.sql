-- File: schema.sql
-- Purpose: Defines the structure of the database, including tables for users, news, and reservations.

-- Create the database if it does not already exist
CREATE DATABASE IF NOT EXISTS fh_webdevlotr 
CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Select the newly created database
USE fh_webdevlotr;

-- Table: users
-- Stores user account details, including roles and creation timestamps
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,               -- Unique ID for each user
    username VARCHAR(50) NOT NULL UNIQUE,            -- Unique username for login
    password VARCHAR(255) NOT NULL,                  -- Encrypted password
    email VARCHAR(100) NOT NULL UNIQUE,              -- Unique email address
    role ENUM('user', 'admin') NOT NULL DEFAULT 'user', -- Role (user or admin)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP   -- Automatically records creation time
);

-- Table: news
-- Stores news posts with optional images
CREATE TABLE IF NOT EXISTS news (
    id INT AUTO_INCREMENT PRIMARY KEY,               -- Unique ID for each news post
    title VARCHAR(150) NOT NULL,                     -- Title of the news post
    content TEXT NOT NULL,                           -- Content of the post
    image_path VARCHAR(255),                         -- Path to the optional image
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP   -- Automatically records creation time
);

-- Table: reservations
-- Stores room reservations made by users
CREATE TABLE IF NOT EXISTS reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,               -- Unique ID for each reservation
    user_id INT NOT NULL,                            -- ID of the user making the reservation
    check_in_date DATE NOT NULL,                     -- Check-in date
    check_out_date DATE NOT NULL,                    -- Check-out date
    status ENUM('new', 'confirmed', 'cancelled') NOT NULL DEFAULT 'new', -- Reservation status
    with_breakfast BOOLEAN NOT NULL DEFAULT FALSE,   -- Whether breakfast is included
    with_parking BOOLEAN NOT NULL DEFAULT FALSE,     -- Whether parking is included
    with_pet BOOLEAN NOT NULL DEFAULT FALSE,         -- Whether pets are included
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Automatically records creation time
    FOREIGN KEY (user_id) REFERENCES users(id)       -- Links to the 'users' table
        ON DELETE CASCADE                            -- Deletes reservations if user is deleted
);
