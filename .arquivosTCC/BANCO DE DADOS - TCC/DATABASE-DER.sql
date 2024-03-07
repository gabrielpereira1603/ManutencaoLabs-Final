CREATE TABLE componente (
    codcomponente INT AUTO_INCREMENT PRIMARY KEY,
    nome_componente VARCHAR(80) NOT NULL
);

CREATE TABLE laboratorio (
    codlaboratorio INT AUTO_INCREMENT PRIMARY KEY,
    numerolaboratorio VARCHAR(100) NOT NULL
);

CREATE TABLE situacao (
    codsituacao INT AUTO_INCREMENT PRIMARY KEY,
    tiposituacao VARCHAR(80) NOT NULL
);

CREATE TABLE computador (
    codcomputador INT AUTO_INCREMENT PRIMARY KEY,
    patrimonio VARCHAR(255) DEFAULT NULL,
    codsituacao_fk INT NOT NULL,
    codlaboratorio_fk INT NOT NULL,
    FOREIGN KEY (codsituacao_fk) REFERENCES situacao(codsituacao),
    FOREIGN KEY (codlaboratorio_fk) REFERENCES laboratorio(codlaboratorio)
);

CREATE TABLE nivel_acesso (
    codnivel_acesso INT AUTO_INCREMENT PRIMARY KEY,
    tipo_acesso VARCHAR(100) NOT NULL
);

CREATE TABLE usuario (
    codusuario INT AUTO_INCREMENT PRIMARY KEY,
    login NUMERIC DEFAULT NULL,
    senha VARCHAR(250) DEFAULT NULL,
    nome_usuario VARCHAR(100) NOT NULL,
    email_usuario VARCHAR(105) DEFAULT NULL,
    reset_token VARCHAR(100) DEFAULT NULL,
    token VARCHAR(255) DEFAULT NULL,
    reset_expires TIMESTAMP DEFAULT NULL,
    nivelacesso_fk INT NOT NULL,
    FOREIGN KEY (nivelacesso_fk) REFERENCES nivel_acesso(codnivel_acesso)
);

CREATE TABLE reclamacao (
    codreclamacao INT AUTO_INCREMENT PRIMARY KEY,
    descricao VARCHAR(2000) NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'aberta',
    datahora_reclamacao TIMESTAMP DEFAULT NULL,
    datahora_fimreclamacao TIMESTAMP DEFAULT NULL,
    codcomputador_fk INT NOT NULL,
    codlaboratorio_fk INT NOT NULL,
    codusuario_fk INT NOT NULL,
    FOREIGN KEY (codcomputador_fk) REFERENCES computador(codcomputador),
    FOREIGN KEY (codlaboratorio_fk) REFERENCES laboratorio(codlaboratorio),
    FOREIGN KEY (codusuario_fk) REFERENCES usuario(codusuario)
);

CREATE TABLE foto (
    codfoto INT AUTO_INCREMENT PRIMARY KEY,
    foto_reclamacao varchar(255)
);

CREATE TABLE manutencao (
    codmanutencao INT AUTO_INCREMENT PRIMARY KEY,
    descricao_manutencao VARCHAR(2000) NOT NULL,
    datahora_manutencao TIMESTAMP DEFAULT NULL,
    codusuario_fk INT NOT NULL,
    codreclamacao_fk INT NOT NULL,
    FOREIGN KEY (codreclamacao_fk) REFERENCES reclamacao(codreclamacao),
    FOREIGN KEY (codusuario_fk) REFERENCES usuario(codusuario)
);

CREATE TABLE reclamacao_componente (
    codreclamacao_fk INT NOT NULL,
    codcomponente_fk INT NOT NULL,
    PRIMARY KEY (codreclamacao_fk, codcomponente_fk),
    FOREIGN KEY (codreclamacao_fk) REFERENCES reclamacao(codreclamacao),
    FOREIGN KEY (codcomponente_fk) REFERENCES componente(codcomponente)
);

CREATE TABLE reclamacao_foto (
    codreclamacao_fk INT NOT NULL,
    codfoto_fk INT NOT NULL,
    PRIMARY KEY (codreclamacao_fk, codfoto_fk),
    FOREIGN KEY (codreclamacao_fk) REFERENCES reclamacao(codreclamacao),
    FOREIGN KEY (codfoto_fk) REFERENCES foto(codfoto)
);
