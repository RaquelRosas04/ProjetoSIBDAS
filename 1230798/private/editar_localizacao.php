<?php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/includes/funcoes.php';

redirect_if_not_logged();

$erro = '';

try {
    $ligacao = new PDO(
        "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8",
        MYSQL_USERNAME,
        MYSQL_PASSWORD
    );

    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id'] ?? '';
        $idEdificio = $_POST['idEdificio'] ?? '';
        $idServico = $_POST['idServico'] ?? '';
        $andar = trim($_POST['andar'] ?? '');
        $sala = trim($_POST['sala'] ?? '');

        if ($id === '' || $idEdificio === '' || $idServico === '' || $andar === '' || $sala === '') {
            $erro = 'Preencha todos os campos.';
        } else {
            $sql = "
                UPDATE localizacao
                SET
                    idEdificio = ?,
                    idServico = ?,
                    andar = ?,
                    sala = ?
                WHERE id = ?
            ";

            $stmt = $ligacao->prepare($sql);
            $stmt->execute([
                $idEdificio,
                $idServico,
                $andar,
                $sala,
                $id
            ]);

            definir_mensagem('success', 'Localizacao atualizada com sucesso.');

            header('Location: localizacoes.php');
            exit;
        }
    }

    $id = $_GET['id'] ?? ($_POST['id'] ?? '');

    if ($id === '') {
        header('Location: localizacoes.php');
        exit;
    }

    $stmt = $ligacao->prepare("
        SELECT id, idEdificio, idServico, andar, sala
        FROM localizacao
        WHERE id = ?
    ");
    $stmt->execute([$id]);
    $localizacao = $stmt->fetch(PDO::FETCH_OBJ);

    if (!$localizacao) {
        header('Location: localizacoes.php');
        exit;
    }

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
    $erro = 'Erro ao carregar localizacao.';
    $localizacao = null;
    $edificios = [];
    $servicos = [];
}

include __DIR__ . '/includes/header_priv.php';

?>

<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">
            <i class="bi bi-pencil-square me-2 text-primary"></i>
            Editar Localizacao
        </h2>

        <a href="localizacoes.php" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </div>

    <div class="card p-4 shadow-sm">

        <?php if (!empty($erro)): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($erro) ?>
            </div>
        <?php endif; ?>

        <?php if ($localizacao): ?>
            <form method="post">

                <input type="hidden" name="id" value="<?= htmlspecialchars($localizacao->id) ?>">

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">Edificio</label>
                        <select name="idEdificio" class="form-select" required>
                            <option value="">Selecione o edificio</option>

                            <?php foreach ($edificios as $edificio): ?>
                                <option
                                    value="<?= htmlspecialchars($edificio->id) ?>"
                                    <?= ($edificio->id == $localizacao->idEdificio) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($edificio->nome) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Servico</label>
                        <select name="idServico" class="form-select" required>
                            <option value="">Selecione o servico</option>

                            <?php foreach ($servicos as $servico): ?>
                                <option
                                    value="<?= htmlspecialchars($servico->id) ?>"
                                    <?= ($servico->id == $localizacao->idServico) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($servico->descricao) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Andar</label>
                        <input
                            type="number"
                            name="andar"
                            class="form-control"
                            value="<?= htmlspecialchars($localizacao->andar) ?>"
                            required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Sala</label>
                        <input
                            type="text"
                            name="sala"
                            class="form-control"
                            value="<?= htmlspecialchars($localizacao->sala) ?>"
                            required>
                    </div>

                </div>

                <div class="mt-4 d-flex justify-content-between">
                    <a href="localizacoes.php" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Voltar
                    </a>

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check2-square me-1"></i>
                        Guardar Alteracoes
                    </button>
                </div>

            </form>
        <?php endif; ?>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/1230798.js"></script>

</body>
</html>
