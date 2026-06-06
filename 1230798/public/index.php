<?php include '../private/includes/header_pub.php'; ?>


<div class="hero-content text-center">
  <h1>Gestão Inteligente de Equipamentos</h1>
  <p>Controlo total, eficiência máxima e segurança hospitalar.</p>

  <a href="../login/login.php" class="btn btn-primary btn-lg mt-3">
    Entrar no Sistema
  </a>
</div>

</section>

<!-- SOBRE -->
<section class="container py-5" id="sobre">

  <div class="container">

    <div class="row align-items-center">

      <div class="col-md-6">
        <h3 class="mb-3">Sobre Nós</h3>

        <p id="sobre1" class="text-muted">
          A MedInt é uma plataforma dedicada à gestão de equipamentos médicos,
          focada na eficiência operacional, segurança e controlo total dos ativos hospitalares.
        </p>

        <p id="sobre2" class="text-muted">
          Desenvolvemos soluções modernas que permitem às instituições otimizar processos,
          reduzir custos e garantir a qualidade dos serviços prestados.
        </p>
      </div>

      <div class="col-md-6 text-center">
        <img src="../assets/images/nos.jpg" class="img-fluid rounded shadow">
      </div>

    </div>

  </div>

</section>

<!-- FEATURES -->
<section class="sobre py-5" id="funcionalidades">

  <div class="row g-4">

    <div class="col-md-4">
      <div class="feature-box">
        <i class="bi bi-cpu"></i>
        <h5>Gestão Centralizada</h5>
        <p>Todos os equipamentos num único sistema.</p>
      </div>
    </div>

    <div class="col-md-4">
      <div class="feature-box">
        <i class="bi bi-tools"></i>
        <h5>Manutenção</h5>
        <p>Controle de estado e intervenções.</p>
      </div>
    </div>

    <div class="col-md-4">
      <div class="feature-box">
        <i class="bi bi-bar-chart"></i>
        <h5>Relatórios</h5>
        <p>Análise avançada de desempenho.</p>
      </div>
    </div>

  </div>

</section>



<!-- VALORES -->
<section class="bg-light py-5">

  <div class="container text-center">

    <h3 class="mb-4">Porque escolher a MedInt?</h3>

    <div class="row">

      <div class="col-md-4">
        <i class="bi bi-shield-check fs-1 mb-3"></i>
        <h5>Segurança</h5>
        <p class="text-muted">Proteção total dos dados e processos.</p>
      </div>

      <div class="col-md-4">
        <i class="bi bi-lightning-charge fs-1 mb-3"></i>
        <h5>Eficiência</h5>
        <p class="text-muted">Automatização e rapidez operacional.</p>
      </div>

      <div class="col-md-4">
        <i class="bi bi-globe fs-1 mb-3"></i>
        <h5>Escalabilidade</h5>
        <p class="text-muted">Adaptável a qualquer instituição.</p>
      </div>

    </div>

  </div>

</section>

<!-- COMO FUNCIONA -->
<section class="py-5 sobre" id="como">
  <div class="container text-center">

    <h3 class="mb-5">Como Funciona</h3>

    <div class="row">

      <div class="col-md-4">
        <i class="bi bi-upload fs-1 mb-3"></i>
        <h5>Registar</h5>
        <p class="text-muted">Adiciona equipamentos ao sistema.</p>
      </div>

      <div class="col-md-4">
        <i class="bi bi-gear fs-1 mb-3"></i>
        <h5>Gerir</h5>
        <p class="text-muted">Controla estado e manutenção.</p>
      </div>

      <div class="col-md-4">
        <i class="bi bi-bar-chart fs-1 mb-3"></i>
        <h5>Analisar</h5>
        <p class="text-muted">Consulta relatórios e desempenho.</p>
      </div>

    </div>

  </div>
</section>

<!-- CONTACTOS / FOOTER -->
<section id="contactos" class="bg-dark text-white py-5">
  <div class="container text-center">

    <h4 class="mb-3">Contactos</h4>
    <p id="txtEmail">Email: geral@medint.pt</p>
    <p id="txtTelefone">Telefone: +351 912 345 678</p>

  </div>
</section>

<?php include '../private/includes/footer_pub.php'; ?>

<script>
  document.getElementById("sobre1").innerHTML =
    localStorage.getItem("sobre1") || "Texto default 1";

  document.getElementById("sobre2").innerHTML =
    localStorage.getItem("sobre2") || "Texto default 2";

  document.getElementById("txtEmail").innerHTML =
    "Email: " + (localStorage.getItem("email") || "geral@medint.pt");

  document.getElementById("txtTelefone").innerHTML =
    "Telefone: " + (localStorage.getItem("telefone") || "+351 912 345 678");
</script>

</body>


</html>