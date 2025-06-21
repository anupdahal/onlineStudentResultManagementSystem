-- Create the database if it doesn't exist and switch to it
CREATE DATABASE IF NOT EXISTS resultManagementSystem;
USE resultManagementSystem;

-- ===================================================
-- Table: students
-- This table stores student details.
-- Note: 'parent_phone' is used (not "parents_phone").
-- ===================================================
-- DROP TABLE IF EXISTS students;
CREATE TABLE students (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  phone VARCHAR(20),
  parent_phone VARCHAR(20),
  photo VARCHAR(255) DEFAULT NULL,
  address TEXT,
  password VARCHAR(255) NOT NULL,
  status ENUM('pending','approved') DEFAULT 'pending'
);

-- ===================================================
-- Table: teachers
-- This table stores teacher details.
-- ===================================================
-- DROP TABLE IF EXISTS teachers;
CREATE TABLE teachers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  phone VARCHAR(20),
  photo VARCHAR(255) DEFAULT NULL,
  password VARCHAR(255) NOT NULL
);

-- ===================================================
-- Table: classes
-- This table stores class/semester information.
-- ===================================================
-- DROP TABLE IF EXISTS classes;
CREATE TABLE classes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  class_name VARCHAR(50) NOT NULL
);

-- ===================================================
-- Table: subjects
-- This table stores subjects linked to a specific class.
-- ===================================================
-- DROP TABLE IF EXISTS subjects;
CREATE TABLE subjects (
  id INT AUTO_INCREMENT PRIMARY KEY,
  class_id INT NOT NULL,
  subject_name VARCHAR(100) NOT NULL,
  max_theory INT DEFAULT 100,
  max_practical INT DEFAULT 100,
  FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE CASCADE
);

-- ===================================================
-- Table: results
-- This table stores the marks for students.
-- A teacher may enter marks for the same student and subject
-- for different terms (e.g. First Term, Second Term, Final Term).
-- A UNIQUE KEY prevents duplicate entries for (student, subject, term).
-- ===================================================
-- DROP TABLE IF EXISTS results;
CREATE TABLE results (
  id INT AUTO_INCREMENT PRIMARY KEY,
  student_id INT NOT NULL,
  subject_id INT NOT NULL,
  theory_marks INT DEFAULT 0,
  practical_marks INT DEFAULT 0,
  term VARCHAR(50) NOT NULL,
  added_by_teacher_id INT NOT NULL,
  timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY unique_result (student_id, subject_id, term),
  FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
  FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE,
  FOREIGN KEY (added_by_teacher_id) REFERENCES teachers(id) ON DELETE CASCADE
);

-- ===================================================
-- Table: notices
-- This table stores notices sent either to students or teachers.
-- For notices sent by teachers, 'sender_teacher_id' is set.
-- For notices sent by the admin, 'sender_teacher_id' is NULL.
-- Note: The 'target_type' column indicates whether the recipient is a 'student' or a 'teacher'.
-- ===================================================
-- DROP TABLE IF EXISTS notices;
CREATE TABLE notices (
  id INT AUTO_INCREMENT PRIMARY KEY,
  target_type ENUM('student','teacher') NOT NULL,
  target_id INT NOT NULL,
  subject VARCHAR(255) NOT NULL,
  message TEXT NOT NULL,
  sender_teacher_id INT DEFAULT NULL,
  timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (sender_teacher_id) REFERENCES teachers(id) ON DELETE SET NULL
  -- Note: No foreign key on target_id since it can reference an ID in either students or teachers table.
);
