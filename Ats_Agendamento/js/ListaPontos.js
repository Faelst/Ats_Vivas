$(document).ready(function () {

    $('tabelaPontos').empty();
    $('#tabelaPontos').DataTable({
        retrieve: true,
        "ordering": true,
        "language": {
            "url": "JsonTabela/Portuguese-Brasil.json",
        },
        ajax: {
            url: './php/PopularTables.php?flag=3',
            dataSrc: ''
        },
        columns: [
            { 'data': 'id_tecnico' },
            { 'data': 'nome_tecnico' },
            { 'data': 'Pontos_normais' },
            { 'data': 'Pontos_adicionados' },
            { 'data': 'pontos_totais' },
        ]
    });

    var table = $('#tabelaPontos').DataTable();
    table.columns(4).order('desc').draw();

    $("#dataInicial").datepicker({
        dateFormat: 'dd/mm/yy',
        dayNames: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'],
        dayNamesMin: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S', 'D'],
        dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'],
        monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
        monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
        nextText: 'Próximo',
        prevText: 'Anterior'
    });

    $("#dataFinal").datepicker({
        dateFormat: 'dd/mm/yy',
        dayNames: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'],
        dayNamesMin: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S', 'D'],
        dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'],
        monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
        monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
        nextText: 'Próximo',
        prevText: 'Anterior'
    });
})

$('#btnFiltrar').click(function () {

    let dataInicial = $('#dataInicial').val()
    let dataFinal = $('#dataFinal').val()

    if (dataInicial.length || dataFinal.length) {
        // your code here
        intervaloData($('#dataInicial').val(), $('#dataFinal').val())
    }
})

function intervaloData(data1, data2) {
    var table = $('#tabelaPontos').DataTable();
    $.get('php/EditarExcluir.php?flag=intervalo&dataInicial=' + data1 + '&dataFinal=' + data2)
        .done(function (data) {
                       
            if (data !== '0') {
                if(data !== "Digite as Datas Corretamente."){
                var jsonData = JSON.parse(data);
                preencherTabelaFiltro(jsonData);
                }else{ alert("Nenhuma informação encontrada\nDIGITE AS DATAS CORRETAMENTE.") }
            } else {
                alert('Nenhuma Informação foi encontrada');
                table.clear().draw();
            }
        });
}

function preencherTabelaFiltro(jsonData) {

    var table = $('#tabelaPontos').DataTable();

    table.clear().draw();

    table.rows.add(jsonData).draw();

    table.columns(4).order('desc').draw();

}