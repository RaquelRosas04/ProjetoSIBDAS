<?php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/includes/funcoes.php';

redirect_if_not_logged();

$perfilAtual = strtolower($_SESSION['perfil'] ?? '');
$isGestor = ($perfilAtual === 'gestor');
$dominioEmail = 'medint.pt';

if (!$isGestor) {
    definir_mensagem('danger', 'Não tem permissões para aceder a esta página.');
    header('Location: lista_equipamentos.php');
    exit;
}

function gerar_email_por_nome($nome, $dominio)
{
    $nome = trim($nome);
    $nome = iconv('UTF-8', 'ASCII//TRANSLIT', $nome);
    $nome = strtolower($nome);
    $nome = preg_replace('/[^a-z0-9 ]/', '', $nome);
    $partes = preg_split('/\s+/', $nome, -1, PREG_SPLIT_NO_EMPTY);

    if (count($partes) === 0) {
        return '';
    }

    $primeiroNome = $partes[0];
    $ultimoNome = count($partes) > 1 ? $partes[count($partes) - 1] : '';
    $baseEmail = $ultimoNome !== '' ? $primeiroNome . '.' . $ultimoNome : $primeiroNome;

    return $baseEmail . '@' . $dominio;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmarPassword = $_POST['confirmar_password'] ?? '';
    $perfil = $_POST['perfil'] ?? '';

    $perfisPermitidos = ['Gestor', 'Tecnico'];

    if (strlen($nome) < 2) {
        definir_mensagem('danger', 'Introduza o nome.');
        header('Location: criar_conta.php');
        exit;
    }

    $email = gerar_email_por_nome($nome, $dominioEmail);

    if (strlen($password) < 6 || strlen($password) > 12) {
        definir_mensagem('danger', 'A password deve ter entre 6 e 12 caracteres.');
        header('Location: criar_conta.php');
        exit;
    }

    if ($password !== $confirmarPassword) {
        definir_mensagem('danger', 'As passwords nao coincidem.');
        header('Location: criar_conta.php');
        exit;
    }

    if (!in_array($perfil, $perfisPermitidos, true)) {
        definir_mensagem('danger', 'Selecione um perfil valido.');
        header('Location: criar_conta.php');
        exit;
    }

    try {
        $ligacao = new PDO(
            "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8",
            MYSQL_USERNAME,
            MYSQL_PASSWORD
        );

        $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $emailBase = substr($email, 0, strpos($email, '@'));
        $emailFinal = $email;
        $contador = 2;

        $stmtExiste = $ligacao->prepare("SELECT email FROM users WHERE email = ? LIMIT 1");

        do {
            $stmtExiste->execute([$emailFinal]);
            $emailExiste = $stmtExiste->fetch(PDO::FETCH_OBJ);

            if ($emailExiste) {
                $emailFinal = $emailBase . $contador . '@' . $dominioEmail;
                $contador++;
            }
        } while ($emailExiste);

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $stmtInserir = $ligacao->prepare("
            INSERT INTO users (email, password, perfil)
            VALUES (?, ?, ?)
        ");

        $stmtInserir->execute([
            $emailFinal,
            $passwordHash,
            $perfil
        ]);

        definir_mensagem('success', 'Conta criada com sucesso. Email: ' . $emailFinal);
        header('Location: criar_conta.php');
        exit;

    } catch (PDOException $e) {
        definir_mensagem('danger', 'Erro ao criar conta: ' . $e->getMessage());
        header('Location: criar_conta.php');
        exit;
    }
}

include __DIR__ . '/includes/header_priv.php';

?>

<div class="container py-5" style="padding-top: 100px;">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">
            <i class="bi bi-person-plus me-2 text-primary"></i>
            Criar Conta
        </h2>
    </div>

    <div class="card p-4 shadow-sm">
        <form method="post" action="criar_conta.php" autocomplete="off">

            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">Nome</label>
                    <input type="text"
                           name="nome"
                           id="nomeConta"
                           class="form-control"
                           required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Perfil</label>
                    <select name="perfil" class="form-select" required>
                        <option value="">Selecione...</option>
                        <option value="Gestor">Gestor</option>
                        <option value="Tecnico">Técnico</option>
                    </select>
                </div>

                <div class="col-md-12">
                    <label class="form-label">Email Gerado</label>
                    <input type="text"
                           id="emailGerado"
                           class="form-control bg-readonly"
                           readonly>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Password</label>
                    <input type="password"
                           name="password"
                           class="form-control"
                           minlength="6"
                           maxlength="12"
                           required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Confirmar Password</label>
                    <input type="password"
                           name="confirmar_password"
                           id="confirmarPassword"
                           class="form-control"
                           minlength="6"
                           maxlength="12"
                           required>
                    <div class="invalid-feedback">
                        As passwords nao coincidem.
                    </div>
                </div>

                <div class="col-12 d-flex justify-content-end gap-2 mt-4">
                    <a href="lista_equipamentos_unidade.php" class="btn btn-outline-secondary">
                        Cancelar
                    </a>

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>
                        Criar Conta
                    </button>
                </div>

            </div>

        </form>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const nomeConta = document.getElementById("nomeConta");
    const emailGerado = document.getElementById("emailGerado");
    const password = document.querySelector('input[name="password"]');
    const confirmarPassword = document.getElementById("confirmarPassword");
    const formCriarConta = document.querySelector('form[action="criar_conta.php"]');
    const dominioEmail = "<?= htmlspecialchars($dominioEmail) ?>";

    function gerarEmail(nome) {
        const partes = nome
            .normalize("NFD")
            .replace(/[\u0300-\u036f]/g, "")
            .toLowerCase()
            .replace(/[^a-z0-9 ]/g, "")
            .trim()
            .split(/\s+/)
            .filter(Boolean);

        if (partes.length === 0) {
            return "";
        }

        const primeiroNome = partes[0];
        const ultimoNome = partes.length > 1 ? partes[partes.length - 1] : "";
        const baseEmail = ultimoNome !== "" ? primeiroNome + "." + ultimoNome : primeiroNome;

        return baseEmail + "@" + dominioEmail;
    }

    nomeConta?.addEventListener("input", function () {
        emailGerado.value = gerarEmail(nomeConta.value);
    });

    function validarPasswords() {
        if (!password || !confirmarPassword) {
            return true;
        }

        const passwordsIguais = confirmarPassword.value === "" || password.value === confirmarPassword.value;

        confirmarPassword.classList.toggle("is-invalid", !passwordsIguais);
        confirmarPassword.setCustomValidity(passwordsIguais ? "" : "As passwords nao coincidem.");

        return passwordsIguais;
    }

    password?.addEventListener("input", validarPasswords);
    confirmarPassword?.addEventListener("input", validarPasswords);

    formCriarConta?.addEventListener("submit", function (e) {
        if (!validarPasswords()) {
            e.preventDefault();
            confirmarPassword.focus();
        }
    });
});
</script>

<?php include __DIR__ . '/includes/modal_mensagem.php'; ?>

</body>
</html>
