
<?php include 'includes/header_priv.php'; ?>
<?require_once __DIR__ . '/../../includes/db_connect.php';?>
<?php require_once __DIR__ . '/includes/funcoes.php';
      redirect_if_not_logged();
?>

  <!-- CONTEÚDO -->
  <div class="container py-4" style="padding-top: 100px;">

    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2>
        <i class="bi bi-geo-alt me-2 text-primary"></i>
        Localizações
      </h2>

      <a href="inserir_localizacao.php" class="btn btn-primary">
        <i class="bi bi-plus"></i> Inserir Localização
      </a>
    </div>

    <!-- FILTROS -->
    <div class="card p-3 mb-4 shadow-sm">

      <div class="row g-2 align-items-center">

        <div class="col-md-3">
          <input type="text" id="fNome" class="form-control" placeholder="Edifício">
        </div>

        <div class="col-md-3">
          <input type="text" id="fNIF" class="form-control" placeholder="Serviço">
        </div>

        <div class="col-md-2">
          <input type="text" id="fEmail" class="form-control" placeholder="Andar">
        </div>

        <div class="col-md-2">
          <input type="text" id="fTelefone" class="form-control" placeholder="Sala">
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
            <th>Edifício</th>
            <th>Serviço</th>
            <th>Andar</th>
            <th>Sala</th>
            <th>Ações</th>
          </tr>
        </thead>

        <tbody>

          <tr>
            <td>Hospital Central</td>
            <td>Cardiologia</td>
            <td>2</td>
            <td>203</td>

            <td>
              <!-- EDITAR -->
              <a href="editar_localizacao.php?id=1" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-pencil"></i>
              </a>


              <!-- APAGAR -->
              <button class="btn btn-sm btn-outline-danger btn-apagar">
                <i class="bi bi-trash"></i>
              </button>
            </td>
          </tr>

        </tbody>

      </table>
    </div>

  </div>


   <!-- BOTAO APAGAR-->

  <div class="modal fade" id="modalApagar" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">

        <div class="modal-header">
          <h5 class="modal-title text-danger">
            <i class="bi bi-exclamation-triangle"></i> Confirmar
          </h5>
          <button class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          Tem a certeza que deseja apagar esta localização?
        </div>

        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal">
            Cancelar
          </button>

          <button class="btn btn-danger" id="confirmarApagar">
            Apagar
          </button>
        </div>

      </div>
    </div>
  </div>





  <div class="modal fade" id="modalSucesso" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">

        <div class="modal-header">
          <h5 class="modal-title text-success">
            <i class="bi bi-check-circle me-2"></i>
            Sucesso
          </h5>
        </div>

        <div class="modal-body">
          Equipamento inserido com sucesso!<br><br>
          Deseja inserir outro equipamento?
        </div>

        <div class="modal-footer">
          <button class="btn btn-outline-secondary" id="btnIrLista">
            Concluir
          </button>

          <button class="btn btn-primary" id="btnNovo">
            Inserir outro
          </button>
        </div>

      </div>
    </div>
  </div>



  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../js/1230798.js"></script>


      <script>

      let linhaLocalizacao = null;

      document.addEventListener("click", function (e) {

        // clicar no botão apagar
        if (e.target.closest(".btn-apagar")) {

          linhaLocalizacao = e.target.closest("tr");


          new bootstrap.Modal(document.getElementById("modalApagar")).show();
        }

        // confirmar apagar
        if (e.target.id === "confirmarApagar") {

          if (linhaLocalizacao) {
            linhaLocalizacao.remove();
          }

          bootstrap.Modal.getInstance(document.getElementById("modalApagar")).hide();
        }

      });

    </script>

</body>

</html>