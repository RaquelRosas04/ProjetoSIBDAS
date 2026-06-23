<?php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/includes/funcoes.php';

redirect_if_not_logged();

try {
    $ligacao = new PDO(
        "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8",
        MYSQL_USERNAME,
        MYSQL_PASSWORD
    );

    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $where = [];
    $params = [];

    if (!empty($_GET['fCodigo'])) {
        $where[] = "euni.codigo LIKE :codigo";
        $params[':codigo'] = '%' . $_GET['fCodigo'] . '%';
    }

    if (!empty($_GET['fNome'])) {
        $where[] = "e.descricao LIKE :nome";
        $params[':nome'] = '%' . $_GET['fNome'] . '%';
    }

    if (!empty($_GET['fMarca'])) {
        $where[] = "marca.descricao LIKE :marca";
        $params[':marca'] = '%' . $_GET['fMarca'] . '%';
    }

    if (!empty($_GET['fModelo'])) {
        $where[] = "e.modelo LIKE :modelo";
        $params[':modelo'] = '%' . $_GET['fModelo'] . '%';
    }

    if (!empty($_GET['fSerie'])) {
        $where[] = "euni.numSerie LIKE :serie";
        $params[':serie'] = '%' . $_GET['fSerie'] . '%';
    }

    if (!empty($_GET['fLocal'])) {
        $where[] = "
            CONCAT(edificios.nome, ' ', servicos.descricao, ' ', localizacao.andar, ' ', localizacao.sala)
            LIKE :local
        ";
        $params[':local'] = '%' . $_GET['fLocal'] . '%';
    }

    if (!empty($_GET['fEstado'])) {
        $where[] = "euni.estado LIKE :estado";
        $params[':estado'] = '%' . $_GET['fEstado'] . '%';
    }

    if (!empty($_GET['fCriticidade'])) {
        $where[] = "e.criticidade LIKE :criticidade";
        $params[':criticidade'] = '%' . $_GET['fCriticidade'] . '%';
    }

    $sql = "
        SELECT
            euni.codigo AS Codigo,
            e.descricao AS Nome,
            marca.descricao AS Marca,
            e.modelo AS Modelo,
            euni.numSerie AS NumeroSerie,
            CONCAT(
                edificios.nome,
                ' - ',
                servicos.descricao,
                ' - Andar ',
                localizacao.andar,
                ' - Sala ',
                localizacao.sala
            ) AS Localizacao,
            euni.estado AS Estado,
            e.criticidade AS Criticidade
        FROM equipamentos e
        INNER JOIN equipamentounidade euni
            ON e.id = euni.idEquipamento
        INNER JOIN marca
            ON e.idMarca = marca.id
        INNER JOIN localizacao
            ON euni.idLocalizacao = localizacao.id
        INNER JOIN edificios
            ON localizacao.idEdificio = edificios.id
        INNER JOIN servicos
            ON localizacao.idServico = servicos.id
    ";

    if (!empty($where)) {
        $sql .= " WHERE " . implode(" AND ", $where);
    }

    $sql .= " ORDER BY euni.codigo";

    $stmt = $ligacao->prepare($sql);
    $stmt->execute($params);

    $equipamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die('Erro ao exportar equipamentos.');
}

header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename=equipamentos_unidade.csv');

$output = fopen('php://output', 'w');

// Ajuda o Excel a reconhecer UTF-8 corretamente
fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

fputcsv($output, [
    'Código',
    'Nome',
    'Marca',
    'Modelo',
    'Número Série',
    'Localização',
    'Estado',
    'Criticidade'
], ';');

foreach ($equipamentos as $eq) {
    fputcsv($output, $eq, ';');
}

fclose($output);
exit;