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
        $where[] = "localizacao.idServico LIKE :local";
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
            euni.codigo,
            euni.numSerie,
            e.descricao
        FROM equipamentos e
        INNER JOIN equipamentounidade euni
            ON e.id = euni.idEquipamento
        INNER JOIN marca
            ON e.idMarca = marca.id
        INNER JOIN localizacao
            ON euni.idLocalizacao = localizacao.id
    ";

    if (!empty($where)) {
        $sql .= " WHERE " . implode(" AND ", $where);
    }

    $sql .= " ORDER BY euni.codigo";

    $stmt = $ligacao->prepare($sql);
    $stmt->execute($params);

    $equipamentos = $stmt->fetchAll(PDO::FETCH_OBJ);

} catch (PDOException $e) {
    die('Erro ao carregar etiquetas.');
}

?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Etiquetas de Equipamentos</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: #f8f9fa;
            padding: 30px;
            font-family: Arial, sans-serif;
        }

        .barra {
            text-align: center;
            margin-bottom: 25px;
        }

        .folha {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            justify-content: center;
        }

        .etiqueta {
            width: 8cm;
            height: 4cm;
            background: white;
            border: 2px solid #000;
            padding: 10px;
            text-align: center;
            page-break-inside: avoid;
        }

        .titulo {
            font-size: 16px;
            font-weight: bold;
            border-bottom: 1px solid #000;
            padding-bottom: 4px;
            margin-bottom: 8px;
        }

        .codigo {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .equipamento {
            font-size: 14px;
            margin-bottom: 8px;
        }

        .serie {
            font-size: 13px;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .barra {
                display: none;
            }

            .folha {
                justify-content: flex-start;
                gap: 8px;
            }

            .etiqueta {
                margin: 0;
                break-inside: avoid;
            }
        }
    </style>
</head>

<body>

    <div class="barra">
        <button onclick="window.print()" class="btn btn-primary">
            <i class="bi bi-printer"></i> Imprimir Etiquetas
        </button>

        <button onclick="window.close()" class="btn btn-secondary">
            Fechar
        </button>
    </div>

    <?php if (count($equipamentos) == 0): ?>

        <div class="alert alert-warning text-center">
            Não existem equipamentos para imprimir.
        </div>

    <?php else: ?>

        <div class="folha">

            <?php foreach ($equipamentos as $equipamento): ?>

                <div class="etiqueta">

                    <div class="titulo">MEDINT</div>

                    <div class="codigo">
                        <?= htmlspecialchars($equipamento->codigo) ?>
                    </div>

                    <div class="equipamento">
                        <?= htmlspecialchars($equipamento->descricao) ?>
                    </div>

                    <div class="serie">
                        Nº Série: <?= htmlspecialchars($equipamento->numSerie) ?>
                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>
<script>
window.onload = function () {
    window.print();
};
</script>
</body>
</html>