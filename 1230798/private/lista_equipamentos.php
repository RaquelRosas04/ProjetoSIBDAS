<?php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/includes/funcoes.php';

redirect_if_not_logged();

$server_error = $_SESSION['server_error'] ?? '';
unset($_SESSION['server_error']);

$server_success = $_SESSION['server_success'] ?? '';
unset($_SESSION['server_success']);


try {
  $ligacao = new PDO(
    "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8",
    MYSQL_USERNAME,
    MYSQL_PASSWORD
  );


  $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $sql = "
    SELECT 
        e.id,
        e.descricao,
        tipoequipamento.descricao AS tipo,
        marca.descricao AS marca,
        e.modelo,
        e.criticidade,
        fabricante.nome AS fabricante
    FROM equipamentos e
    INNER JOIN marca  ON e.idMarca = marca.id
         left JOIN fabricante f ON e.idFabricante = f.id
    INNER JOIN tipoequipamento ON e.idTipo= tipoequipamento.id
    INNER JOIN fabricante ON e.idFabricante=fabricante.id
    ORDER BY e.id DESC
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

    <a href="inserir_equipamento.php" class="btn btn-primary">
      <i class="bi bi-plus"></i> Inserir Equipamento
    </a>


  </div>


  <!-- FILTROS -->
  <div class="card p-3 mb-4 shadow-sm">
    <div class="row g-2">

      <div class="col-md-2">
        <input type="text" id="fEqNome" class="form-control" placeholder="Nome">
      </div>

      <div class="col-md-2">
        <input type="text" id="fEqTipo" class="form-control" placeholder="Tipo">
      </div>

      <div class="col-md-2">
        <input type="text" id="fEqMarca" class="form-control" placeholder="Marca">
      </div>

      <div class="col-md-1">
        <input type="text" id="fEqModelo" class="form-control" placeholder="Modelo">
      </div>

      <div class="col-md-2">
        <select id="fEqCriticidade" class="form-select">
          <option value="">Criticidade</option>
          <option>Baixa</option>
          <option>Média</option>
          <option>Alta</option>
          <option>Suporte de Vida</option>
        </select>
      </div>

      <div class="col-md-1">
        <input type="text" id="fEqFabricante" class="form-control" placeholder="Fabricante">
      </div>

      <div class="col-md-1 d-grid">
        <button class="btn btn-primary" id="btnFiltrarEquipamentos">
          <i class="bi bi-search"></i>
        </button>
      </div>

      <div class="col-md-1 ">
        <button class="btn btn-outline-secondary w-100" id="btnLimparEquipamentos">
          Limpar
        </button>
      </div>

    </div>
  </div>


  <div class="table-responsive">
    <table class="table table-bordered align-middle">
      <thead class="table-custom">
        <tr>
          <th>Nome</th>
          <th>Tipo</th>
          <th>Marca</th>
          <th>Modelo</th>
          <th>Criticidade</th>
          <th>Fabricante</th>
          <th>Ações</th>
        </tr>
      </thead>

      <tbody id="tbodyEquipamentos">

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

              <td><?= htmlspecialchars($equipamento->descricao) ?></td>
              <td><?= htmlspecialchars($equipamento->tipo) ?></td>
              <td><?= htmlspecialchars($equipamento->marca) ?></td>
              <td><?= htmlspecialchars($equipamento->modelo) ?></td>
              <td><?= htmlspecialchars($equipamento->criticidade) ?></td>
              <td><?= htmlspecialchars($equipamento->fabricante) ?></td>

              <td>
                <a href="editar_equipamento.php?id=<?= urlencode($equipamento->id) ?>"
                  class="btn btn-sm btn-outline-primary">
                  <i class="bi bi-pencil"></i>
                </a>

                <button
                  type="button"
                  class="btn btn-sm btn-outline-danger btn-apagar"
                  data-url="apagar_equipamento.php?id=<?= urlencode($equipamento->id) ?>">
                  <i class="bi bi-trash"></i>
                </button>
              </td>

            </tr>
          <?php endforeach; ?>

        <?php endif; ?>

      </tbody>
    </table>


    <a href="exportar_equipamentos.php" class="btn btn-success" id="btnExportar">
      <i class="bi bi-file-earmark-excel"></i> Exportar Excel
    </a>

    <nav id="paginacaoEquipamentos" class="mt-3 paginacao-inventario-wrapper">
      <ul class="pagination pagination-sm justify-content-end paginacao-inventario"></ul>
    </nav>

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
        Tem a certeza que deseja eliminar este equipamento?
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
<script src="../js/1230798.js?v=3"></script>

<?php include __DIR__ . '/includes/modal_mensagem.php'; ?>

<script>
  let urlApagarEquipamento = null;

  document.addEventListener("click", function(e) {
    const botaoApagar = e.target.closest(".btn-apagar");

    if (botaoApagar) {
      urlApagarEquipamento = botaoApagar.dataset.url;
      new bootstrap.Modal(document.getElementById("modalApagar")).show();
    }

    if (e.target.id === "confirmarApagar") {
      if (urlApagarEquipamento) {
        window.location.href = urlApagarEquipamento;
      }
    }
  });
</script>
</div>

</body>

</html>
