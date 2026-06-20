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

  $sql = "
        SELECT euni.id, euni.codigo , e.descricao, marca.descricao AS marca, e.modelo, euni.numSerie, 
         CONCAT(edificios.nome,' - ',
            servicos.descricao,' - Andar ',
            localizacao.andar, ' - Sala ', localizacao.sala
        ) as localizacao, euni.estado, e.criticidade
        FROM equipamentos e
        INNER JOIN equipamentounidade euni
            ON e.id= euni.idequipamento
        INNER JOIN marca on e.idMarca=marca.id
        INNER JOIN localizacao ON euni.idlocalizacao=localizacao.id
        INNER JOIN edificios ON localizacao.idEdificio = edificios.id
        INNER JOIN servicos ON localizacao.idServico = servicos.id
    ";

  $stmt = $ligacao->query($sql);
  $equipamentos = $stmt->fetchAll(PDO::FETCH_OBJ);
  $erro = '';
} catch (PDOException $e) {
  $erro = 'Erro ao carregar equipamentos.';
  $equipamentos = [];
}

?>

<?php include 'includes/header_priv.php'; ?>




<!-- CONTEÚDO -->
<div class="container py-5" style="padding-top: 100px;">

  <!-- AQUI -->
  <div class="d-flex justify-content-between align-items-center mb-4">

    <h2 class="mb-0">
      <i class="bi bi-truck me-2 text-primary"></i>
      Lista de Equipamentos
    </h2>

    <a href="inserir_equipamento_unidade.php" class="btn btn-primary">
      <i class="bi bi-plus"></i> Inserir Unidade
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
          <option>Calibração</option>
          <option>Quarentena</option>
          <option>Abatido</option>
        </select>
      </div>

      <div class="col-md-1">
        <select id="fCriticidade" class="form-select">
          <option value="">Criticidade</option>
          <option>Baixa</option>
          <option>Média</option>
          <option>Alta</option>
          <option>Suporte de Vida</option>
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

        <?php if (!empty($erro)): ?>

          <tr>
            <td colspan="9" class="text-center text-danger">
              <?= htmlspecialchars($erro) ?>
            </td>
          </tr>

        <?php elseif (count($equipamentos) == 0): ?>

          <tr>
            <td colspan="9" class="text-center text-muted">
              Não existem equipamentos registados.
            </td>
          </tr>

        <?php else: ?>

          <?php foreach ($equipamentos as $equipamento): ?>
            <tr>
              <td><?= htmlspecialchars($equipamento->codigo) ?></td>
              <td><?= htmlspecialchars($equipamento->descricao) ?></td>
              <td><?= htmlspecialchars($equipamento->marca) ?></td>
              <td><?= htmlspecialchars($equipamento->modelo) ?></td>
              <td><?= htmlspecialchars($equipamento->numSerie) ?></td>
              <td><?= htmlspecialchars($equipamento->localizacao) ?></td>
              <td><?= htmlspecialchars($equipamento->estado) ?></td>
              <td><?= htmlspecialchars($equipamento->criticidade) ?></td>

              <td>
                <a href="detalhes_equipamento.php?id=<?= urlencode($equipamento->id) ?>"
                  class="btn btn-sm btn-outline-primary">
                  <i class="bi bi-eye"></i>
                </a>


              </td>
            </tr>
          <?php endforeach; ?>

        <?php endif; ?>

      </tbody>
    </table>



    <a href="etiquetas_equipamentos_unidade.php"
      id="btnImprimirEtiquetas"
      class="btn btn-outline-dark"
      target="_blank">
      <i class="bi bi-printer"></i> Imprimir Etiquetas
    </a>


    <a href="exportar_equipamentos_unidade.php"
      id="btnExportarExcel"
      class="btn btn-success">
      <i class="bi bi-file-earmark-excel"></i> Exportar Excel
    </a>
  </div>





</div>

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
        Tem a certeza que deseja eliminar esta unidade de equipamento?
      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">
          Cancelar
        </button>
        <button class="btn btn-danger" id="confirmarApagar">
          Eliminar
        </button>
      </div>

    </div>
  </div>
</div>





<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/1230798.js"></script>



</div>

</body>

</html>