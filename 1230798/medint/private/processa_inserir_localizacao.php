<?php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/includes/funcoes.php';

redirect_if_not_logged();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: inserir_localizacao.php');
    exit;
}

$idEdificio = $_POST['idEdificio'] ?? '';
$idServico = $_POST['idServico'] ?? '';
$andar = trim($_POST['andar'] ?? '');
$sala = trim($_POST['sala'] ?? '');

$erros = [];

if ($idEdificio === '') {
    $erros[] = 'Selecione o edificio.';
}

if ($idServico === '') {
    $erros[] = 'Selecione o servico.';
}

if ($andar === '') {
    $erros[] = 'Preencha o andar.';
}

if ($sala === '') {
    $erros[] = 'Preencha a sala.';
}

if (!empty($erros)) {
    $_SESSION['validation_errors'] = $erros;
    header('Location: inserir_localizacao.php');
    exit;
}

try {
    $ligacao = new PDO(
        "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8",
        MYSQL_USERNAME,
        MYSQL_PASSWORD
    );

    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmtVerifica = $ligacao->prepare("
        SELECT id
        FROM localizacao
        WHERE idEdificio = ?
          AND idServico = ?
          AND andar = ?
          AND sala = ?
        LIMIT 1
    ");

    $stmtVerifica->execute([
        $idEdificio,
        $idServico,
        $andar,
        $sala
    ]);

    if ($stmtVerifica->fetch(PDO::FETCH_OBJ)) {
        $_SESSION['validation_errors'] = ['Localização já existente.'];
        header('Location: inserir_localizacao.php');
        exit;
    }

    $sql = "
        INSERT INTO localizacao
        (
            idEdificio,
            idServico,
            andar,
            sala
        )
        VALUES (?, ?, ?, ?)
    ";

    $stmt = $ligacao->prepare($sql);
    $stmt->execute([
        $idEdificio,
        $idServico,
        $andar,
        $sala
    ]);

    definir_mensagem('success', 'Localização inserida com sucesso.');

    header('Location: localizacoes.php');
    exit;

} catch (PDOException $e) {
    $_SESSION['validation_errors'] = ['Erro ao inserir localização.'];
    header('Location: inserir_localizacao.php');
    exit;
}
