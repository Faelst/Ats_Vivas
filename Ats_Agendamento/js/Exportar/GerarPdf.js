
$(function () {
  $('#dataInicialPdf').datepicker({ dateFormat: 'dd/mm/yy' })
  $('#dataFinalPdf').datepicker({ dateFormat: 'dd/mm/yy' })
})

$('#dataInicialPdf').click(function () {
  $('#ui-datepicker-div').css('z-index', '9999')
})
$('#dataFinalPdf').click(function () {
  $('#ui-datepicker-div').css('z-index', '9999')
})

$('#gerarPDF').click(function (e) {
  e.preventDefault()

  const dataInicialPdf = moment($('#dataInicialPdf').val(), 'DD/MM/YYYY')
  const dataFinalPdf = moment($('#dataFinalPdf').val(), 'DD/MM/YYYY')

  $.ajax({
    url: 'php/phpPdf/GerarPdf.php',
    type: 'POST',
    data: {
      dataInicial: dataInicialPdf.format('YYYY-MM-DD'),
      dataFinal: dataFinalPdf.format('YYYY-MM-DD')
    },
    beforeSend: function () {
      $('#modalLoading').modal({
        keyboard: false,
        backdrop: false,
        show: true,
        focus: true
      })
    }
  })
    .done(function (pdf) {
      console.log(pdf)
        window.open('http://177.126.240.61/Main_caps/Caps_Agendamento/php/phpPdf/'+(pdf.slice(1,-1)), '_blank');
        $('#modalLoading').modal('hide');
    })
    .fail(function (jqXHR, textStatus, msg) {
      alert(msg)
    })
})
