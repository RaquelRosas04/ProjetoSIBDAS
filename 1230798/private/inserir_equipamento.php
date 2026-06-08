<?php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/includes/funcoes.php';

redirect_if_not_logged();

$erro = '';
$sucesso = '';


?>

<?php include 'includes/header_priv.php'; ?>






<!DOCTYPE html>
<html lang="pt">

<head>
  <meta charset="UTF-8">
  <title>Lista de Equipamentos</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/1230798.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <link rel="icon" href="../assets/images/aba.png" type="image/png">

</head>



<body class="container py-5">

  <!-- NAVBAR -->
  <nav class="navbar navbar-expand-lg navbar-dark fixed-top custom-navbar">
    <div class="container-fluid px-3">
      <a href="dashboard.php" class="navbar-brand">
        <img src="../assets/images/logo.png" height="45">
      </a>
    </div>
  </nav>

  <!-- CONTEÚDO -->
  <div class="container py-5" style="padding-top: 100px;">

    <!-- TÍTULO -->
    <h2 class="mb-4">
      <i class="bi bi-plus-circle me-2 text-primary"></i>
      Inserir Equipamento
    </h2>

    <!-- FORM -->
    <div class="card p-4 shadow-sm">

      <form>

        <!--  DADOS DO EQUIPAMENTO -->
        <h5 class="class=mt-3 mb-2">Dados do Equipamento</h5>

        <div class="row g-3 mb-4">

          <div class="col-md-2">
            <label class="form-label">Código</label>
            <input type="text" id="codigo" class="form-control" required>
          </div>

          <div class="col-md-4">
            <label class="form-label">Designação</label>
            <input type="text" id="designacao" class="form-control" required>
          </div>

          <div class="col-md-3">
            <label class="form-label">Grupo</label>
            <select id="grupo" class="form-select">
              <option>Gastro</option>
              <option>Oftalmologia</option>
              <option>Cardiologia</option>
              <option>Dentaria</option>
            </select>
          </div>

          <div class="col-md-3">
            <label class="form-label">Marca</label>
            <input type="text" id="marca" class="form-control">
          </div>

          <div class="col-md-3">
            <label class="form-label">Modelo</label>
            <input type="text" id="modelo" class="form-control">
          </div>

          <div class="col-md-4">
            <label class="form-label">Nº Série</label>
            <input type="text" id="numSerie" class="form-control">
          </div>


          <div class="col-md-4">
            <label class="form-label">Criticidade</label>
            <select id="criticidade" class="form-select">
              <option>Baixo</option>
              <option>Médio</option>
              <option>Alto</option>
              <option>Suporte de vida</option>
            </select>
          </div>

        </div>


        <h5 class="class=mt-3 mb-2">Localização</h5>

        <div class="row g-3 mb-4">

          <div class="col-md-4">
            <label class="form-label">Edifício</label>
            <input type="text" id="edificio" class="form-control" required>
          </div>

          <div class="col-md-3">
            <label class="form-label">Serviço</label>
            <input type="text" id="servico" class="form-control" required>
          </div>

          <div class="col-md-2">
            <label class="form-label">Andar</label>
            <input type="number" id="andar" class="form-control" required>
          </div>

          <div class="col-md-2">
            <label class="form-label">Sala</label>
            <input type="text" id="sala" class="form-control" required>
          </div>

        </div>


        <!-- OUTROS DADOS -->
        <h5 class="class=mt-3 mb-2">Outros Dados</h5>

        <div class="row g-3">

          <div class="col-md-4">
            <label class="form-label">Fabricante</label>
            <input type="text" id="fabricante" class="form-control">
          </div>

          <div class="col-md-3">
            <label class="form-label">Data de aquisição</label>
            <input type="date" id="dataAquisicao" class="form-control">
          </div>

          <div class="col-md-2">
            <label class="form-label">Ano de Fabrico</label>
            <input type="number" id="anoFabrico" class="form-control">
          </div>


          <div class="col-md-3">
            <label class="form-label">Custo de aquisição</label>
            <input type="number" id="custoAquisicao" class="form-control">
          </div>


          <div class="col-md-4">
            <label class="form-label">Tipo de Entrada</label>
            <select id="tipoEntrada" class="form-select">
              <option>Compra</option>
              <option>Doação</option>
              <option>Aluguer</option>
              <option>Empréstimo</option>
            </select>
          </div>

          <div class="col-md-3">
            <label class="form-label">Estado</label>
            <select id="estado" class="form-select">
              <option>Ativo</option>
              <option>Em manutenção</option>
              <option>Em calibração</option>
              <option>Em Quarentena</option>
              <option>Inativo</option>
              <option>Abatido</option>
            </select>
          </div>




          <div class="col-md-3">
            <label class="form-label">Data Fim Garantia</label>
            <input type="date" id="dataGarantia" class="form-control">
          </div>



          <div class="col-12">
            <label class="form-label">Observações</label>
            <textarea class="form-control" rows="3"></textarea>
          </div>

        </div>


        <!-- BOTÕES -->
        <div class="mt-4 d-flex justify-content-between"> <a href="lista_equipamentos.php"
            class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Voltar </a> <button type="submit" class="btn btn-primary"> <i
              class="bi bi-plus-circle me-1"></i> Inserir Equipamento </button>
        </div>


      </form>

    </div>

  </div>



  <div class="modal fade" id="modalSucesso" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">

        <div class="modal-header">
          <h5 class="modal-title text-success">
            <i class="bi bi-check-circle me-2"></i>
            Sucesso
          </h5>
        </div>

        <div class="modal-body">
          Equipamento inserido com sucesso!<br><br>
          Deseja inserir outro equipamento?
        </div>

        <div class="modal-footer">
          <button class="btn btn-outline-secondary" id="btnIrLista">
            Concluir
          </button>

          <button class="btn btn-primary" id="btnNovo">
            Inserir outro
          </button>
        </div>

      </div>
    </div>
  </div>




    <script>
    document.getElementById("formLocalizacao").addEventListener("submit", function (e) {

      e.preventDefault();

      let edificio = document.getElementById("edificio").value.trim();
      let servico = document.getElementById("servico").value.trim();
      let andar = document.getElementById("andar").value.trim();
      let sala = document.getElementById("sala").value.trim();

      if (!edificio || !servico || !andar || !sala) {

        let erro = document.getElementById("msgErro");
        erro.classList.remove("d-none");

        setTimeout(() => {
          erro.classList.add("d-none");
        }, 3000);

        return;
      }

      // 🔥 SIMULAÇÃO (depois PHP)
      console.log("Localização inserida");

      // REDIRECIONAR
      window.location.href = "localizacoes.php";

    });
  </script>
  

