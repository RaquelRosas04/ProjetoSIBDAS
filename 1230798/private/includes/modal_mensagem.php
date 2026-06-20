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
    $classe = 'bg-info text-white';
    $icone = 'bi-info-circle-fill';

    if ($tipo === 'success') {
        $titulo = 'Sucesso';
        $classe = 'bg-success text-white';
        $icone = 'bi-check-circle-fill';
    } elseif ($tipo === 'danger') {
        $titulo = 'Erro';
        $classe = 'bg-danger text-white';
        $icone = 'bi-exclamation-triangle-fill';
    } elseif ($tipo === 'warning') {
        $titulo = 'Atenção';
        $classe = 'bg-warning text-dark';
        $icone = 'bi-exclamation-circle-fill';
    } elseif ($tipo === 'info') {
        $titulo = 'Informação';
        $classe = 'bg-info text-white';
        $icone = 'bi-info-circle-fill';
    }
?>

<div class="modal fade" id="modalMensagemSistema" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow">

            <div class="modal-header <?= $classe ?>">
                <h5 class="modal-title">
                    <i class="bi <?= $icone ?> me-2"></i>
                    <?= htmlspecialchars($titulo) ?>
                </h5>

                <button type="button"
                        class="btn-close <?= $tipo === 'warning' ? '' : 'btn-close-white' ?>"
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