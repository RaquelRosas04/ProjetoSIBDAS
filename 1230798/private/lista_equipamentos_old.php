<?php include 'includes/header_priv.php'; ?>
<? require_once __DIR__ . '/../../includes/db_connect.php'; ?>
<?php require_once __DIR__ . '/includes/funcoes.php';
      redirect_if_not_logged();
?>




<!-- CONTEÚDO -->
<div class="container py-5" style="padding-top: 100px;">

  <!-- AQUI -->
  <div class="d-flex justify-content-between align-items-center mb-4">

    <h2 class="mb-0">
      <i class="bi bi-truck me-2 text-primary"></i>
      Lista de Equipamentos
    </h2>

    <a href="inserir_equipamento.php" class="btn btn-primary">
      <i class="bi bi-plus"></i> Inserir Equipamento
    </a>


  </div>


  <!-- FILTROS -->

  <div class="card p-3 mb-4 shadow-sm">
    <div class="row g-2">

      <div class="col-md-1">
        <input type="text" id="fCodigo" class="form-control" placeholder="Código">
      </div>

      <div class="col-md-2">
        <input type="text" id="fNome" class="form-control" placeholder="Nome">
      </div>

      <div class="col-md-1">
        <input type="text" id="fMarca" class="form-control" placeholder="Marca">
      </div>

      <div class="col-md-1">
        <input type="text" id="fModelo" class="form-control" placeholder="Modelo">
      </div>

      <div class="col-md-2">
        <input type="text" id="fSerie" class="form-control" placeholder="Nº Série">
      </div>

      <div class="col-md-1">
        <input type="text" id="fLocal" class="form-control" placeholder="Localização">
      </div>

      <div class="col-md-1">
        <select id="fEstado" class="form-select">
          <option value="">Estado</option>
          <option>Ativo</option>
          <option>Inativo</option>
          <option>Manutenção</option>
        </select>
      </div>

      <div class="col-md-1">
        <select id="fCriticidade" class="form-select">
          <option value="">Criticidade</option>
          <option>Baixo</option>
          <option>Médio</option>
          <option>Alto</option>
        </select>
      </div>

      <div class="col-md-1 d-grid">
        <button class="btn btn-primary" id="btnFiltrar">
          <i class="bi bi-search"></i>
        </button>
      </div>



      <div class="col-md-1 ">
        <button class="btn btn-outline-secondary w-100" id="btnLimpar">
          Limpar
        </button>
      </div>

    </div>
  </div>

  <div class="table-responsive">
    <table class="table table-bordered align-middle">
      <thead class="table-custom">
        <tr>
          <th>Código</th>
          <th>Nome</th>
          <th>Marca</th>
          <th>Modelo</th>
          <th>Nº Série</th>
          <th>Localização</th>
          <th>Estado</th>
          <th>Criticidade</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>

        <!-- EQUIPAMENTO-->
        <tr>
          <td>CD0034</td>
          <td>Aparelho de Sinais Vitais</td>
          <td>GE</td>
          <td>TSV</td>
          <td>1298748930WE</td>
          <td>Gabinete 3</td>
          <td>Ativo</td>
          <td>Alto</td>

          <td>

            <a href="detalhes_equipamento.php" class="btn btn-sm btn-outline-primary">
              <i class="bi bi-eye"></i>
            </a>


          </td>
        </tr>



      </tbody>
    </table>
  </div>





</div>






<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/1230798.js"></script>

</div>

</body>

</html>