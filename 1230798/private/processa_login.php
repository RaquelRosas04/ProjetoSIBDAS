<?php

session_start();


require_once __DIR__ . '/../config/config.php';


if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Location: ../public/login.php');
    exit;
}

$username = $_POST['text_username'] ?? '';
$password = $_POST['text_password'] ?? '';

$validation_errors = [];

if (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
    $validation_errors[] = 'O username tem que ser um email válido.';
}

if (strlen($password) < 6 || strlen($password) > 12) {
    $validation_errors[] = 'A password deve ter entre 6 e 12 caracteres.';
}

if (!empty($validation_errors)) {
    $_SESSION['validation_errors'] = $validation_errors;
    header('Location: ../public/login.php');
    exit;
}

try {
 $ligacao = new PDO(
   "mysql:host=" . MYSQL_HOST . ";dbname=" . MYSQL_DATABASE . ";charset=utf8",
    MYSQL_USERNAME,
    MYSQL_PASSWORD
    );

    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT * FROM users WHERE email = ?";



$stmt = $ligacao->prepare($sql);
$stmt->execute([$username]);

//echo "Email recebido: " . $username . "<br>";

$user = $stmt->fetch(PDO::FETCH_OBJ);

//var_dump($user);



} catch (PDOException $e) {
    $_SESSION['server_error'] = 'Erro na ligação à base de dados.';
    header('Location: ../public/login.php');
    exit;
}

if (!$user) {
    $_SESSION['server_error'] = 'Login inválido.';
    header('Location: ../public/login.php');
    exit;
}


if ($user->pass != $password) {
    $_SESSION['server_error'] = 'Login inválido.';
    header('Location: ../public/login.php');
    exit;
}
// boa pratica para nao deixar a ligacao à bd aberta
$ligacao = null;

$_SESSION['utilizador'] = $user->email;
$_SESSION['perfil'] = $user->perfil;

header('Location: lista_equipamentos.php');
exit;