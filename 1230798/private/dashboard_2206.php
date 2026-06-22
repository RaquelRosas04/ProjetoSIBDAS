<?php include 'includes/header_priv.php'; ?>
<? require_once __DIR__ . '/../../includes/db_connect.php'; ?>
<?php require_once __DIR__ . '/includes/funcoes.php';
redirect_if_not_logged();
?>

<div class="container py-4">

  <h2 class="mb-4">
    <i class="bi bi-speedometer2 me-2"></i>
    Dashboard
  </h2>

  <!-- 🔹 GRÁFICOS TOPO -->
  <div class="row mb-4">

    <!-- Estado -->
    <div class="col-md-5">
      <h6>Estado dos Equipamentos</h6>
      <canvas id="graficoEstado" height="200"></canvas>
      <div class="text-center mt-2">
        <strong>Total:</strong> 120 equipamentos
      </div>
    </div>

    <!-- Serviço -->
    <div class="col-md-7">
      <h6>Equipamentos por Serviço</h6>
      <canvas id="graficoServico" height="200"></canvas>
    </div>

  </div>

  <!-- 🔹 INDICADORES -->
  <div class="row g-3 mb-4">

    <div class="col-md-3">
      <div class="card text-center p-3 shadow-sm">
        <h6>Garantia Expirada</h6>
        <h3 class="text-danger">8</h3>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card text-center p-3 shadow-sm">
        <h6>Sem Documentação</h6>
        <h3 class="text-warning">5</h3>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card text-center p-3 shadow-sm">
        <h6>Garantia (30 dias)</h6>
        <h3 class="text-warning">6</h3>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card text-center p-3 shadow-sm">
        <h6>Criticidade Elevada</h6>
        <h3 class="text-danger">12</h3>
      </div>
    </div>

  </div>

  <!-- 🔹 TABELA -->
  <div class="mb-4">

    <h6>Equipamentos com Garantia Expirada</h6>

    <table class="table table-sm">
      <thead>
        <tr>
          <th>Código</th>
          <th>Nome</th>
          <th>Garantia</th>
        </tr>
      </thead>

      <tbody>
        <tr>
          <td>EQ001</td>
          <td>Ventilador</td>
          <td class="text-danger">Expirada</td>
        </tr>
      </tbody>
    </table>

  </div>

  <!-- 🔹 GRÁFICO FINAL -->
  <div class="mb-4" style="max-width: 500px;">
    <h6>Equipamentos por Categoria</h6>
    <canvas id="graficoCategoria" height="180"></canvas>
  </div>

</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
  const total = 120;

  // 🔹 ESTADO
  new Chart(document.getElementById('graficoEstado'), {
    type: 'doughnut',
    data: {
      labels: ['Ativo', 'Manutenção', 'Inativo'],
      datasets: [{
        data: [95, 15, 10],
        backgroundColor: ['#2556a0', '#4b93c0', '#6c757d']
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

  // 🔹 SERVIÇO
  new Chart(document.getElementById('graficoServico'), {
    type: 'bar',
    data: {
      labels: ['Cardiologia', 'Urgência', 'UCI'],
      datasets: [{
        label: 'Equipamentos',
        data: [30, 50, 40],
        backgroundColor: '#2556a0'
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false
    }
  });

  // 🔹 CATEGORIA
  new Chart(document.getElementById('graficoCategoria'), {
    type: 'pie',
    data: {
      labels: ['Suporte de Vida', 'Diagnóstico', 'Monitorização'],
      datasets: [{
        data: [40, 30, 50],
        backgroundColor: ['#2556a0', '#4b93c0',  '#6c757d']
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