-- 1. Limpa os testes antigos
TRUNCATE TABLE lotes_massa;

-- 2. Remove colunas desnecessárias e adiciona contador de batidas e chave única
ALTER TABLE lotes_massa
    DROP COLUMN note,
    DROP COLUMN created_at,
    ADD COLUMN batch_count INT DEFAULT 1 AFTER worker_id,
    ADD UNIQUE KEY unique_op_worker (operacao_id, worker_id);
