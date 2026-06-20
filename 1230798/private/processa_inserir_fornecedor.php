<?php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/includes/funcoes.php';

redirect_if_not_logged();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: inserir_fornecedor.php');
    exit;
}

$nome = trim($_POST['nome'] ?? '');
$nif = trim($_POST['nif'] ?? '');
$email = trim($_POST['email'] ?? '');
$telefone = trim($_POST['telefone'] ?? '');
$codPostal = trim($_POST['codPostal'] ?? '');
$morada = trim($_POST['morada'] ?? '');
$localidade = trim($_POST['localidade'] ?? '');
$www = trim($_POST['www'] ?? '');


$erros = [];

if ($nome === '') {
    $erros[] = 'Preencha o nome.';
}

if ($nif === '') {
    $erros[] = 'Preencha o NIF.';
}

if ($email === '') {
    $erros[] = 'Preencha o email.';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $erros[] = 'Email inválido.';
}

if ($telefone === '') {
    $erros[] = 'Preencha o telefone.';
}

if ($codPostal === '') {
    $erros[] = 'Preencha o código postal.';
}

if ($morada === '') {
    $erros[] = 'Preencha a morada.';
}


if (!empty($erros)) {
    $_SESSION['validation_errors'] = $erros;
    header('Location: inserir_fornecedor.php');
    exit;
}

try {
    $ligacao = new PDO(
        "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8",
        MYSQL_USERNAME,
        MYSQL_PASSWORD
    );

    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sqlVerifica = "
        SELECT id
        FROM fornecedores
        WHERE nif = ? OR email = ?
        LIMIT 1
    ";

    $stmtVerifica = $ligacao->prepare($sqlVerifica);
    $stmtVerifica->execute([$nif, $email]);

    if ($stmtVerifica->fetch(PDO::FETCH_OBJ)) {
        $_SESSION['validation_errors'] = ['Já existe um fornecedor com esse NIF ou email.'];
        header('Location: inserir_fornecedor.php');
        exit;
    }

    $sql = "
        INSERT INTO fornecedores
        (
            nome,
            morada, 
            localidade,
            codPostal,
            telefone,
            nif,
            www,
            email
        )
        VALUES (?, ?, ?, ?, ?, ?, ?,?)
    ";

    $stmt = $ligacao->prepare($sql);

    $stmt->execute([
        $nome,
        $morada,
        $localidade,
        $codPostal,
        $telefone,
        $nif,
        $www,
        $email
    ]);

    definir_mensagem('success', 'Fornecedor inserido com sucesso.');

    header('Location: fornecedores.php');
    exit;

} catch (PDOException $e) {
    echo "Erro ao inserir fornecedor: " . $e->getMessage();
}