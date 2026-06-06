<?php include 'includes/header_priv.php'; ?>
<?require_once __DIR__ . '/../../includes/db_connect.php';?>

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
          <button class="nav-link" data-bs-toggle="tab" data-bs-target="#ot">
            OT
          </button>
        </li>

        <li class="nav-item">
          <button class="nav-link" data-bs-toggle="tab" data-bs-target="#historicoOT">
            Histórico OT
          </button>
        </li>

        <li class="nav-item">
          <button class="nav-link" data-bs-toggle="tab" data-bs-target="#fornecedores">
            Fornecedor
          </button>
        </li>

        <li class="nav-item">
          <button class="nav-link" data-bs-toggle="tab" data-bs-target="#anexos">
            Anexos
          </button>
        </li>



      </ul>


      <div class="tab-content">

        <!-- Dados -->
        <div class="tab-pane fade show active" id="dados">


          <div class="row mb-4">

            <div class="col-md-6">
              <label class="form-label fw-bold">Código Inventário:</label>
              <p id="txtCodigo">EQ001</p>
            </div>

            <div class="col-md-6">
              <label class="form-label fw-bold">Categoria:</label>
              <p id="txtCategoria">Suporte de Vida</p>
            </div>

          </div>

          <div class="row mb-4">
            <div class="col-md-6">
              <label class="form-label fw-bold">Nome:</label>
              <p id="txtNome">–</p>
            </div>

            <div class="col-md-6">
              <label class="form-label fw-bold">Marca:</label>
              <p id="txtMarca">–</p>
            </div>
          </div>

          <div class="row mb-4">
            <div class="col-md-6">
              <label class="form-label fw-bold">Modelo:</label>
              <p id="txtModelo">–</p>
            </div>

            <div class="col-md-6">
              <label class="form-label fw-bold">Nº Série:</label>
              <p id="txtNSerie">–</p>
            </div>
          </div>

          <div class="row mb-4">
            <div class="col-md-6">
              <label class="form-label fw-bold">Localização:</label>
              <p id="txtLocalizacao">–</p>
            </div>

            <div class="col-md-6">
              <label class="form-label fw-bold">Estado:</label>
              <p id="txtEstado">–</p>
            </div>
          </div>

          <div class="row mb-4">
            <div class="col-md-6">
              <label class="form-label fw-bold">Criticidade:</label>
              <p id="txtCriticidade">–</p>
            </div>
          </div>


          <div class="row mb-4">

            <div class="col-md-6">
              <label class="form-label fw-bold">Fabricante:</label>
              <p id="txtFabricante">Dräger</p>
            </div>

            <div class="col-md-6">
              <label class="form-label fw-bold">Ano Fabrico:</label>
              <p id="txtAno">2021</p>
            </div>

          </div>

          <div class="row mb-4">

            <div class="col-md-6">
              <label class="form-label fw-bold">Data Aquisição:</label>
              <p id="txtData">2022-03-10</p>
            </div>

            <div class="col-md-6">
              <label class="form-label fw-bold">Tipo de Entrada:</label>
              <p id="txtEntrada">Compra</p>
            </div>

          </div>


          <a href="editar_equipamento.php" class="btn btn-primary mb-4">
            <i class="bi bi-pencil"></i> Editar Dados
          </a>

        </div>



        <!-- Historico do equipamento -->
        <div class="tab-pane fade" id="historicoEquipamento">

          <h5 class="mt-3 mb-2">Histórico do Equipamento</h5>

          <table class="table table-bordered table-sm">
            <thead class="table-custom">
              <tr>
                <th>ID</th>
                <th>Tipo</th>
                <th>Data</th>
                <th>Técnico</th>
                <th>Resultado</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>OT0005</td>
                <td>Calibração</td>
                <td>2025-04-10</td>
                <td>Ana Costa</td>
                <td><span class="badge bg-success">Concluído</span></td>
              </tr>
            </tbody>
          </table>

        </div>


<!-- Fornecedores -->

        <div class="tab-pane fade" id="fornecedores">

          <h5 class="mt-3 mb-3">Fornecedores Associados</h5>

          <div class="row g-3">

            <!-- FORNECEDOR 1 -->
            <div class="col-md-6">
              <div class="card shadow-sm p-3">

                <h6 class="fw-bold mb-2">Dräger</h6>

                <p class="mb-1"><strong>Tipo:</strong>
                  <span class="class=" mb-1>Fabricante</span>
                </p>

                <p class="mb-1"><strong>NIF:</strong> 123456789</p>
                <p class="mb-1"><strong>Email:</strong> info@drager.com</p>
                <p class="mb-1"><strong>Telefone:</strong> 912345678</p>

                <p class="mb-1"><strong>Contacto:</strong> João Silva</p>
                <p class="mb-1"><strong>Tel. Contacto:</strong> 912000000</p>

                <p class="mb-0"><strong>Website:</strong> www.drager.com</p>

              </div>
            </div>


          </div>

        </div>

        <!------ANEXOS-->

        <div class="tab-pane fade" id="anexos">

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
        "EQ001": { nome: "Ventilador", marca: "Dräger", modelo: "V500" },
        "EQ002": { nome: "Monitor", marca: "Philips", modelo: "MX450" }
      };

      if (equipamentos[id]) {
        document.getElementById("txtNome").innerText = equipamentos[id].nome;
        document.getElementById("txtMarca").innerText = equipamentos[id].marca;
        document.getElementById("txtModelo").innerText = equipamentos[id].modelo;
      }
    }

    let anexos = [];

    document.addEventListener("click", function (e) {

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
  </script>


</body>

</html>