// ----------------------------------------DETALHES_EQUIPAMENTO------------------------------------------
//----------------------------------------DETALHES EQUIPAMENTOS---------------------------------


const form = document.getElementById("formPesquisaEquipamento");

if (form) {
  form.addEventListener("submit", function (e) {

    e.preventDefault();

    const codigo = document.getElementById("inputBuscaCodigo").value.trim();

    const equipamentos = [
      {
        codigo: "AB0012",
        nome: "Monitor de Sinais Vitais",
        marca: "GE",
        modelo: "TSV",
        serie: "1298748930WE",
        localizacao: "Gabinete 3",
        estado: "Ativo",
        criticidade: "Alta"
      }
    ];

    const equipamento = equipamentos.find(eq => eq.codigo === codigo);

    if (equipamento) {

      document.getElementById("txtNome").textContent = equipamento.nome;
      document.getElementById("txtMarca").textContent = equipamento.marca;
      document.getElementById("txtModelo").textContent = equipamento.modelo;
      document.getElementById("txtNSerie").textContent = equipamento.serie;
      document.getElementById("txtLocalizacao").textContent = equipamento.localizacao;
      document.getElementById("txtEstado").textContent = equipamento.estado;
      document.getElementById("txtCriticidade").textContent = equipamento.criticidade;

      document.getElementById("resultadoEquipamento").style.display = "block";

    } else {
      const modal = new bootstrap.Modal(document.getElementById('modalErro'));
      modal.show();
    }
  });
}


// LISTA DE EQUIPAMENTOS -- BOTÃO VER
document.querySelectorAll(".btn-ver").forEach(btn => {
  btn.addEventListener("click", function () {

    document.getElementById("mFornecedor").textContent = this.dataset.fornecedor;
    document.getElementById("mAno").textContent = this.dataset.ano;
    document.getElementById("mAquisicao").textContent = this.dataset.aquisicao;
    document.getElementById("mGarantia").textContent = this.dataset.garantia;
    document.getElementById("mTipo").textContent = this.dataset.tipo;
    document.getElementById("mEntrada").textContent = this.dataset.entrada;

    const modal = new bootstrap.Modal(document.getElementById("modalDetalhes"));
    modal.show();
  });
});


// Filtros-- lista_equipamentos

function aplicarFiltros() {

  const fCodigo = document.getElementById("fCodigo").value.toLowerCase();
  const fNome = document.getElementById("fNome").value.toLowerCase();
  const fGrupo = document.getElementById("fGrupo").value.toLowerCase();
  const fMarca = document.getElementById("fMarca").value.toLowerCase();
  const fModelo = document.getElementById("fModelo").value.toLowerCase();
  const fSerie = document.getElementById("fSerie").value.toLowerCase();
  const fCriticidade = document.getElementById("fCriticidade").value.toLowerCase();
  const fEdificio = document.getElementById("fEdificio").value.toLowerCase();
  const fServico = document.getElementById("fServico").value.toLowerCase();
  const fAndar = document.getElementById("fAndar").value.toLowerCase();
  const fSala = document.getElementById("fSala").value.toLowerCase();
  const fFabricante = document.getElementById("fFabricante").value.toLowerCase();
  const fAno = document.getElementById("fAno").value.toLowerCase();
  const fEstado = document.getElementById("fEstado").value.toLowerCase();
  const fGarantia = document.getElementById("fGarantia").value.toLowerCase();


  document.querySelectorAll(" tbody tr").forEach(row => {

    const td = row.querySelectorAll("td");

    const match =
      td[0].textContent.toLowerCase().includes(fCodigo) &&
      td[1].textContent.toLowerCase().includes(fNome) &&
      td[2].textContent.toLowerCase().includes(fGrupo) &&
      td[3].textContent.toLowerCase().includes(fMarca) &&
      td[4].textContent.toLowerCase().includes(fModelo) &&
      td[5].textContent.toLowerCase().includes(fSerie) &&
      td[6].textContent.toLowerCase().includes(fCriticidade) &&
      td[7].textContent.toLowerCase().includes(fEdificio) &&
      td[8].textContent.toLowerCase().includes(fServico)&&
      td[9].textContent.toLowerCase().includes(fAndar)&&
      td[10].textContent.toLowerCase().includes(fSala)&&
      td[11].textContent.toLowerCase().includes(fFabricante)&&
      td[12].textContent.toLowerCase().includes(fAno)&&
      td[13].textContent.toLowerCase().includes(fEstado)&&
      td[14].textContent.toLowerCase().includes(fGarantia);
      

    row.style.display = match ? "" : "none";
  });
}