<script src="../js/1230798.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>

    document.addEventListener("DOMContentLoaded", function () {

      let form = document.getElementById("formEquipamento");

      form.addEventListener("submit", function (e) {

        e.preventDefault();

        let msg = "";
        let erroCampo = null;

        // limpar erros antigos
        document.querySelectorAll(".is-invalid").forEach(el => {
          el.classList.remove("is-invalid");
        });

        // 🔎 CAMPOS (EXATOS DO TEU FORM)
        let codigo = document.getElementById("codigo");
        let designacao = document.getElementById("designacao");
        let grupo = document.getElementById("grupo");
        let modelo = document.getElementById("modelo");
        let numSerie = document.getElementById("numSerie");
        let criticidade = document.getElementById("criticidade");

        let edificio = document.getElementById("edificio");
        let servico = document.getElementById("servico");
        let andar = document.getElementById("andar");
        let sala = document.getElementById("sala");

        let dataAquisicao = document.getElementById("dataAquisicao");
        let dataGarantia = document.getElementById("dataGarantia");

        // VALIDAÇÃO CAMPOS
        if (!codigo.value.trim()) {
          msg = "Preencha o código";
          erroCampo = codigo;
        }
        else if (!designacao.value.trim()) {
          msg = "Preencha a designação";
          erroCampo = designacao;
        }
        else if (!grupo.value) {
          msg = "Selecione o grupo";
          erroCampo = grupo;
        }
        else if (!numSerie.value.trim()) {
          msg = "Preencha o nº de série";
          erroCampo = numSerie;
        }
        else if (!criticidade.value) {
          msg = "Selecione a criticidade";
          erroCampo = criticidade;
        }
        else if (!edificio.value.trim()) {
          msg = "Preencha o edifício";
          erroCampo = edificio;
        }
        else if (!servico.value.trim()) {
          msg = "Preencha o serviço";
          erroCampo = servico;
        }
        else if (!andar.value.trim()) {
          msg = "Preencha o andar";
          erroCampo = andar;
        }
        else if (!sala.value.trim()) {
          msg = "Preencha a sala";
          erroCampo = sala;
        }

        //  VALIDAÇÃO DATAS
        else if (dataAquisicao.value && dataGarantia.value) {

          let d1 = new Date(dataAquisicao.value);
          let d2 = new Date(dataGarantia.value);

          if (d2 <= d1) {
            msg = "A garantia deve ser posterior à aquisição";
            erroCampo = dataGarantia;
          }
        }

        //  VALIDAÇÃO Nº SÉRIE (simulação)
        let seriesExistentes = ["123", "ABC", "XYZ"];

        if (numSerie.value && seriesExistentes.includes(numSerie.value)) {
          msg = "Nº de série já existe";
          erroCampo = numSerie;
        }

        //  MOSTRAR ERRO
        if (msg !== "") {

          let toast = document.getElementById("msgErro");

          toast.innerText = msg;
          toast.classList.add("toast-show");

          setTimeout(() => {
            toast.classList.remove("toast-show");
          }, 3000);

          if (erroCampo) {
            erroCampo.classList.add("is-invalid");
            erroCampo.focus();
          }

          return;
        }

        //  SUCESSO
        let modal = new bootstrap.Modal(document.getElementById("modalSucesso"));
        modal.show();

      });

    });


  </script>




</body>

</html>