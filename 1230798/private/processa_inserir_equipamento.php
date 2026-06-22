<?php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/includes/funcoes.php';

redirect_if_not_logged();

$perfilAtual = strtolower($_SESSION['perfil'] ?? '');

if ($perfilAtual === 'tecnico') {
    definir_mensagem('warning', 'Não tem permissão para adicionar equipamentos.');
    header('Location: lista_equipamentos.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: inserir_equipamento.php');
    exit;
}

$descricao = trim($_POST['descricao'] ?? '');
$idTipo = $_POST['idTipo'] ?? '';
$idMarca = $_POST['idMarca'] ?? '';
$modelo = trim($_POST['modelo'] ?? '');
$anosGarantia = $_POST['anosGarantia'] ?? '';
$criticidade = $_POST['criticidade'] ?? '';
$componente = $_POST['componente'] ?? 0;
$idFabricante = $_POST['idFabricante'] ?? '';
$fornecedores = $_POST['fornecedores'] ?? [];
$tiposFornecedor = $_POST['tiposFornecedor'] ?? [];
$componentes = $_POST['componentes'] ?? [];

$erros = [];

if ($descricao === '') {
    $erros[] = 'Preencha a designação do equipamento.';
}


if ($idFabricante === '') {
    $erros[] = 'Selecione o fabricante.';
}

if ($idTipo === '') {
    $erros[] = 'Selecione o tipo/categoria.';
}

if ($idMarca === '') {
    $erros[] = 'Selecione a marca.';
}

if ($modelo === '') {
    $erros[] = 'Preencha o modelo.';
}

if ($anosGarantia === '') {
    $erros[] = 'Preencha os anos de garantia.';
}

if ($criticidade === '') {
    $erros[] = 'Selecione a criticidade.';
}

if (!empty($erros)) {
    $_SESSION['validation_errors'] = $erros;
    header('Location: inserir_equipamento.php');
    exit;
}



function guardar_manual($campo)
{
    if (empty($_FILES[$campo]['name'])) {
        return null;
    }

    $extensoesPermitidas = ['pdf', 'doc', 'docx'];
    $nomeOriginal = $_FILES[$campo]['name'];
    $extensao = strtolower(pathinfo($nomeOriginal, PATHINFO_EXTENSION));

    if (!in_array($extensao, $extensoesPermitidas, true)) {
        throw new Exception('Tipo de ficheiro inválido em ' . $campo . '.');
    }

    $pastaDestino = __DIR__ . '/../uploads/manuais/';

    if (!is_dir($pastaDestino)) {
        mkdir($pastaDestino, 0777, true);
    }

    $nomeFicheiro = $campo . '_' . uniqid() . '.' . $extensao;
    $caminhoServidor = $pastaDestino . $nomeFicheiro;
    $caminhoBD = '../uploads/manuais/' . $nomeFicheiro;

    move_uploaded_file($_FILES[$campo]['tmp_name'], $caminhoServidor);

    return $caminhoBD;
}




try {
    $ligacao = new PDO(
        "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8",
        MYSQL_USERNAME,
        MYSQL_PASSWORD
    );

    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $ligacao->beginTransaction();

    $componente = $_POST['componente'] ?? 0;
    $componente = ($componente == 1 || $componente === 'Sim') ? 1 : 0;
    $manualSer = guardar_manual('manualSer');
    $manualTec = guardar_manual('manualTec');
    $manualCon = guardar_manual('manualCon');


    $sqlEquipamento = "
        INSERT INTO equipamentos
        (
            descricao,
            idTipo,
            idfabricante,
            idMarca,
            modelo,
            anosGarantia,
            criticidade,
            componente,
            manualSer,
            manualTec,
            manualCon
        )
        VALUES (?, ?, ?, ?, ?, ?, ?,?,?,?,?)
    ";

    $stmtEquipamento = $ligacao->prepare($sqlEquipamento);

    $stmtEquipamento->execute([
        $descricao,
        $idTipo,
        $idMarca,
        $idFabricante,
        $modelo,
        $anosGarantia,
        $criticidade,
        ($componente ? "\x01" : "\x00"),
        $manualSer,
        $manualTec,
        $manualCon

    ]);

    $idEquipamento = $ligacao->lastInsertId();



    foreach ($componentes as $idComponente) {
        if (!empty($idComponente)) {
            $sqlComponente = "
                INSERT INTO equipamentocomponentes
                (
                    idEquiPai,
                    idEquiComp
                )
                VALUES (?, ?)
            ";

            $stmtComponente = $ligacao->prepare($sqlComponente);
            $stmtComponente->execute([
                $idEquipamento,
                $idComponente
            ]);
        }
    }

    $ligacao->commit();

    definir_mensagem('success', 'Equipamento inserido com sucesso.');

    header('Location: lista_equipamentos.php');
    exit;

} catch (PDOException $e) {
    if (isset($ligacao) && $ligacao->inTransaction()) {
        $ligacao->rollBack();
    }

    echo "Erro ao inserir equipamento: " . $e->getMessage();
}
