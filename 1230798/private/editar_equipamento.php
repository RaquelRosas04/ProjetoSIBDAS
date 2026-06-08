<?php require_once __DIR__ . '/includes/funcoes.php';
redirect_if_not_logged();
?>

<!DOCTYPE html>
<html lang="pt">

<head>
  <meta charset="UTF-8">
  <title>Editar Equipamento</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <!-- Estilos comuns -->

  <link rel="stylesheet" href="../css/1230798.css">
  <style>
    /* Para destacar o campo NIF como não editável */
    .bg-readonly {
      background-color: #e9ecef !important;
    }

    .form-label {
      font-weight: 500;
    }
  </style>
</head>


<body class="container py-5">

  <h2 class="mb-4">
    <i class="bi bi-pencil-square me-2"></i>
    Editar Detalhes do Equipamento
  </h2>


  <form id="formEditar">
    <!-- 1ª linha: Código, nº de série -->
    <div class="row mb-4">
      <div class="col-md-6">
        <label for="inputCodigo" class="form-label">Código de Inventário</label>
        <input type="text" id="inputCodigo" class="form-control bg-readonly" readonly>
      </div>
      <div class="col-md-6">
        <label for="inputNSerie" class="form-label">Nº Série</label>
        <input type="text" id="inputNSerie" class="form-control bg-readonly" readonly>
      </div>
    </div>

    <!-- 2ª linha: Nome,  Marca -->
    <div class="row mb-4">
      <div class="col-md-6">
        <label for="inputNome" class="form-label">Nome</label>
        <input type="text" id="inputNome" class="form-control bg-readonly" readonly>
      </div>
      <div class="col-md-6">
        <label for="inputMarca" class="form-label">Marca</label>
        <input type="text" id="inputMarca" class="form-control bg-readonly" readonly>
      </div>
    </div>


    <!-- 3ª linha: Localizaçao | Modelo -->
    <div class="row mb-4">
      <div class="col-md-6">
        <label for="inputLocalizacao" class="form-label">Localização</label>
        <input type="tel" id="inputLocalicao" class="form-control" placeholder="Ex: Gabinete Cardiologia">
      </div>
      <div class="col-md-6">
        <label for="inputModelo" class="form-label">Modelo</label>
        <input type="text" id="inputModelo" class="form-control" placeholder="VST">
      </div>
    </div>

    <!-- 4ª linha: estado, criticidade -->
    <div class="row mb-4">
      <div class="col-md-6">
        <label for="selectEstado" class="form-label">Estado</label>
        <select id="selectEstado" class="form-select" required>
          <option value="">-- Selecionar --</option>
          <option value="Ativo">Ativo</option>
          <option value="Manutencao">Em Manutenção</option>
          <option value="Calibracao">Em Calibração</option>
          <option value="Quarentena">Em Quarentena</option>
          <option value="Inativo">Inativo</option>
          <option value="Abatido">Abatido</option>
        </select>
      </div>
      <div class="col-md-6">
        <label for="inputCriticidade" class="form-label">Criticidade</label>
        <select id="selectCriticidade" class="form-select" required>
          <option value="">-- Selecionar --</option>
          <option value="Baixo">Baixo</option>
          <option value="Médio">Médio</option>
          <option value="Alto">Alto</option>
          <option value="SuporteVida">Suporte de Vida</option>
        </select>
      </div>
    </div>


    <!-- 5ª linha: Fornecedor | AnoF Fabrico -->
    <div class="row mb-4">
      <div class="col-md-6">
        <label for="inputFornecedor" class="form-label">Fornecedor</label>
        <input type="text" id="inputFornecedor" class="form-control">
      </div>
      <div class="col-md-6">
        <label for="inputAnoFabrico" class="form-label">Ano de Fabrico</label>
        <input type="text" id="inputAnoFabrico" class="form-control">
      </div>
    </div>

    <!-- 6ª linha: Data de aquisiçao | Data de garantia -->
    <div class="row mb-4">
      <div class="col-md-6">
        <label for="inputAquisicao" class="form-label">Data de Aquisição</label>
        <input type="date" id="inputAquisicao" class="form-control">
      </div>
      <div class="col-md-6">
        <label for="inputGarantia" class="form-label">Data de Garantia</label>
        <input type="date" id="inputGarantia" class="form-control">
      </div>
    </div>


    <div class="row g-3 align-items-end">

      <div class="col-md-6">
        <label class="form-label">Tipo de Entrada</label>
        <input type="text" class="form-control">
      </div>

      <div class="col-md-6 d-flex align-items-end">

        <input type="file" id="ficheiro" hidden>

        <label for="ficheiro" class="upload-btn">
          Anexar ficheiros
        </label>

      </div>

    </div>



    <!-- BOTÃO GUARDAR (SEPARADO E COM ESPAÇO) -->
    <div class="mt-4">
      <button class="btn btn-primary">
        <i class="bi bi-check2-square me-2"></i>
        Guardar Alterações
      </button>
    </div>


    <!-- Modal de Feedback ao salvar -->
    <div class="modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
          <div class="modal-body text-center">
            <i class="bi bi-check-circle-fill text-success fs-1 mb-3"></i>
            <h5 class="modal-title mb-2" id="feedbackModalLabel">Alterações Guardadas!</h5>
            <p class="mb-0">O seu agendamento foi atualizado com sucesso.</p>
          </div>
          <div class="modal-footer justify-content-center">
            <!-- Botão OK que redireciona -->
            <button type="button"
              class="btn btn-primary"
              data-bs-dismiss="modal"
              id="btnFecharFeedback">
              OK
            </button>
          </div>
        </div>
      </div>
    </div>




    <!-- Bootstrap JS + Day.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>



    <!-- Script externo -->
    <script src="../js/1230798.js"></script>
</body>

</html>