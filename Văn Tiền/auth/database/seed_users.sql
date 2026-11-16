INSERT INTO users (username, email, password_hash, full_name, role, status) VALUES
('admin01', 'admin01@example.com', '$2y$10$dtAyVSElql6YbJToL5qInOzrEynwsiEWKlLZa7cvrad8mWB1RYsy6', 'Quản trị viên', 'admin', 'active'),
('editor01', 'editor01@example.com', '$2y$10$.i7Co3c25a738hkL5/F1deW10MAE6SRlKSI9zt2nqtR8rwa6oiNLa', 'Biên tập viên', 'editor', 'active'),
('locked01', 'locked01@example.com', '$2y$10$dExnr.cvAMUXbXJpG2c4JuEpryJu9lhxulST/3YSKD6LOD/MOIT1a', 'Tài khoản bị khóa', 'editor', 'locked');
