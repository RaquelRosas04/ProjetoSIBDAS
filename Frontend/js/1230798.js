// -----------------------------DETALHES_EQUIPAMENTO------------------------------------------
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
  const fMarca = document.getElementById("fMarca").value.toLowerCase();
  const fModelo = document.getElementById("fModelo").value.toLowerCase();
  const fSerie = document.getElementById("fSerie").value.toLowerCase();
  const fLocal = document.getElementById("fLocal").value.toLowerCase();
  const fEstado = document.getElementById("fEstado").value.toLowerCase();
  const fCriticidade = document.getElementById("fCriticidade").value.toLowerCase();

  document.querySelectorAll("table tbody tr").forEach(row => {

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
document.querySelectorAll("#fCodigo, #fNome, #fMarca, #fModelo, #fSerie, #fLocal")
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

  document.querySelectorAll("#fCodigo, #fNome, #fMarca, #fModelo, #fSerie, #fLocal")
    .forEach(input => input.value = "");

  document.getElementById("fEstado").value = "";
  document.getElementById("fCriticidade").value = "";

  aplicarFiltros(); // mostrar tudo outra vez
});


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