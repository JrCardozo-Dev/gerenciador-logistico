
CREATE DATABASE IF NOT EXISTS gerenciador_logistico;
USE gerenciador_logistico;

CREATE TABLE IF NOT EXISTS produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    peso DECIMAL(10,2) NOT NULL,
    valor DECIMAL(10,2) NOT NULL,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS enderecos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome_destinatario VARCHAR(100) NOT NULL,
    cep VARCHAR(10) NOT NULL,
    rua VARCHAR(150) NOT NULL,
    numero VARCHAR(20),
    bairro VARCHAR(100),
    cidade VARCHAR(100) NOT NULL,
    estado VARCHAR(2) NOT NULL,
    complemento VARCHAR(100),
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS transportadoras (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(120) NOT NULL,
    cnpj VARCHAR(18),
    telefone VARCHAR(20),
    email VARCHAR(100),
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS fretes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    produto_id INT NOT NULL,
    endereco_origem_id INT NOT NULL,
    endereco_destino_id INT NOT NULL,
    transportadora_id INT NOT NULL,
    data_frete DATE NOT NULL,
    valor_frete DECIMAL(10,2),
    status VARCHAR(30) DEFAULT 'Pendente',
    FOREIGN KEY (produto_id) REFERENCES produtos(id),
    FOREIGN KEY (endereco_origem_id) REFERENCES enderecos(id),
    FOREIGN KEY (endereco_destino_id) REFERENCES enderecos(id),
    FOREIGN KEY (transportadora_id) REFERENCES transportadoras(id)
);
