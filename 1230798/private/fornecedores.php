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

        <tbody id="tbodyFornecedores">

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

                  <button
                    type="button"
                    class="btn btn-sm btn-outline-danger btn-apagar"
                    data-url="apagar_fornecedor.php?id=<?= urlencode($fornecedor->id) ?>">
                    <i class="bi bi-trash"></i>
                  </button>
                </td>
              </tr>
            <?php endforeach; ?>

          <?php endif; ?>

        </tbody>
      </table>

      <nav id="paginacaoFornecedores" class="mt-3 paginacao-inventario-wrapper">
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
          Tem a certeza que deseja eliminar este fornecedor?
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

  <?php include __DIR__ . '/includes/modal_mensagem.php'; ?>

  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const tbody = document.getElementById("tbodyFornecedores");
      const paginacao = document.querySelector("#paginacaoFornecedores .pagination");

      if (!tbody || !paginacao) {
        return;
      }

      const linhasPorPagina = 10;
      let paginaAtual = 1;

      const filtros = {
        nome: document.getElementById("fNome"),
        nif: document.getElementById("fNIF"),
        email: document.getElementById("fEmail"),
        telefone: document.getElementById("fTelefone"),
        morada: document.getElementById("fMorada"),
        codPostal: document.getElementById("fCodPostal")
      };

      function textoFiltro(campo) {
        return (campo?.value || "").toLowerCase().trim();
      }

      function obterLinhasFiltradas() {
        const nome = textoFiltro(filtros.nome);
        const nif = textoFiltro(filtros.nif);
        const email = textoFiltro(filtros.email);
        const telefone = textoFiltro(filtros.telefone);
        const morada = textoFiltro(filtros.morada);
        const codPostal = textoFiltro(filtros.codPostal);

        return Array.from(tbody.querySelectorAll("tr")).filter(function (linha) {
          const colunas = linha.querySelectorAll("td");

          if (colunas.length < 6) {
            return false;
          }

          const linhaNome = colunas[0].textContent.toLowerCase();
          const linhaNif = colunas[1].textContent.toLowerCase();
          const linhaEmail = colunas[2].textContent.toLowerCase();
          const linhaTelefone = colunas[3].textContent.toLowerCase();
          const linhaMorada = colunas[4].textContent.toLowerCase();
          const linhaCodPostal = colunas[5].textContent.toLowerCase();

          return linhaNome.includes(nome)
            && linhaNif.includes(nif)
            && linhaEmail.includes(email)
            && linhaTelefone.includes(telefone)
            && linhaMorada.includes(morada)
            && linhaCodPostal.includes(codPostal);
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
    let urlApagarFornecedor = null;

    document.addEventListener("click", function(e) {

      // clicar no botão apagar
      const botaoApagar = e.target.closest(".btn-apagar");

      if (botaoApagar) {
        urlApagarFornecedor = botaoApagar.dataset.url;
        new bootstrap.Modal(document.getElementById("modalApagar")).show();
      }

      // confirmar apagar
      if (e.target.id === "confirmarApagar") {

        if (urlApagarFornecedor) {
          window.location.href = urlApagarFornecedor;
        }
      }

    });
  </script>

</body>

</html>
