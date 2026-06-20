<?php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/includes/funcoes.php';

redirect_if_not_logged();

$ligacao = new PDO(
    "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8",
    MYSQL_USERNAME,
    MYSQL_PASSWORD
);

$ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$sql = "
SELECT 
    e.descricao AS Nome,
    tipoequipamento.descricao AS Tipo,
    marca.descricao AS Marca,
    e.modelo AS Modelo,
    e.criticidade AS Criticidade,
    f.nome AS Fabricante
FROM equipamentos e
INNER JOIN marca ON e.idMarca = marca.id
LEFT JOIN fabricante f ON e.idFabricante = f.id
INNER JOIN tipoequipamento ON e.idTipo = tipoequipamento.id
ORDER BY e.id DESC
";

$stmt = $ligacao->query($sql);
$equipamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=equipamentos.csv');

$output = fopen('php://output', 'w');

fputcsv($output, ['Nome', 'Tipo', 'Marca', 'Modelo', 'Criticidade', 'Fabricante'], ';');

foreach ($equipamentos as $eq) {
    fputcsv($output, $eq, ';');
}

fclose($output);
exit;