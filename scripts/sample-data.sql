-- Datos de ejemplo para StudyFlow
-- Insertar después de crear el esquema

-- Insertar asignaturas típicas de Ingeniería en Informática
INSERT INTO subjects (code, name, credits, semester, year_level, description) VALUES
('INF101', 'Fundamentos de Programación', 6, 1, 1, 'Introducción a la programación usando Python y conceptos básicos'),
('INF102', 'Matemáticas Discretas', 4, 1, 1, 'Lógica, conjuntos, grafos y matemáticas para informática'),
('INF103', 'Inglés Técnico I', 3, 1, 1, 'Inglés orientado a tecnología y documentación técnica'),
('INF201', 'Programación Orientada a Objetos', 6, 2, 1, 'POO usando Java, herencia, polimorfismo y patrones'),
('INF202', 'Base de Datos I', 5, 2, 1, 'Diseño de BD relacionales, SQL y normalización'),
('INF203', 'Estructuras de Datos', 5, 2, 1, 'Listas, pilas, colas, árboles y algoritmos'),
('INF301', 'Desarrollo Web', 6, 3, 2, 'HTML, CSS, JavaScript y frameworks modernos'),
('INF302', 'Base de Datos II', 5, 3, 2, 'BD avanzadas, procedimientos, triggers y optimización'),
('INF303', 'Redes de Computadores', 5, 3, 2, 'Protocolos de red, TCP/IP y arquitecturas distribuidas');

-- Insertar usuario de ejemplo
INSERT INTO users (student_id, first_name, last_name, email, password_hash, career, year_level) VALUES
('2024001', 'María', 'González', 'maria.gonzalez@ucsc.cl', 'hash_password_123', 'Ingeniería de Ejecución en Informática', 1),
('2024002', 'Carlos', 'Rodríguez', 'carlos.rodriguez@ucsc.cl', 'hash_password_456', 'Ingeniería de Ejecución en Informática', 1),
('2023015', 'Ana', 'Martínez', 'ana.martinez@ucsc.cl', 'hash_password_789', 'Ingeniería de Ejecución en Informática', 2);

-- Inscribir estudiantes en asignaturas
INSERT INTO enrollments (user_id, subject_id, semester, status) VALUES
-- María (1er año)
(1, 1, '2024-2', 'active'), -- Fundamentos de Programación
(1, 2, '2024-2', 'active'), -- Matemáticas Discretas
(1, 3, '2024-2', 'active'), -- Inglés Técnico I
-- Carlos (1er año)
(2, 1, '2024-2', 'active'), -- Fundamentos de Programación
(2, 2, '2024-2', 'active'), -- Matemáticas Discretas
(2, 3, '2024-2', 'active'), -- Inglés Técnico I
-- Ana (2do año)
(3, 7, '2024-2', 'active'), -- Desarrollo Web
(3, 8, '2024-2', 'active'), -- Base de Datos II
(3, 9, '2024-2', 'active'); -- Redes de Computadores

-- Insertar tareas de ejemplo
INSERT INTO tasks (user_id, subject_id, title, description, due_date, priority, status) VALUES
(1, 1, 'Tarea 1: Variables y Tipos de Datos', 'Crear programa que maneje diferentes tipos de datos en Python', '2024-09-30 23:59:00', 'medium', 'pending'),
(1, 1, 'Proyecto Final: Sistema de Gestión', 'Desarrollar un sistema completo usando POO', '2024-10-15 23:59:00', 'high', 'pending'),
(1, 2, 'Ejercicios de Lógica Proposicional', 'Resolver 20 ejercicios del capítulo 3', '2024-09-28 23:59:00', 'medium', 'pending'),
(1, 3, 'Essay: Technology Impact', 'Write a 500-word essay about technology impact on society', '2024-10-05 23:59:00', 'low', 'pending'),
(2, 1, 'Laboratorio 2: Estructuras de Control', 'Implementar algoritmos usando if, while y for', '2024-09-29 23:59:00', 'medium', 'completed'),
(2, 2, 'Tarea: Teoría de Grafos', 'Resolver problemas de grafos y árboles', '2024-10-02 23:59:00', 'high', 'pending'),
(3, 7, 'Proyecto Web: Landing Page', 'Crear landing page responsive con HTML/CSS/JS', '2024-10-10 23:59:00', 'high', 'in_progress'),
(3, 8, 'Diseño de Base de Datos', 'Crear MER para sistema de biblioteca universitaria', '2024-10-08 23:59:00', 'medium', 'pending');

-- Insertar notas de ejemplo
INSERT INTO grades (user_id, subject_id, evaluation_type, grade, weight, evaluation_date, comments) VALUES
-- Notas de María
(1, 1, 'Parcial 1', 6.2, 0.30, '2024-09-15', 'Buen manejo de conceptos básicos'),
(1, 1, 'Parcial 2', 5.8, 0.30, '2024-09-22', 'Mejorar en estructuras de datos'),
(1, 2, 'Parcial 1', 6.5, 0.30, '2024-09-16', 'Excelente en lógica proposicional'),
(1, 3, 'Oral 1', 6.0, 0.25, '2024-09-18', 'Buena pronunciación, mejorar vocabulario'),
-- Notas de Carlos
(2, 1, 'Parcial 1', 5.5, 0.30, '2024-09-15', 'Necesita reforzar conceptos básicos'),
(2, 1, 'Parcial 2', 6.1, 0.30, '2024-09-22', 'Mejora notable en la segunda evaluación'),
(2, 2, 'Parcial 1', 5.9, 0.30, '2024-09-16', 'Buen trabajo en general'),
-- Notas de Ana
(3, 7, 'Proyecto 1', 6.8, 0.40, '2024-09-20', 'Excelente implementación de responsive design'),
(3, 8, 'Parcial 1', 6.3, 0.30, '2024-09-17', 'Buen dominio de SQL avanzado'),
(3, 9, 'Laboratorio 1', 5.7, 0.20, '2024-09-19', 'Configuración correcta de protocolos');

-- Insertar recordatorios
INSERT INTO reminders (user_id, task_id, title, message, reminder_date, is_sent) VALUES
(1, 1, 'Recordatorio: Tarea de Programación', 'No olvides entregar la tarea de variables y tipos de datos', '2024-09-29 10:00:00', FALSE),
(1, 3, 'Recordatorio: Ejercicios de Lógica', 'Quedan 2 días para entregar los ejercicios de lógica proposicional', '2024-09-26 15:00:00', FALSE),
(2, 6, 'Recordatorio: Tarea de Grafos', 'Tarea de teoría de grafos vence mañana', '2024-10-01 09:00:00', FALSE),
(3, 7, 'Recordatorio: Proyecto Web', 'Revisar responsive design del proyecto web', '2024-10-08 14:00:00', FALSE);

-- Insertar actividad de ejemplo
INSERT INTO activity_log (user_id, action, entity_type, entity_id, description) VALUES
(1, 'CREATE', 'task', 1, 'Creó nueva tarea: Variables y Tipos de Datos'),
(1, 'CREATE', 'task', 2, 'Creó nueva tarea: Proyecto Final Sistema de Gestión'),
(2, 'COMPLETE', 'task', 5, 'Completó tarea: Laboratorio 2 Estructuras de Control'),
(1, 'CREATE', 'grade', 1, 'Registró nota de Parcial 1 en Fundamentos de Programación'),
(3, 'UPDATE', 'task', 7, 'Actualizó estado de Proyecto Web a "en progreso"');

-- Consultas útiles para verificar los datos
-- SELECT * FROM user_stats;
-- SELECT * FROM subject_averages;
-- SELECT * FROM upcoming_tasks;
