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
        marca.descricao AS marca,
        e.modelo,
        e.criticidade
    FROM equipamentos e
    INNER JOIN marca  ON e.idMarca = marca.id
         left JOIN fabricante f ON e.idFabricante = f.id
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
          <th>Fabricante</th>
          <th>Marca</th>
          <th>Modelo</th>
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
      <td><?= htmlspecialchars($equipamento->id) ?></td>
      <td><?= htmlspecialchars($equipamento->descricao) ?></td>
      <td><?= htmlspecialchars($equipamento->fabricante ?? '') ?></td>
      <td><?= htmlspecialchars($equipamento->marca) ?></td>
      <td><?= htmlspecialchars($equipamento->modelo) ?></td>
      <td><?= htmlspecialchars($equipamento->criticidade) ?></td>

                <td>
                  <a href="editar_equipamento.php?id=<?= urlencode($equipamento->id) ?>"
                    class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-pencil"></i>
                  </a>

                  <a href="apagar_equipamento.php?id=<?= urlencode($equipamento->id) ?>"
                    class="btn btn-sm btn-outline-danger"
                    onclick="return confirm('Tem a certeza que deseja eliminar este equipamento?');">
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






<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/1230798.js"></script>

<?php include __DIR__ . '/includes/modal_mensagem.php'; ?>
</div>

</body>

</html>