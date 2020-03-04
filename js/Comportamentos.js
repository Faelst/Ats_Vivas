var valorPontoExtra
var check
var duplado
var qtdeDuplicado

$(document).ready(function () {
  //ESCONDER O AS INFORMAÇÕES DE PONTO EXTRA
  $('#divPontoExtra').hide()
  $('#tecnicoDuplado').hide()

  check = false
  //funcão para chama a validação por mascara
  callMasck()

  $('#dataAbertura').datepicker({
    dateFormat: 'dd/mm/yy',
    dayNames: [
      'Domingo',
      'Segunda',
      'Terça',
      'Quarta',
      'Quinta',
      'Sexta',
      'Sábado'
    ],
    dayNamesMin: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S', 'D'],
    dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'],
    monthNames: [
      'Janeiro',
      'Fevereiro',
      'Março',
      'Abril',
      'Maio',
      'Junho',
      'Julho',
      'Agosto',
      'Setembro',
      'Outubro',
      'Novembro',
      'Dezembro'
    ],
    monthNamesShort: [
      'Jan',
      'Fev',
      'Mar',
      'Abr',
      'Mai',
      'Jun',
      'Jul',
      'Ago',
      'Set',
      'Out',
      'Nov',
      'Dez'
    ],
    nextText: 'Próximo',
    prevText: 'Anterior'
  })
})

$('#PotucaoExtra').click(function () {
  if (document.getElementById('PotucaoExtra').checked) {
    $('#divPontoExtra').show('slow')
    check = true
  } else {
    $('#divPontoExtra').hide('slow')
    check = false
  }
  console.log('check ' + check)
})

$('#btnCadastrar').click(function () {
  validarCampos('#nOrdemServico')
  validarCampos('#selectCIdade')
  validarCampos('#dataAbertura')
  validarCampos('#nomeTenico')
  validarCampos('#tipoOrdem')
  validarCampos('#txtAreaObservação')

  if (
    document.getElementById('PotucaoExtra').checked &&
    $('#txtPontosExtra').val().length
  ) {
    validarCampos('#txtAreaPontoExtra')
  } else {
    $('#txtAreaPontoExtra').css('border-color', 'white')
  }

  if ($('#tipoOrdem').val() === 'Duplado') {
    validarCampos('#nomeTenicoDuplado')
  }
  //Faz a verificação e depois chama outro metodo.
  protocoloDuplicado()

  // VERIFICA SE A ALGUM ERRO NA HORA DO CARREGAMENTO DA PAGINA

  // ESTA COMENTADO PELO FATO DE ESTAR DANDO ALGUM ERRO
  /*window.onerror = function (msg, url, lineNo, columnNo, error){
        var string = msg.toLowerCase();
        var substring = "script error";
        if (string.indexOf(substring) > -1) {
            alert('Script Error: See Browser Console for Detail');
        } else {
            alert(msg, url, lineNo, columnNo, error);
        }
        return false;
    };*/
})

function protocoloDuplicado () {
  $.get(
    './php/CadastroAgendamento.php?switchFlag=2&nOrdem=' +
      $('#nOrdemServico').val()
  ).done(function (resp) {
    console.log(resp)
    if (parseInt(resp) == 0) {
      callCadastro()
    } else {
      if (
        confirm(
          'Deseja cadastrar a Ordem: ' +
            $('#nOrdemServico').val() +
            ' novamente ?'
        )
      ) {
        callCadastro()
      }
    }
  })
}

function validarCampos (elemento) {
  if (!$(elemento).val().length) {
    $(elemento).css('border-color', 'red');
  } else {
    $(elemento).css('border-color', 'white');
  }
}

$('.dropdown-menu a').click(function (e) {
  if ($(this).text() != 'Duplado') {
    $('#pontoExtra').show('slow')
    $('#tecnicoDuplado').hide('slow')
    $('#txPontos').val('')
    $('#tipoOrdem').val($(this).text())
    $('#txPontos').val($(this).attr('value'))
    valorPontoExtra = $(this).attr('value')
    $('#txPontos').prop('disabled', true)
  } else {
    duplado($(this).text())
  }
})

