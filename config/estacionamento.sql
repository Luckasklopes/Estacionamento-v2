CREATE DATABASE estacionamento;
USE estacionamento;

CREATE TABLE entradas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    placa VARCHAR(10) NOT NULL,
    hora_entrada TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    hora_saida TIMESTAMP NULL,
    valor DECIMAL(5, 2) DEFAULT 0
);

CREATE TABLE historico (
    id INT AUTO_INCREMENT PRIMARY KEY,
    placa VARCHAR(10) NOT NULL,
    hora_entrada TIMESTAMP,
    hora_saida TIMESTAMP,
    valor DECIMAL(5, 2)
);

CREATE TABLE configuracoes (
    id INT PRIMARY KEY,
    taxa_minima DECIMAL(5,2) NOT NULL,
    preco_por_hora DECIMAL(5,2) NOT NULL
);

INSERT INTO configuracoes (id, taxa_minima, preco_por_hora) VALUES (1, 5.00, 3.00);
