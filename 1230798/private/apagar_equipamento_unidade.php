<?php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/includes/funcoes.php';

redirect_if_not_logged();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: lista_equipamentos_unidade.php');
    exit;
}

$id = $_POST['id'] ?? '';
$motivo = trim($_POST['motivo'] ?? '');

if (empty($id)) {
    definir_mensagem('danger', 'Unidade de equipamento invalida.');
    header('Location: lista_equipamentos_unidade.php');
    exit;
}

if ($motivo === '') {
    definir_mensagem('warning', 'Indique a razão do abate.');
    header('Location: detalhes_equipamento.php?id=' . urlencode($id));
    exit;
}

try {
    $ligacao = new PDO(
        "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8",
        MYSQL_USERNAME,
        MYSQL_PASSWORD
    );

    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $ligacao->beginTransaction();

    $stmtUnidade = $ligacao->prepare("
        SELECT id, idEquipamento, idLocalizacao, obs
        FROM equipamentounidade
        WHERE id = ?
    ");
    $stmtUnidade->execute([$id]);
    $unidade = $stmtUnidade->fetch(PDO::FETCH_OBJ);

    if (!$unidade) {
        $ligacao->rollBack();
        definir_mensagem('danger', 'Unidade de equipamento nao encontrada.');
        header('Location: lista_equipamentos_unidade.php');
        exit;
    }

    $obsAtual = trim($unidade->obs ?? '');
    $obsAbate = 'Abatido em ' . date('d/m/Y') . '. Razão: ' . $motivo;
    $novaObs = $obsAtual !== '' ? $obsAtual . PHP_EOL . $obsAbate : $obsAbate;

    $stmtUpdate = $ligacao->prepare("
        UPDATE equipamentounidade
        SET estado = 'Abatido',
            obs = ?
        WHERE id = ?
    ");
    $stmtUpdate->execute([$novaObs, $id]);

    $stmtColunaObs = $ligacao->query("SHOW COLUMNS FROM equipamentocadastro LIKE 'obs'");
    $temObsCadastro = (bool) $stmtColunaObs->fetch(PDO::FETCH_OBJ);

    if ($temObsCadastro) {
        $stmtHistorico = $ligacao->prepare("
            INSERT INTO equipamentocadastro
                (idequipamento, idlocalizacao, estado, data, obs)
            VALUES
                (?, ?, 'Abatido', CURDATE(), ?)
        ");

        $stmtHistorico->execute([
            $unidade->idEquipamento,
            $unidade->idLocalizacao,
            $motivo
        ]);
    } else {
        $stmtHistorico = $ligacao->prepare("
            INSERT INTO equipamentocadastro
                (idequipamento, idlocalizacao, estado, data)
            VALUES
                (?, ?, 'Abatido', CURDATE())
        ");

        $stmtHistorico->execute([
            $unidade->idEquipamento,
            $unidade->idLocalizacao
        ]);
    }

    $ligacao->commit();

    definir_mensagem('success', 'Unidade abatida com sucesso.');
    header('Location: lista_equipamentos_unidade.php');
    exit;

} catch (PDOException $e) {
    if (isset($ligacao) && $ligacao->inTransaction()) {
        $ligacao->rollBack();
    }

    definir_mensagem('danger', 'Erro ao abater unidade: ' . $e->getMessage());
    header('Location: detalhes_equipamento.php?id=' . urlencode($id));
    exit;
}
