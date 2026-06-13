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

    $tipos = $ligacao->query("
        SELECT id, descricao
        FROM tipoequipamento
        ORDER BY descricao
    ")->fetchAll(PDO::FETCH_OBJ);

    $marcas = $ligacao->query("
        SELECT id, descricao
        FROM marca
        ORDER BY descricao
    ")->fetchAll(PDO::FETCH_OBJ);

    $fornecedores = $ligacao->query("
        SELECT id, nome
        FROM fornecedores
        ORDER BY nome
    ")->fetchAll(PDO::FETCH_OBJ);

    $componentes = $ligacao->query("
        SELECT id, descricao, modelo
        FROM equipamentos
        WHERE componente = 1
        ORDER BY descricao
    ")->fetchAll(PDO::FETCH_OBJ);

} catch (PDOException $e) {
    die("Erro ao carregar dados: " . $e->getMessage());
}

include __DIR__ . '/includes/header_priv.php';

?>

<div class="container py-5" style="padding-top: 100px;">

    <h2 class="mb-4">
        <i class="bi bi-plus-circle me-2 text-primary"></i>
        Inserir Equipamento
    </h2>

    <div class="card p-4 shadow-sm">

        <form method="post" action="processa_inserir_equipamento.php">

            <ul class="nav nav-tabs mb-4" id="tabsEquipamento" role="tablist">

                <li class="nav-item" role="presentation">
                    <button class="nav-link active"
                            data-bs-toggle="tab"
                            data-bs-target="#dados"
                            type="button">
                        Dados do Equipamento
                    </button>
                </li>

                <li class="nav-item" role="presentation">
                    <button class="nav-link"
                            data-bs-toggle="tab"
                            data-bs-target="#fornecedores"
                            type="button">
                        Fornecedores
                    </button>
                </li>

                <li class="nav-item" role="presentation">
                    <button class="nav-link"
                            data-bs-toggle="tab"
                            data-bs-target="#componentes"
                            type="button">
                        Componentes
                    </button>
                </li>

            </ul>

            <div class="tab-content">

                <!-- TAB 1: DADOS -->
                <div class="tab-pane fade show active" id="dados">

                    <div class="row g-3">

                        <div class="col-md-5">
                            <label class="form-label">Designação</label>
                            <input type="text"
                                   name="descricao"
                                   class="form-control"
                                   required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Tipo / Categoria</label>
                            <select name="idTipo" class="form-select" required>
                                <option value="">Selecione</option>

                                <?php foreach ($tipos as $tipo): ?>
                                    <option value="<?= $tipo->id ?>">
                                        <?= htmlspecialchars($tipo->descricao) ?>
                                    </option>
                                <?php endforeach; ?>

                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Marca</label>
                            <select name="idMarca" class="form-select" required>
                                <option value="">Selecione</option>

                                <?php foreach ($marcas as $marca): ?>
                                    <option value="<?= $marca->id ?>">
                                        <?= htmlspecialchars($marca->descricao) ?>
                                    </option>
                                <?php endforeach; ?>

                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Modelo</label>
                            <input type="text"
                                   name="modelo"
                                   class="form-control"
                                   required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Anos de Garantia</label>
                            <input type="number"
                                   name="anosGarantia"
                                   class="form-control"
                                   min="0"
                                   required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Criticidade</label>
                            <select name="criticidade" class="form-select" required>
                                <option value="">Selecione</option>
                                <option value="Baixa">Baixa</option>
                                <option value="Média">Média</option>
                                <option value="Alta">Alta</option>
                                <option value="Suporte de vida">Suporte de vida</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">É componente?</label>
                            <select name="componente" class="form-select" required>
                                <option value="0">Não</option>
                                <option value="1">Sim</option>
                            </select>
                        </div>

                    </div>

                </div>

                <!-- TAB 2: FORNECEDORES -->
                <div class="tab-pane fade" id="fornecedores">

                    <div class="alert alert-info">
                        Pode associar vários fornecedores ao equipamento, distinguindo fabricante, distribuidor ou assistência técnica.
                    </div>

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">Fornecedor 1</label>
                            <select name="fornecedores[]" class="form-select">
                                <option value="">Selecione</option>

                                <?php foreach ($fornecedores as $fornecedor): ?>
                                    <option value="<?= $fornecedor->id ?>">
                                        <?= htmlspecialchars($fornecedor->nome) ?>
                                    </option>
                                <?php endforeach; ?>

                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Tipo de Fornecedor</label>
                            <select name="tiposFornecedor[]" class="form-select">
                                <option value="">Selecione</option>
                                <option value="Fabricante">Fabricante</option>
                                <option value="Distribuidor">Distribuidor</option>
                                <option value="Assistência técnica">Assistência técnica</option>
                                <option value="Consumíveis">Consumíveis</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Fornecedor 2</label>
                            <select name="fornecedores[]" class="form-select">
                                <option value="">Selecione</option>

                                <?php foreach ($fornecedores as $fornecedor): ?>
                                    <option value="<?= $fornecedor->id ?>">
                                        <?= htmlspecialchars($fornecedor->nome) ?>
                                    </option>
                                <?php endforeach; ?>

                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Tipo de Fornecedor</label>
                            <select name="tiposFornecedor[]" class="form-select">
                                <option value="">Selecione</option>
                                <option value="Fabricante">Fabricante</option>
                                <option value="Distribuidor">Distribuidor</option>
                                <option value="Assistência técnica">Assistência técnica</option>
                                <option value="Consumíveis">Consumíveis</option>
                            </select>
                        </div>

                    </div>

                </div>

                <!-- TAB 3: COMPONENTES -->
                <div class="tab-pane fade" id="componentes">

                    <div class="alert alert-info">
                        Se este equipamento tiver componentes associados, selecione-os aqui.
                    </div>

                    <div class="row g-3">

                        <?php foreach ($componentes as $comp): ?>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           name="componentes[]"
                                           value="<?= $comp->id ?>"
                                           id="comp<?= $comp->id ?>">

                                    <label class="form-check-label" for="comp<?= $comp->id ?>">
                                        <?= htmlspecialchars($comp->descricao) ?>
                                        <?= !empty($comp->modelo) ? ' - ' . htmlspecialchars($comp->modelo) : '' ?>
                                    </label>
                                </div>
                            </div>
                        <?php endforeach; ?>

                    </div>

                </div>

            </div>

            <div class="mt-4 d-flex justify-content-between">
                <a href="lista_equipamentos.php" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i>
                    Voltar
                </a>

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i>
                    Inserir Equipamento
                </button>
            </div>

        </form>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/1230798.js"></script>

</body>
</html>