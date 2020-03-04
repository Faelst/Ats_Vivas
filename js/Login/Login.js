
$(document).ready(function () {
    $('#btnLogin').click(function (e) {

        var nomeUsuario = $('#usuarioLogin').val();
        var senhaUsuario = $('#senhaLogin').val();

        if (nomeUsuario == '') {
            $('.validaLogin').html('<span class="badge badge-danger conteudo ">Informe as Corretamente informações</span>')
        } else if (senhaUsuario == "") {
            $('.validaLogin').html('<span class="badge badge-danger conteudo ">Informe Corretamente as Informações</span>')
        } else {
            chamarGet(nomeUsuario, senhaUsuario);
            $('.conteudo').hide();
        }

        function chamarGet(nomeUsuario, senhaUsuario) {
            $.get('./php/ValidarLogin.php?switchFlag=0&usuarioLogin=' + nomeUsuario + '&senhaLogin=' + senhaUsuario)
                .done(function (resp) {
                    resposta = JSON.parse(resp);
                    if (parseInt(resposta.rows) == 1) {
                        window.location.href = 'http://177.126.240.61/Main_ats/ats'; //(document.URL).replace("login.php","index.php");
                    } else {
                        $('.validaLogin').html('<span class="badge badge-danger conteudo ">LOGIN INCORRETO.</span>');
                    }
                })
        }
    })
})

