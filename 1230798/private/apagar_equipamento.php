<?php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/includes/funcoes.php';

redirect_if_not_logged();

$id = $_GET['id'] ?? '';

if (empty($id)) {
    header('Location: lista_equipamentos.php');
    exit;
}

try {
    $ligacao = new PDO(
        "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8",
        MYSQL_USERNAME,
        MYSQL_PASSWORD
    );

    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 1. Verificar se existem unidades associadas
    $stmt = $ligacao->prepare("
        SELECT COUNT(*) AS total
        FROM equipamentounidade
        WHERE idEquipamento = ?
    ");

    $stmt->execute([$id]);
    $resultado = $stmt->fetch(PDO::FETCH_OBJ);

    if ($resultado->total > 0) {
        definir_mensagem(
         'warning',
          'Não é possível apagar este equipamento porque existem unidades físicas associadas.'
             );

        header('Location: lista_equipamentos.php');
        exit;
    }

    // 2. Apagar associações de componentes
    $ligacao->beginTransaction();

    $stmt = $ligacao->prepare("
        DELETE FROM equipamentocomponentes
        WHERE idEquiPai = ? OR idEquiComp = ?
    ");
    $stmt->execute([$id, $id]);

    // 3. Apagar equipamento
    $stmt = $ligacao->prepare("
        DELETE FROM equipamentos
        WHERE id = ?
    ");
    $stmt->execute([$id]);

    $ligacao->commit();

    // $_SESSION['server_success'] = 'Equipamento apagado com sucesso.';
    definir_mensagem('success', 'Equipamento apagado com sucesso.');

    header('Location: lista_equipamentos.php');
    exit;

} catch (PDOException $e) {
    if (isset($ligacao) && $ligacao->inTransaction()) {
        $ligacao->rollBack();
    }

    $_SESSION['server_error'] = 'Erro ao apagar equipamento: ' . $e->getMessage();

    header('Location: lista_equipamentos.php');
    exit;
}