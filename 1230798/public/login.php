


<?php

session_start();

require_once __DIR__ . '/../config/config.php';

//echo MYSQL_HOST . '<br>';
//echo MYSQL_DATABASE . '<br>';
//echo MYSQL_USERNAME . '<br>';
//exit;

$validation_errors = $_SESSION['validation_errors'] ?? [];
unset($_SESSION['validation_errors']);

$server_error = $_SESSION['server_error'] ?? '';
unset($_SESSION['server_error']);

?>


<!DOCTYPE html>
<html lang="pt">

<head>
  <meta charset="UTF-8">
  <title>Login - MedInt</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/1230798.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../js/1230798.js"></script>
  <link rel="icon" href="../assets/images/aba.png" type="image/png">
</head>

<body class="login-body">

  <div class="login-container">

    <!-- ESQUERDA -->
    <div class="login-left">
      <div class="login-left-content">
        <h4>MedInt</h4>
        <p>Gestão inteligente de equipamentos médicos</p>
      </div>
    </div>

    <!-- DIREITA -->
    <div class="login-right">

      <div class="logo">Login</div>

      <!--comentado alteraçao Login -->
      <!--<form id="formLogin"> --> 
<!-- Valida dados errrados no login e aparece a mensagem de login novamente-->
          <?php if (!empty($validation_errors)): ?>
            <div class="alert alert-danger p-2 text-center">
              <?php foreach ($validation_errors as $erro): ?>
                <div><?= htmlspecialchars($erro) ?></div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>

          <?php if (!empty($server_error)): ?>
            <div class="alert alert-danger p-2 text-center">
              <?= htmlspecialchars($server_error) ?>
            </div>
          <?php endif; ?>




      <form id="formLogin" action="../private/processa_login.php" method="post">

        <div class="mb-3">
          <label class="form-label">Email</label>
         <input type="email" id="email" name="text_username" class="form-control" placeholder="email@exemplo.com">
        </div>

        <div class="mb-3">
          <label class="form-label">Password</label>
          <input type="password" id="password" name="text_password" class="form-control" placeholder="********">
        </div>

        <button class="btn btn-primary w-100 mb-3">
          Entrar
        </button>

        <div class="text-center">
          <small class="text-muted">Esqueceu-se da palavra-passe?</small>
        </div>

      </form>

    </div>

  </div>

  <!-- Script externo -->
  <script src="../js/1230798.js"></script>

  <div class="modal fade" id="modalErroLogin" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">

        <div class="modal-header">
          <h5 class="modal-title text-danger">
            <i class="bi bi-exclamation-triangle me-2"></i>
            Erro de Login
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          Credenciais inválidas!
        </div>

        <div class="modal-footer">
          <button class="btn btn-primary" data-bs-dismiss="modal">
            OK
          </button>
        </div>

      </div>
    </div>
  </div>


</body>

</html>