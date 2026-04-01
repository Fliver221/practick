INSERT INTO users (login, password_hash, full_name, phone, email, role, created_at, updated_at) VALUES
('student01', '$2y$10$QZH2k1b44YV1NQ6OIR9kV.LBls4.TbAifQjMq2uH9eFoAZwNnLFfK', 'Иванов Иван Иванович', '8(999)123-45-67', 'student01@example.com', 'user', NOW(), NOW()),
('student02', '$2y$10$QZH2k1b44YV1NQ6OIR9kV.LBls4.TbAifQjMq2uH9eFoAZwNnLFfK', 'Петров Петр Сергеевич', '8(999)987-65-43', 'student02@example.com', 'user', NOW(), NOW())
ON DUPLICATE KEY UPDATE updated_at = VALUES(updated_at);

-- Пароль для demo-пользователей: password123
