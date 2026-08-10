CREATE TABLE IF NOT EXISTS people (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  role ENUM('Montagem','Massa','Cozinha','Forno','Despacho','Atendimento','Estoque','Liderança','Outros') NOT NULL,
  active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS operations (
  id INT AUTO_INCREMENT PRIMARY KEY,
  operation_date DATE NOT NULL UNIQUE,
  status ENUM('draft','production_open','kitchen_closed','completed') NOT NULL DEFAULT 'draft',
  started_at DATETIME NULL,
  kitchen_closed_at DATETIME NULL,
  completed_at DATETIME NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS operation_team (
  operation_id INT NOT NULL,
  person_id INT NOT NULL,
  role VARCHAR(40) NOT NULL,
  PRIMARY KEY(operation_id, person_id),
  FOREIGN KEY(operation_id) REFERENCES operations(id) ON DELETE CASCADE,
  FOREIGN KEY(person_id) REFERENCES people(id) ON DELETE CASCADE
);
CREATE TABLE IF NOT EXISTS commands (
  id INT AUTO_INCREMENT PRIMARY KEY,
  operation_id INT NOT NULL,
  command_number INT NOT NULL,
  assembler_id INT NOT NULL,
  pizzas INT NOT NULL DEFAULT 1,
  volcano_qty INT NOT NULL DEFAULT 0,
  esfiha_qty INT NOT NULL DEFAULT 0,
  sweet_qty INT NOT NULL DEFAULT 0,
  note TEXT NULL,
  status ENUM('cozinha','forno','despacho') NOT NULL DEFAULT 'cozinha',
  error_type VARCHAR(120) NULL,
  error_note TEXT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_operation_command(operation_id, command_number),
  FOREIGN KEY(operation_id) REFERENCES operations(id) ON DELETE CASCADE,
  FOREIGN KEY(assembler_id) REFERENCES people(id)
);
CREATE TABLE IF NOT EXISTS dispatches (
  command_id INT PRIMARY KEY,
  status ENUM('aguardando','conferido','entrega') NOT NULL DEFAULT 'aguardando',
  complements VARCHAR(255) NULL,
  payment_status VARCHAR(80) NULL,
  note TEXT NULL,
  received_at DATETIME NULL,
  checked_at DATETIME NULL,
  out_for_delivery_at DATETIME NULL,
  FOREIGN KEY(command_id) REFERENCES commands(id) ON DELETE CASCADE
);
CREATE TABLE IF NOT EXISTS mass_batches (
  id INT AUTO_INCREMENT PRIMARY KEY,
  operation_id INT NOT NULL,
  worker_id INT NOT NULL,
  batch_number INT NOT NULL,
  flour_kg DECIMAL(10,2) NOT NULL DEFAULT 0,
  water_l DECIMAL(10,2) NOT NULL DEFAULT 0,
  yeast_g DECIMAL(10,2) NOT NULL DEFAULT 0,
  salt_g DECIMAL(10,2) NOT NULL DEFAULT 0,
  oil_l DECIMAL(10,2) NOT NULL DEFAULT 0,
  note TEXT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY(operation_id) REFERENCES operations(id) ON DELETE CASCADE,
  FOREIGN KEY(worker_id) REFERENCES people(id)
);
CREATE TABLE IF NOT EXISTS settings (
  setting_key VARCHAR(80) PRIMARY KEY,
  setting_value VARCHAR(255) NOT NULL
);
