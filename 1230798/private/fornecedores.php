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

  $fornecedores = $ligacao->query("
        SELECT id, nome, nif, email, telefone,morada, codPostal
        FROM fornecedores
        ORDER BY nome
    ")->fetchAll(PDO::FETCH_OBJ);

  $erro = '';
} catch (PDOException $e) {
  $erro = 'Erro ao carregar fornecedores.';
  $fornecedores = [];
}

include __DIR__ . '/includes/header_priv.php';

?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">


<body>

  <!-- CONTEÚDO -->
  <div class="container py-4">

    <!-- TÍTULO + BOTÃO -->
    <div class="d-flex justify-content-between align-items-center mb-4">

      <h2 class="mb-0">
        <i class="bi bi-truck me-2 text-primary"></i>
        Fornecedores
      </h2>

      <a href="inserir_fornecedor.php" class="btn btn-primary">
        <i class="bi bi-plus"></i> Inserir Fornecedor
      </a>

    </div>

    <!-- FILTROS -->
    <div class="card p-3 mb-4 shadow-sm">

      <div class="row g-2 align-items-center">

        <div class="col-md-1">
          <input type="text" id="fNome" class="form-control" placeholder="Nome">
        </div>

        <div class="col-md-2">
          <input type="text" id="fNIF" class="form-control" placeholder="NIF">
        </div>

        <div class="col-md-2">
          <input type="text" id="fEmail" class="form-control" placeholder="Email">
        </div>

        <div class="col-md-2">
          <input type="text" id="fTelefone" class="form-control" placeholder="Telefone">
        </div>

        <div class="col-md-1">
          <input type="text" id="fMorada" class="form-control" placeholder="Morada">
        </div>

        <div class="col-md-2">
          <input type="text" id="fCodPostal" class="form-control" placeholder="Código Postal">
        </div>



        <!-- BOTÕES -->
        <div class="col-md-1">
          <button class="btn btn-primary w-100" id="btnFiltrar">
            <i class="bi bi-search"></i>
          </button>
        </div>

        <div class="col-md-1">
          <button class="btn btn-outline-secondary w-100" id="btnLimpar">
            Limpar
          </button>
        </div>

      </div>

    </div>

    <!-- TABELA -->
    <div class="table-responsive">
      <table class="table table-bordered align-middle">
 
        <thead class="table-custom">
          <tr>
            <th>Nome</th>
            <th>NIF</th>
            <th>Email</th>
            <th>Telefone</th>
            <th>Morada</th>
            <th>Código Postal</th>
          </tr>
        </thead>

        <tbody>

          <?php if (!empty($erro)): ?>

            <tr>
              <td colspan="7" class="text-center text-danger">
                <?= htmlspecialchars($erro) ?>
              </td>
            </tr>

          <?php elseif (count($fornecedores) == 0): ?>

            <tr>
              <td colspan="7" class="text-center text-muted">
                Não existem fornecedores registados.
              </td>
            </tr>

          <?php else: ?>

            <?php foreach ($fornecedores as $fornecedor): ?>
              <tr>
                <td><?= htmlspecialchars($fornecedor->nome) ?></td>
                <td><?= htmlspecialchars($fornecedor->nif) ?></td>
                <td><?= htmlspecialchars($fornecedor->email ?? '') ?></td>
                <td><?= htmlspecialchars($fornecedor->telefone) ?></td>
                <td><?= htmlspecialchars($fornecedor->morada) ?></td>
                <td><?= htmlspecialchars($fornecedor->codPostal) ?></td>


                <td>
                  <a href="editar_fornecedor.php?id=<?= urlencode($fornecedor->id) ?>"
                    class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-pencil"></i>
                  </a>

                  <a href="apagar_fornecedor.php?id=<?= urlencode($fornecedor->id) ?>"
                    class="btn btn-sm btn-outline-danger"
                    onclick="return confirm('Tem a certeza que deseja eliminar este fornecedor?');">
                    <i class="bi bi-trash"></i>
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>

          <?php endif; ?>

        </tbody>
      </table>
    </div>

  </div>




  <div class="modal fade" id="modalApagar" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">

        <div class="modal-header">
          <h5 class="modal-title text-danger">
            <i class="bi bi-trash me-2"></i>
            Confirmar
          </h5>
        </div>

        <div class="modal-body">
          Tem a certeza que deseja eliminar este fornecedor?
        </div>

        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button class="btn btn-danger" id="confirmarApagar">
            Eliminar
          </button>


          </button>
        </div>

      </div>
    </div>
  </div>




  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../js/1230798.js"></script>


  <script>
    let linhaFornecedor = null;

    document.addEventListener("click", function(e) {

      // clicar no botão apagar
      if (e.target.closest(".btn-apagar")) {

        linhaFornecedor = e.target.closest("tr");


        new bootstrap.Modal(document.getElementById("modalApagar")).show();
      }

      // confirmar apagar
      if (e.target.id === "confirmarApagar") {

        if (linhaFornecedor) {
          linhaFornecedor.remove();
        }

        bootstrap.Modal.getInstance(document.getElementById("modalApagar")).hide();
      }

    });
  </script>

</body>

</html>