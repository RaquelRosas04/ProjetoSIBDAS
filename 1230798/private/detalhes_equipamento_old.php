<?php include 'includes/header_priv.php'; ?>
<? require_once __DIR__ . '/../../includes/db_connect.php'; ?>
<?php require_once __DIR__ . '/includes/funcoes.php';
      redirect_if_not_logged();
?>

<div class="container py-4">
  <!-- Título -->
  <h2 class="mb-4">
    <i class="bi bi-cpu me-2 text-primary"></i>
    Detalhes do Equipamento
  </h2>

  <!-- Pesquisa -->
  <form id="formPesquisaEquipamento" class="row g-3 mb-4">
    <div class="col-md-8">
      <input type="text" id="inputBuscaCodigo" class="form-control" placeholder="Introduza o Código do Equipamento">
    </div>
    <div class="col-md-4">
      <button type="submit" class="btn btn-primary w-100">
        <i class="bi bi-search me-1"></i> Pesquisar
      </button>
    </div>
  </form>

  <!-- RESULTADO -->
  <div id="resultadoEquipamento" style="display: none;">

    <!-- Tabs -->
    <ul class="nav nav-tabs mb-3">

      <li class="nav-item">
        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#dados">
          Dados
        </button>
      </li>

      <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#consumivel">
          Acessórios e Consumíveis
        </button>
      </li>

      <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#historicoEquipamento">
          Histórico de Equipamentos
        </button>
      </li>

      <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#fornecedores">
          Fornecedores
        </button>
      </li>

      <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#contratos">
          Contratos
        </button>
      </li>

      <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#anexos">
          Anexos
        </button>
      </li>

    </ul>


    <div class="tab-content">

      <div class="tab-pane fade show active" id="dados">

        <div class="row">

          <div class="col-md-6">
            <p><strong>Código:</strong> <span id="txtCodigo">EQ001</span></p>
            <p><strong>Nome:</strong> <span id="txtNome">–</span></p>
            <p><strong>Modelo:</strong> <span id="txtModelo">–</span></p>
            <p><strong>Localização:</strong> <span id="txtLocalizacao">–</span></p>
            <p><strong>Criticidade:</strong> <span id="txtCriticidade">–</span></p>
            <p><strong>Fabricante:</strong> <span id="txtFabricante">Dräger</span></p>
            <p><strong>Data Aquisição:</strong> <span id="txtData">2022-03-10</span></p>
          </div>

          <div class="col-md-6">
            <p><strong>Categoria:</strong> <span id="txtCategoria">Suporte de Vida</span></p>
            <p><strong>Marca:</strong> <span id="txtMarca">–</span></p>
            <p><strong>Nº Série:</strong> <span id="txtNSerie">–</span></p>
            <p><strong>Estado:</strong> <span id="txtEstado">–</span></p>
            <p><strong>Ano:</strong> <span id="txtAno">2021</span></p>
            <p><strong>Tipo Entrada:</strong> <span id="txtEntrada">Compra</span></p>
          </div>

        </div>

        <div class="d-flex gap-2 mt-3">
          <a href="editar_equipamento.php" class="btn btn-primary mt-3">
            <i class="bi bi-pencil"></i> Editar
          </a>

          <button id="btnAbater" class="btn btn-outline-danger mt-3">
            <i class="bi bi-trash"></i> Abater
          </button>
        </div>

      </div>


      <!-- Consumiveis e acessorios -->
      <div class="tab-pane fade" id="consumivel">


        <h5>Acessórios</h5>

        <table class="table">
          <thead>
            <tr>
              <th>Nome</th>
              <th>Tipo</th>
              <th>Quantidade</th>
            </tr>
          </thead>

          <tbody>
            <tr>
              <td>Sonda ECG</td>
              <td>Acessório</td>
              <td>3</td>
            </tr>
          </tbody>
        </table>


        <h5>Consumíveis</h5>

        <table class="table">
          <thead>
            <tr>
              <th>Nome</th>
              <th>Tipo</th>
              <th>Quantidade</th>
            </tr>
          </thead>

          <tbody>
            <tr>
              <td>Sonda ECG</td>
              <td>Acessório</td>
              <td>3</td>
            </tr>
          </tbody>
        </table>



      </div>


      <!-- Historico do equipamento -->
      <div class="tab-pane fade" id="historicoEquipamento">

        <h5 class="mt-3 mb-2">Histórico do Equipamento</h5>


        <table class="table table-sm align-middle">

          <thead class="table">
            <tr>
              <th style="width: 120px;">Data</th>
              <th>Evento</th>
              <th>Descrição</th>
            </tr>
          </thead>

          <tbody>

            <tr>
              <td>2022</td>
              <td>Aquisição</td>
              <td>Equipamento adquirido via compra</td>
            </tr>

            <tr>
              <td>2023</td>
              <td>Mudança</td>
              <td>Transferido para Gabinete 3</td>
            </tr>

            <tr>
              <td>2024</td>
              <td>Manutenção</td>
              <td>Substituição de componentes internos</td>
            </tr>

            <tr>
              <td>2025</td>
              <td>Incidente</td>
              <td>Falha temporária registada</td>
            </tr>

          </tbody>

        </table>



      </div>

      <!-- Fornecedores -->

      <div class="tab-pane fade" id="fornecedores">

        <h5 class="mt-3 mb-3">Fornecedores Associados</h5>

        <table class="table table-sm align-middle">

  <thead class="table-light">
    <tr>
              <th>Nome</th>
              <th>NIF</th>
              <th>Tipo</th>
              <th>Email</th>
              <th>Telefone</th>
              <th>Contacto</th>
      <th style="width: 80px;"></th>
    </tr>
  </thead>

  <tbody id="listaFornecedores">

    <tr>
              <td>Dräger</td>
              <td>123456789</td>
              <td>Fabricante</span></td>
              <td>info@drager.com</td>
              <td>912345678</td>
              <td>João Silva</td>
      <td>
                </button>
                <button class="btn btn-sm btn-outline-danger btn-apagar">
                  <i class="bi bi-trash"></i>
                </button>
      </td>
    </tr>

  </tbody>

