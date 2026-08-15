CREATE DATABASE IF NOT EXISTS imperial_pizza;
USE imperial_pizza;

CREATE TABLE IF NOT EXISTS configuracoes (
    `key` VARCHAR(50) PRIMARY KEY,
    `value` VARCHAR(255) NOT NULL
);

INSERT IGNORE INTO configuracoes (`key`, `value`) VALUES 
('commandMax', '1000'),
('defaultPizzaQty', '1'),
('volcanoEquivalent', '2'),
('esfihaGroup', '5'),
('esfihaEquivalent', '2'),
('recentCommands', '6');

CREATE TABLE IF NOT EXISTS equipe (
    id VARCHAR(36) PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    cargo VARCHAR(50) NOT NULL
);

CREATE TABLE IF NOT EXISTS operacoes (
    id VARCHAR(36) PRIMARY KEY,
    `date` DATE NULL,
    status VARCHAR(50) NOT NULL DEFAULT 'draft',
    started_at DATETIME NULL,
    kitchen_closed_at DATETIME NULL,
    completed_at DATETIME NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS operacao_equipe (
    operacao_id VARCHAR(36) NOT NULL,
    equipe_id VARCHAR(36) NOT NULL,
    PRIMARY KEY (operacao_id, equipe_id),
    FOREIGN KEY (operacao_id) REFERENCES operacoes(id) ON DELETE CASCADE,
    FOREIGN KEY (equipe_id) REFERENCES equipe(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS estoque_massas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    operacao_id VARCHAR(36) NOT NULL,
    flour_kg DECIMAL(10,2) DEFAULT 0,
    sugar_g DECIMAL(10,2) DEFAULT 0,
    salt_g DECIMAL(10,2) DEFAULT 0,
    eggs INT DEFAULT 0,
    oil_ml DECIMAL(10,2) DEFAULT 0,
    water_l DECIMAL(10,2) DEFAULT 0,
    yeast_g DECIMAL(10,2) DEFAULT 0,
    stock_saved BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (operacao_id) REFERENCES operacoes(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS lotes_massa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    operacao_id VARCHAR(36) NOT NULL,
    worker_id VARCHAR(36) NOT NULL,
    batch_count INT DEFAULT 1,
    flour_kg DECIMAL(10,2) DEFAULT 0,
    sugar_g DECIMAL(10,2) DEFAULT 0,
    salt_g DECIMAL(10,2) DEFAULT 0,
    eggs INT DEFAULT 0,
    oil_ml DECIMAL(10,2) DEFAULT 0,
    water_l DECIMAL(10,2) DEFAULT 0,
    yeast_g DECIMAL(10,2) DEFAULT 0,
    UNIQUE KEY unique_op_worker (operacao_id, worker_id),
    FOREIGN KEY (operacao_id) REFERENCES operacoes(id) ON DELETE CASCADE,
    FOREIGN KEY (worker_id) REFERENCES equipe(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS comandas (
    id VARCHAR(36) PRIMARY KEY,
    operacao_id VARCHAR(36) NOT NULL,
    number INT NOT NULL,
    pizzas INT DEFAULT 1,
    volcano INT DEFAULT 0,
    esfiha INT DEFAULT 0,
    sweet INT DEFAULT 0,
    note TEXT NULL,
    status VARCHAR(50) NOT NULL DEFAULT 'cozinha',
    dispatch_status VARCHAR(50) NOT NULL DEFAULT 'aguardando',
    beverage BOOLEAN DEFAULT FALSE,
    `change` BOOLEAN DEFAULT FALSE,
    change_amount VARCHAR(50) NULL,
    ketchup BOOLEAN DEFAULT FALSE,
    mayonnaise BOOLEAN DEFAULT FALSE,
    dispatch_note TEXT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    cozinha_time DATETIME NULL,
    forno_time DATETIME NULL,
    despacho_time DATETIME NULL,
    entrega_time DATETIME NULL,
    UNIQUE KEY idx_operacao_number (operacao_id, number),
    FOREIGN KEY (operacao_id) REFERENCES operacoes(id) ON DELETE CASCADE
);

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
