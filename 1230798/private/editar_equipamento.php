<?php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/includes/funcoes.php';

redirect_if_not_logged();

$erros = [];


function guardar_manual($campo, $manualAtual = null)
{
    if (empty($_FILES[$campo]['name'])) {
        return $manualAtual;
    }

    $extensoesPermitidas = ['pdf', 'doc', 'docx'];
    $nomeOriginal = $_FILES[$campo]['name'];
    $extensao = strtolower(pathinfo($nomeOriginal, PATHINFO_EXTENSION));

    if (!in_array($extensao, $extensoesPermitidas, true)) {
        throw new Exception('Tipo de ficheiro inválido em ' . $campo . '.');
    }

    $pastaDestino = __DIR__ . '/../uploads/manuais/';

    if (!is_dir($pastaDestino)) {
        mkdir($pastaDestino, 0777, true);
    }

    $nomeFicheiro = $campo . '_' . uniqid() . '.' . $extensao;
    $caminhoServidor = $pastaDestino . $nomeFicheiro;
    $caminhoBD = '../uploads/manuais/' . $nomeFicheiro;

    move_uploaded_file($_FILES[$campo]['tmp_name'], $caminhoServidor);

    return $caminhoBD;
}

try {
    $ligacao = new PDO(
        "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8",
        MYSQL_USERNAME,
        MYSQL_PASSWORD
    );

    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $id = $_POST['id'] ?? '';

        $descricao = trim($_POST['descricao'] ?? '');
        $idTipo = $_POST['idTipo'] ?? '';
        $idfabricante = $_POST['idfabricante'] ?? null;

        if ($idfabricante === '') {
            $idfabricante = null;
        }

        $idMarca = $_POST['idMarca'] ?? '';
        $modelo = trim($_POST['modelo'] ?? '');
        $anosGarantia = $_POST['anosGarantia'] ?? '';
        $criticidade = $_POST['criticidade'] ?? '';
        $componente = $_POST['componente'] ?? 0;
        $componentes = $_POST['componentes'] ?? [];

        $componente = ($componente == 1 || $componente === 'Sim') ? 1 : 0;

        if ($id === '') {
            $erros[] = 'Equipamento inválido.';
        }

        if ($descricao === '') {
            $erros[] = 'Preencha a designação do equipamento.';
        }

        if ($idTipo === '') {
            $erros[] = 'Selecione o tipo/categoria.';
        }

        if ($idMarca === '') {
            $erros[] = 'Selecione a marca.';
        }

        if ($modelo === '') {
            $erros[] = 'Preencha o modelo.';
        }

        if ($anosGarantia === '') {
            $erros[] = 'Preencha os anos de garantia.';
        }

        if ($criticidade === '') {
            $erros[] = 'Selecione a criticidade.';
        }

        if (!empty($erros)) {
            $_SESSION['validation_errors'] = $erros;
            header('Location: editar_equipamento.php?id=' . urlencode($id));
            exit;
        }

        $ligacao->beginTransaction();

            $stmtManuaisAtuais = $ligacao->prepare("
                SELECT manualSer, manualTec, manualCon
                FROM equipamentos
                WHERE id = ?
            ");
            $stmtManuaisAtuais->execute([$id]);
            $manuaisAtuais = $stmtManuaisAtuais->fetch(PDO::FETCH_OBJ);

            $manualSer = guardar_manual('manualSer', $manuaisAtuais->manualSer ?? null);
            $manualTec = guardar_manual('manualTec', $manuaisAtuais->manualTec ?? null);
            $manualCon = guardar_manual('manualCon', $manuaisAtuais->manualCon ?? null);



        $sqlEquipamento = "
            UPDATE equipamentos
            SET descricao = ?,
                idTipo = ?,
                idfabricante = ?,
                idMarca = ?,
                modelo = ?,
                anosGarantia = ?,
                criticidade = ?,
                componente = ?,
                manualSer = ?,
                manualTec = ?,
                manualCon = ?
            WHERE id = ?
        ";

        $stmtEquipamento = $ligacao->prepare($sqlEquipamento);

        $stmtEquipamento->execute([
            $descricao,
            $idTipo,
            $idfabricante,
            $idMarca,
            $modelo,
            $anosGarantia,
            $criticidade,
            ($componente ? "\x01" : "\x00"),
             $manualSer,
            $manualTec,
            $manualCon,
            $id
        ]);

        $stmtDelete = $ligacao->prepare("
            DELETE FROM equipamentocomponentes
            WHERE idEquiPai = ?
        ");
        $stmtDelete->execute([$id]);

        $componentes = array_unique($componentes);

        foreach ($componentes as $idComponente) {
            if (!empty($idComponente) && $idComponente != $id) {

                $stmtComponente = $ligacao->prepare("
                    INSERT INTO equipamentocomponentes
                    (
                        idEquiPai,
                        idEquiComp
                    )
                    VALUES (?, ?)
                ");

                $stmtComponente->execute([
                    $id,
                    $idComponente
                ]);
            }
        }

        $ligacao->commit();

        if (function_exists('definir_mensagem')) {
            definir_mensagem('success', 'Equipamento alterado com sucesso.');
        }

        header('Location: lista_equipamentos.php');
        exit;
    }

    $id = $_GET['id'] ?? '';

    if ($id === '') {
        header('Location: lista_equipamentos.php');
        exit;
    }

    $stmtEquipamento = $ligacao->prepare("
        SELECT id,
               descricao,
               idTipo,
               idfabricante,
               idMarca,
               modelo,
               anosGarantia,
               criticidade,
               componente + 0 AS componente,
               manualSer,
               manualTec,
               manualCon
        FROM equipamentos
        WHERE id = ?
    ");
    $stmtEquipamento->execute([$id]);
    $equipamento = $stmtEquipamento->fetch(PDO::FETCH_OBJ);

    if (!$equipamento) {
        header('Location: lista_equipamentos.php');
        exit;
    }

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

    $stmtComponentes = $ligacao->prepare("
        SELECT id, descricao, modelo
        FROM equipamentos
        WHERE componente = 1
          AND id <> ?
        ORDER BY descricao
    ");

       //fabricantes
    $fabricantes = $ligacao->query("
    SELECT id, nome
    FROM fabricante
    ORDER BY nome
")->fetchAll(PDO::FETCH_OBJ);

    $stmtComponentes->execute([$id]);
    $componentes = $stmtComponentes->fetchAll(PDO::FETCH_OBJ);

    $stmtAssociados = $ligacao->prepare("
        SELECT ec.idEquiComp,
               e.descricao,
               e.modelo
        FROM equipamentocomponentes ec
        INNER JOIN equipamentos e ON ec.idEquiComp = e.id
        WHERE ec.idEquiPai = ?
        ORDER BY e.descricao
    ");
    $stmtAssociados->execute([$id]);
    $componentesAssociados = $stmtAssociados->fetchAll(PDO::FETCH_OBJ);

    $idsComponentesAssociados = [];

    foreach ($componentesAssociados as $compAssoc) {
        $idsComponentesAssociados[] = (string) $compAssoc->idEquiComp;
    }

    $validation_errors = $_SESSION['validation_errors'] ?? [];
    unset($_SESSION['validation_errors']);

} catch (PDOException $e) {
    die("Erro ao carregar dados: " . $e->getMessage());
}

include __DIR__ . '/includes/header_priv.php';

?>

<div class="container py-5" style="padding-top: 100px;">

    <h2 class="mb-4">
        <i class="bi bi-pencil-square me-2 text-primary"></i>
        Editar Equipamento
    </h2>

    <div class="card p-4 shadow-sm">

        <?php if (!empty($validation_errors)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($validation_errors as $erro): ?>
                        <li><?= htmlspecialchars($erro) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

<form method="post"
      action="editar_equipamento.php?id=<?= urlencode($equipamento->id) ?>"
      enctype="multipart/form-data">

            <input type="hidden" name="id" value="<?= htmlspecialchars($equipamento->id) ?>">

            <h5 class="mt-3 mb-3">Dados do Equipamento</h5>

            <div class="row g-3">

                <div class="col-md-5">
                    <label class="form-label">Designação</label>
                    <input type="text"
                           name="descricao"
                           class="form-control"
                           value="<?= htmlspecialchars($old_input['descricao'] ?? $equipamento->descricao) ?>"
                           required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Tipo / Categoria</label>
                    <select name="idTipo" class="form-select" required>
                        <option value="">Selecione</option>

                        <?php foreach ($tipos as $tipo): ?>
                            <option value="<?= $tipo->id ?>"
                                <?= (($old_input['idTipo'] ?? $equipamento->idTipo) == $tipo->id) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($tipo->descricao) ?>
                            </option>
                        <?php endforeach; ?>

                    </select>
                </div>

                <div class="col-md-3">

                        <label class="form-label">Fabricante</label>

                        <select name="idfabricante" class="form-select" >
                            <option value="">Selecione</option>
                            <option value="">Sem fabricante / Não aplicável</option>
                            <?php foreach ($fabricantes as $fabricante): ?>
                                <option value="<?= $fabricante->id ?>"
                                    <?= (($old_input['idfabricante'] ?? $equipamento->idfabricante) == $fabricante->id) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($fabricante->nome) ?>
                                </option>
                            <?php endforeach; ?>

                        </select>
                    </div>



                <div class="col-md-3">
                    <label class="form-label">Marca</label>
                    <select name="idMarca" class="form-select" required>
                        <option value="">Selecione</option>

                        <?php foreach ($marcas as $marca): ?>
                            <option value="<?= $marca->id ?>"
                                <?= (($old_input['idMarca'] ?? $equipamento->idMarca) == $marca->id) ? 'selected' : '' ?>>
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
                           value="<?= htmlspecialchars($old_input['modelo'] ?? $equipamento->modelo) ?>"
                           required>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Anos de Garantia</label>
                    <input type="number"
                           name="anosGarantia"
                           class="form-control"
                           min="0"
                           value="<?= htmlspecialchars($old_input['anosGarantia'] ?? $equipamento->anosGarantia) ?>"
                           required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Criticidade</label>
                    <select name="criticidade" class="form-select" required>
                        <option value="">Selecione</option>

                        <option value="Baixa" <?= $equipamento->criticidade === 'Baixa' ? 'selected' : '' ?>>
                            Baixa
                        </option>

                        <option value="Média" <?= $equipamento->criticidade === 'Média' ? 'selected' : '' ?>>
                            Média
                        </option>

                        <option value="Alta" <?= $equipamento->criticidade === 'Alta' ? 'selected' : '' ?>>
                            Alta
                        </option>

                        <option value="Suporte de vida" <?= $equipamento->criticidade === 'Suporte de vida' ? 'selected' : '' ?>>
                            Suporte de vida
                        </option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">É componente?</label>
                    <select name="componente" class="form-select" required>
                        <option value="0" <?= $equipamento->componente == 0 ? 'selected' : '' ?>>
                            Não
                        </option>

                        <option value="1" <?= $equipamento->componente == 1 ? 'selected' : '' ?>>
                            Sim
                        </option>
                    </select>
                </div>

            </div>

<hr class="my-4">

<h5 class="mt-3 mb-3">
    <i class="bi bi-file-earmark-text me-1"></i>
    Manuais
</h5>

<div class="row g-3">

    <div class="col-md-4">
        <label class="form-label">Manual de Serviço</label>
        <input type="file" name="manualSer" id="manualSer" class="form-control">

        <?php if (!empty($equipamento->manualSer)): ?>
            <a href="<?= htmlspecialchars($equipamento->manualSer) ?>"
               class="btn btn-sm btn-outline-primary mt-2"
               target="_blank">
                <i class="bi bi-eye"></i> Ver atual
            </a>
        <?php endif; ?>

        <a href="#" id="verManualSer" class="btn btn-sm btn-outline-secondary mt-2 d-none" target="_blank">
            <i class="bi bi-eye"></i> Ver novo
        </a>
    </div>

    <div class="col-md-4">
        <label class="form-label">Manual Técnico</label>
        <input type="file" name="manualTec" id="manualTec" class="form-control">

        <?php if (!empty($equipamento->manualTec)): ?>
            <a href="<?= htmlspecialchars($equipamento->manualTec) ?>"
               class="btn btn-sm btn-outline-primary mt-2"
               target="_blank">
                <i class="bi bi-eye"></i> Ver atual
            </a>
        <?php endif; ?>

        <a href="#" id="verManualTec" class="btn btn-sm btn-outline-secondary mt-2 d-none" target="_blank">
            <i class="bi bi-eye"></i> Ver novo
        </a>
    </div>

    <div class="col-md-4">
        <label class="form-label">Manual Consumíveis</label>
        <input type="file" name="manualCon" id="manualCon" class="form-control">

        <?php if (!empty($equipamento->manualCon)): ?>
            <a href="<?= htmlspecialchars($equipamento->manualCon) ?>"
               class="btn btn-sm btn-outline-primary mt-2"
               target="_blank">
                <i class="bi bi-eye"></i> Ver atual
            </a>
        <?php endif; ?>

        <a href="#" id="verManualCon" class="btn btn-sm btn-outline-secondary mt-2 d-none" target="_blank">
            <i class="bi bi-eye"></i> Ver novo
        </a>
    </div>

</div>

            <div class="mt-4 d-flex justify-content-between">
                <a href="lista_equipamentos.php" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i>
                    Voltar
                </a>

                <div>
                    <button type="button"
                            id="btnMostrarComponentes"
                            class="btn btn-outline-primary me-2 <?= !empty($componentesAssociados) ? 'd-none' : '' ?>">
                        <i class="bi bi-diagram-3"></i>
                        Associar Componentes
                    </button>

                    <button type="submit" id="btnGuardarEquipamentoTopo" class="btn btn-primary <?= !empty($componentesAssociados) ? 'd-none' : '' ?>">
                        <i class="bi bi-save me-1"></i>
                        Guardar Alterações
                    </button>
                </div>
            </div>

            <div id="areaComponentes" class="<?= !empty($componentesAssociados) ? '' : 'd-none' ?>">

                <hr class="my-4">

                <h5 class="mt-3 mb-3">Componentes associados</h5>

                <div class="row g-2 mb-3 align-items-end">

                    <div class="col-md-8">
                        <label class="form-label">Componente</label>

                        <select id="selectComponente" class="form-select">
                            <option value="">Selecione um componente</option>

                            <?php foreach ($componentes as $comp): ?>
                                <option value="<?= $comp->id ?>"
                                        data-descricao="<?= htmlspecialchars($comp->descricao, ENT_QUOTES) ?>"
                                        data-modelo="<?= htmlspecialchars($comp->modelo ?? '', ENT_QUOTES) ?>">
                                    <?= htmlspecialchars($comp->descricao) ?>
                                    <?= !empty($comp->modelo) ? ' - ' . htmlspecialchars($comp->modelo) : '' ?>
                                </option>
                            <?php endforeach; ?>

                        </select>
                    </div>

                    <div class="col-md-2">
                        <button type="button"
                                id="btnAdicionarComponente"
                                class="btn btn-outline-primary w-100">
                            <i class="bi bi-plus-circle"></i>
                            Adicionar
                        </button>
                    </div>

                </div>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle mb-0" id="tabelaComponentes">
                        <thead class="table-custom">
                            <tr>
                                <th>Componente</th>
                                <th>Modelo</th>
                                <th style="width: 120px;">Ações</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr id="linhaSemComponentes"
                                <?= !empty($componentesAssociados) ? 'style="display:none;"' : '' ?>>
                                <td colspan="3" class="text-center text-muted">
                                    Nenhum componente associado.
                                </td>
                            </tr>

                            <?php foreach ($componentesAssociados as $compAssoc): ?>
                                <tr data-id="<?= htmlspecialchars($compAssoc->idEquiComp) ?>">
                                    <td>
                                        <?= htmlspecialchars($compAssoc->descricao) ?>

                                        <input type="hidden"
                                               name="componentes[]"
                                               value="<?= htmlspecialchars($compAssoc->idEquiComp) ?>">
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($compAssoc->modelo ?? 'Sem modelo') ?>
                                    </td>

                                    <td>
                                        <button type="button"
                                                class="btn btn-sm btn-outline-danger btn-remover-componente">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                        </tbody>
                    </table>
                </div>

                <div class="mt-4 d-flex justify-content-between">
                    <a href="lista_equipamentos.php" class="btn btn-outline-secondary">
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

</div>

<div class="modal fade" id="modalComponentes" tabindex="-1">
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
                <p class="mb-0" id="textoModalComponentes"></p>
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
document.addEventListener('DOMContentLoaded', function () {

    function mostrarModalComponentes(mensagem) {
        const texto = document.getElementById('textoModalComponentes');
        const modalElemento = document.getElementById('modalComponentes');

        if (!texto || !modalElemento) {
            alert(mensagem);
            return;
        }

        texto.textContent = mensagem;

        const modal = new bootstrap.Modal(modalElemento);
        modal.show();
    }

    const btnMostrarComponentes = document.getElementById('btnMostrarComponentes');
    const areaComponentes = document.getElementById('areaComponentes');

    if (btnMostrarComponentes && areaComponentes) {
        btnMostrarComponentes.addEventListener('click', function () {
            areaComponentes.classList.remove('d-none');
            btnMostrarComponentes.classList.add('d-none');

            const btnGuardarEquipamentoTopo = document.getElementById('btnGuardarEquipamentoTopo');

            if (btnGuardarEquipamentoTopo) {
                btnGuardarEquipamentoTopo.classList.add('d-none');
            }
        });
    }

    const selectComponente = document.getElementById('selectComponente');
    const btnAdicionar = document.getElementById('btnAdicionarComponente');
    const tabelaBody = document.querySelector('#tabelaComponentes tbody');
    const linhaSemComponentes = document.getElementById('linhaSemComponentes');

    if (!selectComponente || !btnAdicionar || !tabelaBody || !linhaSemComponentes) {
        return;
    }

    let componentesSelecionados = <?= json_encode($idsComponentesAssociados) ?>;

    btnAdicionar.addEventListener('click', function () {

        const option = selectComponente.options[selectComponente.selectedIndex];

        if (!option.value) {
            mostrarModalComponentes('Selecione um componente.');
            return;
        }

        const id = option.value;
        const descricao = option.dataset.descricao;
        const modelo = option.dataset.modelo || 'Sem modelo';

        if (componentesSelecionados.includes(id)) {
            mostrarModalComponentes('Este componente já foi adicionado.');
            return;
        }

        componentesSelecionados.push(id);

        linhaSemComponentes.style.display = 'none';

        const tr = document.createElement('tr');
        tr.setAttribute('data-id', id);

        tr.innerHTML = `
            <td>
                ${descricao}
                <input type="hidden" name="componentes[]" value="${id}">
            </td>
            <td>${modelo}</td>
            <td>
                <button type="button"
                        class="btn btn-sm btn-outline-danger btn-remover-componente">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        `;

        tabelaBody.appendChild(tr);
        selectComponente.value = '';
    });

    tabelaBody.addEventListener('click', function (e) {

        const botao = e.target.closest('.btn-remover-componente');

        if (!botao) {
            return;
        }

        const tr = botao.closest('tr');
        const id = tr.getAttribute('data-id');

        componentesSelecionados = componentesSelecionados.filter(item => item !== id);

        tr.remove();

        if (componentesSelecionados.length === 0) {
            linhaSemComponentes.style.display = '';
        }
    });

});


function prepararPreviewManual(inputId, linkId) {
    const input = document.getElementById(inputId);
    const link = document.getElementById(linkId);

    if (!input || !link) return;

    input.addEventListener("change", function () {
        const ficheiro = input.files[0];

        if (!ficheiro) {
            link.classList.add("d-none");
            link.removeAttribute("href");
            return;
        }

        link.href = URL.createObjectURL(ficheiro);
        link.classList.remove("d-none");
    });
}

prepararPreviewManual("manualSer", "verManualSer");
prepararPreviewManual("manualTec", "verManualTec");
prepararPreviewManual("manualCon", "verManualCon");
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/1230798.js"></script>

<?php include __DIR__ . '/includes/modal_mensagem.php'; ?>

</body>
</html>
