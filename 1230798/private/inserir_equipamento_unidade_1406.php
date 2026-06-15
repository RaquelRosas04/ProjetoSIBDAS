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

    $equipamentos = $ligacao->query("
        SELECT id, descricao, modelo, anosGarantia
        FROM equipamentos
        where componente=0
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
    $erro = "Erro ao carregar dados: " . $e->getMessage();
    $equipamentos = [];
    $localizacoes = [];
    $fornecedores = [];
}

include __DIR__ . '/includes/header_priv.php';

?>

<div class="container py-5" style="padding-top: 100px;">

    <h2 class="mb-4">
        <i class="bi bi-plus-circle me-2 text-primary"></i>
        Inserir Unidade de Equipamento
    </h2>

    <?php if (!empty($erro)): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($erro) ?>
        </div>
    <?php endif; ?>

    <div class="card p-4 shadow-sm">

        <form method="post" action="processa_inserir_equipamento_unidade.php">

            <h5 class="mt-3 mb-3">Dados da Unidade</h5>

            <div class="row g-3 mb-4">

                <div class="col-md-6">
                    <label class="form-label">Equipamento</label>

                    <select id="idEquipamento"
                            name="idEquipamento"
                            class="form-select"
                            required>

                        <option value="">Selecione o equipamento</option>

                        <?php foreach ($equipamentos as $eq): ?>
                            <option
                                value="<?= $eq->id ?>"
                                data-garantia="<?= $eq->anosGarantia ?>">

                                <?= htmlspecialchars($eq->descricao) ?>
                                <?= !empty($eq->modelo) ? ' - ' . htmlspecialchars($eq->modelo) : '' ?>

                            </option>
                        <?php endforeach; ?>

                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Código</label>
                    <input type="text" name="Codigo" class="form-control" maxlength="20" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Nº Série</label>
                    <input type="text" name="numSerie" class="form-control" maxlength="50" required>
                </div>

            </div>

            <h5 class="mt-3 mb-3">Localização e Fornecedor</h5>

            <div class="row g-3 mb-4">

                <div class="col-md-6">
                    <label class="form-label">Localização</label>
                    <select name="idLocalizacao" class="form-select" required>
                        <option value="">Selecione a localização</option>

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
                        <option value="">Selecione o fornecedor</option>

                        <?php foreach ($fornecedores as $fornecedor): ?>
                            <option value="<?= $fornecedor->id ?>">
                                <?= htmlspecialchars($fornecedor->nome) ?>
                            </option>
                        <?php endforeach; ?>

                    </select>
                </div>

            </div>

            <h5 class="mt-3 mb-3">Dados Complementares</h5>

            <div class="row g-3">

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
                    <label class="form-label">Ano de Fabrico</label>
                    <input type="number" name="anoFabrico" class="form-control" min="1900" max="2100" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Data de Aquisição</label>
                    <input type="date" id="dataAquisicao" name="dataAquisicao" class="form-control" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Data Fim Garantia</label>
                    <input type="date" id="dataFimGarantia" name="dataFimGarantia" class="form-control" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Tipo de Entrada</label>
                    <select name="tipoEntrada" class="form-select" required>
                        <option value="">Selecione</option>
                        <option value="Compra">Compra</option>
                        <option value="Doação">Doação</option>
                        <option value="Aluguer">Aluguer</option>
                        <option value="Empréstimo">Empréstimo</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Observações?</label>
                    <select name="obs" class="form-select" required>
                        <option value="0">Não</option>
                        <option value="1">Sim</option>
                    </select>
                </div>


                <div class="col-12">
                  <label class="form-label">Observações</label>
                    <textarea name="obs"
                      class="form-control"
                      rows="3"></textarea>
                  </div>
            </div>

            <div class="mt-4 d-flex justify-content-between">
                <a href="lista_equipamentos.php" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Voltar
                </a>

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i>
                    Inserir Unidade
                </button>
            </div>

        </form>

    </div>

</div>

<!-- para dropdow pesquisavel no inserir_equipamento-->
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/1230798.js"></script>


<!-- <script>
    let tomSelectEquipamento = new TomSelect("#idEquipamento", {
    onChange: function(value) {
        calcularGarantia();
    }
});
</script> -->

<script>
let tomSelectEquipamento = new TomSelect("#idEquipamento", {
    onChange: function(value) {
        console.log("Valor selecionado:", value);
        
        const select = document.getElementById("idEquipamento");
        const option = select.options[select.selectedIndex];
        console.log("Option encontrada:", option);
        console.log("data-garantia:", option?.dataset?.garantia);
        
        calcularGarantia();
    }
});
</script> 


</body>
</html>