-- Judges Table
CREATE TABLE judges (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    display_name VARCHAR(100) NOT NULL
);

-- Users (participants) Table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

-- Scores Table
CREATE TABLE scores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judge_id INT,
    user_id INT,
    points INT CHECK (points BETWEEN 1 AND 100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (judge_id) REFERENCES judges(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);
