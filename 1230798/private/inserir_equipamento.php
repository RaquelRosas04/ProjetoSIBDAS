<?php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/includes/funcoes.php';

redirect_if_not_logged();

$perfilAtual = strtolower($_SESSION['perfil'] ?? '');

if ($perfilAtual === 'tecnico') {
    definir_mensagem('warning', 'Não tem permissão para adicionar equipamentos.');
    header('Location: lista_equipamentos.php');
    exit;
}

try {
    $ligacao = new PDO(
        "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8",
        MYSQL_USERNAME,
        MYSQL_PASSWORD
    );

    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Tipo de equipamentp
    $tipos = $ligacao->query("
        SELECT id, descricao
        FROM tipoequipamento
        ORDER BY descricao
    ")->fetchAll(PDO::FETCH_OBJ);

   //marcas
    $marcas = $ligacao->query("
        SELECT id, descricao
        FROM marca
        ORDER BY descricao
    ")->fetchAll(PDO::FETCH_OBJ);

   //componentes
    $componentes = $ligacao->query("
        SELECT id, descricao, modelo
        FROM equipamentos
        WHERE componente = 1
        ORDER BY descricao
    ")->fetchAll(PDO::FETCH_OBJ);

    //fabricantes
    $fabricantes = $ligacao->query("
    SELECT id, nome
    FROM fabricante
    ORDER BY nome
")->fetchAll(PDO::FETCH_OBJ);

} catch (PDOException $e) {
    die("Erro ao carregar dados: " . $e->getMessage());
}

include __DIR__ . '/includes/header_priv.php';

?>

<div class="container py-5" style="padding-top: 100px;">

    <h2 class="mb-4">
        <i class="bi bi-save me-2 text-primary"></i>
        Inserir Equipamento
    </h2>

    <div class="card p-4 shadow-sm">

        <form method="post" action="processa_inserir_equipamento.php" enctype="multipart/form-data">
            <h5 class="mt-3 mb-3">Dados do Equipamento</h5>

            <div class="row g-3">

                <div class="col-md-5">
                    <label class="form-label">Designação*</label>
                    <input type="text"
                           name="descricao"
                           class="form-control"
                           required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Tipo / Categoria*</label>
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

                        <label class="form-label">Fabricante</label>

                        <select name="idFabricante" class="form-select" >
                            <option value="">Selecione</option>

                            <?php foreach ($fabricantes as $fabricante): ?>
                                <option value="<?= $fabricante->id ?>">
                                    <?= htmlspecialchars($fabricante->nome) ?>
                                </option>
                            <?php endforeach; ?>

                        </select>
                    </div>
                                    <div class="col-md-3">
                    <label class="form-label">Marca*</label>
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
                    <label class="form-label">Modelo*</label>
                    <input type="text"
                           name="modelo"
                           class="form-control"
                           required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Anos de Garantia*</label>
                    <input type="number"
                           name="anosGarantia"
                           class="form-control"
                           min="0"
                           required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Criticidade*</label>
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

            <hr class="my-4">


            <h5 class="mt-3 mb-3">
                <i class="bi bi-file-earmark-text me-1"></i>
                Manuais
            </h5>

            <!--Separador Manuais -->

            <div class="row g-3">

                <div class="col-md-4">
                    <label class="form-label">Manual de Serviço</label>
                    <input type="file" name="manualSer" id="manualSer" class="form-control">
                    <a href="#" id="verManualSer" class="btn btn-sm btn-outline-primary mt-2 d-none" target="_blank">
                        <i class="bi bi-eye"></i> Ver
                    </a>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Manual Técnico</label>
                    <input type="file" name="manualTec" id="manualTec" class="form-control">
                    <a href="#" id="verManualTec" class="btn btn-sm btn-outline-primary mt-2 d-none" target="_blank">
                        <i class="bi bi-eye"></i> Ver
                    </a>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Manual Consumíveis</label>
                    <input type="file" name="manualCon" id="manualCon" class="form-control">
                    <a href="#" id="verManualCon" class="btn btn-sm btn-outline-primary mt-2 d-none" target="_blank">
                        <i class="bi bi-eye"></i> Ver
                    </a>
                </div>

            </div>

            <!--Para mostrar área dos componentes -->
            <div class="mt-4 d-flex justify-content-between">
             <a href="lista_equipamentos.php" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i>
               Voltar
             </a>

            <div>
                    <button type="button"
                            id="btnMostrarComponentes"
                            class="btn btn-outline-primary me-2">
                        <i class="bi bi-diagram-3"></i>
                        Associar Componentes
                    </button>

                    <button type="submit" id="btnInserirEquipamentoTopo" class="btn btn-primary">
                       <!-- por a voltar para para a lista equipamentos-->
                        <i class="bi bi-plus-circle me-1"></i>
                        Inserir Equipamento
                    </button>
                </div>
            </div>

            <!-- delimita a área de componentes-->
            <div id="areaComponentes" class="d-none">
            <hr class="my-4">

            <h5 class="mt-3 mb-3">Componentes associados</h5>

            <div class="row g-2 mb-3 align-items-end">

                <div class="col-md-8">
                    <label class="form-label">Componente</label>
                    <select id="selectComponente" class="form-select">
                        <option value="">Selecione um componente</option>

                        <?php foreach ($componentes as $comp): ?>
                            <option value="<?= $comp->id ?>"
                                    data-descricao="<?= htmlspecialchars($comp->descricao) ?>"
                                    data-modelo="<?= htmlspecialchars($comp->modelo ?? '') ?>">
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
                        <tr id="linhaSemComponentes">
                            <td colspan="3" class="text-center text-muted">
                                Nenhum componente associado.
                            </td>
                        </tr>
                    </tbody>
                </table>
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

const selectComponente = document.getElementById('selectComponente');
const btnAdicionar = document.getElementById('btnAdicionarComponente');
const tabelaBody = document.querySelector('#tabelaComponentes tbody');
const linhaSemComponentes = document.getElementById('linhaSemComponentes');

let componentesSelecionados = [];

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

    if (!botao) return;

    const tr = botao.closest('tr');
    const id = tr.getAttribute('data-id');

    componentesSelecionados = componentesSelecionados.filter(item => item !== id);

    tr.remove();

    if (componentesSelecionados.length === 0) {
        linhaSemComponentes.style.display = '';
    }
});
</script>


<script>
document.getElementById("btnMostrarComponentes").onclick = function () {
    document.getElementById("areaComponentes").classList.remove("d-none");
    document.getElementById("btnInserirEquipamentoTopo").classList.add("d-none");
    this.classList.add("d-none");
};
</script>

 <!--Para mostrar os botoes de ver manuais -->

<script>
document.addEventListener("DOMContentLoaded", function () {
    function prepararPreview(inputId, linkId) {
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

    prepararPreview("manualSer", "verManualSer");
    prepararPreview("manualTec", "verManualTec");
    prepararPreview("manualCon", "verManualCon");
});
</script>




<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/1230798.js"></script>





</body>
</html>
