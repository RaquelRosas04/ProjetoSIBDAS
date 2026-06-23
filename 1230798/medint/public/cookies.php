<!DOCTYPE html>
<html lang="pt">

<head>
  <meta charset="UTF-8">
  <title>Termos e Condições - MedEquip</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/1230798.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="icon" href="../assets/images/logo_.png" type="image/png">

</head>

<body>


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
            <a href="index.php#sobre" class="nav-link">Sobre</a>
          </li>
          <li class="nav-item">
            <a href="index.php#funcionalidades" class="nav-link">Funcionalidades</a>
          </li>
          <li class="nav-item">
            <a href="index.php#como" class="nav-link">Como Funciona</a>
          </li>
          <li class="nav-item">
            <a href="index.php#contactos" class="nav-link">Contactos</a>
          </li>
        </ul>

        <!-- LOGIN (DIREITA) -->
        <div>
          <a href="login.php" class="btn btn-primary">Login</a>
        </div>

      </div>
    </div>
  </nav>

  <!-- CONTEÚDO -->
  <div id="conteudoCookies" class="termos-container">

    <h1>Política de Cookies</h1>

    <h4>O que são Cookies?</h4>
    <p>
      “Cookies” são pequenas etiquetas de software que são armazenadas no seu computador através do navegador (browser),
      retendo apenas informação relacionada com as suas preferências, não incluindo, como tal, os seus dados pessoais.
    </p>

    <h4>Para que servem os Cookies?</h4>
    <p>
      Os cookies servem para ajudar a determinar a utilidade, interesse e o número de utilizações dos websites,
      permitindo uma navegação mais rápida e eficiente, eliminando a necessidade de introduzir repetidamente as mesmas informações.
    </p>

    <h4>Tipos de Cookies utilizados</h4>

    <ul>
      <li><strong>Cookies estritamente necessários:</strong> Permitem navegar no website e utilizar as suas funcionalidades.</li>

      <li><strong>Cookies analíticos:</strong> Utilizados anonimamente para análise estatística e melhoria do website.</li>

      <li><strong>Cookies de funcionalidade:</strong> Guardam as preferências do utilizador.</li>
    </ul>

    <h4>Cookies de Terceiros</h4>
    <p>
      Utilizamos serviços como o Google Analytics para recolher informação sobre a utilização do website.
      Esta informação é recolhida de forma anónima e utilizada para melhorar a experiência do utilizador.
    </p>

    <h4>Como pode gerir os Cookies?</h4>
    <p>
      Todos os browsers permitem ao utilizador aceitar, recusar ou apagar cookies através das configurações do navegador.
    </p>

    <p>
      Pode configurar os cookies no menu “opções” ou “preferências” do seu browser. No entanto, ao desativar cookies,
      algumas funcionalidades do website podem não funcionar corretamente.
    </p>

    <h4>O que acontece quando desativa os Cookies?</h4>
    <p>
      Algumas funcionalidades podem deixar de funcionar, como a identificação do utilizador ou personalização da experiência.
      A navegação poderá ser afetada e menos eficiente.
    </p>

    <h4>Mais informação</h4>
    <p>
      Pode obter mais informações sobre cookies em:
      <a href="https://www.allaboutcookies.org" target="_blank">www.allaboutcookies.org</a>
    </p>

  </div>

  <!-- CONTACTOS / FOOTER -->
  <section id="contactos" class="bg-dark text-white py-5">
    <div class="container text-center">

      <h4 class="mb-3">Contactos</h4>
      <p id="txtEmail">Email: geral@medint.pt</p>
      <p>Telefone: +351 912 345 678</p>

    </div>
  </section>


  <!-- FOOTER -->
  <?php include '../private/includes/footer_pub.php'; ?>


  <script>
    document.getElementById("conteudoCookies").innerHTML =
      localStorage.getItem("cookies") || "Texto default cookies...";

    document.getElementById("txtEmail").innerHTML =
      "Email: " + (localStorage.getItem("email") || "geral@medint.pt");

    document.getElementById("txtTelefone").innerHTML =
      "Telefone: " + (localStorage.getItem("telefone") || "+351 912 345 678");
  </script>

</body>

</html>