function duplado (p1) {
  $('#txPontosDuplado').val('') // seta o valor do input para nulo
  $('#nomeTenicoDuplado').val('') // seta o valor do input para nulo
  $('#tipoOrdem').val(p1) // preenche o input do tipo de solicitação
  $('#txPontos').val('') // seta o valor do input para nulo
  $('#txPontos').attr('placeholder', '_.__') // coloca um place holder no input
  $('#pontoExtra').hide('slow') // Esconde o check box de pontuação extra.
  $('#tecnicoDuplado').show('slow') // Exibe o a o input de tecnico duplado.
  $('#txPontos').css('border-color', 'yellow') // Altera a cor do input para ficar destacado para o usuario.
  $('#txPontos').prop('disabled', false) // Desabilita a opção de inserção de caracter.
  $('#txPontos').inputmask({ mask: ['9.99', '9.99'], keepStatic: true }) // Mascara de input do procedimento duplado.
  popularTecnicoDuplado() // Popula TODOS os tecnicos para que seja cadastrados os procedimentos 'DUPLAODOS'.
}

function popularTecnicoDuplado () {
  var PopularTecnico = []

  $.ajax({
    type: 'GET', //Definimos o método HTTP usado
    dataType: 'json', //Definimos o tipo de retorno
    url: './php/PopularTecnico.php?flag=2', //Definindo o arquivo onde serão buscados os dados
    success: function (dados) {
      for (var i = 0; dados.length > i; i++) {
        //Adicionando registros retornados na tabela
        PopularTecnico[i] = dados[i].nome_tecnico
      }
    },
    error: function (request, status, error) {
      alert(request.responseText)
    }
  })

  $('#nomeTenicoDuplado').empty()

  $('#nomeTenicoDuplado').autocomplete({
    source: PopularTecnico,
    minLength: 3
  })
}

$('#txPontos').change(function () {
  $('#txPontosDuplado').val(($('#txPontos').val() / 2).toFixed(2))
})

function callMasck () {
  $('#dataAbertura').inputmask({
    mask: ['99/99/9999', '99-99-9999'],
    keepStatic: true
  })

  $('#txtPontosExtra').inputmask({
    mask: ['9.99', '9.99'],
    keepStatic: true
  })
}

// chamada da requisição para cadastrar no Banco de dados
function callCadastro () {
  var today = new Date(),
    dd = (today.getDate() + 1).toString().padStart(2, '0'),
    mm = (today.getMonth() + 1).toString().padStart(2, '0'),
    yyyy = today.getFullYear()

  var numeroOrdem = $('#nOrdemServico').val()
  var cidade = $('#selectCIdade').val()
  var dataExecucao = $('#dataAbertura').val()
  var dataFechamento = mm + '/' + dd + '/' + yyyy;
  var tipoOrdenServico = $('#tipoOrdem').val()

  if ($('#tipoOrdem').val() == 'Duplado') {
    var pontuacaoExtra = $('#txPontos').val()
    var motivoPontoExtra = 'Ordem de Serviço Duplada'
  } else {
    var pontuacaoExtra = $('#txtPontosExtra').val()
    var motivoPontoExtra = $('#txtAreaPontoExtra').val()
  }

  var observacao = $('#txtAreaObservação').val();
  var nomeTecnico = $('#nomeTenico').val();

  var nomeTenicoDuplado = $('#nomeTenicoDuplado').val()

  // criação dos paramentros para requisição

  $.ajax({
    url: './php/CadastroAgendamento.php?switchFlag=1',
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
      nomeTenicoDuplado: nomeTenicoDuplado,
      check: check
    }
  }).done(function (resp) {
    if (resp == 1) {
      $('#divCheck').html("<img style='width:50%;' src='img/gif/check.gif' />")
      $('#modalCofirmaCadastro').modal('show')
      setTimeout(function () {
        $('#modalCofirmaCadastro').modal('hide')
      }, 1500)
      limparCampos()
    } else {
      alert(resp)
    }
  })
}

function limparCampos () {
  $('#nOrdemServico').val('')
  $('#selectCIdade').val('')
  $('#dataAbertura').val('')
  $('#tipoOrdem').val('')
  $('#txtPontosExtra').val('')
  $('#txtAreaPontoExtra').val('')
  $('#txtAreaObservação').val('')
  $('#nomeTenico').val('')
  $('#nomeTenicoDuplado').val('')
  $('#txPontosDuplado').val('')
}
