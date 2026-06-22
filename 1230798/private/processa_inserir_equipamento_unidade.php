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

    /*
     * Se algum dia permitires criar equipamento novo nesta página,
     * este bloco continua preparado para isso.
     */
    if (empty($idEquipamento)) {
        $componente = ($componente == 1 || $componente === 'Sim') ? 1 : 0;

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
            ($componente ? "\x01" : "\x00")
        ]);

        $idEquipamento = $ligacao->lastInsertId();
    }

    /*
     * Gerar código automático:
     * identcod + número sequencial com 5 dígitos
     * Exemplo: SP00001, SP00102
     */
    $stmtCodigo = $ligacao->prepare("
        SELECT te.identcod
        FROM equipamentos e
        INNER JOIN tipoequipamento te ON e.idTipo = te.id
        WHERE e.id = ?
    ");

    $stmtCodigo->execute([$idEquipamento]);
    $dadosCodigo = $stmtCodigo->fetch(PDO::FETCH_OBJ);

    if (!$dadosCodigo || empty($dadosCodigo->identcod)) {
        throw new Exception('O tipo de equipamento não tem código identificador definido.');
    }

    $prefixo = strtoupper($dadosCodigo->identcod);

    $stmtUltimoCodigo = $ligacao->prepare("
        SELECT Codigo
        FROM equipamentounidade
        WHERE Codigo LIKE ?
        ORDER BY Codigo DESC
        LIMIT 1
    ");

    $stmtUltimoCodigo->execute([$prefixo . '%']);
    $ultimoCodigo = $stmtUltimoCodigo->fetch(PDO::FETCH_OBJ);

    $numero = 1;

    if ($ultimoCodigo) {
        $numero = intval(substr($ultimoCodigo->Codigo, strlen($prefixo))) + 1;
    }

    $Codigo = $prefixo . str_pad($numero, 5, '0', STR_PAD_LEFT);

    /*
     * Verificar número de série duplicado.
     * O Código já é gerado automaticamente.
     */
        $sqlVerifica = "
            SELECT eu.id
            FROM equipamentounidade eu
            INNER JOIN equipamentos eExistente ON eu.idEquipamento = eExistente.id
            INNER JOIN equipamentos eNovo ON eNovo.id = ?
            WHERE eu.numSerie = ?
            AND eExistente.modelo = eNovo.modelo
            AND (
                    eExistente.idfabricante = eNovo.idfabricante
                    OR (
                        eExistente.idfabricante IS NULL
                        AND eNovo.idfabricante IS NULL
                    )
                )
            LIMIT 1
        ";

        $stmtVerifica = $ligacao->prepare($sqlVerifica);
        $stmtVerifica->execute([
            $idEquipamento,
            $numSerie
        ]);

        if ($stmtVerifica->fetch(PDO::FETCH_OBJ)) {
            $ligacao->rollBack();

            $_SESSION['validation_errors'] = [
                'Já existe uma unidade com esse número de série para o mesmo fabricante e modelo.'
            ];
            //Guarda os dados dos campos do form 
            $_SESSION['old'] = $_POST;
            header('Location: inserir_equipamento_unidade.php');
            exit;
        }

    /*
     * Inserir unidade.
     */
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

    $stmtCadastro = $ligacao->prepare("
    INSERT INTO equipamentocadastro
    (
        idequipamento,
        idlocalizacao,
        estado,
        data
    )
    VALUES (?, ?, ?, ?)
");

$stmtCadastro->execute([
    $idEquipamento,
    $idLocalizacao,
    $estado,
    $dataAquisicao
]);

    /*
     * Inserir fornecedores associados à unidade.
     */
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

    definir_mensagem(
    'success',
    'Unidade inserida com sucesso. Código: ' . $Codigo
);

    header('Location: lista_equipamentos_unidade.php');
    exit;

} catch (Exception $e) {
    if (isset($ligacao) && $ligacao->inTransaction()) {
        $ligacao->rollBack();
    }

    echo "Erro ao inserir unidade de equipamento: " . $e->getMessage();
}