INSERT IGNORE INTO settings(setting_key, setting_value) VALUES
('command_max','1000'),('default_pizza_qty','1'),('volcano_equivalent','2'),('esfiha_group','25'),('esfiha_equivalent','1'),('recent_commands','6');
INSERT INTO people(name, role) VALUES
('Montador Exemplo', 'Montagem'),('Masseiro Exemplo', 'Massa'),('Despacho Exemplo', 'Despacho')
ON DUPLICATE KEY UPDATE name = VALUES(name);
