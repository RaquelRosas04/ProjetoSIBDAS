<?php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/includes/funcoes.php';

redirect_if_not_logged();

$erro = '';
$validation_errors = $_SESSION['validation_errors'] ?? [];
unset($_SESSION['validation_errors']);

try {
    $ligacao = new PDO(
        "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8",
        MYSQL_USERNAME,
        MYSQL_PASSWORD
    );

    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $equipamentos = $ligacao->query("
            SELECT e.id, e.descricao, e.modelo,  e.anosGarantia, te.identcod,
        CONCAT(   UPPER(te.identcod) ,'_',
            LPAD(COALESCE(MAX(
                        CAST(
                            SUBSTRING(eu.Codigo, CHAR_LENGTH(te.identcod) + 1)
                            AS UNSIGNED)                 ),0                ) + 1,5,
                '0'
            )
        ) AS codigoPrevisto
    FROM equipamentos e
    INNER JOIN tipoequipamento te ON e.idTipo = te.id
    LEFT JOIN equipamentounidade eu 
        ON eu.Codigo LIKE CONCAT(te.identcod, '%')
    WHERE e.componente = 0
    GROUP BY 
        e.id,        e.descricao,        e.modelo,        e.anosGarantia,        te.identcod
    ORDER BY e.descricao
    ")->fetchAll(PDO::FETCH_OBJ);

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

        <?php if (!empty($validation_errors)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($validation_errors as $erroValidacao): ?>
                        <li><?= htmlspecialchars($erroValidacao) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post" action="processa_inserir_equipamento_unidade.php">

            <h5 class="mt-3 mb-3">Dados da Unidade</h5>

            <div class="row g-3 mb-4">

                <div class="col-md-6">
                    <label class="form-label">Equipamento*</label>

                    <select id="idEquipamento"
                        name="idEquipamento"
                        class="form-select"
                        required>

                        <option value="">Selecione o equipamento</option>

                        <?php foreach ($equipamentos as $eq): ?>
                            <option
                                value="<?= $eq->id ?>"
                                data-garantia="<?= $eq->anosGarantia ?>"
                                data-codigo="<?= htmlspecialchars($eq->codigoPrevisto) ?>">

                                <?= htmlspecialchars($eq->descricao) ?>
                                <?= !empty($eq->modelo) ? ' - ' . htmlspecialchars($eq->modelo) : '' ?>

                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>



                <div class="col-md-3">
                    <label class="form-label">Código</label>
                    <input type="text"
                        id="codigoPrevisto"
                        class="form-control campo-bloqueado"
                        value="Gerado automaticamente"
                        readonly>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Nº Série</label>
                    <input type="text" name="numSerie" class="form-control" maxlength="50" required>
                </div>

            </div>

            <h5 class="mt-3 mb-3">Localização e Fornecedor</h5>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label">Localizacão*</label>
                    <select id="idLocalizacao" name="idLocalizacao" class="form-select" required>
                        <option value="">Selecione a localização</option>

                        <?php foreach ($localizacoes as $loc): ?>
                            <option value="<?= $loc->id ?>">
                                <?= htmlspecialchars($loc->edificio) ?> -
                                <?= htmlspecialchars($loc->servico) ?> -
                                Andar <?= htmlspecialchars($loc->andar) ?> -
                                Sala <?= htmlspecialchars($loc->sala) ?>
                            </option>
                        <?php endforeach; ?>

                    </select>
                </div>
            </div>

            <h5 class="mt-3 mb-3">Dados Complementares</h5>

            <div class="row g-3">

                <div class="col-md-3">
                    <label class="form-label">Estado*</label>
                    <select name="estado" class="form-select" required>
                        <option value="">Selecione</option>
                        <option value="Ativo">Ativo</option>
                        <option value="Inativo">Inativo</option>
                        <option value="Manutenção">Manutenção</option>
                        <option value="Calibração">Calibração</option>
                        <option value="Quarentena">Quarentena</option>
                        <option value="Abatido">Abatido</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Ano de Fabrico*</label>
                    <input type="number" name="anoFabrico" class="form-control" min="1900" max="2100" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Data de Aquisição*</label>
                    <input type="date" id="dataAquisicao" name="dataAquisicao" class="form-control" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Data Fim Garantia</label>
                    <input type="date"
                        id="dataFimGarantia"
                        name="dataFimGarantia"
                        class="form-control"
                        required>

                    <div class="mt-2 garantia-info">
                        <small class="text-muted me-1">Garantia:</small>
                        <span id="textoGarantia" class="fw-semibold">Não definida</span>
                    </div>
                </div>




                <div class="col-md-4">
                    <label class="form-label">Tipo de Entrada*</label>
                    <select name="tipoEntrada" class="form-select" required>
                        <option value="">Selecione</option>
                        <option value="Compra">Compra</option>
                        <option value="DoaÃ§Ã£o">Doação</option>
                        <option value="Aluguer">Aluguer</option>
                        <option value="EmprÃ©stimo">Empréstimo</option>
                    </select>
                </div>

                <!--Para aparecer a garantia no ecra (mas fica desalinhada)
                        <div class="col-md-4">
            <div class="border rounded p-3 bg-light h-100">
                <small class="text-muted d-block">Garantia</small>

                <span class="fw-semibold">
                    <?php if (!empty($equipamento->anosGarantia)): ?>
                        <?= htmlspecialchars($equipamento->anosGarantia) ?>
                        <?= $equipamento->anosGarantia == 1 ? 'ano de garantia' : 'anos de garantia' ?>
                    <?php else: ?>
                        Não definida
                    <?php endif; ?>
                </span>
            </div>
        </div> -->

                <div class="col-12">
                    <label class="form-label">Observações</label>
                    <textarea name="obs"
                        class="form-control"
                        rows="3"></textarea>
                </div>
            </div>

            <div class="mt-4 d-flex justify-content-between">
                <a href="lista_equipamentos_unidade.php" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Voltar
                </a>

                <div>
                    <button type="button"
                        id="btnMostrarFornecedores"
                        class="btn btn-outline-primary me-2">
                        <i class="bi bi-truck"></i>
                        Associar Fornecedores
                    </button>

                    <button type="submit" id="btnInserirUnidadeTopo" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>
                        Inserir Unidade
                    </button>
                </div>
            </div>


            <div id="areaFornecedores" class="d-none">

                <hr class="my-4">

                <h5 class="mt-3 mb-3">Fornecedores associados</h5>

                <div class="row g-2 mb-3 align-items-end">

                    <div class="col-md-6">
                        <label class="form-label">Fornecedor</label>

                        <select id="selectFornecedorAssociado" class="form-select">
                            <option value="">Selecione um fornecedor</option>

                            <?php foreach ($fornecedores as $fornecedor): ?>
                                <option value="<?= $fornecedor->id ?>"
                                    data-nome="<?= htmlspecialchars($fornecedor->nome, ENT_QUOTES) ?>">
                                    <?= htmlspecialchars($fornecedor->nome) ?>
                                </option>
                            <?php endforeach; ?>

                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Tipo de Fornecedor</label>

                        <select id="selectTipoFornecedor" class="form-select">
                            <option value="">Selecione o tipo</option>
                            <option value="Fabricante">Fabricante</option>
                            <option value="Distribuidor">Distribuidor</option>
                            <option value="AT">Assistência Técnica</option>
                            <option value="Consumiveis">Consumiveis</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <button type="button"
                            id="btnAdicionarFornecedor"
                            class="btn btn-outline-primary w-100">
                            <i class="bi bi-plus-circle"></i>
                            Adicionar
                        </button>
                    </div>

                </div>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle mb-0" id="tabelaFornecedores">
                        <thead class="table-custom">
                            <tr>
                                <th>Fornecedor</th>
                                <th>Tipo</th>
                                <th style="width: 120px;">Ações</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr id="linhaSemFornecedores">
                                <td colspan="3" class="text-center text-muted">
                                    Nenhum fornecedor associado.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 d-flex justify-content-between">
                    <a href="lista_equipamentos_unidade.php" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i>
                        Voltar
                    </a>

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>
                        Inserir Unidade
                    </button>
                </div>

            </div>
        </form>

    </div>

</div>

<div class="modal fade" id="modalFornecedores" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title text-warning">
                    <i class="bi bi-exclamation-circle-fill me-2"></i>
                    Atenção
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <p class="mb-0" id="textoModalFornecedores"></p>
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

            calcularGarantia();
            atualizarTextoGarantia(value);

            const inputCodigo = document.getElementById("codigoPrevisto");
            const selectEquipamento = document.getElementById("idEquipamento");

            if (!inputCodigo || !selectEquipamento) {
                return;
            }

            if (!value) {
                inputCodigo.value = "Gerado automaticamente";
                return;
            }

            let codigo = "";

            for (let i = 0; i < selectEquipamento.options.length; i++) {
                if (selectEquipamento.options[i].value == value) {
                    codigo = selectEquipamento.options[i].getAttribute("data-codigo");
                    break;
                }
            }

            inputCodigo.value = codigo || "Sem código definido";
        }
    });

    let tomSelectLocalizacao = new TomSelect("#idLocalizacao");
