CREATE TABLE componente (
    codcomponente SERIAL PRIMARY KEY,
    nome_componente VARCHAR(80) NOT NULL
);

select*from componente

CREATE TABLE laboratorio (
    codlaboratorio SERIAL PRIMARY KEY,
    numerolaboratorio VARCHAR(100) NOT NULL
);

select*from laboratorio
insert into laboratorio(numerolaboratorio) values ('Laboratório 1');

CREATE TABLE situacao (
    codsituacao SERIAL PRIMARY KEY,
    tiposituacao VARCHAR(80) NOT NULL
);

select*from situacao

-- Inserir 'Disponível'
INSERT INTO situacao(tiposituacao) VALUES ('Disponível');
-- Inserir 'Indisponível'
INSERT INTO situacao(tiposituacao) VALUES ('Indisponível');
-- Inserir 'Em Manutenção'
INSERT INTO situacao(tiposituacao) VALUES ('Em Manutenção');

CREATE TABLE computador (
    codcomputador SERIAL PRIMARY KEY,
    patrimonio VARCHAR(255) DEFAULT NULL,
    codsituacao_fk INT NOT NULL,
    codlaboratorio_fk INT NOT NULL,
    FOREIGN KEY (codsituacao_fk) REFERENCES situacao(codsituacao),
    FOREIGN KEY (codlaboratorio_fk) REFERENCES laboratorio(codlaboratorio)
);

insert into computador(patrimonio,codsituacao_fk,codlaboratorio_fk) values('10',1,1);

select*from computador

CREATE TABLE nivel_acesso (
    codnivel_acesso SERIAL PRIMARY KEY,
    tipo_acesso VARCHAR(100) NOT NULL
);

-- Exemplo de dados na tabela nivel_acesso
INSERT INTO nivel_acesso (tipo_acesso) VALUES
    ('ALUNO'),
    ('CTI'),
    ('ADMIN'),
    ('NENHUMA');

CREATE TABLE usuario (
    codusuario SERIAL PRIMARY KEY,
    login NUMERIC DEFAULT NULL,
    senha VARCHAR(250) DEFAULT NULL,
    nome_usuario VARCHAR(100) NOT NULL,
    email_usuario VARCHAR(105) DEFAULT NULL,
    reset_token VARCHAR(100) DEFAULT NULL,
    token VARCHAR(255) DEFAULT NULL,
    reset_expires TIMESTAMP DEFAULT NULL,
    nivelacesso_fk INT NOT NULL,
    FOREIGN KEY (nivel_acesso_fk) REFERENCES nivel_acesso(codnivel_acesso)
);
INSERT INTO usuario(login, senha, nome_usuario, email_usuario, nivelacesso_fk) VALUES ('321', '$2y$10$OGUiJtdY9uRieCOiThkvvetZ1hvJK9BWaQGjmvbmeTLyMEevgw1yS', 'Gabriel ALUNO', 'gabriel_ap02@yahoo.com', 1);

SELECT usuario.*, nivel_acesso.tipo_acesso
FROM usuario
INNER JOIN nivel_acesso ON usuario.nivelacesso_fk = nivel_acesso.codnivel_acesso;

CREATE TABLE reclamacao (
    codreclamacao SERIAL PRIMARY KEY,
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

SELECT reclamacao.*, usuario.login, usuario.nome_usuario, usuario.email_usuario, laboratorio.numerolaboratorio, computador.patrimonio 
FROM reclamacao 
INNER JOIN usuario ON reclamacao.codusuario_fk = usuario.codusuario 
INNER JOIN laboratorio ON reclamacao.codlaboratorio_fk = laboratorio.codlaboratorio 
INNER JOIN computador ON reclamacao.codcomputador_fk = computador.codcomputador 
WHERE reclamacao.codcomputador_fk = 1 AND reclamacao.status = 'Em aberto'

CREATE TABLE foto (
	codfoto SERIAL PRIMARY KEY,
	foto_reclamacao varchar(255)
)


CREATE TABLE manutencao (
    codmanutencao SERIAL PRIMARY KEY,
    descricao_manutencao VARCHAR(2000) NOT NULL,
    datahora_manutencao TIMESTAMP DEFAULT NULL,
    codusuario_fk INT NOT NULL,
    codreclamacao_fk INT NOT NULL,
    FOREIGN KEY (codreclamacao_fk) REFERENCES reclamacao(codreclamacao),
    FOREIGN KEY (codusuario_fk) REFERENCES usuario(codusuario)
);

select*from manutencao

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

INSERT INTO laboratorio(numerolaboratorio) VALUES ('Laboratório 3');
INSERT INTO componente(nome_componente) VALUES ('Mouse');
INSERT INTO componente(nome_componente) VALUES ('Teclado');
INSERT INTO componente(nome_componente) VALUES ('Monitor');
INSERT INTO componente(nome_componente) VALUES ('Internet');
INSERT INTO componente(nome_componente) VALUES ('Softwares');
INSERT INTO componente(nome_componente) VALUES ('Outros');

select*from foto
select*from reclamacao
select*from reclamacao_componente
select*from usuario
SELECT * FROM computador 

SELECT * FROM computador
INNER JOIN situacao ON computador.codsituacao_fk = situacao.codsituacao
INNER JOIN laboratorio ON computador.codlaboratorio_fk = laboratorio.codlaboratorio
WHERE computador.codlaboratorio_fk = 1
ORDER BY patrimonio ASC
LIMIT 1 OFFSET 1;