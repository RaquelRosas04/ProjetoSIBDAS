<?php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/includes/funcoes.php';

redirect_if_not_logged();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: inserir_equipamento_unidade.php');
    exit;
}

$idEquipamento = $_POST['idEquipamento'] ?? '';

$descricao = trim($_POST['descricao'] ?? '');
$idTipo = $_POST['idTipo'] ?? '';
$idMarca = $_POST['idMarca'] ?? '';
$modelo = trim($_POST['modelo'] ?? '');
$anosGarantia = $_POST['anosGarantia'] ?? null;
$criticidade = $_POST['criticidade'] ?? '';
$componente = $_POST['componente'] ?? 0;
$Codigo = trim($_POST['Codigo'] ?? '');
$idLocalizacao = $_POST['idLocalizacao'] ?? '';
$numSerie = trim($_POST['numSerie'] ?? '');
$estado = $_POST['estado'] ?? '';
$anoFabrico = $_POST['anoFabrico'] ?? '';
$dataAquisicao = $_POST['dataAquisicao'] ?? '';
$dataFimGarantia = $_POST['dataFimGarantia'] ?? '';
$tipoEntrada = $_POST['tipoEntrada'] ?? '';
$obs = trim($_POST['obs'] ?? '');

$fornecedoresAssociados = $_POST['fornecedoresAssociados'] ?? [];
$tiposFornecedoresAssociados = $_POST['tiposFornecedoresAssociados'] ?? [];

$erros = [];

if (empty($idEquipamento)) {
    if (empty($descricao)) {
        $erros[] = 'Preencha a descrição do novo equipamento.';
    }

    if (empty($idTipo)) {
        $erros[] = 'Selecione o tipo do novo equipamento.';
    }

    if (empty($idMarca)) {
        $erros[] = 'Selecione a marca do novo equipamento.';
    }

    if (empty($criticidade)) {
        $erros[] = 'Selecione a criticidade do novo equipamento.';
    }
}

if (empty($Codigo)) {
    $erros[] = 'Preencha o código da unidade.';
}

if (empty($numSerie)) {
    $erros[] = 'Preencha o número de série.';
}

if (empty($idLocalizacao)) {
    $erros[] = 'Selecione a localização.';
}


if (empty($estado)) {
    $erros[] = 'Selecione o estado.';
}

if (empty($anoFabrico)) {
    $erros[] = 'Preencha o ano de fabrico.';
}

if (empty($dataAquisicao)) {
    $erros[] = 'Preencha a data de aquisição.';
}

if (empty($dataFimGarantia)) {
    $erros[] = 'Preencha a data fim de garantia.';
}

if (empty($tipoEntrada)) {
    $erros[] = 'Selecione o tipo de entrada.';
}

if (!empty($dataAquisicao) && !empty($dataFimGarantia) && $dataFimGarantia <= $dataAquisicao) {
    $erros[] = 'A data fim de garantia deve ser posterior à data de aquisição.';
}

if (!empty($anoFabrico)) {
    $anoAtual = date('Y');

    if ($anoFabrico < 1900 || $anoFabrico > $anoAtual) {
        $erros[] = 'O ano de fabrico é inválido.';
    }
}

if (!empty($erros)) {
    session_start();
    $_SESSION['validation_errors'] = $erros;
    header('Location: inserir_equipamento_unidade.php');
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

    if (empty($idEquipamento)) {
        $sqlEquipamento = "
            INSERT INTO equipamentos
            (
                descricao,
                idTipo,
                idMarca,
                modelo,
                anosGarantia,
                criticidade,
                componente
            )
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ";

        $stmtEquipamento = $ligacao->prepare($sqlEquipamento);

        $stmtEquipamento->execute([
            $descricao,
            $idTipo,
            $idMarca,
            $modelo,
            $anosGarantia,
            $criticidade,
            $componente
        ]);

        $idEquipamento = $ligacao->lastInsertId();
        $idEquipamentoUni = $ligacao->lastInsertId();
    }

    $sqlVerifica = "
        SELECT id
        FROM equipamentounidade
        WHERE Codigo = ? OR numSerie = ?
        LIMIT 1
    ";

    $stmtVerifica = $ligacao->prepare($sqlVerifica);
    $stmtVerifica->execute([$Codigo, $numSerie]);

    if ($stmtVerifica->fetch(PDO::FETCH_OBJ)) {
        $ligacao->rollBack();

        session_start();
        $_SESSION['validation_errors'] = ['Já existe uma unidade com esse código ou número de série.'];
        header('Location: inserir_equipamento_unidade.php');
        exit;
    }


    $sqlUnidade = "
        INSERT INTO equipamentounidade
        (
            idEquipamento,
            Codigo,
            idLocalizacao,
            numSerie,
            estado,
            anoFabrico,
            dataAquisicao,
            dataFimGarantia,
            tipoEntrada,
            obs
        )
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ";
  

    $stmtUnidade = $ligacao->prepare($sqlUnidade);
    $stmtUnidade->execute([
        $idEquipamento,
        $Codigo,
        $idLocalizacao,
        $numSerie,
        $estado,
        $anoFabrico,
        $dataAquisicao,
        $dataFimGarantia,
        $tipoEntrada,
        $obs
    ]);

    $idEquipamentoUni = $ligacao->lastInsertId();


// echo '<pre>';
// echo 'ID unidade criada: ';
// var_dump($idEquipamentoUni);

// echo 'POST completo:';
// print_r($_POST);

// echo 'Fornecedores associados:';
// print_r($fornecedoresAssociados);

// echo 'Tipos fornecedores associados:';
// print_r($tiposFornecedoresAssociados);
// echo '</pre>';
// exit;

    foreach ($fornecedoresAssociados as $index => $idFornecedorAssociado) {

    $tipoFornecedor = $tiposFornecedoresAssociados[$index] ?? '';

    if (!empty($idFornecedorAssociado) && !empty($tipoFornecedor)) {

        $stmtFornecedor = $ligacao->prepare("
            INSERT INTO equipamentofornecedores
            (
                idEquipamentoUni,
                idFornecedor,
                TipoFornecedor
            )
            VALUES (?, ?, ?)
        ");

        $stmtFornecedor->execute([
            $idEquipamentoUni,
            $idFornecedorAssociado,
            $tipoFornecedor
        ]);
    }
}
    $ligacao->commit();
    header('Location: lista_equipamentos.php');
    exit;

} catch (PDOException $e) {
    if (isset($ligacao) && $ligacao->inTransaction()) {
        $ligacao->rollBack();
    }

    echo "Erro ao inserir unidade de equipamento: " . $e->getMessage();
}