</table>


      </div>





      <!------Contratos-->

      <div class="tab-pane fade" id="contratos">

        <h5 class="mt-3">Documentos</h5>

        <!-- BOTÃO ANEXAR -->
        <div class="mb-3">
          <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalAnexo">
            <i class="bi bi-paperclip"></i> Anexar ficheiro
          </button>
        </div>

        <div class="table-responsive">
          <table class="table table-bordered table-sm">
            <thead class="table-custom">
              <tr>
                <th>Descrição</th> <!-- NOVO -->
                <th>Nome</th>
                <th>Tipo</th>
                <th>Ação</th>
              </tr>
            </thead>

            <tbody id="listaAnexos">
              <tr>
                <td>Manual Técnico</td>
                <td>manual.pdf</td>
                <td>PDF</td>
                <td><button class="btn btn-sm btn-outline-primary">Ver</button></td>
              </tr>

              <tr>
                <td>Garantia</td>
                <td>garantia.pdf</td>
                <td>PDF</td>
                <td><button class="btn btn-sm btn-outline-primary">Ver</button></td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="col-md-6">
          <label class="form-label fw-bold">Fim Garantia:</label>
          <p>2025-03-10</p>
        </div>

      </div>

      <!------ANEXOS-->

      <div class="tab-pane fade" id="anexos">

        <h5 class="mt-3">Anexos</h5>

        <div class="d-flex flex-wrap gap-3 mt-3">

          <a href="../assets/anexos/manual_servico.pdf" target="_blank" class="btn btn-anexo">
            <i class="bi bi-file-earmark-text"></i>
            Manual de Serviço
          </a>

          <a href="../assets/anexos/manual_utilizacao.pdf" target="_blank" class="btn btn-anexo">
            <i class="bi bi-file-earmark-text"></i>
            Manual de Utilização
          </a>

          <a href="../assets/anexos/consumiveis.pdf" target="_blank" class="btn btn-anexo">
            <i class="bi bi-box-seam"></i>
            Consumíveis
          </a>

        </div>

      </div>

      <!-- MODAL -->
      <div class="modal fade" id="modalAnexo">
        <div class="modal-dialog">
          <div class="modal-content">


            <div class="modal-header">
              <h5>Novo Anexo</h5>
            </div>

            <div class="modal-body">
              <input type="file" id="ficheiroAnexo" class="form-control mb-2">
              <input type="text" id="descricaoAnexo" class="form-control" placeholder="Descrição">
            </div>

            <div class="d-flex justify-content-center">
              <div id="msgErro" class="alert alert-danger d-none px-3 py-2 text-center" role="alert"
                style="max-width: 300px;">
                Preencha todos os campos
              </div>
            </div>

            <div class="modal-footer">
              <button class="btn btn-primary" id="btnGuardarAnexo">Upload</button>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>

</div>



<div class="modal fade" id="modalAbater" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title text-danger">
          <i class="bi bi-exclamation-triangle me-2"></i>
          Confirmar Abate
        </h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        Tem a certeza que deseja abater este equipamento?
      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">
          Cancelar
        </button>

        <button class="btn btn-danger" id="confirmarAbater">
          Abater
        </button>
      </div>

    </div>
  </div>
</div>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/1230798.js"></script>

</div>



<script>
  const params = new URLSearchParams(window.location.search);
  const id = params.get("id");

  if (id) {
    document.getElementById("txtCodigo").innerText = id;

    let equipamentos = {
      "EQ001": {
        nome: "Ventilador",
        marca: "Dräger",
        modelo: "V500"
      },
      "EQ002": {
        nome: "Monitor",
        marca: "Philips",
        modelo: "MX450"
      }
    };

    if (equipamentos[id]) {
      document.getElementById("txtNome").innerText = equipamentos[id].nome;
      document.getElementById("txtMarca").innerText = equipamentos[id].marca;
      document.getElementById("txtModelo").innerText = equipamentos[id].modelo;
    }
  }

  let anexos = [];

  document.addEventListener("click", function(e) {

    if (e.target.id === "btnGuardarAnexo") {

      let f = document.getElementById("ficheiroAnexo").files[0];
      let d = document.getElementById("descricaoAnexo").value;

      if (!f || d === "") {
        alert("Preenche tudo");
        return;
      }

      anexos.push({
        nome: f.name,
        tipo: f.name.split('.').pop(),
        desc: d
      });

      let tabela = document.getElementById("listaAnexos");
      tabela.innerHTML = "";

      anexos.forEach(a => {
        tabela.innerHTML += `
        <tr>
          <td>${a.desc}</td>
          <td>${a.nome}</td>
          <td>${a.tipo}</td>
        </tr>
      `;
      });

      bootstrap.Modal.getInstance(document.getElementById("modalAnexo")).hide();
    }

  });


  
/////////////ACHO QUE NAO E PRECISOO

        let linhaEquipamento = null;

      document.addEventListener("click", function (e) {

        // clicar no botão apagar
        if (e.target.closest(".btn-apagar")) {

          linhaEquipamento = e.target.closest("tr");


          new bootstrap.Modal(document.getElementById("modalApagar")).show();
        }

        // confirmar apagar
        if (e.target.id === "confirmarApagar") {

          if (linhaEquipamneto) {
            linhaEquipamento.remove();
          }

          bootstrap.Modal.getInstance(document.getElementById("modalApagar")).hide();
        }

      });
</script>


</body>

</html>