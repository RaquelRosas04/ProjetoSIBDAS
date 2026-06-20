<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$mensagemModal = $_SESSION['mensagem_modal'] ?? null;
unset($_SESSION['mensagem_modal']);
 
if (!empty($mensagemModal)) {

    $tipo = $mensagemModal['tipo'] ?? 'info';
    $texto = $mensagemModal['texto'] ?? '';

    $titulo = 'Mensagem';
    $classe = 'text-info';
    $icone = 'bi-info-circle-fill';

    if ($tipo === 'success') {
        $titulo = 'Sucesso';
        $classe = 'text-success';
        $icone = 'bi-check-circle-fill';
    } elseif ($tipo === 'danger') {
        $titulo = 'Erro';
        $classe = 'text-danger';
        $icone = 'bi-exclamation-triangle-fill';
    } elseif ($tipo === 'warning') {
        $titulo = 'Atenção';
        $classe = 'text-warning';
        $icone = 'bi-exclamation-circle-fill';
    } elseif ($tipo === 'info') {
        $titulo = 'Informação';
        $classe = 'text-info';
        $icone = 'bi-info-circle-fill';
    }
?>

<div class="modal fade" id="modalMensagemSistema" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow">

            <div class="modal-header">
                <h5 class="modal-title <?= $classe ?>">
                    <i class="bi <?= $icone ?> me-2"></i>
                    <?= htmlspecialchars($titulo) ?>
                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>
            </div>

            <div class="modal-body">
                <p class="mb-0">
                    <?= htmlspecialchars($texto) ?>
                </p>
            </div>

            <div class="modal-footer">
                <button type="button"
                        class="btn btn-primary"
                        data-bs-dismiss="modal">
                    OK
                </button>
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const modalElemento = document.getElementById("modalMensagemSistema");

    if (modalElemento) {
        const modal = new bootstrap.Modal(modalElemento);
        modal.show();
    }
});
</script>

<?php
}
?>
