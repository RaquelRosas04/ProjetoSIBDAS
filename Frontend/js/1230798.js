//DETALHES_EQUIPAMENTO-----------------------------------------------

document.getElementById("formPesquisaEquipamento")
  .addEventListener("submit", function (e) {

    e.preventDefault();

    const codigo = document.getElementById("inputBuscaCodigo").value.trim();

    // Simulação de dados (depois ligas ao backend)
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

      // Preencher dados
      document.getElementById("txtNome").textContent = equipamento.nome;
      document.getElementById("txtMarca").textContent = equipamento.marca;
      document.getElementById("txtModelo").textContent = equipamento.modelo;
      document.getElementById("txtNSerie").textContent = equipamento.serie;
      document.getElementById("txtLocalizacao").textContent = equipamento.localizacao;
      document.getElementById("txtEstado").textContent = equipamento.estado;
      document.getElementById("txtCriticidade").textContent = equipamento.criticidade;

      // Mostrar resultado
      document.getElementById("resultadoEquipamento").style.display = "block";

    } else {
      alert("Equipamento não encontrado");
    }
});
