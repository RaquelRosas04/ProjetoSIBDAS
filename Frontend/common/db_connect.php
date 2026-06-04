<?php
// Caminho: includes/db_connect.php

// Inclui as credenciais de configuração da base de dados
require_once __DIR__ . 'config.php';

// Cria a ligação ao MySQL
$conn = new mysqli(MYSQL_HOST, MYSQL_USERNAME, MYSQL_PASSWORD, MYSQL_DATABASE);

// Verifica se a ligação falhou
if ($conn->connect_error) {
    die("Erro de ligação à base de dados: " . $conn->connect_error);
}
?>
