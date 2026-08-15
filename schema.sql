-- Run this in MySQL to create the database and tables
CREATE DATABASE IF NOT EXISTS cv_builder CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE cv_builder;

-- Main resume table
CREATE TABLE IF NOT EXISTS resumes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(100) NOT NULL,
  email VARCHAR(120) NOT NULL,
  phone VARCHAR(30) NOT NULL,
  location VARCHAR(120),
  summary TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Education entries
CREATE TABLE IF NOT EXISTS education (
  id INT AUTO_INCREMENT PRIMARY KEY,
  resume_id INT NOT NULL,
  institution VARCHAR(150) NOT NULL,
  degree VARCHAR(150),
  start_year VARCHAR(10),
  end_year VARCHAR(10),
  score VARCHAR(20),
  FOREIGN KEY (resume_id) REFERENCES resumes(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Experience entries
CREATE TABLE IF NOT EXISTS experience (
  id INT AUTO_INCREMENT PRIMARY KEY,
  resume_id INT NOT NULL,
  company VARCHAR(150) NOT NULL,
  role VARCHAR(150),
  start_date VARCHAR(20),
  end_date VARCHAR(20),
  description TEXT,
  FOREIGN KEY (resume_id) REFERENCES resumes(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Projects
CREATE TABLE IF NOT EXISTS projects (
  id INT AUTO_INCREMENT PRIMARY KEY,
  resume_id INT NOT NULL,
  title VARCHAR(150) NOT NULL,
  tech VARCHAR(150),
  link VARCHAR(255),
  description TEXT,
  FOREIGN KEY (resume_id) REFERENCES resumes(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Skills (simple list)
CREATE TABLE IF NOT EXISTS skills (
  id INT AUTO_INCREMENT PRIMARY KEY,
  resume_id INT NOT NULL,
  skill VARCHAR(100) NOT NULL,
  level ENUM('Beginner','Intermediate','Advanced') DEFAULT 'Intermediate',
  FOREIGN KEY (resume_id) REFERENCES resumes(id) ON DELETE CASCADE
) ENGINE=InnoDB;
