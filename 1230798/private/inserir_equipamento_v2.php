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

    $equipamentos = $ligacao->query("
        SELECT id, descricao, modelo
        FROM equipamentos
        ORDER BY descricao
    ")->fetchAll(PDO::FETCH_OBJ);

    $marcas = $ligacao->query("
        SELECT id, descricao
        FROM marca
        ORDER BY descricao
    ")->fetchAll(PDO::FETCH_OBJ);

    $tipos = $ligacao->query("
        SELECT id, descricao
        FROM tipoequipamento
        ORDER BY descricao
    ")->fetchAll(PDO::FETCH_OBJ);

    $localizacoes = $ligacao->query("
        SELECT id, idEdificio, idServico, andar, sala
        FROM localizacao
        ORDER BY idEdificio, idServico, andar, sala
    ")->fetchAll(PDO::FETCH_OBJ);

    $fornecedores = $ligacao->query("
        SELECT id, nome
        FROM fornecedores
        ORDER BY nome
    ")->fetchAll(PDO::FETCH_OBJ);

} catch (PDOException $e) {
    die("Erro ao carregar dados: " . $e->getMessage());
}

include __DIR__ . '/includes/header_priv.php';

?>

<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">

<div class="container py-5" style="padding-top: 100px;">

    <h2 class="mb-4">
        <i class="bi bi-plus-circle me-2 text-primary"></i>
        Inserir Unidade de Equipamento
    </h2>

    <div class="card p-4 shadow-sm">

        <form method="post" action="processa_inserir_equipamento_unidade.php">

            <h5 class="mt-3 mb-3">1. Equipamento / Modelo</h5>

            <div class="alert alert-info">
                Se o equipamento já existir, selecione-o. Se não existir, deixe este campo vazio e preencha os dados do novo equipamento.
            </div>

            <div class="row g-3 mb-4">

                <div class="col-md-12">
                    <label class="form-label">Equipamento existente</label>
                    <select id="idEquipamento" name="idEquipamento" class="form-select">
                        <option value="">Criar novo equipamento</option>

                        <?php foreach ($equipamentos as $eq): ?>
                            <option value="<?= $eq->id ?>">
                                <?= htmlspecialchars($eq->descricao) ?>
                                <?= !empty($eq->modelo) ? ' - ' . htmlspecialchars($eq->modelo) : '' ?>
                            </option>
                        <?php endforeach; ?>

                    </select>
                </div>

            </div>

            <h5 class="mt-3 mb-3">2. Novo Equipamento</h5>

            <div class="row g-3 mb-4">

                <div class="col-md-5">
                    <label class="form-label">Descrição</label>
                    <input type="text" name="descricao" class="form-control">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Tipo</label>
                    <select name="idTipo" class="form-select">
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
                    <select name="idMarca" class="form-select">
                        <option value="">Selecione</option>

                        <?php foreach ($marcas as $marca): ?>
                            <option value="<?= $marca->id ?>">
                                <?= htmlspecialchars($marca->descricao) ?>
                            </option>
                        <?php endforeach; ?>

                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Modelo</label>
                    <input type="text" name="modelo" class="form-control">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Anos Garantia</label>
                    <input type="number" name="anosGarantia" class="form-control" min="0">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Criticidade</label>
                    <select name="criticidade" class="form-select">
                        <option value="">Selecione</option>
                        <option value="Baixo">Baixo</option>
                        <option value="Médio">Médio</option>
                        <option value="Alto">Alto</option>
                        <option value="Suporte de vida">Suporte de vida</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">É componente?</label>
                    <select name="componente" class="form-select">
                        <option value="0">Não</option>
                        <option value="1">Sim</option>
                    </select>
                </div>

            </div>

            <h5 class="mt-3 mb-3">3. Unidade Física</h5>

            <div class="row g-3">

                <div class="col-md-3">
                    <label class="form-label">Código</label>
                    <input type="text" name="Codigo" class="form-control" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Nº Série</label>
                    <input type="text" name="numSerie" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Localização</label>
                    <select name="idLocalizacao" class="form-select" required>
                        <option value="">Selecione</option>

                        <?php foreach ($localizacoes as $loc): ?>
                            <option value="<?= $loc->id ?>">
                                Edifício <?= htmlspecialchars($loc->idEdificio) ?> -
                                Serviço <?= htmlspecialchars($loc->idServico) ?> -
                                Andar <?= htmlspecialchars($loc->andar) ?> -
                                Sala <?= htmlspecialchars($loc->sala) ?>
                            </option>
                        <?php endforeach; ?>

                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Fornecedor</label>
                    <select name="idFornecedor" class="form-select" required>
                        <option value="">Selecione</option>

                        <?php foreach ($fornecedores as $fornecedor): ?>
                            <option value="<?= $fornecedor->id ?>">
                                <?= htmlspecialchars($fornecedor->nome) ?>
                            </option>
                        <?php endforeach; ?>

                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Estado</label>
                    <select name="estado" class="form-select" required>
                        <option value="">Selecione</option>
                        <option value="Ativo">Ativo</option>
                        <option value="Inativo">Inativo</option>
                        <option value="Em manutenção">Em manutenção</option>
                        <option value="Em calibração">Em calibração</option>
                        <option value="Em Quarentena">Em Quarentena</option>
                        <option value="Abatido">Abatido</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Ano Fabrico</label>
                    <input type="number" name="anoFabrico" class="form-control" min="1900" max="2100" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Data Aquisição</label>
                    <input type="date" name="dataAquisicao" class="form-control" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Data Fim Garantia</label>
                    <input type="date" name="dataFimGarantia" class="form-control" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Tipo Entrada</label>
                    <select name="tipoEntrada" class="form-select" required>
                        <option value="">Selecione</option>
                        <option value="Compra">Compra</option>
                        <option value="Doação">Doação</option>
                        <option value="Aluguer">Aluguer</option>
                        <option value="Empréstimo">Empréstimo</option>
                    </select>
                </div>

                <div class="col-md-8">
                    <label class="form-label">Observações</label>
                    <textarea name="obs" class="form-control" rows="2"></textarea>
                </div>

            </div>

            <div class="mt-4 d-flex justify-content-between">
                <a href="lista_equipamentos.php" class="btn btn-outline-secondary">
                    Voltar
                </a>

                <button type="submit" class="btn btn-primary">
                    Inserir Unidade
                </button>
            </div>

        </form>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

<script>
new TomSelect("#idEquipamento", {
    create: false,
    sortField: {
        field: "text",
        direction: "asc"
    }
});
</script>

</body>
</html>