</script>


<script>
    document.addEventListener("DOMContentLoaded", function() {

        function mostrarModalFornecedores(mensagem) {
            const texto = document.getElementById("textoModalFornecedores");
            const modalElemento = document.getElementById("modalFornecedores");

            if (!texto || !modalElemento) {
                alert(mensagem);
                return;
            }

            texto.textContent = mensagem;

            const modal = new bootstrap.Modal(modalElemento);
            modal.show();
        }

        const btnMostrarFornecedores = document.getElementById("btnMostrarFornecedores");
        const areaFornecedores = document.getElementById("areaFornecedores");

        if (btnMostrarFornecedores && areaFornecedores) {
            btnMostrarFornecedores.addEventListener("click", function() {
                areaFornecedores.classList.remove("d-none");
                btnMostrarFornecedores.classList.add("d-none");

                const btnInserirUnidadeTopo = document.getElementById("btnInserirUnidadeTopo");

                if (btnInserirUnidadeTopo) {
                    btnInserirUnidadeTopo.classList.add("d-none");
                }
            });
        }

        const selectFornecedor = document.getElementById("selectFornecedorAssociado");
        const selectTipoFornecedor = document.getElementById("selectTipoFornecedor");
        const btnAdicionarFornecedor = document.getElementById("btnAdicionarFornecedor");
        const tabelaBody = document.querySelector("#tabelaFornecedores tbody");
        const linhaSemFornecedores = document.getElementById("linhaSemFornecedores");

        if (!selectFornecedor || !selectTipoFornecedor || !btnAdicionarFornecedor || !tabelaBody || !linhaSemFornecedores) {
            return;
        }

        let fornecedoresSelecionados = [];

        btnAdicionarFornecedor.addEventListener("click", function() {

            const optionFornecedor = selectFornecedor.options[selectFornecedor.selectedIndex];

            const idFornecedor = optionFornecedor.value;
            const nomeFornecedor = optionFornecedor.dataset.nome;
            const tipoFornecedor = selectTipoFornecedor.value;

            if (!idFornecedor) {
                mostrarModalFornecedores("Selecione um fornecedor.");
                return;
            }

            if (!tipoFornecedor) {
                mostrarModalFornecedores("Selecione o tipo de fornecedor.");
                return;
            }

            const chave = idFornecedor + "|" + tipoFornecedor;

            if (fornecedoresSelecionados.includes(chave)) {
                mostrarModalFornecedores("Este fornecedor já foi adicionado com esse tipo.");
                return;
            }

            fornecedoresSelecionados.push(chave);

            linhaSemFornecedores.style.display = "none";

            const tr = document.createElement("tr");
            tr.setAttribute("data-chave", chave);

            tr.innerHTML = `
            <td>
                ${nomeFornecedor}
                <input type="hidden" name="fornecedoresAssociados[]" value="${idFornecedor}">
            </td>
            <td>
                ${tipoFornecedor}
                <input type="hidden" name="tiposFornecedoresAssociados[]" value="${tipoFornecedor}">
            </td>
            <td>
                <button type="button"
                        class="btn btn-sm btn-outline-danger btn-remover-fornecedor">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        `;

            tabelaBody.appendChild(tr);

            selectFornecedor.value = "";
            selectTipoFornecedor.value = "";
        });

        tabelaBody.addEventListener("click", function(e) {

            const botao = e.target.closest(".btn-remover-fornecedor");

            if (!botao) {
                return;
            }

            const tr = botao.closest("tr");
            const chave = tr.getAttribute("data-chave");

            fornecedoresSelecionados = fornecedoresSelecionados.filter(item => item !== chave);

            tr.remove();

            if (fornecedoresSelecionados.length === 0) {
                linhaSemFornecedores.style.display = "";
            }
        });

    });
</script>

</body>

</html>

