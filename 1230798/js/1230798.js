// ======================================== EQUIPAMENTOS=========================================


// ---------------------------------------------------------------------------------------------
// ----------------------------------------DETALHES_EQUIPAMENTO------------------------------------------
// ---------------------------------------------------------------------------------------------

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







//  ANEXOS


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




///NAO SEI BEM O QUE FAZ --------------------------------------------------------------


// ATUALIZAR TABELA
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



// REMOVER ANEXO
document.addEventListener("click", function(e) {

  if (e.target.closest(".btn-remover-anexo")) {

    let index = e.target.closest(".btn-remover-anexo").dataset.index;

    anexos.splice(index, 1);
    atualizarTabelaAnexos();
  }

});




// Meter o numero na pesquisa
const params = new URLSearchParams(window.location.search);
const id = params.get("id");

console.log("Equipamento:", id);

if (id) {
  document.getElementById("txtCodigo").innerText = id;
}



// ---------------------------------------------------------------------------------------------
// ----------------------------------------LISTA_EQUIPAMENTOS-----------------------------------
// ---------------------------------------------------------------------------------------------

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

// Filtros

function aplicarFiltros() {

 const fCodigo = document.getElementById("fCodigo").value.toLowerCase();
  const fNome = document.getElementById("fNome").value.toLowerCase();
  const fMarca = document.getElementById("fMarca").value.toLowerCase();
  const fModelo = document.getElementById("fModelo").value.toLowerCase();
  const fSerie = document.getElementById("fSerie").value.toLowerCase();
  const fLocal = document.getElementById("fLocal").value.toLowerCase();
  const fEstado = document.getElementById("fEstado").value.toLowerCase();
  const fCriticidade = document.getElementById("fCriticidade").value.toLowerCase();


  document.querySelectorAll(" tbody tr").forEach(row => {

    const td = row.querySelectorAll("td");

    const match =
      td[0].textContent.toLowerCase().includes(fCodigo) &&
      td[1].textContent.toLowerCase().includes(fNome) &&
      td[2].textContent.toLowerCase().includes(fMarca) &&
      td[3].textContent.toLowerCase().includes(fModelo) &&
      td[4].textContent.toLowerCase().includes(fSerie) &&
      td[5].textContent.toLowerCase().includes(fLocal) &&
      td[6].textContent.toLowerCase().includes(fEstado) &&
      td[7].textContent.toLowerCase().includes(fCriticidade);
      

    row.style.display = match ? "" : "none";
  });
}


// botão filtrar
document.getElementById("btnFiltrar")?.addEventListener("click", aplicarFiltros);

// filtro automático ao escrever
document.querySelectorAll("#fCodigo, #fNome,  #fMarca, #fModelo, #fSerie, #fLocal, #fEstado, #fCriticidade")
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

  document.querySelectorAll("#fCodigo, #fNome,  #fMarca, #fModelo, #fSerie, #fLocal, #fEstado, #fCriticidade")
    .forEach(input => input.value = "");

  document.getElementById("fEstado").value = "";
  document.getElementById("fCriticidade").value = "";

  aplicarFiltros(); // mostrar tudo outra vez
});








//------------------------------------------------------------------------------------------------
//----------------------------------------FORNECEDORES------------------------------------
//------------------------------------------------------------------------------------------------

//FILTROS Tabela

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



//-----------------------------------------------------------------------------------------
//--------------------------------------LOCALIZAÇAO------------------------------------------
//-----------------------------------------------------------------------------------------



//Apgar localizaçao


//-----------------------------------Filtros Localizaçao----------------------------

// FILTROS LOCALIZAÇÕES


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


//-----------------------------------------------------------------------------------------
//--------------------------------Equipamentos Unidade------------------------------------------
//-----------------------------------------------------------------------------------------



function calcularGarantia() {
    const equipamento = document.getElementById("idEquipamento");
    const dataAquisicao = document.getElementById("dataAquisicao");
    const dataGarantia = document.getElementById("dataFimGarantia");

    if (!equipamento || !dataAquisicao || !dataGarantia) {
        return;
    }

    if (!equipamento.value || !dataAquisicao.value) {
        return;
    }

    const anosGarantia =
        equipamento.options[equipamento.selectedIndex].dataset.garantia;

    if (!anosGarantia) {
        return;
    }

    let data = new Date(dataAquisicao.value);

    data.setFullYear(data.getFullYear() + parseInt(anosGarantia));

    dataGarantia.value = data.toISOString().split("T")[0];
}

document.addEventListener("DOMContentLoaded", function () {
    const dataAquisicao = document.getElementById("dataAquisicao");

    if (dataAquisicao) {
        dataAquisicao.addEventListener("change", calcularGarantia);
    }
});



function atualizarTextoGarantia(valorSelecionado = null) {
    const selectEquipamento = document.getElementById("idEquipamento");
    const textoGarantia = document.getElementById("textoGarantia");

    if (!selectEquipamento || !textoGarantia) return;

    const valor = valorSelecionado || selectEquipamento.value;

    if (!valor) {
        textoGarantia.textContent = "Não definida";
        return;
    }

    const option = Array.from(selectEquipamento.options).find(opt => opt.value == valor);

    if (!option) {
        textoGarantia.textContent = "Não definida";
        return;
    }

    const anosGarantia = option.dataset.garantia;

    if (!anosGarantia || anosGarantia == 0) {
        textoGarantia.textContent = "Sem garantia definida";
        return;
    }

    textoGarantia.textContent =
        anosGarantia == 1
            ? "1 ano de garantia"
            : anosGarantia + " anos de garantia";
}




function mostrarCodigoPrevisto(valorSelecionado = null) {
    const select = document.getElementById("idEquipamento");
    const inputCodigo = document.getElementById("codigoPrevisto");

    if (!select || !inputCodigo) return;

    const valor = valorSelecionado || select.value;

    if (!valor) {
        inputCodigo.value = "Gerado automaticamente";
        return;
    }

    const option = Array.from(select.options).find(opt => opt.value == valor);

    if (!option) {
        inputCodigo.value = "Gerado automaticamente";
        return;
    }

    inputCodigo.value = option.dataset.codigo || "Sem código definido";
}


// document.addEventListener("DOMContentLoaded", function () {
//     const equipamento = document.getElementById("idEquipamento");

//     if (equipamento) {
//         equipamento.addEventListener("change", mostrarCodigoPrevisto);
//     }
// });

//-----------------------------------------------------------------------------------------
//--------------------------------Equipamentos ------------------------------------------
//-----------------------------------------------------------------------------------------

//-- Abre grelha de componenentes


// document.addEventListener("DOMContentLoaded", function () {

//     const btnMostrarComponentes = document.getElementById("btnMostrarComponentes");
//     const areaComponentes = document.getElementById("areaComponentes");

//     if (btnMostrarComponentes && areaComponentes) {

//         btnMostrarComponentes.addEventListener("click", function () {

//             areaComponentes.classList.toggle("d-none");

//         });

//     }

// });


// document.addEventListener("DOMContentLoaded", function () {

//     console.log("JS carregado");

//     const btnMostrarComponentes = document.getElementById("btnMostrarComponentes");
//     const areaComponentes = document.getElementById("areaComponentes");

//     console.log(btnMostrarComponentes);
//     console.log(areaComponentes);

//     if (btnMostrarComponentes && areaComponentes) {

//         btnMostrarComponentes.addEventListener("click", function () {

//             console.log("Clique!");

//             areaComponentes.classList.toggle("d-none");
//         });

//     }

// });