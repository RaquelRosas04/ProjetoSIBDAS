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
        e.criticidade
    FROM equipamentos e
    INNER JOIN marca  ON e.idMarca = marca.id
         left JOIN fabricante f ON e.idFabricante = f.id
    INNER JOIN tipoequipamento ON e.idTipo= tipoequipamento.id
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
        <input type="text" id="fNome" class="form-control" placeholder="Nome">
      </div>

      <div class="col-md-2">
        <input type="text" id="fTipo" class="form-control" placeholder="Tipo">
      </div>

      <div class="col-md-2">
        <input type="text" id="fMarca" class="form-control" placeholder="Marca">
      </div>

      <div class="col-md-1">
        <input type="text" id="fModelo" class="form-control" placeholder="Modelo">
      </div>

      <div class="col-md-2">
        <select id="fCriticidade" class="form-select">
          <option value="">Criticidade</option>
          <option>Baixo</option>
          <option>Médio</option>
          <option>Alto</option>
        </select>
      </div>

      <div class="col-md-1">
        <input type="text" id="fFabricante" class="form-control" placeholder="Fabricante">
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
              <td><?= htmlspecialchars($equipamento->fabricante ?? '') ?></td>

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
<script src="../js/1230798.js?v=2"></script>

<?php include __DIR__ . '/includes/modal_mensagem.php'; ?>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    const tbody = document.getElementById("tbodyEquipamentos");
    const paginacao = document.querySelector("#paginacaoEquipamentos .pagination");

    if (!tbody || !paginacao) {
      return;
    }

    const linhasPorPagina = 10;
    let paginaAtual = 1;

    const filtros = {
      nome: document.getElementById("fNome"),
      tipo: document.getElementById("fTipo"),
      marca: document.getElementById("fMarca"),
      modelo: document.getElementById("fModelo"),
      criticidade: document.getElementById("fCriticidade"),
      fabricante: document.getElementById("fFabricante")
    };

    function textoFiltro(campo) {
      return (campo?.value || "").toLowerCase().trim();
    }

    function obterLinhasFiltradas() {
      const nome = textoFiltro(filtros.nome);
      const tipo = textoFiltro(filtros.tipo);
      const marca = textoFiltro(filtros.marca);
      const modelo = textoFiltro(filtros.modelo);
      const criticidade = textoFiltro(filtros.criticidade);
      const fabricante = textoFiltro(filtros.fabricante);

      return Array.from(tbody.querySelectorAll("tr")).filter(function (linha) {
        const colunas = linha.querySelectorAll("td");

        if (colunas.length < 6) {
          return false;
        }

        const linhaNome = colunas[0].textContent.toLowerCase();
        const linhaTipo = colunas[1].textContent.toLowerCase();
        const linhaMarca = colunas[2].textContent.toLowerCase();
        const linhaModelo = colunas[3].textContent.toLowerCase();
        const linhaCriticidade = colunas[4].textContent.toLowerCase().trim();
        const linhaFabricante = colunas[5].textContent.toLowerCase();

        return linhaNome.includes(nome)
          && linhaTipo.includes(tipo)
          && linhaMarca.includes(marca)
          && linhaModelo.includes(modelo)
          && (criticidade === "" || linhaCriticidade === criticidade)
          && linhaFabricante.includes(fabricante);
      });
    }

    function criarItem(texto, desativado, aoClicar) {
      const item = document.createElement("li");
      item.className = "page-item" + (desativado ? " disabled" : "");

      const botao = document.createElement("button");
      botao.type = "button";
      botao.className = "page-link";
      botao.innerHTML = texto;
      botao.disabled = desativado;

      botao.addEventListener("click", function () {
        if (!desativado) {
          aoClicar();
        }
      });

      item.appendChild(botao);
      paginacao.appendChild(item);
    }

    function renderizarPaginacao() {
      const todasLinhas = Array.from(tbody.querySelectorAll("tr"));
      const linhasDados = todasLinhas.filter(function (linha) {
        return linha.querySelectorAll("td").length >= 6;
      });

      if (linhasDados.length === 0) {
        todasLinhas.forEach(function (linha) {
          linha.style.display = "";
        });
        paginacao.innerHTML = "";
        return;
      }

      const linhasFiltradas = obterLinhasFiltradas();
      const totalPaginas = Math.ceil(linhasFiltradas.length / linhasPorPagina);

      if (paginaAtual > totalPaginas) {
        paginaAtual = 1;
      }

      todasLinhas.forEach(function (linha) {
        linha.style.display = "none";
      });

      const inicio = (paginaAtual - 1) * linhasPorPagina;
      const fim = inicio + linhasPorPagina;

      linhasFiltradas.slice(inicio, fim).forEach(function (linha) {
        linha.style.display = "";
      });

      paginacao.innerHTML = "";

      if (totalPaginas <= 1) {
        return;
      }

      criarItem("&laquo;", paginaAtual === 1, function () {
        paginaAtual--;
        renderizarPaginacao();
      });

      const indicador = document.createElement("li");
      indicador.className = "page-item disabled";
      indicador.innerHTML = '<span class="page-link">Página ' + paginaAtual + ' de ' + totalPaginas + '</span>';
      paginacao.appendChild(indicador);

      criarItem("&raquo;", paginaAtual === totalPaginas, function () {
        paginaAtual++;
        renderizarPaginacao();
      });
    }

    Object.values(filtros).forEach(function (campo) {
      if (!campo) {
        return;
      }

      campo.addEventListener("input", function () {
        paginaAtual = 1;
        renderizarPaginacao();
      });

      campo.addEventListener("change", function () {
        paginaAtual = 1;
        renderizarPaginacao();
      });
    });

    document.getElementById("btnFiltrar")?.addEventListener("click", function () {
      paginaAtual = 1;
      renderizarPaginacao();
    });

    document.getElementById("btnLimpar")?.addEventListener("click", function () {
      setTimeout(function () {
        paginaAtual = 1;
        renderizarPaginacao();
      }, 0);
    });

    renderizarPaginacao();
  });
</script>

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
