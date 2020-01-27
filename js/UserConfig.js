/*        Alterar fotos de perfil               */
$(document).ready(function () {
  //esconder inputs de alterar senha do usuario
  $('#imgUsuario').click(function () {
    $('#alterarSenha').hide('slow')
    $('#linkAlterarSenha').show('slow')
    $('#senhaAtual').val('')
    $('#novaSenha').val('')
    $('#confirmSenha').val('')
  })

  // Carregar fotos de perfil dos usuarios
  $.post('./php/uploadFoto.php?flag=1').done(function (resp) {
    console.log(resp)
    resp == '' ? (resp = 'padrao.png') : (resp = resp)
    $('#imgUsuario').attr('src', 'php/img/' + resp)
    $('#alterarFoto').attr('src', 'php/img/' + resp)
  })

  $('#upload_link').on('click', function (e) {
    e.preventDefault()
    $('#upload:hidden').trigger('click')
  })

  // Carrega a imagem selecionada no elemento <img>
  $('#upload').on('change', function () {
    var files = !!this.files ? this.files : []
    if (!files.length || !window.FileReader) return

    if (/^image/.test(files[0].type)) {
      var reader = new FileReader()
      reader.readAsDataURL(files[0])
      reader.onload = function () {
        $('#alterarFoto').attr('src', this.result)
      }
    }
  })

  $(`#savarConfig`).click(function () {
    $('#imgUsuario').attr('src', $('#alterarFoto').attr('src'))

    var fd = new FormData()
    var files = $('#upload')[0].files[0]
    fd.append('file', files)

    $.ajax({
      url: './php/uploadFoto.php?flag=2',
      type: 'post',
      data: fd,
      contentType: false,
      processData: false,
      success: function (response) {
        console.log(response)
        if (response != 0) {
        } else {
          alert('file not uploaded')
        }
      }
    })
  })

  /// ALTERAR SENHA DE USUARIO

  $('#linkAlterarSenha').click(function (e) {
    e.preventDefault()
    $('#alterarSenha').show('slow')
    $('#linkAlterarSenha').hide('slow')

    let validadetion = 0

    $('#senhaAtual').change(function () {
      $.post('./php/uploadFoto.php?flag=3', {
        senhaAtual: $('#senhaAtual').val()
      }).done(function (rows) {
        if (parseInt(rows) >= 1) {
          $('#senhaAtual').css('border-color', '#ced4da')
          validadetion++
        } else {
          $('#senhaAtual').css('border-color', 'red')
        }
      })
    })

    $('#savarConfig').click(function () {
      var senhas = {
        atual: $('#senhaAtual').val(),
        nova: $('#novaSenha').val(),
        confirm: $('#confirmSenha').val()
      }

      if (senhas.atual.length && senhas.nova.length && senhas.confirm.length) {
        validadetion++
      } else {
        if (!senhas.atual.length) {
          $('#senhaAtual').css('border-color', 'red')
        } else {
          $('#senhaAtual').css('border-color', '#ced4da')
        }
        if (!senhas.nova.length) {
          $('#novaSenha').css('border-color', 'red')
        } else {
          $('#novaSenha').css('border-color', '#ced4da')
        }
        if (!senhas.confirm.length) {
          $('#confirmSenha').css('border-color', 'red')
        } else {
          $('#confirmSenha').css('border-color', '#ced4da')
        }
        alert('ATENÇÃO\nDigite a nova senha corretamente.')
      }
      if (senhas.nova.length <= 5) {
        $('#novaSenha').css('border-color', 'red')
      } else {
        validadetion++
        $('#novaSenha').css('border-color', '#ced4da')
      }
      if (senhas.confirm.length <= 5) {
        $('#confirmSenha').css('border-color', 'red')
      } else {
        validadetion++
        $('#confirmSenha').css('border-color', '#ced4da')
      }

      if (senhas.nova == senhas.confirm) {
        validadetion++
      } else {
        $('#novaSenha').css('border-color', 'red')
        $('#confirmSenha').css('border-color', 'red')
        alert('Digite as Senhas Corretamente')
      }
      console.log(validadetion)
      if (validadetion >= 5) {
        $.post('./php/uploadFoto.php?flag=4', {
          novaSenha: $('#novaSenha').val()
        })
        .done(function (resp) {
          if (parseInt(resp) == 1) {
            alert('Senha Alterada')
          }else{
            alert(resp);
          }
        })
      }
    })
  })
})
