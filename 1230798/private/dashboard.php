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

  <!-- KPIs -->
  <div class="row g-3 mb-4">

    <div class="col-md-3">
      <div class="card text-center p-3 shadow-sm">
        <h6>Total Equipamentos</h6>
        <h3 class="text-primary">120</h3>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card text-center p-3 shadow-sm">
        <h6>Ativos</h6>
        <h3 class="text-success">95</h3>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card text-center p-3 shadow-sm">
        <h6>Manutenção</h6>
        <h3 class="text-warning">15</h3>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card text-center p-3 shadow-sm">
        <h6>Inativos</h6>
        <h3 class="text-danger">10</h3>
      </div>
    </div>

  </div>