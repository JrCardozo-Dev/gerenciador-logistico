
# 📦 Gerenciador Logístico

Sistema web desenvolvido como parte de uma Atividade Prática Orientada (APO) do curso de Engenharia de Software da UNIPAR EAD.

O objetivo do projeto é facilitar o gerenciamento de produtos, endereços, transportadoras e fretes por meio de uma interface simples e organizada.

## 🚀 Tecnologias utilizadas

- PHP
- MySQL
- HTML5
- CSS3
- JavaScript
- Bootstrap 5
- XAMPP
- Git e GitHub

## ⚙️ Funcionalidades

- Cadastro de produtos.
- Listagem e edição de produtos.
- Cadastro, listagem e edição de endereços.
- Cadastro, listagem e edição de transportadoras.
- Geração de fretes relacionando produtos, endereços e transportadoras.
- Listagem de fretes cadastrados.
- Relatórios de fretes por data.
- Exportação de relatórios em CSV.
- Dashboard com indicadores do sistema.
- Resumo dinâmico do trajeto utilizando JavaScript.

## 💻 Como executar o projeto

1. Instale o XAMPP.
2. Inicie os serviços Apache e MySQL.
3. Clone este repositório para a pasta `htdocs` do XAMPP.
4. Crie um banco de dados chamado `gerenciador_logistico` no phpMyAdmin.
5. Crie as tabelas necessárias utilizando os comandos SQL do projeto.
6. Confira as configurações de conexão no arquivo `includes/conexao.php`.
7. Acesse pelo navegador:

   http://localhost/gerenciador-logistico/

## 🗂️ Estrutura do projeto

```text
gerenciador-logistico/
├── css/
├── includes/
│   └── conexao.php
├── js/
│   └── fretes.js
├── index.php
├── produtos.php
├── lista_produtos.php
├── editar_produto.php
├── enderecos.php
├── lista_enderecos.php
├── editar_endereco.php
├── transportadoras.php
├── lista_transportadoras.php
├── editar_transportadora.php
├── fretes.php
├── lista_fretes.php
├── relatorios.php
└── README.md
```

## 👨‍💻 Autor

Desenvolvido por estudante do curso de Engenharia de Software — UNIPAR EAD.

Projeto acadêmico desenvolvido para a Atividade Prática Orientada (APO).
