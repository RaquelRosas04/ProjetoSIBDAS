<?php require_once __DIR__ . '/includes/funcoes.php';
      redirect_if_not_logged();
?>

<!DOCTYPE html>
<html lang="pt">

<head>
  <meta charset="UTF-8">
  <title>Inserir Fornecedor</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/1230798.css">
</head>

<body>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark fixed-top custom-navbar">
    <div class="container-fluid px-3">
      <a href="dashboard.php" class="navbar-brand">
        <img src="../assets/images/logo.png" height="45">
      </a>
    </div>
  </nav>


  <div class="container py-4" style="padding-top: 100px;">

    <h2 class="mb-4">
      <i class="bi bi-truck me-2 text-primary">
      </i> Inserir Fornecedor
    </h2>

    <div class="card p-4 shadow-sm">

      <!-- MENSAGEM ERRO -->
      <div class="d-flex justify-content-center mb-3">
        <div id="msgErro" class="alert alert-danger d-none px-3 py-2 small text-center" style="max-width: 300px;">
          Preencha todos os campos
        </div>
      </div>

      <form id="formFornecedor">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Nome</label>
            <input type="text" class="form-control" id="nome" required>
          </div>

          <div class="col-md-6">
            <label class="form-label">NIF</label>
            <input type="text" class="form-control" id="nif">
          </div>

          <div class="col-md-6">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" id="email">
          </div>

          <div class="col-md-6">
            <label class="form-label">Telefone</label>
            <input type="text" class="form-control" id="telefone">
          </div>

          <div class="col-md-6">
            <label class="form-label">Código Postal</label>
            <input type="text" class="form-control" id="codPostal">
          </div>

          <div class="col-md-6">
            <label class="form-label">Morada</label>
            <input type="text" class="form-control" id="morada">
          </div>

          <div class="col-md-6">
            <label class="form-label">Tipo de Fornecedor</label>
            <select class="form-select" id="tipo">
              <option>Fabricante</option>
              <option>Distribuidor</option>
              <option>Assistência Técnica</option>
              <option>Consumíveis</option>
            </select>
          </div>

          <div class="col-12"> <label class="form-label">Observações</label> <textarea class="form-control" rows="3"
              id="obs"></textarea>
          </div>

        </div>

        <!-- BOTÕES -->
        <div class="mt-4 d-flex justify-content-between"> <a href="fornecedores.php" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Voltar </a> <button type="submit" class="btn btn-primary"> <i
              class="bi bi-plus-circle me-1"></i> Inserir Fornecedor </button>
        </div>

      </form>
    </div>
  </div>



  <div id="msgErro" class="toast-custom"></div>

<!--  MODAL SUCESSO -->
  <div class="modal fade" id="modalSucesso" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-success">
            <i class="bi bi-check-circle me-2"> </i>
            Sucesso
          </h5>
        </div>

        <div class="modal-body"> Fornecedor inserido com sucesso!<br>
          <br> Deseja inserir outro fornecedor?
        </div>

        <div class="modal-footer">
          <button class="btn btn-outline-secondary" id="btnIrLista"> Não, voltar à lista</button>
          <button class="btn btn-primary" id="btnNovoFornecedor"> Sim, inserir outro </button>
        </div>

      </div>
    </div>
  </div> 

<script src="../js/1230798.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>

    let nome = document.getElementById("nome").value.trim();
    let nif = document.getElementById("nif").value.trim();
    let email = document.getElementById("email").value.trim();
    let telefone = document.getElementById("telefone").value.trim();
    let codPostal = document.getElementById("codPostal").value.trim();
    let morada = document.getElementById("morada").value.trim();

    let msg = "";

    // VALIDAÇÃO CORRETA (ORDEM IMPORTA)
    if (nome === "") {
      msg = "Preencha o nome";
    }
    else if (nif === "") {
      msg = "Preencha o NIF";
    }
    else if (email === "") {
      msg = "Preencha o email";
    }
    else if (!email.includes("@") || !email.includes(".")) {
      msg = "Email inválido";
    }
    else if (telefone === "") {
      msg = "Preencha o telefone";
    }
    else if (codPostal === "") {
      msg = "Preencha o código postal";
    }
    else if (morada === "") {
      msg = "Preencha a morada";
    }


    // MOSTRAR TOAST
    if (msg !== "") {
      let toast = document.getElementById("msgErro");

      if (!toast) {
        console.log("Toast não existe no HTML"); // DEBUG
        return;
      }

      toast.innerText = msg;
      toast.classList.add("toast-show");

      setTimeout(() => {
        toast.classList.remove("toast-show");
      }, 3000);

      // REDIRECIONAR
      window.location.href = "fornecedores.php";
    }

  </script>



  <div id="msgErro" class="toast-custom">
    Preencha todos os campos
  </div>

</body>

</html