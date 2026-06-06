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

      <form id="formLogin">

        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" id="email" class="form-control" placeholder="email@exemplo.com">
        </div>

        <div class="mb-3">
          <label class="form-label">Password</label>
          <input type="password" id="password" class="form-control" placeholder="********">
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