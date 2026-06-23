<?php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/includes/funcoes.php';

redirect_if_not_logged();

$totalEquipamentos = 0;
$garantiaExpirada = 0;
$semDocumentacao = 0;
$garantia30Dias = 0;
$criticidadeElevada = 0;
$equipamentosGarantiaExpirada = [];
$estadoLabels = [];
$estadoDados = [];
$servicoLabels = [];
$servicoDados = [];
$categoriaLabels = [];
$categoriaDados = [];
$erroDashboard = '';

try {
  $ligacao = new PDO(
    "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8",
    MYSQL_USERNAME,
    MYSQL_PASSWORD
  );

  $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $totalEquipamentos = (int) $ligacao->query("
    SELECT COUNT(*)
    FROM equipamentounidade
  ")->fetchColumn();

  $garantiaExpirada = (int) $ligacao->query("
    SELECT COUNT(*)
    FROM equipamentounidade
    WHERE dataFimGarantia IS NOT NULL
      AND dataFimGarantia < CURDATE()
  ")->fetchColumn();

  $garantia30Dias = (int) $ligacao->query("
    SELECT COUNT(*)
    FROM equipamentounidade
    WHERE dataFimGarantia IS NOT NULL
      AND dataFimGarantia BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY)
  ")->fetchColumn();

  $semDocumentacao = (int) $ligacao->query("
    SELECT COUNT(*)
    FROM equipamentos
    WHERE (manualSer IS NULL OR manualSer = '')
      AND (manualTec IS NULL OR manualTec = '')
      AND (manualCon IS NULL OR manualCon = '')
  ")->fetchColumn();

  $criticidadeElevada = (int) $ligacao->query("
    SELECT COUNT(*)
    FROM equipamentounidade eu
    INNER JOIN equipamentos e ON eu.idEquipamento = e.id
    WHERE e.criticidade IN ('Alta', 'Suporte de vida')
  ")->fetchColumn();

  $stmtEstado = $ligacao->query("
    SELECT estado, COUNT(*) AS total
    FROM equipamentounidade
    GROUP BY estado
    ORDER BY estado
  ");

  foreach ($stmtEstado->fetchAll(PDO::FETCH_OBJ) as $linha) {
    $estadoLabels[] = $linha->estado ?: 'Sem estado';
    $estadoDados[] = (int) $linha->total;
  }

  $stmtServico = $ligacao->query("
    SELECT s.descricao AS servico, COUNT(*) AS total
    FROM equipamentounidade eu
    INNER JOIN localizacao l ON eu.idLocalizacao = l.id
    INNER JOIN servicos s ON l.idServico = s.id
    GROUP BY s.id, s.descricao
    ORDER BY total DESC, s.descricao
  ");

  foreach ($stmtServico->fetchAll(PDO::FETCH_OBJ) as $linha) {
    $servicoLabels[] = $linha->servico;
    $servicoDados[] = (int) $linha->total;
  }

  $stmtCategoria = $ligacao->query("
    SELECT t.descricao AS categoria, COUNT(*) AS total
    FROM equipamentos e
    INNER JOIN tipoequipamento t ON e.idTipo = t.id
    GROUP BY t.id, t.descricao
    ORDER BY total DESC, t.descricao
  ");

  foreach ($stmtCategoria->fetchAll(PDO::FETCH_OBJ) as $linha) {
    $categoriaLabels[] = $linha->categoria;
    $categoriaDados[] = (int) $linha->total;
  }

  $stmtGarantiaExpirada = $ligacao->query("
    SELECT eu.codigo, e.descricao, eu.dataFimGarantia
    FROM equipamentounidade eu
    INNER JOIN equipamentos e ON eu.idEquipamento = e.id
    WHERE eu.dataFimGarantia IS NOT NULL
      AND eu.dataFimGarantia < CURDATE()
    ORDER BY eu.dataFimGarantia ASC
    LIMIT 10
  ");

  $equipamentosGarantiaExpirada = $stmtGarantiaExpirada->fetchAll(PDO::FETCH_OBJ);

} catch (PDOException $e) {
  $erroDashboard = 'Erro ao carregar dados do dashboard.';
}

function formatar_data_dashboard($data)
{
  if (empty($data)) {
    return '-';
  }

  return date('d/m/Y', strtotime($data));
}

include __DIR__ . '/includes/header_priv.php';

?>

<div class="container py-4">

  <h2 class="mb-4">
    <i class="bi bi-speedometer2 me-2"></i>
    Dashboard
  </h2>

  <?php if (!empty($erroDashboard)): ?>
    <div class="alert alert-danger">
      <?= htmlspecialchars($erroDashboard) ?>
    </div>
  <?php endif; ?>

  <div class="row mb-4">

    <div class="col-md-5">
      <h6>Estado dos Equipamentos</h6>
      <canvas id="graficoEstado" height="200"></canvas>
      <div class="text-center mt-2">
        <strong>Total:</strong> <?= $totalEquipamentos ?> equipamentos
      </div>
    </div>

    <div class="col-md-7">
      <h6>Equipamentos por Serviço</h6>
      <canvas id="graficoServico" height="200"></canvas>
    </div>

  </div>

  <div class="row g-3 mb-4">

    <div class="col-md-3">
      <div class="card text-center p-3 shadow-sm">
        <h6>Garantia Expirada</h6>
        <h3 class="text-danger"><?= $garantiaExpirada ?></h3>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card text-center p-3 shadow-sm">
        <h6>Sem Documentação</h6>
        <h3 class="text-warning"><?= $semDocumentacao ?></h3>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card text-center p-3 shadow-sm">
        <h6>Garantia (30 dias)</h6>
        <h3 class="text-warning"><?= $garantia30Dias ?></h3>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card text-center p-3 shadow-sm">
        <h6>Criticidade Elevada</h6>
        <h3 class="text-danger"><?= $criticidadeElevada ?></h3>
      </div>
    </div>

  </div>

  <div class="mb-4">

    <h6>Equipamentos com Garantia Expirada</h6>

    <table class="table table-sm">
      <thead>
        <tr>
          <th>Código</th>
          <th>Nome</th>
          <th>Fim Garantia</th>
        </tr>
      </thead>

      <tbody>
        <?php if (!empty($equipamentosGarantiaExpirada)): ?>
          <?php foreach ($equipamentosGarantiaExpirada as $equipamento): ?>
            <tr>
              <td><?= htmlspecialchars($equipamento->codigo) ?></td>
              <td><?= htmlspecialchars($equipamento->descricao) ?></td>
              <td class="text-danger"><?= formatar_data_dashboard($equipamento->dataFimGarantia) ?></td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="3" class="text-center text-muted">
              Sem equipamentos com garantia expirada.
            </td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>

  </div>

  <div class="mb-4" style="max-width: 500px;">
    <h6>Equipamentos por Categoria</h6>
    <canvas id="graficoCategoria" height="180"></canvas>
  </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
  const total = <?= json_encode($totalEquipamentos) ?>;
  const estadoLabels = <?= json_encode($estadoLabels, JSON_UNESCAPED_UNICODE) ?>;
  const estadoDados = <?= json_encode($estadoDados) ?>;
  const servicoLabels = <?= json_encode($servicoLabels, JSON_UNESCAPED_UNICODE) ?>;
  const servicoDados = <?= json_encode($servicoDados) ?>;
  const categoriaLabels = <?= json_encode($categoriaLabels, JSON_UNESCAPED_UNICODE) ?>;
  const categoriaDados = <?= json_encode($categoriaDados) ?>;

  new Chart(document.getElementById('graficoEstado'), {
    type: 'doughnut',
    data: {
      labels: estadoLabels,
      datasets: [{
        data: estadoDados,
        backgroundColor: ['#2556a0', '#4b93c0', '#6c757d', '#dc3545', '#198754']
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { position: 'bottom' }
      }
    },
    plugins: [{
      id: 'textoCentro',
      beforeDraw(chart) {
        const { width, height } = chart;
        const ctx = chart.ctx;

        ctx.restore();
        ctx.font = "bold 18px sans-serif";
        ctx.textAlign = "center";
        ctx.textBaseline = "middle";
        ctx.fillText(total, width / 2, height / 2);
        ctx.save();
      }
    }]
  });

  new Chart(document.getElementById('graficoServico'), {
    type: 'bar',
    data: {
      labels: servicoLabels,
      datasets: [{
        label: 'Equipamentos',
        data: servicoDados,
        backgroundColor: '#2556a0'
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false
    }
  });

  new Chart(document.getElementById('graficoCategoria'), {
    type: 'pie',
    data: {
      labels: categoriaLabels,
      datasets: [{
        data: categoriaDados,
        backgroundColor: ['#2556a0', '#4b93c0', '#6c757d', '#198754', '#dc3545', '#ffc107']
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false
    }
  });
</script>

<style>
  canvas {
    max-height: 220px;
  }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