// botão filtrar
document.getElementById("btnFiltrar")?.addEventListener("click", aplicarFiltros);

// filtro automático ao escrever
document.querySelectorAll("#fCodigo, #fNome, #fGrupo, #fMarca, #fModelo, #fSerie, #fEdificio, #fServico, #fAndar, #fSala, #fFabricante, #fAno, #fEstado, #fGarantia")
  .forEach(input => {
    input.addEventListener("input", aplicarFiltros);
  });

// selects também
document.querySelectorAll("#fEstado, #fCriticidade")
  .forEach(select => {
    select.addEventListener("change", aplicarFiltros);
  });

// botão limpar
document.getElementById("btnLimpar")?.addEventListener("click", function () {

  document.querySelectorAll("#fCodigo, #fNome, #fGrupo, #fMarca, #fModelo, #fSerie, #fLocal, #fEdificio, #fServico, #fAndar, #fSala, #fFabricante, #fAno, #fEstado, #fGarantia")
    .forEach(input => input.value = "");

  document.getElementById("fEstado").value = "";
  document.getElementById("fCriticidade").value = "";

  aplicarFiltros(); // mostrar tudo outra vez
});



// =======================
//  ANEXOS
// =======================

let anexos = [];

// CLICK GLOBAL (EVITA ERROS DE NULL)
document.addEventListener("click", function(e) {

  // 👉 BOTÃO UPLOAD
  if (e.target.id === "btnGuardarAnexo") {

    let ficheiroInput = document.getElementById("ficheiroAnexo");
    let descricaoInput = document.getElementById("descricaoAnexo");

    if (!ficheiroInput || !descricaoInput) return;

    let ficheiro = ficheiroInput.files[0];
    let descricao = descricaoInput.value.trim();

    // VALIDAÇÃO
    if (!ficheiro || descricao === "") {
      alert("Preencha todos os campos");
      return;
    }

    // GUARDAR
    anexos.push({
      nome: ficheiro.name,
      tipo: ficheiro.name.split('.').pop().toUpperCase(),
      descricao: descricao
    });

    atualizarTabelaAnexos();

    // LIMPAR CAMPOS
    ficheiroInput.value = "";
    descricaoInput.value = "";

    // FECHAR MODAL
    let modal = bootstrap.Modal.getInstance(document.getElementById("modalAnexo"));
    if (modal) modal.hide();
  }

});


// =======================
// ATUALIZAR TABELA
// =======================
function atualizarTabelaAnexos() {

  let tabela = document.getElementById("listaAnexos");
  if (!tabela) return;

  tabela.innerHTML = "";

  anexos.forEach((a, index) => {

    tabela.innerHTML += `
      <tr>
        <td>${a.descricao}</td>
        <td>${a.nome}</td>
        <td>${a.tipo}</td>
        <td>
          <button class="btn btn-sm btn-outline-primary">Ver</button>
          <button class="btn btn-sm btn-danger btn-remover-anexo" data-index="${index}">
            <i class="bi bi-trash"></i>
          </button>
        </td>
      </tr>
    `;
  });

}


// =======================
// REMOVER ANEXO
// =======================
document.addEventListener("click", function(e) {

  if (e.target.closest(".btn-remover-anexo")) {

    let index = e.target.closest(".btn-remover-anexo").dataset.index;

    anexos.splice(index, 1);
    atualizarTabelaAnexos();
  }

});



