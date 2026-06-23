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
        $nome = trim($_POST['nome'] ?? '');
        $nif = trim($_POST['nif'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telefone = trim($_POST['telefone'] ?? '');
        $localidade = trim($_POST['localidade'] ?? '');

        if ($id === '' || $nome === '' || $nif === '' || $email === '' || $telefone === '') {
            $erro = 'Preencha todos os campos obrigatórios.';
        } else {
            $sql = "
                UPDATE fornecedores
                SET
                    nome = ?,
                    nif = ?,
                    email = ?,
                    telefone = ?,
                    localidade = ?
                WHERE id = ?
            ";

            $stmt = $ligacao->prepare($sql);
            $stmt->execute([
                $nome,
                $nif,
                $email,
                $telefone,
                $localidade,
                $id
            ]);

            definir_mensagem('success', 'Fornecedor alterado com sucesso.');

            header('Location: fornecedores.php');
            exit;
        }
    }

    $id = $_GET['id'] ?? ($_POST['id'] ?? '');

    if ($id === '') {
        header('Location: fornecedores.php');
        exit;
    }

    $stmt = $ligacao->prepare("
        SELECT id, nome, nif, email, telefone, localidade
        FROM fornecedores
        WHERE id = ?
    ");

    $stmt->execute([$id]);

    $fornecedor = $stmt->fetch(PDO::FETCH_OBJ);

    if (!$fornecedor) {
        header('Location: fornecedores.php');
        exit;
    }

} catch (PDOException $e) {
    die('Erro: ' . $e->getMessage());
}

include __DIR__ . '/includes/header_priv.php';

?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<body>

<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">
            <i class="bi bi-pencil-square me-2 text-primary"></i>
            Editar Fornecedor
        </h2>

        <a href="fornecedores.php" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </div>

    <div class="card p-4 shadow-sm">

        <?php if (!empty($erro)): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($erro) ?>
            </div>
        <?php endif; ?>

        <form method="post">

            <input type="hidden" name="id" value="<?= htmlspecialchars($fornecedor->id) ?>">

            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">Nome*</label>
                    <input type="text"
                           class="form-control"
                           name="nome"
                           value="<?= htmlspecialchars($fornecedor->nome) ?>"
                           required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">NIF*</label>
                    <input type="text"
                           class="form-control"
                           name="nif"
                           value="<?= htmlspecialchars($fornecedor->nif) ?>"
                           required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Email*</label>
                    <input type="email"
                           class="form-control"
                           name="email"
                           value="<?= htmlspecialchars($fornecedor->email ?? '') ?>"
                           required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Telefone*</label>
                    <input type="text"
                           class="form-control"
                           name="telefone"
                           value="<?= htmlspecialchars($fornecedor->telefone) ?>"
                           required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Localidade</label>
                    <input type="text"
                           class="form-control"
                           name="localidade"
                           value="<?= htmlspecialchars($fornecedor->localidade) ?>">
                </div>

            </div>

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

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/1230798.js"></script>

</body>
</html>
