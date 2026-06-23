# MedInt - Sistema de Gestao de Equipamentos Medicos

## Autor

Raquel Rosas | 1230798

## Descricao

A MedInt e uma aplicacao web desenvolvida para apoiar a gestao de equipamentos medicos em ambiente hospitalar.

Permite registar, consultar e gerir equipamentos, unidades fisicas, fornecedores, localizacoes, componentes, contratos, documentacao e historico de alteracoes.

A aplicacao inclui ainda funcionalidades de pesquisa, filtros, exportacao para Excel, impressao de etiquetas e dashboard com indicadores gerais.

## Tecnologias Utilizadas

- PHP
- MySQL
- PDO
- HTML
- CSS
- Bootstrap
- JavaScript
- Laragon

## Estrutura do Projeto

```text
1230798/
  assets/        imagens da aplicacao
  common/        ficheiros comuns, como ligacao a base de dados
  config/        configuracao da aplicacao
  css/           estilos da aplicacao
  js/            scripts JavaScript
  private/       paginas protegidas por autenticacao
  public/        paginas publicas
  uploads/       ficheiros carregados pelo utilizador
  README.md      documentacao do projeto
```

## Principais Modulos

- Equipamentos
- Unidades de equipamento
- Fornecedores
- Localizacoes
- Componentes
- Contratos
- Documentacao e manuais
- Historico de estados e localizacoes
- Dashboard
- Exportacao para Excel
- Impressao de etiquetas
- Criacao de contas

## Perfis de Utilizador

A aplicacao utiliza autenticacao por email e password.

Existem dois perfis principais:

- Gestor: tem acesso a todas as funcionalidades.
- Tecnico: tem acesso limitado e nao pode inserir equipamentos.

As paginas privadas sao protegidas por sessoes. Caso o utilizador nao esteja autenticado, e redirecionado para a pagina de login.

## Instalacao e Execucao

1. Colocar a pasta do projeto em:

```text
C:\laragon\www\1230798
```

2. Abrir o Laragon e iniciar os servicos Apache e MySQL.

3. Importar a base de dados MySQL do projeto.

4. Confirmar os dados de ligacao no ficheiro:

```text
config/config.php
```

5. Aceder a aplicacao no browser:

```text
http://localhost/1230798/public/index.php
```

6. Aceder diretamente ao login:

```text
http://localhost/1230798/public/login.php
```

## Testes Principais

Para validar o funcionamento da aplicacao, devem ser testadas as seguintes operacoes:

- iniciar sessao com utilizador valido;
- testar acesso com perfis diferentes;
- inserir, editar e apagar equipamentos;
- impedir a eliminacao de equipamentos ou componentes associados;
- inserir, editar e apagar fornecedores;
- impedir a eliminacao de fornecedores associados;
- inserir, editar e apagar localizacoes;
- impedir localizacoes duplicadas;
- impedir a eliminacao de localizacoes associadas;
- inserir unidades de equipamento;
- alterar estado e localizacao de uma unidade;
- verificar o historico de alteracoes;
- associar fornecedores a unidades;
- anexar manuais e contratos;
- exportar dados para Excel com filtros aplicados;
- imprimir etiquetas respeitando os filtros;
- consultar indicadores no dashboard.

## Validacao e Seguranca

A aplicacao possui validacoes no cliente, com JavaScript, e no servidor, com PHP.

A ligacao a base de dados e feita com PDO e consultas preparadas.

A integridade dos dados e garantida atraves de chaves primarias, chaves estrangeiras e validacoes na logica da aplicacao.

Tambem existem mensagens de sucesso, erro e aviso para informar o utilizador sobre o resultado das operacoes.

## Observacoes

- Os ficheiros carregados pelo utilizador sao guardados na pasta `uploads`.
- Os caminhos dos ficheiros sao registados na base de dados.
- Caso o nome da base de dados, utilizador ou password sejam alterados, o ficheiro `config/config.php` deve ser atualizado.
- As credenciais de teste devem corresponder aos utilizadores existentes na tabela `users`.
