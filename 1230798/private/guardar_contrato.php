<?php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/includes/funcoes.php';

redirect_if_not_logged();

$idEquipamentoUni = $_POST['idEquipamentoUni'] ?? '';
$dataInicio = $_POST['dataInicio'] ?? '';
$dataFim = $_POST['dataFim'] ?? null;
$obs = trim($_POST['obs'] ?? '');

if (empty($idEquipamentoUni) || empty($dataInicio) || empty($_FILES['ficheiro']['name'])) {
    definir_mensagem('danger', 'Preencha os campos obrigatórios.');
    header('Location: detalhes_equipamento.php?id=' . urlencode($idEquipamentoUni));
    exit;
}

if (!empty($dataFim) && $dataFim < $dataInicio) {
    definir_mensagem('danger', 'A data fim deve ser posterior à data início.');
    header('Location: detalhes_equipamento.php?id=' . urlencode($idEquipamentoUni));
    exit;
}

try {
    $ligacao = new PDO(
        "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8",
        MYSQL_USERNAME,
        MYSQL_PASSWORD
    );

    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pastaDestino = __DIR__ . '/../uploads/contratos/';

    if (!is_dir($pastaDestino)) {
        mkdir($pastaDestino, 0777, true);
    }

    $nomeOriginal = $_FILES['ficheiro']['name'];
    $extensao = pathinfo($nomeOriginal, PATHINFO_EXTENSION);
    $nomeFicheiro = uniqid('contrato_', true) . '.' . $extensao;

    $caminhoServidor = $pastaDestino . $nomeFicheiro;
    $caminhoBD = '../uploads/contratos/' . $nomeFicheiro;

    move_uploaded_file($_FILES['ficheiro']['tmp_name'], $caminhoServidor);

    $stmt = $ligacao->prepare("
        INSERT INTO equipamentocontratos
            (idEquipamentoUni, dataInicio, dataFim, obs, caminho, ficheiro)
        VALUES
            (?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $idEquipamentoUni,
        $dataInicio,
        !empty($dataFim) ? $dataFim : null,
        $obs,
        $caminhoBD,
        $nomeOriginal
    ]);

    definir_mensagem('success', 'Contrato guardado com sucesso.');

} catch (PDOException $e) {
    definir_mensagem('danger', 'Erro ao guardar contrato: ' . $e->getMessage());
}

header('Location: detalhes_equipamento.php?id=' . urlencode($idEquipamentoUni));
exit;