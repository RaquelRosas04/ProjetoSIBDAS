<?php require_once __DIR__ . '/includes/funcoes.php';
      redirect_if_not_logged();
?>

<!DOCTYPE html>
<html lang="pt">

<head>
  <meta charset="UTF-8">
  <title>Inserir Localização</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/1230798.css">
</head>

<body>

  <!-- NAVBAR -->
  <nav class="navbar navbar-expand-lg navbar-dark fixed-top custom-navbar">
    <div class="container-fluid px-3">
      <a href="dashboard.php" class="navbar-brand">
        <img src="../assets/images/logo.png" height="45">
      </a>
    </div>
  </nav>

  <!-- CONTEÚDO -->
  <div class="container py-4" style="padding-top: 100px;">

    <h2 class="mb-4">
      <i class="bi bi-plus-circle me-2 text-primary"></i>
      Inserir Localização
    </h2>

    <div class="card p-4 shadow-sm">

      <!-- MENSAGEM ERRO -->
      <div class="d-flex justify-content-center mb-3">
        <div id="msgErro" class="alert alert-danger d-none px-3 py-2 small text-center" style="max-width: 300px;">
          Preencha todos os campos
        </div>
      </div>

      <form id="formLocalizacao">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Edifício</label>
            <input type="text" id="edificio" class="form-control">
          </div>

          <div class="col-md-6">
            <label class="form-label">Serviço</label>
            <input type="text" id="servico" class="form-control">
          </div>

          <div class="col-md-4">
            <label class="form-label">Andar</label>
            <input type="number" id="andar" class="form-control">
          </div>

          <div class="col-md-4">
            <label class="form-label">Sala</label>
            <input type="text" id="sala" class="form-control">
          </div>

        </div>


        <!-- BOTÕES -->
        <div class="mt-4 d-flex justify-content-between"> <a href="localizacoes.php" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Voltar </a> <button type="submit" class="btn btn-primary"> <i
              class="bi bi-plus-circle me-1"></i> Inserir Localização </button>
        </div>

      </form>
    </div>
  </div>






  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../js/1230798.js"></script>



  <script>
    document.getElementById("formLocalizacao").addEventListener("submit", function(e) {

      e.preventDefault();

      let edificio = document.getElementById("edificio").value.trim();
      let servico = document.getElementById("servico").value.trim();
      let andar = document.getElementById("andar").value.trim();
      let sala = document.getElementById("sala").value.trim();

      if (!edificio || !servico || !andar || !sala) {

        let erro = document.getElementById("msgErro");
        erro.classList.remove("d-none");

        setTimeout(() => {
          erro.classList.add("d-none");
        }, 3000);

        return;
      }

      // 🔥 SIMULAÇÃO (depois PHP)
      console.log("Localização inserida");

      // REDIRECIONAR
      window.location.href = "localizacoes.php";

    });
  </script>

</body>

</html>