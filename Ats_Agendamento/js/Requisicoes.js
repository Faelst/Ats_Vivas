
$(document).ready(function () {
    $('tbody').empty();
    $('#example').DataTable({

        "ordering": true,
        "language": {
            "url": "JsonTabela/Portuguese-Brasil.json",
        },
        ajax: {
            url: './php/PopularTables.php?flag=2',
            dataSrc: ''
        },
        columns: [
            { 'data': 'id_tecnico' },
            { 'data': 'Nome_tecnico' },
            { 'data': 'instalacao' },
            { 'data': 'manutencao' },
            { 'data': 'retirada' },
            { 'data': 'vistoria' },
            { 'data': 'rompimento' },
            { 'data': 'duplado' },
            { 'data': 'treinamento_nf' },
            { 'data': 'nao_executado' },
        ]
    });

    var table = $('#example').DataTable();

    table.columns(0).order('desc').draw();


})


$("#dataInicialAtividades").datepicker({
    dateFormat: 'dd/mm/yy',
    dayNames: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'],
    dayNamesMin: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S', 'D'],
    dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'],
    monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
    monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
    nextText: 'Próximo',
    prevText: 'Anterior'
});

$("#dataFinalAtividades ").datepicker({
    dateFormat: 'dd/mm/yy',
    dayNames: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'],
    dayNamesMin: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S', 'D'],
    dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'],
    monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
    monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
    nextText: 'Próximo',
    prevText: 'Anterior'
});


var PopularTecnico = [];

$.ajax({
    type: 'GET',		    //Definimos o método HTTP usado
    dataType: 'json',	            //Definimos o tipo de retorno
    url: "./php/PopularTecnico.php?flag=1",    //Definindo o arquivo onde serão buscados os dados
    success: function (dados) {
        for (var i = 0; dados.length > i; i++) {
            //Adicionando registros retornados na tabela
            PopularTecnico[i] = dados[i].nome_tecnico;

        }
    },
    error: function (request, status, error) {
        alert(request.responseText);
    }
});

$('#nomeTenico').empty();

$('#nomeTenico').autocomplete({
    source: PopularTecnico,
    minLength: 3
});


/*       filtro por data         */

$('#btnFiltrarAtividades').click(function () {
    filtroAtividades();
})

function filtroAtividades() {

    const dataInicial = $('#dataInicialAtividades').val()
    const dataFinal = $('#dataFinalAtividades').val()

    $.get(`./php/PopularTables.php?flag=2&dataInicialAtividades=${dataInicial}&dataFinalAtividades=${dataFinal}`)
        .done(function (resp) {
            try{
                var jsonData = JSON.parse(resp);
                preencherTabelaAtividadeFiltro(jsonData);
            }catch (e) {
                 alert('nenhuma informação foi encontrada.\nerro:'+e)
                 $('#example').DataTable().clear().draw();
             }
        })

    function preencherTabelaAtividadeFiltro(jsonData) {

        var table = $('#example').DataTable();

        table.clear().draw();

        table.rows.add(jsonData).draw();

        table.columns(1).order('desc').draw();

    }

}
