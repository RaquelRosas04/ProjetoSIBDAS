<?php require_once __DIR__ . '/../../config/config.php'; ?>

<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <title><?php echo APP_NAME; ?></title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/1230798.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="icon" href="/assets/images/aba.png" type="image/png">

  <style>
    html {
      scroll-behavior: smooth;
    }
  </style>
</head>

<body>

<!-- HERO -->
<section class="hero" id="home">

  <div class="overlay"></div>

  <!-- NAVBAR -->
 <nav class="navbar navbar-expand-lg navbar-dark fixed-top custom-navbar">
  <div class="container-fluid px-3">

    <!-- LOGO (ESQUERDA) -->
    <a href="index.php" class="navbar-brand">
      <img src="../assets/images/logo.png" height="45">
    </a>

    <!-- BOTÃO MOBILE -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navContent">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- CONTEÚDO -->
    <div class="collapse navbar-collapse">

      <!-- MENU CENTRADO -->
<ul class="navbar-nav mx-auto nav-center">
  <li class="nav-item">
    <a href="#sobre" class="nav-link">Sobre</a>
  </li>
  <li class="nav-item">
    <a href="#funcionalidades" class="nav-link">Funcionalidades</a>
  </li>
  <li class="nav-item">
    <a href="#como" class="nav-link">Como Funciona</a>
  </li>
  <li class="nav-item">
    <a href="#contactos" class="nav-link">Contactos</a>
  </li>
</ul>

      <!-- LOGIN (DIREITA) -->
      <div>
        <a href="../public/login.php" class="btn btn-primary">Login</a>
      </div>

    </div>
  </div>
</nav>