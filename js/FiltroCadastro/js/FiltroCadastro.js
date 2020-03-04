$("#dataInicialCadastradas").mask("00/00/0000");
$("#dataFinal").mask("00/00/0000");

var operadoreSelecionados = null;
/*  ativar multi Select */
$("#btnFiltro").click(function() {
  /* carregar os Select Operadores */
  $.ajax({
    url: "./php/FiltroCadastro/FiltroCadastro.php",
    async: false,
    data: {
      metodo: "carregarOperador"
    },
    method: "GET"
  }).done(function(callback) {
    callback = JSON.parse(callback);
    var html = "";
    callback.map(function(call) {
      html += `<option value=${call.id}>${call.nome}</option>`;
    });
    $("#framework").html(html);
  });

  $("#framework").multiselect({
    nonSelectedText: "Selecionar Operador",
    includeSelectAllOption: true,
    buttonWidth: "250px"
  });

  $("#framework").on("change", function() {
    operadoreSelecionados = $(this).val();
  });
});

var somenteExtra = false;
$("#checkPontuaçãoExtra").click(function() {
  if ($(this).is(":checked")) {
    somenteExtra = true;
  } else if ($(this).is(":not(:checked)")) {
    somenteExtra = false;
  }
});

$("#btnFiltrar").click(function() {
  /*console.log(operadoreSelecionados);*/
  var objetoFiltro = {
    operadoreSelecionados: operadoreSelecionados,
    dataInicia: $("#dataInicialCadastradas").val(),
    dataFinal: $("#dataFinal").val(),
    somenteExtra: somenteExtra
  };
  fazerRequisicaoAJAX(objetoFiltro);
  $("#modalLoading").modal("hide");
});

function fazerRequisicaoAJAX(objetoFiltro) {
  /* console.log(objetoFiltro); */
  $("#FiltroCadastro").modal("hide");
  $.ajax({
    url: "./php/FiltroCadastro/FiltroCadastro.php",
    async: false,
    method: "POST",
    data: {
      metodo: "FiltrarTabela",
      objetoFiltro: JSON.stringify(objetoFiltro)
    },
    beforeSend: function() {
      $("#modalLoading").modal("show");
    }
  })
    .done(function(data) {
      console.log(typeof(data));
      carregarTabela(data);
      setInterval(function() {
        $("#modalLoading").modal("hide");
      }, 100);
    })
    .fail(function() {
      alert("Algo ocorreu de errado.\nPorfavor, notifique a Equipe de desenvolvimento.");
        $("#modalLoading").modal("hide");
    });
}

function carregarTabela(data){

  console.log(JSON.parse(data))
  
  var tabelaOrdensOrdens = $('#example').DataTable({
        ajax: JSON.parse(data) 
  });
  
  tabelaOrdensOrdens.clear().draw();
  //tabelaOrdensOrdens.ajax.reload();
}
