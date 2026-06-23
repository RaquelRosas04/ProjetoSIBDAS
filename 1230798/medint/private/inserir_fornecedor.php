<?php

require_once __DIR__ . '/includes/funcoes.php';

redirect_if_not_logged();

$validation_errors = $_SESSION['validation_errors'] ?? [];
unset($_SESSION['validation_errors']);

?>

<!DOCTYPE html>
<html lang="pt">

<head>
  <meta charset="UTF-8">
  <title>Inserir Fornecedor</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/1230798.css">
  <link rel="icon" href="../assets/images/aba.png" type="image/png">
</head>

<body>

  <nav class="navbar navbar-expand-lg navbar-dark fixed-top custom-navbar">
    <div class="container-fluid px-3">
      <a href="dashboard.php" class="navbar-brand">
        <img src="../assets/images/logo.png" height="45">
      </a>
    </div>
  </nav>

  <div class="container py-4" style="padding-top: 100px;">

    <h2 class="mb-4">
      <i class="bi bi-truck me-2 text-primary"></i>
      Inserir Fornecedor
    </h2>

    <div class="card p-4 shadow-sm">

      <?php if (!empty($validation_errors)): ?>
        <div class="alert alert-danger">
          <?php foreach ($validation_errors as $erro): ?>
            <div><?= htmlspecialchars($erro) ?></div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <form id="formFornecedor"
        method="post"
        action="processa_inserir_fornecedor.php">

        <div class="row g-3">

          <div class="col-md-6">
            <label class="form-label">Nome*</label>
            <input type="text" class="form-control" id="nome" name="nome" required>
          </div>

          <div class="col-md-6">
            <label class="form-label">NIF*</label>
            <input type="text" class="form-control" id="nif" name="nif" required>
          </div>

          <div class="col-md-6">
            <label class="form-label">Morada*</label>
            <input type="text" class="form-control" id="morada" name="morada" required>
          </div>

          <div class="col-md-6">
            <label class="form-label">Localidade</label>
            <input type="text" class="form-control" id="localidade" name="localidade" >
          </div>

          <div class="col-md-6">
            <label class="form-label">Código postal*</label>
            <input type="text" class="form-control" id="codPostal" name="codPostal" required>
          </div>


          <div class="col-md-6">
            <label class="form-label">Telefone*</label>
            <input type="text" class="form-control" id="telefone" name="telefone" required>
          </div>


          <div class="col-md-6">
            <label class="form-label">Email*</label>
            <input type="email" class="form-control" id="email" name="email" required>
          </div>

          <div class="col-md-6">
            <label class="form-label">WebSite</label>
            <input type="email" class="form-control" id="www" name="www">
          </div>


          <div class="mt-4 d-flex justify-content-between">
            <a href="fornecedores.php" class="btn btn-outline-secondary">
              <i class="bi bi-arrow-left"></i>
              Voltar
            </a>

            <button type="submit" class="btn btn-primary">
              <i class="bi bi-plus-circle me-1"></i>
              Inserir Fornecedor
            </button>
          </div>

      </form>

    </div>
  </div>

  <script src="../js/1230798.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>