<?php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/includes/funcoes.php';

redirect_if_not_logged();

$id = $_GET['id'] ?? '';

if (empty($id)) {
    header('Location: fornecedores.php');
    exit;
}

try {

    $ligacao = new PDO(
        "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8",
        MYSQL_USERNAME,
        MYSQL_PASSWORD
    );

    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verificar se existe
    $stmt = $ligacao->prepare("
        SELECT id
        FROM fornecedores
        WHERE id = ?
    ");

    $stmt->execute([$id]);

    if (!$stmt->fetch(PDO::FETCH_OBJ)) {
        header('Location: fornecedores.php');
        exit;
    }

    // Verificar se o fornecedor esta associado a equipamentos
    $stmt = $ligacao->prepare("
        SELECT COUNT(*) AS total
        FROM equipamentofornecedores
        WHERE idFornecedor = ?
    ");
    $stmt->execute([$id]);
    $associacoesEquipamento = $stmt->fetch(PDO::FETCH_OBJ);

    if ($associacoesEquipamento->total > 0) {
        definir_mensagem(
            'warning',
            'Não é possível apagar este fornecedor porque existem equipamentos associados.'
        );

        header('Location: fornecedores.php');
        exit;
    }

    // Apagar
    $stmt = $ligacao->prepare("
        DELETE FROM fornecedores
        WHERE id = ?
    ");

    $stmt->execute([$id]);

    definir_mensagem('success', 'Fornecedor apagado com sucesso.');

    header('Location: fornecedores.php');
    exit;

} catch (PDOException $e) {

    definir_mensagem('danger', 'Erro ao apagar fornecedor.');

    header('Location: fornecedores.php');
    exit;

}
