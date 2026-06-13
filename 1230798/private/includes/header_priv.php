<?php require_once __DIR__ . '/../../config/config.php'; ?>

<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <title><?php echo APP_NAME; ?></title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- para dropdow pesquisavel no inserir_equipamento-->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
    <!-- CSS -->
    <link rel="stylesheet" href="../css/1230798.css">
    <link rel="icon" href="../assets/images/aba.png" type="image/png">



</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top custom-navbar">
        <div class="container-fluid px-3">

            <!-- LOGO (ESQUERDA) -->
            <a href="dashboard.php" class="navbar-brand d-flex align-items-center">
                <img src="../assets/images/logo.png" height="45" class="me-2 logo-nav">
            </a>

            <!-- BOTÃO MOBILE -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- CONTEÚDO -->
            <div class="collapse navbar-collapse" id="navContent">

                <!-- MENU CENTRADO -->
                <ul class="navbar-nav mx-auto nav-center">

                    <li class="nav-item">
                        <a class="nav-link <?= ($currentPage == 'dashboard.php') ? 'active' : '' ?>"href="dashboard.php">Dashboard</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link <?= ($currentPage == 'detalhes_equipamento.php') ? 'active' : '' ?>" href="detalhes_equipamento.php">Detalhes</a>
                    </li>


                    <li class="nav-item dropdown">
                        <a class="nav-link <?= ($currentPage == 'lista_equipamentos.php') ? 'active' : '' ?>"href="lista_equipamentos.php" data-bs-toggle="dropdown">
                            Equipamentos
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="lista_equipamentos.php">Lista</a></li>
                            <li><a class="dropdown-item" href="inserir_equipamento.php">Inserir Equipamento</a></li>
                            

                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link <?= ($currentPage == 'lista_equipamentos.php') ? 'active' : '' ?>"href="lista_equipamentos.php" data-bs-toggle="dropdown">
                            Inventário
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="lista_equipamentos_unidade.php">Lista</a></li>
                            <li><a class="dropdown-item" href="inserir_equipamento_unidade.php">Inserir Equipamento Série</a></li>
                            

                        </ul>
                    </li>


                    <li class="nav-item">
                        <a class="nav-link <?= ($currentPage == 'fornecedores.php') ? 'active' : '' ?>" href="fornecedores.php">Fornecedores</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link <?= ($currentPage == 'localizacoes.php') ? 'active' : '' ?>" href="localizacoes.php">Localizações</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link <?= ($currentPage == 'admin_editar.php') ? 'active' : '' ?>" href="admin_editar.php">Conteúdos</a>
                    </li>

                </ul>

                <!-- DIREITA -->
                <div>
                   <!-- <button class="btn btn-outline-light">Logout</button> -->
                    <button
                        class="btn btn-outline-light"
                        onclick="window.location.href='/1230798/public/logout.php'">
                        Logout
                    </button>

                </div>

            </div>
        </div>
    </nav>