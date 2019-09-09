var check;
var duplado;

$(document).ready(function () {
    //ESCONDER O AS INFORMAÇÕES DE PONTO EXTRA
    $("#divPontoExtra").hide();
    check = false;
    //funcão para chama a validação por mascara
    callMasck();

    $("#dataAbertura").datepicker({
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

$('#PotucaoExtra').click(function () {

    if (document.getElementById('PotucaoExtra').checked) {
        $("#divPontoExtra").show();
        check = true;
    } else {
        $("#divPontoExtra").hide();
        check = false;
    }
    console.log('check' + check);
});

$('#btnCadastrar').click(function () {
    validarCampos('#nOrdemServico');
    validarCampos('#selectCIdade');
    validarCampos('#dataAbertura');
    validarCampos('#nomeTenico')
    validarCampos('#tipoOrdem')

    if ((document.getElementById('PotucaoExtra').checked) && (($('#txtPontosExtra').val()).length)) {
        validarCampos('#txtAreaPontoExtra')
    } else { $('#txtAreaPontoExtra').css('border-color', 'white') }

    console.log(valorPontoExtra)
    if (valorPontoExtra == '0') {

        validarCampos('#txtAreaObservação')
    } else { $('#txtAreaObservação').css('border-color', 'white') }


    // APÓS FAZER TODAS AS VALIDÇÕES E ESTAR TUDO CORRETO CHAMAR FUNÇÃO PARA EXECUTAR O CADASTRO NO BANCO.
    callCadastro();

    window.onerror = function (msg, url, lineNo, columnNo, error) {
        var string = msg.toLowerCase();
        var substring = "script error";
        if (string.indexOf(substring) > -1) {
            alert('Script Error: See Browser Console for Detail');
        } else {
            alert(msg, url, lineNo, columnNo, error);
        }
        return false;
    };
})

function validarCampos(elemento) {
    if (!($(elemento).val()).length) {
        $(elemento).css('border-color', 'red')
    } else { $(elemento).css('border-color', 'white') }
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


function callMasck() {
    $("#dataAbertura").inputmask({
        mask: ['99/99/9999', '99-99-9999'],
        keepStatic: true
    });

    $("#txtPontosExtra").inputmask({
        mask: ['9.99', '9.99'],
        keepStatic: true
    });

}

// chamada da requisição para cadastrar no Banco de dados
function callCadastro() {


    var today = new Date(),
        dd = (today.getDate() + 1).toString().padStart(2, '0'),
        mm = (today.getMonth() + 1).toString().padStart(2, '0'),
        yyyy = today.getFullYear();

    var numeroOrdem = $('#nOrdemServico').val();
    var cidade = $('#selectCIdade').val();
    var dataExecucao = $('#dataAbertura').val();
    var dataFechamento = mm + '/' + dd + '/' + yyyy;
    var tipoOrdenServico = $('#tipoOrdem').val();

    if ($('#tipoOrdem').val() == 'Duplado') {
        var pontuacaoExtra = $('#txPontos').val();
        var motivoPontoExtra = 'Ordem de Serviço Duplada';

    } else {
        var pontuacaoExtra = $('#txtPontosExtra').val();
        var motivoPontoExtra = $('#txtAreaPontoExtra').val();
    }

    var observacao = $('#txtAreaObservação').val();
    var nomeTecnico = $('#nomeTenico').val();

    console.log(pontuacaoExtra);
    console.log(motivoPontoExtra);
    // criação dos paramentros para requisição

    alert('ENQUANTO EU NAO GANHAR BOLO DE LEITE NINHO NAO VOU CADASTAR !!!!!!!!!!!!!!')

     $.ajax({
         url: './php/CadastroAgendamento.php',
         cache: 'false',
         method: 'GET',
         async: true,
         dataType: 'html',
         data: {
             numeroOrdem: numeroOrdem,
             cidade: cidade,
             dataExecucao: dataExecucao,
             dataFechamento: dataFechamento,
             tipoOrdenServico: tipoOrdenServico,
             pontuacaoExtra: pontuacaoExtra,
             motivoPontoExtra: motivoPontoExtra,
             observacao: observacao,
             nomeTecnico: nomeTecnico,
             check: check
         },
     })
         .done(function (resp) {
             if (resp == 1) {
                 alert("Cadastro realizado com Sucesso.\nAgendamento e o melhor setor da VIVAS.");
                 limparCampos();
             } else {
                 alert(resp);
             }
         })
 
}


function limparCampos() {
    $('#nOrdemServico').val('');
    $('#selectCIdade').val('');
    $('#dataAbertura').val('');
    $('#tipoOrdem').val('');
    $('#txtPontosExtra').val('');
    $('#txtAreaPontoExtra').val('');
    $('#txtAreaObservação').val('');
    $('#nomeTenico').val('');
}