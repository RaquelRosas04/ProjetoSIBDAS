<!DOCTYPE html>
<html lang="pt">

<head>
  <meta charset="UTF-8">
  <title>Editar Fornecedor</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <!-- CSS -->
  <link rel="stylesheet" href="../css/1230798.css">

  <style>
    .toast-custom {
      position: fixed;
      top: 90px;
      left: 50%;
      transform: translateX(-50%);
      background-color: #f8d7da;
      color: #842029;
      padding: 10px 20px;
      border-radius: 8px;
      font-size: 14px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      opacity: 0;
      transition: opacity 0.3s;
      z-index: 9999;
    }

    .toast-show {
      opacity: 1;
    }
  </style>
</head>

<body>



  <!-- CONTEÚDO -->
  <div class="container py-4" style="padding-top: 0px;">

    <h2 class="mb-4">
      <i class="bi bi-pencil-square me-2 text-primary"></i>
      Editar Fornecedor
    </h2>

    <form id="formEditar">

      <div class="row g-3">

        <div class="col-md-6">
          <label class="form-label">Nome</label>
          <input type="text" class="form-control" id="nome" value="MedTech" disabled>
        </div>

        <div class="col-md-6">
          <label class="form-label">NIF</label>
          <input type="text" class="form-control" id="nif" value="123456789" disabled>
        </div>

        <div class="col-md-6">
          <label class="form-label">Email</label>
          <input type="email" class="form-control" id="email" value="geral@medtech.pt">
        </div>

        <div class="col-md-6">
          <label class="form-label">Telefone</label>
          <input type="text" class="form-control" id="telefone" value="912345678">
        </div>

        <div class="col-md-6">
          <label class="form-label">Código Postal</label>
          <input type="text" class="form-control" id="codPostal" value="1000-200">
        </div>

        <div class="col-md-6">
          <label class="form-label">Morada</label>
          <input type="text" class="form-control" id="morada" value="Lisboa">
        </div>

        <div class="col-12">
          <label class="form-label">Observações</label>
          <textarea class="form-control" rows="3" id="obs">Fornecedor principal</textarea>
        </div>

      </div>

      <!-- BOTÕES -->
      <div class="mt-4 d-flex justify-content-between">

        <a href="fornecedores.php" class="btn btn-outline-secondary">
          <i class="bi bi-arrow-left"></i> Voltar
        </a>

        <button type="submit" class="btn btn-primary">
          <i class="bi bi-check2-square me-1"></i>
          Guardar Alterações
        </button>

      </div>

    </form>

  </div>

  <div id="msgErro" class="toast-custom">
    Preencha todos os campos
  </div>

  <!-- MODAL SUCESSO -->
  <div class="modal fade" id="modalSucesso" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">

        <div class="modal-header">
          <h5 class="modal-title text-success">
            <i class="bi bi-check-circle me-2"></i>
            Sucesso
          </h5>
        </div>

        <div class="modal-body">
          Alterações guardadas com sucesso!
        </div>

        <div class="modal-footer">
          <button class="btn btn-primary" id="btnVoltarLista">
            OK
          </button>
        </div>

      </div>
    </div>
  </div>

  <!-- JS -->


  <script>


    document.addEventListener("DOMContentLoaded", function () {

      let form = document.getElementById("formEditar");

      if (!form) return;



      form.addEventListener("submit", function (e) {

        e.preventDefault();

        let nif = document.getElementById("nif").value.trim();
        let email = document.getElementById("email").value.trim();
        let telefone = document.getElementById("telefone").value.trim();
        let codPostal = document.getElementById("codPostal").value.trim();
        let morada = document.getElementById("morada").value.trim();

        // 🔴 VALIDAÇÃO (obs NÃO entra)
        if (!nif || !email || !telefone || !codPostal || !morada) {

          let toast = document.getElementById("msgErro");
          toast.innerText = "Preencha todos os campos obrigatórios";
          toast.classList.add("toast-show");

          setTimeout(() => {
            toast.classList.remove("toast-show");
          }, 3000);

          return;
        }


        // 🔥 MOSTRAR MODAL
        let modal = new bootstrap.Modal(document.getElementById("modalSucesso"));
        modal.show();

      });

      // BOTÃO OK
      document.getElementById("btnVoltarLista").addEventListener("click", function () {
        window.location.href = "fornecedores.php";
      });

    });



  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>