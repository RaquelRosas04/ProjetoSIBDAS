<?php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/includes/funcoes.php';

redirect_if_not_logged();

$idUnidade = $_GET['id'] ?? '';
$codigoPesquisa = trim($_GET['codigo'] ?? '');

$erro = '';
$unidade = null;
$fornecedores = [];
$historico = [];
$acessorios = [];
$consumiveis = [];

function formatar_data($data)
{
    if (empty($data)) {
        return '–';
    }

    return date('d/m/Y', strtotime($data));
}

function texto_garantia($anos)
{
    if ($anos === null || $anos === '') {
        return 'Não definida';
    }

    return $anos == 1 ? '1 ano de garantia' : $anos . ' anos de garantia';
}

try {
    $ligacao = new PDO(
        "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8",
        MYSQL_USERNAME,
        MYSQL_PASSWORD
    );

    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (!empty($idUnidade) || !empty($codigoPesquisa)) {

        if (!empty($idUnidade)) {
            $where = "eu.id = ?";
            $parametro = $idUnidade;
        } else {
            $where = "eu.Codigo = ?";
            $parametro = $codigoPesquisa;
        }

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

                e.descricao AS nomeEquipamento,
                e.modelo,
                e.anosGarantia,
                e.criticidade,

                m.descricao AS marca,
                t.descricao AS categoria,
                f.nome AS fabricante,

                l.idEdificio,
                l.idServico,
                l.andar,
                l.sala

            FROM equipamentounidade eu
            INNER JOIN equipamentos e ON eu.idEquipamento = e.id
            INNER JOIN marca m ON e.idMarca = m.id
            INNER JOIN tipoequipamento t ON e.idTipo = t.id
            LEFT JOIN fabricante f ON e.idfabricante = f.id
            LEFT JOIN localizacao l ON eu.idLocalizacao = l.id
            WHERE $where
            LIMIT 1
        ");

        $stmtUnidade->execute([$parametro]);
        $unidade = $stmtUnidade->fetch(PDO::FETCH_OBJ);

        if ($unidade) {

            $stmtFornecedores = $ligacao->prepare("
                SELECT 
                    forn.nome,
                    forn.nif,
                    forn.email,
                    forn.telefone,
                    forn.www,
                    ef.TipoFornecedor
                FROM equipamentofornecedores ef
                INNER JOIN fornecedores forn ON ef.idFornecedor = forn.id
                WHERE ef.idEquipamentoUni = ?
                ORDER BY forn.nome
            ");

            $stmtFornecedores->execute([$unidade->id]);
            $fornecedores = $stmtFornecedores->fetchAll(PDO::FETCH_OBJ);

            $stmtHistorico = $ligacao->prepare("
                SELECT 
                    ec.data,
                    ec.estado,
                    l.idEdificio,
                    l.idServico,
                    l.andar,
                    l.sala
                FROM equipamentocadastro ec
                LEFT JOIN localizacao l ON ec.idlocalizacao = l.id
                WHERE ec.idequipamento = ?
                ORDER BY ec.data DESC, ec.id DESC
            ");

            $stmtHistorico->execute([$unidade->idEquipamento]);
            $historico = $stmtHistorico->fetchAll(PDO::FETCH_OBJ);

            $stmtComponentes = $ligacao->prepare("
                SELECT 
                    comp.descricao,
                    comp.modelo,
                    tipo.descricao AS tipo
                FROM equipamentocomponentes ec
                INNER JOIN equipamentos comp ON ec.idEquiComp = comp.id
                LEFT JOIN tipoequipamento tipo ON comp.idTipo = tipo.id
                WHERE ec.idEquiPai = ?
                ORDER BY comp.descricao
            ");

            $stmtComponentes->execute([$unidade->idEquipamento]);
            $componentes = $stmtComponentes->fetchAll(PDO::FETCH_OBJ);

            foreach ($componentes as $comp) {
                if (!empty($comp->tipo) && stripos($comp->tipo, 'consum') !== false) {
                    $consumiveis[] = $comp;
                } else {
                    $acessorios[] = $comp;
                }
            }
        }
    }

} catch (PDOException $e) {
    $erro = 'Erro ao carregar dados: ' . $e->getMessage();
}

include __DIR__ . '/includes/header_priv.php';

?>

<div class="container py-4">

    <h2 class="mb-4">
        <i class="bi bi-cpu me-2 text-primary"></i>
        Detalhes do Equipamento
    </h2>

    <form id="formPesquisaEquipamento"
          class="row g-3 mb-4"
          method="get"
          action="detalhes_equipamento.php">

        <div class="col-12 col-md-8">
            <input type="text"
                   name="codigo"
                   id="inputBuscaCodigo"
                   class="form-control"
                   placeholder="Introduza o Código do Equipamento"
                   value="<?= htmlspecialchars($codigoPesquisa) ?>">
        </div>

        <div class="col-12 col-md-4">
            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-search me-1"></i>
                Pesquisar
            </button>
        </div>
    </form>

    <?php if (!empty($erro)): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($erro) ?>
        </div>
    <?php endif; ?>

    <?php if ((isset($_GET['id']) || isset($_GET['codigo'])) && !$unidade && empty($erro)): ?>
        <div class="alert alert-warning">
            Não foi encontrada nenhuma unidade de equipamento.
        </div>
    <?php endif; ?>

    <?php if ($unidade): ?>

        <div id="resultadoEquipamento">

            <ul class="nav nav-tabs mb-3">

                <li class="nav-item">
                    <button class="nav-link active"
                            data-bs-toggle="tab"
                            data-bs-target="#dados"
                            type="button">
                        Dados
                    </button>
                </li>

                <li class="nav-item">
                    <button class="nav-link"
                            data-bs-toggle="tab"
                            data-bs-target="#consumivel"
                            type="button">
                        Acessórios e Consumíveis
                    </button>
                </li>

                <li class="nav-item">
                    <button class="nav-link"
                            data-bs-toggle="tab"
                            data-bs-target="#historicoEquipamento"
                            type="button">
                        Histórico de Equipamentos
                    </button>
                </li>

                <li class="nav-item">
                    <button class="nav-link"
                            data-bs-toggle="tab"
                            data-bs-target="#fornecedores"
                            type="button">
                        Fornecedores
                    </button>
                </li>

                <li class="nav-item">
                    <button class="nav-link"
                            data-bs-toggle="tab"
                            data-bs-target="#contratos"
                            type="button">
                        Contratos
                    </button>
                </li>

                <li class="nav-item">
                    <button class="nav-link"
                            data-bs-toggle="tab"
                            data-bs-target="#anexos"
                            type="button">
                        Anexos
                    </button>
                </li>

            </ul>

            <div class="tab-content">

                <div class="tab-pane fade show active" id="dados">

                    <div class="row">

                        <div class="col-12 col-md-6">
                            <p><strong>Código:</strong> <?= htmlspecialchars($unidade->Codigo) ?></p>
                            <p><strong>Nome:</strong> <?= htmlspecialchars($unidade->nomeEquipamento) ?></p>
                            <p><strong>Modelo:</strong> <?= htmlspecialchars($unidade->modelo ?? '–') ?></p>

                            <p>
                                <strong>Localização:</strong>
                                Edifício <?= htmlspecialchars($unidade->idEdificio ?? '–') ?> -
                                Serviço <?= htmlspecialchars($unidade->idServico ?? '–') ?> -
                                Andar <?= htmlspecialchars($unidade->andar ?? '–') ?> -
                                Sala <?= htmlspecialchars($unidade->sala ?? '–') ?>
                            </p>

                            <p><strong>Criticidade:</strong> <?= htmlspecialchars($unidade->criticidade ?? '–') ?></p>
                            <p><strong>Fabricante:</strong> <?= htmlspecialchars($unidade->fabricante ?? 'Não definido') ?></p>
                            <p><strong>Data Aquisição:</strong> <?= formatar_data($unidade->dataAquisicao) ?></p>
                        </div>

                        <div class="col-12 col-md-6">
                            <p><strong>Categoria:</strong> <?= htmlspecialchars($unidade->categoria ?? '–') ?></p>
                            <p><strong>Marca:</strong> <?= htmlspecialchars($unidade->marca ?? '–') ?></p>
                            <p><strong>Nº Série:</strong> <?= htmlspecialchars($unidade->numSerie ?? '–') ?></p>
                            <p><strong>Estado:</strong> <?= htmlspecialchars($unidade->estado ?? '–') ?></p>
                            <p><strong>Ano:</strong> <?= htmlspecialchars($unidade->anoFabrico ?? '–') ?></p>
                            <p><strong>Tipo Entrada:</strong> <?= htmlspecialchars($unidade->tipoEntrada ?? '–') ?></p>
                            <p><strong>Garantia:</strong> <?= texto_garantia($unidade->anosGarantia) ?></p>
                            <p><strong>Fim Garantia:</strong> <?= formatar_data($unidade->dataFimGarantia) ?></p>
                        </div>

                    </div>

                    <?php if (!empty($unidade->obs)): ?>
                        <div class="mt-3">
                            <strong>Observações:</strong>
                            <p><?= nl2br(htmlspecialchars($unidade->obs)) ?></p>
                        </div>
                    <?php endif; ?>

                    <div class="d-flex gap-2 mt-3">
                        <a href="editar_equipamento_unidade.php?id=<?= urlencode($unidade->id) ?>"
                           class="btn btn-primary mt-3">
                            <i class="bi bi-pencil"></i>
                            Editar
                        </a>

                        <button id="btnAbater"
                                class="btn btn-outline-danger mt-3"
                                data-bs-toggle="modal"
                                data-bs-target="#modalAbater">
                            <i class="bi bi-trash"></i>
                            Abater
                        </button>
                    </div>

                </div>

                <div class="tab-pane fade" id="consumivel">

                    <h5>Acessórios</h5>

                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Tipo</th>
                                <th>Quantidade</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if (!empty($acessorios)): ?>
                                <?php foreach ($acessorios as $acessorio): ?>
                                    <tr>
                                        <td>
                                            <?= htmlspecialchars($acessorio->descricao) ?>
                                            <?= !empty($acessorio->modelo) ? ' - ' . htmlspecialchars($acessorio->modelo) : '' ?>
                                        </td>
                                        <td><?= htmlspecialchars($acessorio->tipo ?? 'Acessório') ?></td>
                                        <td>1</td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="text-center text-muted">
                                        Nenhum acessório associado.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>

                    <h5>Consumíveis</h5>

                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Tipo</th>
                                <th>Quantidade</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if (!empty($consumiveis)): ?>
                                <?php foreach ($consumiveis as $consumivel): ?>
                                    <tr>
                                        <td>
                                            <?= htmlspecialchars($consumivel->descricao) ?>
                                            <?= !empty($consumivel->modelo) ? ' - ' . htmlspecialchars($consumivel->modelo) : '' ?>
                                        </td>
                                        <td><?= htmlspecialchars($consumivel->tipo ?? 'Consumível') ?></td>
                                        <td>1</td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="text-center text-muted">
                                        Nenhum consumível associado.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>

                </div>

                <div class="tab-pane fade" id="historicoEquipamento">

                    <h5 class="mt-3 mb-2">Histórico do Equipamento</h5>

                    <table class="table table-sm align-middle">

                        <thead class="table">
                            <tr>
                                <th style="width: 120px;">Data</th>
                                <th>Estado</th>
                                <th>Localização</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if (!empty($historico)): ?>
                                <?php foreach ($historico as $registo): ?>
                                    <tr>
                                        <td><?= formatar_data($registo->data) ?></td>
                                        <td><?= htmlspecialchars($registo->estado ?? '–') ?></td>
                                        <td>
                                            Edifício <?= htmlspecialchars($registo->idEdificio ?? '–') ?> -
                                            Serviço <?= htmlspecialchars($registo->idServico ?? '–') ?> -
                                            Andar <?= htmlspecialchars($registo->andar ?? '–') ?> -
                                            Sala <?= htmlspecialchars($registo->sala ?? '–') ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="text-center text-muted">
                                        Sem histórico registado.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>

                    </table>

                </div>


                

                <div class="tab-pane fade" id="fornecedores">

                    <h5 class="mt-3 mb-3">Fornecedores Associados</h5>

                    <table class="table table-sm align-middle">

                        <thead class="table-light">
                            <tr>
                                <th>Nome</th>
                                <th>NIF</th>
                                <th>Tipo</th>
                                <th>Email</th>
                                <th>Telefone</th>
                                <th>Contacto</th>
                            </tr>
                        </thead>

                        <tbody id="listaFornecedores">
                            <?php if (!empty($fornecedores)): ?>
                                <?php foreach ($fornecedores as $fornecedor): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($fornecedor->nome ?? '–') ?></td>
                                        <td><?= htmlspecialchars($fornecedor->nif ?? '–') ?></td>
                                        <td><?= htmlspecialchars($fornecedor->TipoFornecedor ?? '–') ?></td>
                                        <td><?= htmlspecialchars($fornecedor->email ?? '–') ?></td>
                                        <td><?= htmlspecialchars($fornecedor->telemovel ?? '–') ?></td>
                                        <td><?= htmlspecialchars($fornecedor->www ?? '–') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted">
                                        Nenhum fornecedor associado.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>

                    </table>

                </div>

                <div class="tab-pane fade" id="contratos">

                    <h5 class="mt-3">Documentos</h5>

                    <div class="mb-3">
                        <button class="btn btn-outline-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#modalAnexo">
                            <i class="bi bi-paperclip"></i>
                            Anexar ficheiro
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="table-custom">
                                <tr>
                                    <th>Descrição</th>
                                    <th>Nome</th>
                                    <th>Tipo</th>
                                    <th>Ação</th>
                                </tr>
                            </thead>

                            <tbody id="listaAnexos">
                                <tr>
                                    <td colspan="4" class="text-center text-muted">
                                        Sem documentos registados.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Fim Garantia:</label>
                        <p><?= formatar_data($unidade->dataFimGarantia) ?></p>
                    </div>

                </div>

                <div class="tab-pane fade" id="anexos">

                    <h5 class="mt-3">Anexos</h5>

                    <div class="d-flex flex-wrap gap-3 mt-3">
                        <p class="text-muted">Sem anexos registados.</p>
                    </div>

                </div>

                <div class="modal fade" id="modalAnexo">
                    <div class="modal-dialog">
                        <div class="modal-content">

                            <div class="modal-header">
                                <h5>Novo Anexo</h5>
                                <button class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                <input type="file" id="ficheiroAnexo" class="form-control mb-2">
                                <input type="text" id="descricaoAnexo" class="form-control" placeholder="Descrição">
                            </div>

                            <div class="modal-footer">
                                <button class="btn btn-primary" id="btnGuardarAnexo">
                                    Upload
                                </button>
                            </div>

                        </div>
                    </div>
                </div>

            </div>

        </div>

    <?php endif; ?>

</div>

<div class="modal fade" id="modalAbater" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title text-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Confirmar Abate
                </h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                Tem a certeza que deseja abater esta unidade de equipamento?
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">
                    Cancelar
                </button>

                <a href="apagar_equipamento_unidade.php?id=<?= urlencode($unidade->id) ?>"
                   class="btn btn-danger">
                    Abater
                </a>
            </div>

        </div>
    </div>
</div>

<script>
let anexos = [];

document.addEventListener("click", function(e) {

    if (e.target.id === "btnGuardarAnexo") {

        let ficheiro = document.getElementById("ficheiroAnexo").files[0];
        let descricao = document.getElementById("descricaoAnexo").value;

        if (!ficheiro || descricao === "") {
            alert("Preencha todos os campos.");
            return;
        }

        anexos.push({
            nome: ficheiro.name,
            tipo: ficheiro.name.split('.').pop(),
            desc: descricao
        });

        let tabela = document.getElementById("listaAnexos");
        tabela.innerHTML = "";

        anexos.forEach(a => {
            tabela.innerHTML += `
                <tr>
                    <td>${a.desc}</td>
                    <td>${a.nome}</td>
                    <td>${a.tipo}</td>
                    <td><button class="btn btn-sm btn-outline-primary">Ver</button></td>
                </tr>
            `;
        });

        bootstrap.Modal.getInstance(document.getElementById("modalAnexo")).hide();
    }

});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/1230798.js"></script>

<?php include __DIR__ . '/includes/modal_mensagem.php'; ?>

</body>
</html>