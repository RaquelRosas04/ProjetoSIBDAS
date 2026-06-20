<?php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/includes/funcoes.php';

redirect_if_not_logged();

$id = $_GET['id'] ?? '';

if (empty($id)) {
    header('Location: localizacoes.php');
    exit;
}

try {
    $ligacao = new PDO(
        "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8",
        MYSQL_USERNAME,
        MYSQL_PASSWORD
    );

    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $ligacao->prepare("
        SELECT id
        FROM localizacao
        WHERE id = ?
    ");
    $stmt->execute([$id]);

    if (!$stmt->fetch(PDO::FETCH_OBJ)) {
        header('Location: localizacoes.php');
        exit;
    }

    $stmt = $ligacao->prepare("
        SELECT COUNT(*) AS total
        FROM equipamentounidade
        WHERE idLocalizacao = ?
    ");
    $stmt->execute([$id]);
    $associacoes = $stmt->fetch(PDO::FETCH_OBJ);

    if ($associacoes->total > 0) {
        definir_mensagem(
            'warning',
            'Nao e possivel apagar esta localizacao porque existem equipamentos associados.'
        );

        header('Location: localizacoes.php');
        exit;
    }

    $stmt = $ligacao->prepare("
        DELETE FROM localizacao
        WHERE id = ?
    ");
    $stmt->execute([$id]);

    definir_mensagem('success', 'Localizacao apagada com sucesso.');

    header('Location: localizacoes.php');
    exit;

} catch (PDOException $e) {
    definir_mensagem('danger', 'Erro ao apagar localizacao.');

    header('Location: localizacoes.php');
    exit;
}
