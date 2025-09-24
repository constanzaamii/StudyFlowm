-- StudyFlow Database Schema
-- Sistema de Gestión Académica UCSC

-- Crear base de datos
CREATE DATABASE IF NOT EXISTS studyflow_db;
USE studyflow_db;

-- Tabla de usuarios (estudiantes)
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_id VARCHAR(20) UNIQUE NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    career VARCHAR(100) NOT NULL,
    year_level INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla de asignaturas
CREATE TABLE subjects (
    id INT PRIMARY KEY AUTO_INCREMENT,
    code VARCHAR(20) UNIQUE NOT NULL,
    name VARCHAR(200) NOT NULL,
    credits INT NOT NULL,
    semester INT NOT NULL,
    year_level INT NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de tareas/entregas
CREATE TABLE tasks (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    subject_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    due_date DATETIME NOT NULL,
    priority ENUM('low', 'medium', 'high') DEFAULT 'medium',
    status ENUM('pending', 'in_progress', 'completed', 'overdue') DEFAULT 'pending',
    completion_date DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE
);

-- Tabla de notas/calificaciones
CREATE TABLE grades (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    subject_id INT NOT NULL,
    evaluation_type VARCHAR(100) NOT NULL, -- 'parcial1', 'parcial2', 'examen', 'tarea', etc.
    grade DECIMAL(3,2) NOT NULL, -- Nota del 1.0 al 7.0
    weight DECIMAL(3,2) NOT NULL, -- Peso porcentual (0.30 = 30%)
    evaluation_date DATE NOT NULL,
    comments TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE
);

-- Tabla de inscripciones (relación usuario-asignatura)
CREATE TABLE enrollments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    subject_id INT NOT NULL,
    semester VARCHAR(20) NOT NULL, -- '2024-1', '2024-2'
    status ENUM('active', 'completed', 'dropped') DEFAULT 'active',
    final_grade DECIMAL(3,2) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE,
    UNIQUE KEY unique_enrollment (user_id, subject_id, semester)
);

-- Tabla de recordatorios
CREATE TABLE reminders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    task_id INT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT,
    reminder_date DATETIME NOT NULL,
    is_sent BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (task_id) REFERENCES tasks(id) ON DELETE CASCADE
);

-- Tabla de actividad/logs
CREATE TABLE activity_log (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    action VARCHAR(100) NOT NULL,
    entity_type VARCHAR(50) NOT NULL, -- 'task', 'grade', 'reminder'
    entity_id INT NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Índices para optimizar consultas
CREATE INDEX idx_tasks_user_due_date ON tasks(user_id, due_date);
CREATE INDEX idx_tasks_status ON tasks(status);
CREATE INDEX idx_grades_user_subject ON grades(user_id, subject_id);
CREATE INDEX idx_reminders_date ON reminders(reminder_date, is_sent);
CREATE INDEX idx_activity_user_date ON activity_log(user_id, created_at);

-- Vista para calcular promedios por asignatura
CREATE VIEW subject_averages AS
SELECT 
    e.user_id,
    e.subject_id,
    s.name as subject_name,
    s.code as subject_code,
    ROUND(SUM(g.grade * g.weight) / SUM(g.weight), 2) as weighted_average,
    COUNT(g.id) as total_evaluations,
    e.semester
FROM enrollments e
JOIN subjects s ON e.subject_id = s.id
LEFT JOIN grades g ON e.user_id = g.user_id AND e.subject_id = g.subject_id
WHERE e.status = 'active'
GROUP BY e.user_id, e.subject_id, e.semester;

-- Vista para tareas próximas a vencer
CREATE VIEW upcoming_tasks AS
SELECT 
    t.*,
    s.name as subject_name,
    s.code as subject_code,
    u.first_name,
    u.last_name,
    DATEDIFF(t.due_date, NOW()) as days_until_due
FROM tasks t
JOIN subjects s ON t.subject_id = s.id
JOIN users u ON t.user_id = u.id
WHERE t.status IN ('pending', 'in_progress')
AND t.due_date >= NOW()
AND t.due_date <= DATE_ADD(NOW(), INTERVAL 7 DAY)
ORDER BY t.due_date ASC;

-- Vista para estadísticas del usuario
CREATE VIEW user_stats AS
SELECT 
    u.id as user_id,
    u.first_name,
    u.last_name,
    COUNT(DISTINCT t.id) as total_tasks,
    COUNT(DISTINCT CASE WHEN t.status = 'completed' THEN t.id END) as completed_tasks,
    COUNT(DISTINCT CASE WHEN t.status = 'pending' THEN t.id END) as pending_tasks,
    COUNT(DISTINCT CASE WHEN t.status = 'overdue' THEN t.id END) as overdue_tasks,
    ROUND(AVG(CASE WHEN g.grade IS NOT NULL THEN g.grade END), 2) as overall_average,
    COUNT(DISTINCT e.subject_id) as enrolled_subjects
FROM users u
LEFT JOIN tasks t ON u.id = t.user_id
LEFT JOIN grades g ON u.id = g.user_id
LEFT JOIN enrollments e ON u.id = e.user_id AND e.status = 'active'
GROUP BY u.id;
