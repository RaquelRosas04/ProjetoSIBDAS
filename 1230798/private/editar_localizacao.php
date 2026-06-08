<?php require_once __DIR__ . '/includes/funcoes.php';
      redirect_if_not_logged();
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <title>Editar Localização</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/1230798.css">

    <link rel="icon" href="../assets/images/aba.png" type="image/png">

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
    <div class="container py-4" style="padding-top: 100px;">

        <h2 class="mb-4">
            <i class="bi bi-pencil me-2 text-primary"></i>
            Editar Localização
        </h2>

        <div class="card p-4 shadow-sm">

            <form id="formEditar">

                <div class="row g-3">

                    <!-- EDIFÍCIO (BLOQUEADO) -->
                    <div class="col-md-6">
                        <label class="form-label">Edifício</label>
                        <input type="text" id="edificio" class="form-control" value="Hospital Central" disabled>
                    </div>

                    <!-- SERVIÇO (EDITÁVEL) -->
                    <div class="col-md-6">
                        <label class="form-label">Serviço</label>
                        <input type="text" id="servico" class="form-control" value="Cardiologia">
                    </div>

                    <!-- ANDAR (BLOQUEADO) -->
                    <div class="col-md-4">
                        <label class="form-label">Andar</label>
                        <input type="number" id="andar" class="form-control" value="2" disabled>
                    </div>

                    <!-- SALA (BLOQUEADO) -->
                    <div class="col-md-4">
                        <label class="form-label">Sala</label>
                        <input type="text" id="sala" class="form-control" value="203" disabled>
                    </div>

                </div>

                <!-- TOAST ERRO -->
                <div id="msgErro" class="toast-custom">
                    Preencha o serviço
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i> Guardar Alterações
                    </button>

                    <a href="localizacoes.php" class="btn btn-secondary">
                        Cancelar
                    </a>
                </div>




            </form>

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

        </div>

    </div>



    <!-- JS -->
    <script>

        document.addEventListener("DOMContentLoaded", function () {

            let form = document.getElementById("formEditar");

            if (!form) return;

            form.addEventListener("submit", function (e) {

                e.preventDefault();

                let servico = document.getElementById("servico").value.trim();

                if (!servico) {
                    let toast = document.getElementById("msgErro");
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
                window.location.href = "localizacoes.php";
            });

        });
    </script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>