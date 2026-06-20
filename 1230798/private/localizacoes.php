<?php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/includes/funcoes.php';

redirect_if_not_logged();

try {
    $ligacao = new PDO(
        "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8",
        MYSQL_USERNAME,
        MYSQL_PASSWORD
    );

    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $localizacoes = $ligacao->query("
        SELECT
            l.id,
            e.nome AS edificio,
            s.descricao AS servico,
            l.andar,
            l.sala
        FROM localizacao l
        INNER JOIN edificios e ON l.idEdificio = e.id
        INNER JOIN servicos s ON l.idServico = s.id
        ORDER BY e.nome, s.descricao, l.andar, l.sala
    ")->fetchAll(PDO::FETCH_OBJ);

    $erro = '';
} catch (PDOException $e) {
    $erro = 'Erro ao carregar localizacoes.';
    $localizacoes = [];
}

include __DIR__ . '/includes/header_priv.php';

?>

<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">
            <i class="bi bi-geo-alt me-2 text-primary"></i>
            Localizacoes
        </h2>

        <a href="inserir_localizacao.php" class="btn btn-primary">
            <i class="bi bi-plus"></i> Inserir Localizacao
        </a>
    </div>

    <div class="card p-3 mb-4 shadow-sm">
        <div class="row g-2 align-items-center">

            <div class="col-md-3">
                <input type="text" id="fNome" class="form-control" placeholder="Edificio">
            </div>

            <div class="col-md-3">
                <input type="text" id="fNIF" class="form-control" placeholder="Servico">
            </div>

            <div class="col-md-2">
                <input type="text" id="fEmail" class="form-control" placeholder="Andar">
            </div>

            <div class="col-md-2">
                <input type="text" id="fTelefone" class="form-control" placeholder="Sala">
            </div>

            <div class="col-md-1">
                <button class="btn btn-primary w-100" id="btnFiltrar">
                    <i class="bi bi-search"></i>
                </button>
            </div>

            <div class="col-md-1">
                <button class="btn btn-outline-secondary w-100" id="btnLimpar">
                    Limpar
                </button>
            </div>

        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-custom">
                <tr>
                    <th>Edificio</th>
                    <th>Servico</th>
                    <th>Andar</th>
                    <th>Sala</th>
                    <th>Acoes</th>
                </tr>
            </thead>

            <tbody>

                <?php if (!empty($erro)): ?>

                    <tr>
                        <td colspan="5" class="text-center text-danger">
                            <?= htmlspecialchars($erro) ?>
                        </td>
                    </tr>

                <?php elseif (count($localizacoes) == 0): ?>

                    <tr>
                        <td colspan="5" class="text-center text-muted">
                            Nao existem localizacoes registadas.
                        </td>
                    </tr>

                <?php else: ?>

                    <?php foreach ($localizacoes as $localizacao): ?>
                        <tr>
                            <td><?= htmlspecialchars($localizacao->edificio) ?></td>
                            <td><?= htmlspecialchars($localizacao->servico) ?></td>
                            <td><?= htmlspecialchars($localizacao->andar) ?></td>
                            <td><?= htmlspecialchars($localizacao->sala) ?></td>

                            <td>
                                <a href="editar_localizacao.php?id=<?= urlencode($localizacao->id) ?>"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <button
                                    type="button"
                                    class="btn btn-sm btn-outline-danger btn-apagar"
                                    data-url="apagar_localizacao.php?id=<?= urlencode($localizacao->id) ?>">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                <?php endif; ?>

            </tbody>
        </table>
    </div>

</div>

<div class="modal fade" id="modalApagar" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title text-danger">
                    <i class="bi bi-exclamation-triangle"></i> Confirmar
                </h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                Tem a certeza que deseja apagar esta localizacao?
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">
                    Cancelar
                </button>

                <button class="btn btn-danger" id="confirmarApagar">
                    Apagar
                </button>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/1230798.js"></script>

<?php include __DIR__ . '/includes/modal_mensagem.php'; ?>

<script>
    let urlApagarLocalizacao = null;

    document.addEventListener("click", function(e) {
        const botaoApagar = e.target.closest(".btn-apagar");

        if (botaoApagar) {
            urlApagarLocalizacao = botaoApagar.dataset.url;
            new bootstrap.Modal(document.getElementById("modalApagar")).show();
        }

        if (e.target.id === "confirmarApagar") {
            if (urlApagarLocalizacao) {
                window.location.href = urlApagarLocalizacao;
            }
        }
    });
</script>

</body>
</html>
