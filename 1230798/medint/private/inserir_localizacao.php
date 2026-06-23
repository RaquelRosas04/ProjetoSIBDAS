<?php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/includes/funcoes.php';

redirect_if_not_logged();

$validation_errors = $_SESSION['validation_errors'] ?? [];
unset($_SESSION['validation_errors']);

try {
    $ligacao = new PDO(
        "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8",
        MYSQL_USERNAME,
        MYSQL_PASSWORD
    );

    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $edificios = $ligacao->query("
        SELECT id, nome
        FROM edificios
        ORDER BY nome
    ")->fetchAll(PDO::FETCH_OBJ);

    $servicos = $ligacao->query("
        SELECT id, descricao
        FROM servicos
        ORDER BY descricao
    ")->fetchAll(PDO::FETCH_OBJ);

} catch (PDOException $e) {
    $edificios = [];
    $servicos = [];
    $validation_errors[] = 'Erro ao carregar dados da base de dados.';
}

include __DIR__ . '/includes/header_priv.php';

?>

<div class="container py-4">

    <h2 class="mb-4">
        <i class="bi bi-plus-circle me-2 text-primary"></i>
        Inserir Localização
    </h2>

    <div class="card p-4 shadow-sm">

        <?php if (!empty($validation_errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($validation_errors as $erro): ?>
                    <div><?= htmlspecialchars($erro) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="post" action="processa_inserir_localizacao.php">

            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">Edifício*</label>
                    <select name="idEdificio" class="form-select" required>
                        <option value="">Selecione o edifício</option>

                        <?php foreach ($edificios as $edificio): ?>
                            <option value="<?= htmlspecialchars($edificio->id) ?>">
                                <?= htmlspecialchars($edificio->nome) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Serviço*</label>
                    <select name="idServico" class="form-select" required>
                        <option value="">Selecione o serviço</option>

                        <?php foreach ($servicos as $servico): ?>
                            <option value="<?= htmlspecialchars($servico->id) ?>">
                                <?= htmlspecialchars($servico->descricao) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Andar*</label>
                    <input type="number" name="andar" class="form-control" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Sala*</label>
                    <input type="text" name="sala" class="form-control" required>
                </div>

            </div>

            <div class="mt-4 d-flex justify-content-between">
                <a href="localizacoes.php" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Voltar
                </a>

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Inserir Localização
                </button>
            </div>

        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/1230798.js"></script>

</body>
</html>