// =======================
// Meter o numero na pesquisa
// =======================
const params = new URLSearchParams(window.location.search);
const id = params.get("id");

console.log("Equipamento:", id);

if (id) {
  document.getElementById("txtCodigo").innerText = id;
}






//----------------------------------------LOGIN-------------------------------------------


document.getElementById("formLogin")?.addEventListener("submit", function (e) {

  e.preventDefault();

  const email = document.getElementById("email").value.trim();
  const password = document.getElementById("password").value.trim();

  // validação simples
  if (!email || !password) {
    alert("Preencha todos os campos!");
    return;
  }

  // SIMULAÇÃO (depois ligas ao backend)
  if (email === "admin@medeint.pt" && password === "1234") {

    // guardar sessão (simples)
    localStorage.setItem("user", email);

    // redirecionar
    window.location.href = "../private/lista_equipamentos.html";

  } else {
const modalElement = document.getElementById('modalErroLogin');

if (modalElement) {
  const modal = new bootstrap.Modal(modalElement);
  modal.show();
} else {
  console.error("Modal não encontrado");
}

  }

});



//----------------------------------------FILTROS FORNECEDORES------------------------------------


function filtrarTabela() {

  let nome = document.getElementById("fNome").value.toLowerCase();
  let nif = document.getElementById("fNIF").value.toLowerCase();
  let email = document.getElementById("fEmail").value.toLowerCase();
  let telefone = document.getElementById("fTelefone").value.toLowerCase();
  let cod = document.getElementById("fCodPostal").value.toLowerCase();
  let morada = document.getElementById("fMorada").value.toLowerCase();

  let linhas = document.querySelectorAll("tbody tr");

  linhas.forEach(linha => {

    let col = linha.querySelectorAll("td");

    let mostrar =
      col[0].innerText.toLowerCase().includes(nome) &&
      col[1].innerText.toLowerCase().includes(nif) &&
      col[2].innerText.toLowerCase().includes(email) &&
      col[3].innerText.toLowerCase().includes(telefone) &&
      col[4].innerText.toLowerCase().includes(cod) &&
      col[5].innerText.toLowerCase().includes(morada);

    linha.style.display = mostrar ? "" : "none";
  });
}

 // FILTRO AUTOMÁTICO
  document.querySelectorAll("#fNome, #fNIF, #fEmail, #fTelefone, #fCodPostal, #fMorada")
    .forEach(input => {
      input.addEventListener("input", filtrarTabela);
    });

  //  LIMPAR
  let btnLimpar = document.getElementById("btnLimpar");
  if (btnLimpar) {
    btnLimpar.addEventListener("click", function () {

      document.querySelectorAll("input").forEach(i => i.value = "");

      document.querySelectorAll("tbody tr").forEach(linha => {
        linha.style.display = "";
      });

    });
  }



  //----------------------------------------editar:fornecedorrrrrrrr---------------------------------

  // EDITAR FORNECEDOR
document.addEventListener("click", function(e) {

  if (e.target.closest(".btn-editar")) {

    let linha = e.target.closest("tr");
    let col = linha.querySelectorAll("td");

    let fornecedor = {
      nome: col[0].innerText,
      nif: col[1].innerText,
      email: col[2].innerText,
      telefone: col[3].innerText,
      codPostal: col[4].innerText,
      morada: col[5].innerText,
      obs: "" // podes melhorar depois
    };

    // guardar no browser
    localStorage.setItem("fornecedorEditar", JSON.stringify(fornecedor));

    // ir para página editar
    window.location.href = "editar_fornecedor.html";
  }

});

