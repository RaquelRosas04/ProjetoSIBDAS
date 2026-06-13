MedInt – Sistema de Gestão de Equipamentos Hospitalares

Autor:
- Raquel Rosas | 1230798


 Descrição da Aplicação

A aplicação MedInt é um sistema web desenvolvido para a gestão de equipamentos médicos em ambiente hospitalar.

Permite o controlo completo do parque tecnológico, incluindo:
- registo de equipamentos;
- gestão de estados (ativo, manutenção, inativo);
- associação de fornecedores;
- gestão de localizações;
- controlo de documentação e anexos;
- acompanhamento do histórico dos equipamentos;
- visualização de indicadores através de dashboard.

O sistema foi desenvolvido com foco na organização, eficiência operacional e apoio à tomada de decisão técnica e administrativa.





Estrutura de Diretorias

/Projeto
│
├── /assets                → Área reservada (gestão do sistema)
│   ├── /images
│
├── /common                → Área pública
│   ├── db_connect.php
│
├── /config                → Autenticação
│   ├── config.php
│
├── /css                   → Estilos
│   ├── 1230798.css
│
├── /js                    → Scripts
│   ├── 1230798.js
│
├── /private                
│   ├── /includes        
│       ├──
|
├── /public                  
│   ├── 1230798.js
│
└── README.md            → Documentação do projeto




Intruções para intalação e execução da aplicação

Instruções para a realização dos principais testes da aplicação

Credenciais de acesso correspondentes aos perfis:
Gestor:
Admin:




<Ambiente de Desenvolvimento>
- Servidor Web & PHP: Laragon 
- Base de Dados: MySQL 8.x (Laragon)
- IDE / Editor: Visual Studio Code 



<Instalação e Arranque>

1. Coloque toda a pasta do projeto dentro de `C:\laragon\www\SIBDAS_2A1\`.
2. Abra o Laragon e certifique-se de que os serviços Apache e MySQL estão “Running”.
3. No browser, navegue para:  
     http://localhost/SIBDAS_2A1/public/index.html 
4. Se preferir, pode apontar diretamente para a página de login:  
     http://localhost/SIBDAS_2A1/public/login/login.php  




     <Credenciais de Acesso>

| Perfil         | Username         | Password    |
|----------------|------------------|-------------|
| Assistente     | rita.sousa@lab.pt  | assistente 123    |
| Técnico        | claudia.silva@laboratorio.pt     | claudia123    |
| Administrador  |  paula.ribeiro@admin.pt       | admin123    |
| Cliente        | antoniomaria1@gmail.com     | cli10    |
| Motorista      | david.rodrigues@transportador.pt     | David123   |




<Observações>
--------------
- Se alterar as credenciais ou o nome da base de dados, atualize também o `config.php`.
- Para testar diferentes perfis, use as credenciais acima; 
- Para manutenção e evolução, todas as configurações de BD e caminho estão centralizadas em `config.php`, facilitando alterações futuras.
