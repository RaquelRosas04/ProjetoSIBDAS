<?php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/includes/funcoes.php';

redirect_if_not_logged();

$idUnidade = $_GET['id'] ?? ($_POST['id'] ?? '');

if (empty($idUnidade)) {
    header('Location: lista_equipamentos_unidade.php');
    exit;
}

$erro = '';
$validation_errors = [];

try {
    $ligacao = new PDO(
        "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8",
        MYSQL_USERNAME,
        MYSQL_PASSWORD
    );

    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    /*
     * GRAVAR ALTERAÇÕES
     */
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $id = $_POST['id'] ?? '';
        $idEquipamento = $_POST['idEquipamento'] ?? '';

        $idLocalizacao = $_POST['idLocalizacao'] ?? '';
        $numSerie = trim($_POST['numSerie'] ?? '');
        $estado = $_POST['estado'] ?? '';
        $dataAlteracaoCadastro = $_POST['dataAlteracaoCadastro'] ?? '';
        $anoFabrico = $_POST['anoFabrico'] ?? '';
        $dataAquisicao = $_POST['dataAquisicao'] ?? '';
        $dataFimGarantia = $_POST['dataFimGarantia'] ?? '';
        $tipoEntrada = $_POST['tipoEntrada'] ?? '';
        $obs = trim($_POST['obs'] ?? '');

        $fornecedoresAssociados = $_POST['fornecedoresAssociados'] ?? [];
        $tiposFornecedoresAssociados = $_POST['tiposFornecedoresAssociados'] ?? [];

        $erros = [];

        if (empty($id)) {
            $erros[] = 'Unidade inválida.';
        }

        if (empty($idEquipamento)) {
            $erros[] = 'Equipamento inválido.';
        }

        if (empty($numSerie)) {
            $erros[] = 'Preencha o número de série.';
        }

        if (empty($idLocalizacao)) {
            $erros[] = 'Selecione a localização.';
        }

        if (empty($estado)) {
            $erros[] = 'Selecione o estado.';
        }

        // verifica se estado actual é diferente de estado da base de dados
        $stmtEstadoAtual = $ligacao->prepare("
            SELECT estado, idLocalizacao
            FROM equipamentounidade
            WHERE id = ?
        ");
        $stmtEstadoAtual->execute([$id]);
        $unidadeAtual = $stmtEstadoAtual->fetch(PDO::FETCH_OBJ);

        if (!$unidadeAtual) {
            $erros[] = 'Unidade inválida.';
        }

        
            $estadoAlterado = $unidadeAtual && $estado !== $unidadeAtual->estado;
            $localizacaoAlterada = $unidadeAtual && $idLocalizacao != $unidadeAtual->idLocalizacao;
            $cadastroAlterado = $estadoAlterado || $localizacaoAlterada;

            if ($cadastroAlterado && empty($dataAlteracaoCadastro)) {
                $erros[] = 'Indique a data da alteração de localização/estado.';
            }        
        
        if (empty($anoFabrico)) {
            $erros[] = 'Preencha o ano de fabrico.';
        }

        if (empty($dataAquisicao)) {
            $erros[] = 'Preencha a data de aquisição.';
        }

        if (empty($dataFimGarantia)) {
            $erros[] = 'Preencha a data fim de garantia.';
        }

        if (empty($tipoEntrada)) {
            $erros[] = 'Selecione o tipo de entrada.';
        }

        if (!empty($dataAquisicao) && !empty($dataFimGarantia) && $dataFimGarantia <= $dataAquisicao) {
            $erros[] = 'A data fim de garantia deve ser posterior à data de aquisição.';
        }

        if (!empty($anoFabrico)) {
            $anoAtual = date('Y');

            if ($anoFabrico < 1900 || $anoFabrico > $anoAtual) {
                $erros[] = 'O ano de fabrico é inválido.';
            }
        }

        if (!empty($erros)) {
            $_SESSION['validation_errors'] = $erros;
            header('Location: editar_equipamento_unidade.php?id=' . urlencode($id));
            exit;
        }

        $ligacao->beginTransaction();

        /*
         * Validação:
         * O número de série não pode ser duplicado
         * para equipamentos do mesmo fabricante e modelo.
         * Exclui a própria unidade que está a ser editada.
         */
        $sqlVerifica = "
            SELECT eu.id
            FROM equipamentounidade eu
            INNER JOIN equipamentos eExistente ON eu.idEquipamento = eExistente.id
            INNER JOIN equipamentos eAtual ON eAtual.id = ?
            WHERE eu.numSerie = ?
              AND eu.id <> ?
              AND eExistente.modelo = eAtual.modelo
              AND (
                    eExistente.idfabricante = eAtual.idfabricante
                    OR (
                        eExistente.idfabricante IS NULL
                        AND eAtual.idfabricante IS NULL
                    )
                  )
            LIMIT 1
        ";

        $stmtVerifica = $ligacao->prepare($sqlVerifica);
        $stmtVerifica->execute([
            $idEquipamento,
            $numSerie,
            $id
        ]);

        if ($stmtVerifica->fetch(PDO::FETCH_OBJ)) {
            $ligacao->rollBack();

            $_SESSION['validation_errors'] = [
                'Já existe uma unidade com esse número de série para o mesmo fabricante e modelo.'
            ];

            header('Location: editar_equipamento_unidade.php?id=' . urlencode($id));
            exit;
        }

        /*
         * Atualizar unidade.
         * Equipamento e Código não são alterados.
         */
        $sqlUpdate = "
            UPDATE equipamentounidade
            SET idLocalizacao = ?,
                numSerie = ?,
                estado = ?,
                anoFabrico = ?,
                dataAquisicao = ?,
                dataFimGarantia = ?,
                tipoEntrada = ?,
                obs = ?
            WHERE id = ?
        ";

        $stmtUpdate = $ligacao->prepare($sqlUpdate);

        $stmtUpdate->execute([
            $idLocalizacao,
            $numSerie,
            $estado,
            $anoFabrico,
            $dataAquisicao,
            $dataFimGarantia,
            $tipoEntrada,
            $obs,
            $id
        ]);

            // Se alterou estado ou localização, regista no cadastro/histórico
            if ($cadastroAlterado) {
                $stmtCadastro = $ligacao->prepare("
                    INSERT INTO equipamentocadastro
                    (
                        idequipamento,
                        idlocalizacao,
                        estado,
                        data
                    )
                    VALUES (?, ?, ?, ?)
                ");

                $stmtCadastro->execute([
                    $idEquipamento,
                    $idLocalizacao,
                    $estado,
                    $dataAlteracaoCadastro
                ]);
            }
                    /*
         * Atualizar fornecedores associados.
         * Como são opcionais:
         * - se não vier nenhum, apaga os antigos e fica sem fornecedores associados;
         * - se vierem, apaga os antigos e insere os novos.
         */
        $stmtDeleteFornecedores = $ligacao->prepare("
            DELETE FROM equipamentofornecedores
            WHERE idEquipamentoUni = ?
        ");
        $stmtDeleteFornecedores->execute([$id]);

        foreach ($fornecedoresAssociados as $index => $idFornecedorAssociado) {

            $tipoFornecedor = $tiposFornecedoresAssociados[$index] ?? '';

            if (!empty($idFornecedorAssociado) && !empty($tipoFornecedor)) {

                $stmtFornecedor = $ligacao->prepare("
                    INSERT INTO equipamentofornecedores
                    (
                        idEquipamentoUni,
                        idFornecedor,
                        TipoFornecedor
                    )
                    VALUES (?, ?, ?)
                ");

                $stmtFornecedor->execute([
                    $id,
                    $idFornecedorAssociado,
                    $tipoFornecedor
                ]);
            }
        }

        $ligacao->commit();

        definir_mensagem(
            'success',
            'Unidade de equipamento alterada com sucesso.'
        );

        header('Location: detalhes_equipamento.php?id=' . urlencode($id));
        exit;
    }

    /*
     * CARREGAR DADOS DA UNIDADE
     */
    $stmtUnidade = $ligacao->prepare("
        SELECT 
            eu.id,
            eu.idEquipamento,
            eu.Codigo,
            eu.idLocalizacao,
            eu.numSerie,
            eu.estado,
            eu.anoFabrico,
            eu.dataAquisicao,
            eu.dataFimGarantia,
            eu.tipoEntrada,
            eu.obs,
            e.descricao AS equipamento,
            e.modelo,
            e.anosGarantia,
            m.descricao AS marca,
            f.nome AS fabricante
        FROM equipamentounidade eu
        INNER JOIN equipamentos e ON eu.idEquipamento = e.id
        INNER JOIN marca m ON e.idMarca = m.id
        LEFT JOIN fabricante f ON e.idfabricante = f.id
        WHERE eu.id = ?
    ");

    $stmtUnidade->execute([$idUnidade]);
    $unidade = $stmtUnidade->fetch(PDO::FETCH_OBJ);

    if (!$unidade) {
        header('Location: lista_equipamentos_unidade.php');
        exit;
    }

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

    $stmtFornecedoresAssociados = $ligacao->prepare("
        SELECT 
            ef.idFornecedor,
            ef.TipoFornecedor,
            f.nome
        FROM equipamentofornecedores ef
        INNER JOIN fornecedores f ON ef.idFornecedor = f.id
        WHERE ef.idEquipamentoUni = ?
        ORDER BY f.nome
    ");

    $stmtFornecedoresAssociados->execute([$idUnidade]);
    $fornecedoresAssociadosAtuais = $stmtFornecedoresAssociados->fetchAll(PDO::FETCH_OBJ);

    $chavesFornecedoresAssociados = [];

    foreach ($fornecedoresAssociadosAtuais as $fornAssoc) {
        $chavesFornecedoresAssociados[] = $fornAssoc->idFornecedor . '|' . $fornAssoc->TipoFornecedor;
    }

    $validation_errors = $_SESSION['validation_errors'] ?? [];
    unset($_SESSION['validation_errors']);
} catch (Exception $e) {
    $erro = "Erro ao carregar dados: " . $e->getMessage();

    $unidade = null;
    $localizacoes = [];
    $fornecedores = [];
    $fornecedoresAssociadosAtuais = [];
    $chavesFornecedoresAssociados = [];
}

include __DIR__ . '/includes/header_priv.php';

?>

<div class="container py-5" style="padding-top: 100px;">

    <h2 class="mb-4">
        <i class="bi bi-pencil-square me-2 text-primary"></i>
        Editar Unidade de Equipamento
    </h2>

    <?php if (!empty($erro)): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($erro) ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($validation_errors)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($validation_errors as $erroValidacao): ?>
                    <li><?= htmlspecialchars($erroValidacao) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if ($unidade): ?>

        <div class="card p-4 shadow-sm">

            <form method="post" action="editar_equipamento_unidade.php?id=<?= urlencode($unidade->id) ?>">

                <input type="hidden" name="id" value="<?= htmlspecialchars($unidade->id) ?>">
                <input type="hidden" name="idEquipamento" value="<?= htmlspecialchars($unidade->idEquipamento) ?>">

                <h5 class="mt-3 mb-3">Dados da Unidade</h5>

                <div class="row g-3 mb-4">

                    <div class="col-md-6">
                        <label class="form-label">Equipamento</label>
                        <input type="text"
                            class="form-control campo-bloqueado"
                            value="<?= htmlspecialchars($unidade->equipamento) ?><?= !empty($unidade->modelo) ? ' - ' . htmlspecialchars($unidade->modelo) : '' ?>"
                            readonly>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Código</label>
                        <input type="text"
                            class="form-control campo-bloqueado"
                            value="<?= htmlspecialchars($unidade->Codigo) ?>"
                            readonly>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Nº Série</label>
                        <input type="text"
                            name="numSerie"
                            class="form-control"
                            maxlength="50"
                            value="<?= htmlspecialchars($unidade->numSerie) ?>"
                            required>
                    </div>

                </div>

                <h5 class="mt-3 mb-3">Localização e Estado</h5>

                <div class="row g-3 mb-4">

                    <div class="col-md-6">
                        <label class="form-label">Localização</label>

                        <select id="idLocalizacao" name="idLocalizacao" class="form-select" required>
                            <option value="">Selecione a localização</option>

                            <?php foreach ($localizacoes as $loc): ?>
                                <option value="<?= $loc->id ?>"
                                    <?= $unidade->idLocalizacao == $loc->id ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($loc->edificio) ?> -
                                    <?= htmlspecialchars($loc->servico) ?> -
                                    Andar <?= htmlspecialchars($loc->andar) ?> -
                                    Sala <?= htmlspecialchars($loc->sala) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>


    
                    <div class="col-md-3">
                        <label class="form-label">Estado</label>

                        <select id="estado" name="estado" class="form-select" required>
                            <option value="">Selecione</option>

                            <option value="Ativo" <?= $unidade->estado === 'Ativo' ? 'selected' : '' ?>>Ativo</option>
                            <option value="Inativo" <?= $unidade->estado === 'Inativo' ? 'selected' : '' ?>>Inativo</option>
                            <option value="Manutenção" <?= $unidade->estado === 'Manutenção' ? 'selected' : '' ?>>Manutenção</option>
                            <option value="Calibração" <?= $unidade->estado === 'Calibração' ? 'selected' : '' ?>>Calibração</option>
                            <option value="Quarentena" <?= $unidade->estado === 'Quarentena' ? 'selected' : '' ?>>Quarentena</option>
                            <option value="Abatido" <?= $unidade->estado === 'Abatido' ? 'selected' : '' ?>>Abatido</option>
                        </select>
                    </div>

                    <div class="col-md-3 d-none" id="areaDataCadastro">
                        <label class="form-label">Data da alteração*</label>
                        <input type="date"
                            name="dataAlteracaoCadastro"
                            id="dataAlteracaoCadastro"
                            class="form-control">
                    </div>

                </div>

                <h5 class="mt-3 mb-3">Dados Complementares</h5>

                <div class="row g-3">

                
                    <div class="col-md-3">
                        <label class="form-label">Ano de Fabrico</label>
                        <input type="number"
                            name="anoFabrico"
                            class="form-control"
                            min="1900"
                            max="2100"
                            value="<?= htmlspecialchars($unidade->anoFabrico) ?>"
                            required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Data de Aquisição</label>
                        <input type="date"
                            name="dataAquisicao"
                            class="form-control"
                            value="<?= htmlspecialchars($unidade->dataAquisicao) ?>"
                            required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Data Fim Garantia</label>
                        <input type="date"
                            name="dataFimGarantia"
                            class="form-control"
                            value="<?= htmlspecialchars($unidade->dataFimGarantia) ?>"
                            required>

                        <div class="mt-2 garantia-info">
                            <small class="text-muted me-1">Garantia:</small>
                            <span class="fw-semibold">
                                <?php if (!empty($unidade->anosGarantia)): ?>
                                    <?= htmlspecialchars($unidade->anosGarantia) ?>
                                    <?= $unidade->anosGarantia == 1 ? 'ano de garantia' : 'anos de garantia' ?>
                                <?php else: ?>
                                    Não definida
                                <?php endif; ?>
                            </span>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Tipo de Entrada</label>

                        <select name="tipoEntrada" class="form-select" required>
                            <option value="">Selecione</option>

                            <option value="Compra" <?= $unidade->tipoEntrada === 'Compra' ? 'selected' : '' ?>>
                                Compra
                            </option>

                            <option value="Doação" <?= $unidade->tipoEntrada === 'Doação' ? 'selected' : '' ?>>
                                Doação
                            </option>

                            <option value="Aluguer" <?= $unidade->tipoEntrada === 'Aluguer' ? 'selected' : '' ?>>
                                Aluguer
                            </option>

                            <option value="Empréstimo" <?= $unidade->tipoEntrada === 'Empréstimo' ? 'selected' : '' ?>>
                                Empréstimo
                            </option>
                        </select>
                    </div>

                    <div class="col-md-8">
                        <label class="form-label">Observações</label>
                        <textarea name="obs"
                            class="form-control"
                            rows="2"><?= htmlspecialchars($unidade->obs ?? '') ?></textarea>
                    </div>

                </div>

                <div class="mt-4 d-flex justify-content-between">

                    
                    <a href="detalhes_equipamento.php?id=<?= urlencode($id) ?>"
                    class="btn btn-secondary">
                        Voltar
                    </a>

                    <div>
                        <button type="button"
                            id="btnMostrarFornecedores"
                            class="btn btn-outline-primary me-2 <?= !empty($fornecedoresAssociadosAtuais) ? 'd-none' : '' ?>">
                            <i class="bi bi-truck"></i>
                            Associar Fornecedores
                        </button>

                        <button type="submit" id="btnGuardarUnidadeTopo" class="btn btn-primary <?= !empty($fornecedoresAssociadosAtuais) ? 'd-none' : '' ?>">
                            <i class="bi bi-save me-1"></i>
                            Guardar Alterações
                        </button>
                    </div>
                </div>

                <div id="areaFornecedores"
                    class="<?= !empty($fornecedoresAssociadosAtuais) ? '' : 'd-none' ?>">

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
                                <option value="Consumiveis">Consumíveis</option>
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
                                <tr id="linhaSemFornecedores"
                                    <?= !empty($fornecedoresAssociadosAtuais) ? 'style="display:none;"' : '' ?>>
                                    <td colspan="3" class="text-center text-muted">
                                        Nenhum fornecedor associado.
                                    </td>
                                </tr>




                                <?php foreach ($fornecedoresAssociadosAtuais as $fornAssoc): ?>
                                    <?php $chave = $fornAssoc->idFornecedor . '|' . $fornAssoc->TipoFornecedor; ?>

                                    <tr data-chave="<?= htmlspecialchars($chave) ?>">
                                        <td>
                                            <?= htmlspecialchars($fornAssoc->nome) ?>

                                            <input type="hidden"
                                                name="fornecedoresAssociados[]"
                                                value="<?= htmlspecialchars($fornAssoc->idFornecedor) ?>">
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($fornAssoc->TipoFornecedor) ?>

                                            <input type="hidden"
                                                name="tiposFornecedoresAssociados[]"
                                                value="<?= htmlspecialchars($fornAssoc->TipoFornecedor) ?>">
                                        </td>

                                        <td>
                                            <button type="button"
                                                class="btn btn-sm btn-outline-danger btn-remover-fornecedor">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>

                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 d-flex justify-content-between">
                        <a href="detalhes_equipamento.php" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i>
                            Voltar
                        </a>

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i>
                            Guardar Alterações
                        </button>
                    </div>

                </div>

            </form>

        </div>

    <?php endif; ?>

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

<script>
document.addEventListener("DOMContentLoaded", function () {
    const estadoSelect = document.getElementById("estado");
    const localizacaoSelect = document.getElementById("idLocalizacao");
    const areaDataCadastro = document.getElementById("areaDataCadastro");
    const dataAlteracaoCadastro = document.getElementById("dataAlteracaoCadastro");

    const estadoAtual = <?= json_encode($unidade->estado ?? '') ?>;
    const localizacaoAtual = <?= json_encode((string)($unidade->idLocalizacao ?? '')) ?>;

    if (!estadoSelect || !localizacaoSelect || !areaDataCadastro || !dataAlteracaoCadastro) {
        return;
    }

    function atualizarCampoDataCadastro() {
        const estadoAlterado = estadoSelect.value !== estadoAtual;
        const localizacaoAlterada = localizacaoSelect.value !== localizacaoAtual;

        if (estadoAlterado || localizacaoAlterada) {
            areaDataCadastro.classList.remove("d-none");
            dataAlteracaoCadastro.required = true;

            if (!dataAlteracaoCadastro.value) {
                dataAlteracaoCadastro.value = new Date().toISOString().split("T")[0];
            }
        } else {
            areaDataCadastro.classList.add("d-none");
            dataAlteracaoCadastro.required = false;
            dataAlteracaoCadastro.value = "";
        }
    }

    estadoSelect.addEventListener("change", atualizarCampoDataCadastro);
    localizacaoSelect.addEventListener("change", atualizarCampoDataCadastro);

    atualizarCampoDataCadastro();
});
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

                const btnGuardarUnidadeTopo = document.getElementById("btnGuardarUnidadeTopo");

                if (btnGuardarUnidadeTopo) {
                    btnGuardarUnidadeTopo.classList.add("d-none");
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

        let fornecedoresSelecionados = <?= json_encode($chavesFornecedoresAssociados) ?>;

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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/1230798.js"></script>



</body>

</html>