// CARREGAR DADOS
document.addEventListener("DOMContentLoaded", function() {

  let fornecedor = JSON.parse(localStorage.getItem("fornecedorEditar"));

  if (fornecedor) {
    document.getElementById("nome").value = fornecedor.nome;
    document.getElementById("nif").value = fornecedor.nif;
    document.getElementById("email").value = fornecedor.email;
    document.getElementById("telefone").value = fornecedor.telefone;
    document.getElementById("codPostal").value = fornecedor.codPostal;
    document.getElementById("morada").value = fornecedor.morada;
    document.getElementById("obs").value = fornecedor.obs;
  }

});


let formFornecedor = document.getElementById("formEditarFornecedor");

if (formFornecedor) {
  formFornecedor.addEventListener("submit", function(e) {
    e.preventDefault();

    let modal = new bootstrap.Modal(document.getElementById('modalSucesso'));
    modal.show();
  });
}



//---------------------------LOCALIZAÇAO------------------------------------------

let linhaParaApagar = null;

document.addEventListener("click", function(e) {

  // 👁️ VER EQUIPAMENTOS
  if (e.target.closest(".btn-ver")) {

    let linha = e.target.closest("tr");

    let tabela = document.getElementById("listaEquipamentos");
    tabela.innerHTML = "";

    let equipamentos = [
      { codigo: "EQ001", nome: "Ventilador", estado: "Ativo" },
      { codigo: "EQ002", nome: "Monitor Cardíaco", estado: "Manutenção" }
    ];

    equipamentos.forEach(eq => {

      let badge =
        eq.estado === "Ativo"
          ? '<span class="badge bg-success">Ativo</span>'
          : '<span class="badge bg-warning text-dark">Manutenção</span>';

      tabela.innerHTML += `
        <tr>
          <td>
            <a href="detalhes_equipamento.html?id=${eq.codigo}">
              ${eq.codigo}
            </a>
          </td>
          <td>${eq.nome}</td>
          <td>${badge}</td>
        </tr>
      `;
    });

    new bootstrap.Modal(document.getElementById("modalEquipamentos")).show();
  }

  // 🗑️ CLICK NO LIXO
  if (e.target.closest(".btn-apagar")) {

    linhaParaApagar = e.target.closest("tr");

    new bootstrap.Modal(document.getElementById("modalApagar")).show();
  }

  // ✅ CONFIRMAR APAGAR
  if (e.target.id === "confirmarApagar") {

    if (linhaParaApagar) {
      linhaParaApagar.remove();
    }

    bootstrap.Modal.getInstance(document.getElementById("modalApagar")).hide();
  }

});


//-----------------------------------Filtros Localizaçao----------------------------

// =======================
// FILTROS LOCALIZAÇÕES
// =======================

function aplicarFiltrosLocalizacoes() {

  const fEdificio = document.getElementById("fNome").value.toLowerCase();
  const fServico = document.getElementById("fNIF").value.toLowerCase();
  const fAndar = document.getElementById("fEmail").value.toLowerCase();
  const fSala = document.getElementById("fTelefone").value.toLowerCase();

  document.querySelectorAll("table tbody tr").forEach(row => {

    const td = row.querySelectorAll("td");

    const match =
      td[0].textContent.toLowerCase().includes(fEdificio) &&
      td[1].textContent.toLowerCase().includes(fServico) &&
      td[2].textContent.toLowerCase().includes(fAndar) &&
      td[3].textContent.toLowerCase().includes(fSala);

    row.style.display = match ? "" : "none";
  });
}

// BOTÃO FILTRAR
document.getElementById("btnFiltrar")?.addEventListener("click", aplicarFiltrosLocalizacoes);

// FILTRO AUTOMÁTICO AO ESCREVER
document.querySelectorAll("#fNome, #fNIF, #fEmail, #fTelefone")
  .forEach(input => {
    input.addEventListener("input", aplicarFiltrosLocalizacoes);
  });

  document.getElementById("btnLimpar")?.addEventListener("click", function () {

  document.querySelectorAll("#fNome, #fNIF, #fEmail, #fTelefone")
    .forEach(input => input.value = "");

  aplicarFiltrosLocalizacoes(); // mostra tudo outra vez
});