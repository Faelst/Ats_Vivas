var nOrdem;
var selectCidade;
var dataAbertura;
var nomeTecnico;
var pontos;
var pontosExtra;
var tipoOrdem;
var Obs;
var motivo;





$('#btnModal').click(function (e) {
    e.preventDefault();
    // Validação ( caso o btn a 'PESQUISA ENCONTRE UM ELE ELEMENTO' ocorra estas funções )
    $('.conteudo').hide();
    $('#inputEditar').val('')
    $("#btnExcluir").prop('disabled', true);
    $("#btnAlterar").prop('disabled', true);
    $('#btnPesquisar').prop('disabled', true);
    $('#selectTipoPesquisa').text('Selecionar');
});

$('a').click(function () {
    $('#selectTipoPesquisa').text($('.item-selecionado').attr('value'));
    if ($('#inputEditar').val().length >= 1 && $('#selectTipoPesquisa').text() != 'Selecionar') {
        $('#btnPesquisar').prop('disabled', false);
    } else {
        $('#btnPesquisar').prop('disabled', true);
    }
})

$('#inputEditar').keyup(function () {
    if ($('#inputEditar').val().length >= 1 && $('#selectTipoPesquisa').text() != 'Selecionar') {
        $('#btnPesquisar').prop('disabled', false);
    } else {
        $('#btnPesquisar').prop('disabled', true);
    }
})

$('#btnPesquisar').click(function (e) {


    var numeroOrdem = $('#inputEditar').val();
    var tipoPesquisa = $('.item-selecionado').attr('value');


    e.preventDefault();
    if (tipoPesquisa != undefined && numeroOrdem.length) {
        editarRequisicaoExcluir(tipoPesquisa, numeroOrdem)
    }

});

function preencherCampos(json) {

    limparCampos();

    $('.conteudo').show('slow');
    $("#btnExcluir").prop('disabled', false);
    $("#btnAlterar").prop('disabled', true);

    $('#conteudo').keypress(function () { $("#btnAlterar").prop('disabled', false); })

    console.log(json);

    $('#nOrdemServico').val(json[0].numeroOrdem);
    $('#selectCIdade').val(json[0].cidade);
    $('#dataAbertura').val(dataAtualFormatada(json[0].data_execucao));
    $('#nomeTenico').val(json[0].nomeTecnico);
    $('#txPontos').val(json[0].pontos);
    $('#tipoOrdem').val(json[0].procedimento);
    $('#txtObs').val(json[0].obs);
    $('#txtPontosExtra').val(json[0].ponto_extra);
    $('#txtAreaPontoExtra').val(json[0].motivo);

    nOrdem = json[0].numeroOrdem;
    selectCidade = json[0].cidade;
    dataAbertura = dataAtualFormatada(json[0].data_execucao);
    nomeTecnico = json[0].nomeTecnico
    pontos = json[0].pontos
    tipoOrdem = json[0].procedimento
    Obs = json[0].obs;
    pontosExtra = json[0].ponto_extra
    motivo = json[0].motivo;

    autoComplete();

}

function dataAtualFormatada(p1) {
    var data = new Date(p1),
        dia = (data.getDate() + 1).toString().padStart(2, '0'),
        mes = (data.getMonth() + 1).toString().padStart(2, '0'), //+1 pois no getMonth Janeiro começa com zero.
        ano = data.getFullYear();
    return dia + "/" + mes + "/" + ano;
}


function editarRequisicaoExcluir(tipoPesquisa, numeroOrdem) {

    $.ajax({
        url: 'php/EditarExcluir.php',
        cache: 'false',
        method: 'GET',
        async: true,
        dataType: 'html',
        data: {
            flag: 'setar',
            tipoPesquisa: tipoPesquisa,
            numeroOrdem: numeroOrdem
        }
    })
        .done(function (resp) {
            if (resp.length) {
                preencherCampos(JSON.parse(resp));
            } else {
                alert('Nenhuma informação foi encontrada.');
                $('.conteudo').hide('slow');
                $("#btnExcluir").prop('disabled', true);
                $("#btnAlterar").prop('disabled', true);
            }
        })


}

function limparCampos() {
    $('#nOrdemServico').val('');
    $('#selectCIdade').val('');
    $('#dataAbertura').val('');
    $('#nomeTenico').val('');
    $('#txPontos').val('');
    $('#tipoOrdem').val('');
    $('#txtObs').val('');
    $('#txtPontosExtra').val('')

}

var valorPontoExtra

$('.dropdown-menu a').click(function (e) {
    if ($(this).text() != 'Duplado') {
        $('#pontoExtra').show('slow');
        $('#txPontos').val('');
        $('#tipoOrdem').val($(this).text());
        $('#txPontos').val($(this).attr('value'));
        valorPontoExtra = $(this).attr('value');
    } else {
        duplado($(this).text());
    }

})

function duplado(p1) {
    $('#tipoOrdem').val(p1);
    $('#txPontos').val('');
    $("#txPontos").attr("placeholder", "_.__");
    $('#pontoExtra').hide('slow');
    $('#txPontos').css('border-color', 'yellow');
    $("#txPontos").prop("disabled", false);
    $("#txPontos").inputmask({ mask: ['9.99', '9.99'], keepStatic: true });
}




$('#btnAlterar').click(function () {


    let url = 'php/EditarExcluir.php?flag=alterar&nOrdem=' + $('#nOrdemServico').val() + "&idOrdem=" + $('#inputEditar').val();


    if ($('#selectCIdade').val() !== selectCidade) {
        url += '&selectCIdade=' + $('#selectCIdade').val();
    }
    if ($('#nomeTenico').val() !== nomeTecnico) {
        url += '&nomeTenico=' + $('#nomeTenico').val();
    }
    if ($('#tipoOrdem').val() !== tipoOrdem) {
        url += '&tipoOrdem=' + $('#tipoOrdem').val();
    }
    if ($('#txtPontosExtra').val() !== pontosExtra) {
        url += '&txtPontosExtra=' + $('#txtPontosExtra').val();
    }
    if ($('#txtAreaPontoExtra').val() !== motivo) {
        url += '&txtAreaPontoExtra=' + $('#txtAreaPontoExtra').val();
    }
    if ($('#txtObs').val() !== Obs) {
        url += '&txtObs=' + $('#txtObs').val();
    }

    if (confirm('Tem Certeza que Deseja ALTERAR ?')) {
        $.get(url)
            .done(function (data) {
                if (data == '1') {
                    alert('Ordem alterada com sucesso');
                    limparCampos();
                    $('.conteudo').hide('slow');
                    $('#example').DataTable().ajax.url('php/PopularTables.php?flag=1').load();
                } else {
                    alert(data);
                }


            })
    }
})


$('#btnExcluir').click(function () {

    if (confirm('Tem Certeza que Deseja excluir ?')) {
        $.get('php/EditarExcluir.php?flag=excluir&idOrdem=' + $('#inputEditar').val() + '&nOrdem=' + $('#nOrdemServico').val())
            .done(function (data) {

                if (data == '1') {
                    limparCampos();
                    $('#inputEditar').val('');
                    $('.conteudo').hide('slow');
                    alert('Ordem Deletada com sucesso');

                }
            });
    }

})

function autoComplete() {
    var PopularTecnico = [];

    $.ajax({
        type: 'GET',		    //Definimos o método HTTP usado
        dataType: 'json',	            //Definimos o tipo de retorno
        url: "./php/PopularTecnico.php?flag=2",    //Definindo o arquivo onde serão buscados os dados
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



    $('#nomeTenico').autocomplete({
        source: PopularTecnico,
        minLength: 2
    });
}