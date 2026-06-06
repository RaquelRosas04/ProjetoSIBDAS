<!-- termos.php -->
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
          <a href="../login/login.php" class="btn btn-primary">Login</a>
        </div>

      </div>
    </div>
  </nav>

  <!-- CONTEÚDO -->
  <div id="conteudoTermos" class="termos-container mt-5">

    <h1>Termos e Condições</h1>

    <p><strong>Última atualização:</strong> 2026</p>


    <h4>1. Objeto</h4>
    <p>
      Os presentes Termos e Condições regulam a utilização da plataforma MedEquip,
      um sistema de gestão de equipamentos médicos destinado a instituições e utilizadores autorizados.
    </p>

    <h4>2. Aceitação dos Termos</h4>
    <p>
      Ao aceder e utilizar esta plataforma, o utilizador declara que leu, compreendeu
      e aceita integralmente os presentes Termos e Condições.
    </p>

    <h4>3. Acesso à Plataforma</h4>
    <p>
      O acesso à plataforma pode requerer autenticação através de credenciais
      fornecidas pela entidade gestora do sistema.
    </p>

    <p>
      O utilizador compromete-se a:
    </p>
    <ul>
      <li>Não partilhar as suas credenciais de acesso;</li>
      <li>Garantir a veracidade das informações introduzidas;</li>
      <li>Utilizar o sistema de forma responsável e legal.</li>
    </ul>

    <h4>4. Utilização do Sistema</h4>
    <p>
      A plataforma destina-se exclusivamente à gestão e monitorização de equipamentos,
      incluindo registo, manutenção, localização e análise de desempenho.
    </p>

    <p>
      É expressamente proibido:
    </p>
    <ul>
      <li>Utilizar o sistema para fins ilegais;</li>
      <li>Introduzir dados falsos ou manipulados;</li>
      <li>Tentar aceder a áreas não autorizadas;</li>
      <li>Comprometer a segurança da plataforma.</li>
    </ul>

    <h4>5. Proteção de Dados</h4>
    <p>
      A MedEquip compromete-se a assegurar a proteção e confidencialidade dos dados
      dos utilizadores, em conformidade com a legislação aplicável, incluindo o Regulamento Geral
      sobre a Proteção de Dados (RGPD).
    </p>

    <h4>6. Disponibilidade do Serviço</h4>
    <p>
      A MedEquip envida todos os esforços para garantir a disponibilidade contínua
      da plataforma, não sendo, contudo, responsável por interrupções resultantes de:
    </p>
    <ul>
      <li>Falhas técnicas;</li>
      <li>Manutenção programada;</li>
      <li>Fatores externos fora do seu controlo.</li>
    </ul>

    <h4>7. Responsabilidade</h4>
    <p>
      A MedEquip não se responsabiliza por danos diretos ou indiretos resultantes
      da utilização indevida da plataforma ou da interpretação incorreta dos dados apresentados.
    </p>

    <h4>8. Alterações aos Termos</h4>
    <p>
      A MedEquip reserva-se o direito de alterar os presentes Termos e Condições a qualquer momento,
      sendo as alterações devidamente publicadas nesta página.
    </p>

    <h4>9. Propriedade Intelectual</h4>
    <p>
      Todos os conteúdos, design e funcionalidades da plataforma são propriedade da MedEquip
      ou utilizados sob licença, estando protegidos por legislação aplicável.
    </p>

    <h4>10. Lei Aplicável</h4>
    <p>
      Os presentes Termos e Condições são regidos pela legislação portuguesa.
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
    document.getElementById("conteudoTermos").innerHTML =
      localStorage.getItem("termos") || "Texto default termos...";

    document.getElementById("txtEmail").innerHTML =
      "Email: " + (localStorage.getItem("email") || "geral@medint.pt");

    document.getElementById("txtTelefone").innerHTML =
      "Telefone: " + (localStorage.getItem("telefone") || "+351 912 345 678");
  </script>

</body>

</html>