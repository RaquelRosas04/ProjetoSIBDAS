<!-- Navbar -->
<?php include 'includes/header_priv.php'; ?>
<?require_once __DIR__ . '/../../includes/db_connect.php';?>

    <body>
        <div class="container py-4">

            <div class="admin-header">
                <h2><i class="bi bi-gear me-2"></i>Gestão de Conteúdos</h2>
            </div>

            <!-- SOBRE -->
            <div class="admin-card">
                <h4>Sobre</h4>

                <textarea id="sobre1" class="form-control admin-textarea mb-2" rows="5"></textarea>
                <textarea id="sobre2" class="form-control admin-textarea" rows="5"></textarea>
            </div>

            <!-- CONTACTOS -->
            <div class="admin-card">
                <h4>Contactos</h4>

                <input id="email" class="form-control admin-input mb-2" placeholder="Email">
                <input id="telefone" class="form-control admin-input" placeholder="Telefone">
            </div>

            <!-- TERMOS -->
            <div class="admin-card">
                <h4>Termos e Condições</h4>

                <textarea id="termos" class="form-control admin-textarea" rows="5"></textarea>
            </div>

            <!-- COOKIES -->
            <div class="admin-card">
                <h4>Cookies</h4>

                <textarea id="cookies" class="form-control admin-textarea" rows="5"></textarea>
            </div>

            <!-- PRIVACIDADE -->
            <div class="admin-card">
                <h4>Privacidade</h4>

                <textarea id="privacidade" class="form-control admin-textarea" rows="5"></textarea>
            </div>

            <!-- BOTÃO -->
            <div class="text-center mt-4">
                <button class="btn admin-btn text-white" onclick="guardar()">
                    <i class="bi bi-save me-1"></i> Guardar Alterações
                </button>
            </div>

        </div>

        <script>

            //  PROTEÇÃO ADMIN
            /* const tipo = localStorage.getItem("tipoUser");
            if (tipo !== "admin") {
              window.location.href = "sem_acesso.php";
            } */

            // 🔄 CARREGAR DADOS
            document.getElementById("sobre1").value = localStorage.getItem("sobre1") || "";
            document.getElementById("sobre2").value = localStorage.getItem("sobre2") || "";
            document.getElementById("email").value = localStorage.getItem("email") || "";
            document.getElementById("telefone").value = localStorage.getItem("telefone") || "";
            document.getElementById("termos").value = localStorage.getItem("termos") || "";
            document.getElementById("cookies").value = localStorage.getItem("cookies") || "";
            document.getElementById("privacidade").value = localStorage.getItem("privacidade") || "";

            //  GUARDAR
            function guardar() {

                localStorage.setItem("sobre1", document.getElementById("sobre1").value);
                localStorage.setItem("sobre2", document.getElementById("sobre2").value);
                localStorage.setItem("email", document.getElementById("email").value);
                localStorage.setItem("telefone", document.getElementById("telefone").value);
                localStorage.setItem("termos", document.getElementById("termos").value);
                localStorage.setItem("cookies", document.getElementById("cookies").value);
                localStorage.setItem("privacidade", document.getElementById("privacidade").value);

                new bootstrap.Modal(document.getElementById("modalConteudosGuardados")).show();
            }

        </script>

        <div class="modal fade" id="modalConteudosGuardados" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content shadow">

                    <div class="modal-header">
                        <h5 class="modal-title text-success">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            Sucesso
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <p class="mb-0">Conteúdos atualizados com sucesso.</p>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                            OK
                        </button>
                    </div>

                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    </body>

</html>
