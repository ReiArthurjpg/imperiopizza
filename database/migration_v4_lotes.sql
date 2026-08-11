-- Migração para Lotes de Comandas

-- 1. Cria a tabela de lotes
CREATE TABLE IF NOT EXISTS comandas_lotes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    operacao_id VARCHAR(36) NOT NULL,
    assembler_id VARCHAR(36) NOT NULL,
    comanda_inicio INT NOT NULL,
    comanda_fim INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (operacao_id) REFERENCES operacoes(id) ON DELETE CASCADE,
    FOREIGN KEY (assembler_id) REFERENCES equipe(id) ON DELETE CASCADE
);

-- 2. Migra os dados existentes da tabela comandas para lotes
INSERT IGNORE INTO comandas_lotes (operacao_id, assembler_id, comanda_inicio, comanda_fim, created_at)
SELECT operacao_id, assembler_id, number, number, created_at 
FROM comandas 
WHERE assembler_id IS NOT NULL AND assembler_id != '';

-- 3. Limpa duplicatas de comandas mantendo apenas 1 registro por (operacao_id, number)
DELETE FROM comandas 
WHERE id NOT IN (
    SELECT max_id FROM (
        SELECT MAX(id) as max_id FROM comandas GROUP BY operacao_id, number
    ) AS temp
);

-- 4. Remove a chave estrangeira do assembler_id na tabela comandas
ALTER TABLE comandas DROP FOREIGN KEY comandas_ibfk_2;

-- 5. Remove a coluna e adiciona restrição única
ALTER TABLE comandas DROP COLUMN assembler_id;
ALTER TABLE comandas ADD UNIQUE KEY idx_operacao_number (operacao_id, number);
