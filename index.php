<?php

session_start();

if (empty($_SESSION['validaLogin'])) {
  header('location: Login.php');
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="Content-Language" content="pt-br, en, fr, it">
  <META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Expires" content="0">
  <meta charset="UTF-8">
  <meta name="viewport" content=" height=600,width=444">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Controle de Pontuação</title>

  <link rel="icon" type="image/png" href="img/favicon.png">

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

  <link href="https://fonts.googleapis.com/css?family=Comfortaa&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/themes/base/jquery-ui.css">

  <link href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" rel="Stylesheet">

  <link rel="stylesheet" href="css/style.css">

  <link rel="stylesheet" href="css/styleDashBoard.css">

  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">

  <link rel="stylesheet" type="text/css" href="css/userimg.css">

</head>

<body style="background-image: url('img/images/background-agendamento.png'); background-repeat: no-repeat;background-size: cover;background-attachment: fixed;">

  <div class="cotainer-fluid">

    <div class="d-flex bd-highlight">
      <div class="bd-highlight">
        <button id="changeLog" type="button" class="btn btn-link border-0" data-toggle="modal" data-target="#modalChangeLog">
          <img src="img/images/icons/Change-log.png" style="width: 100%;margin-bottom: 5px" />
        </button>
      </div>

      <div class="modal fade" id="modalChangeLog" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">ChangeLog de Desenvolvimento</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="btn-group dropright">
                <button type="button" id='btnChangeLog' class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Versões
                </button>
                <div class="dropdown-menu">
                  <a class="dropdown-item" href="#">v1.0</a>
                  <a class="dropdown-item" href="#">v1.1</a>
                  <a class="dropdown-item" href="#">v1.2</a>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
          </div>
        </div>
      </div>

      <div class="mr-3 mt-4 mb-5 ml-auto bd-highlight ">
        <span class="my-5">
          <img src="img/images/AT&S.png" />
        </span>
      </div>

      <div class="mr-3 mt-2 mb-1 ml-auto bd-highlight ">
        <!--  <img src="img/images/icons/user.png" style="width: 20.5%;margin-bottom: 5px" />-->
        <!-- <img class="img-thumbnail rounded-circle " src="" style="width: 300px;"/>  -->
        <button id="changeLog" type="button" class="btn btn-link border-0 dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <div class="profile-header-img">
            <img id='imgUsuario' class="img-circle rounded-circle" src="" />
          </div>
          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <span class="dropdown-item" data-toggle="modal" id='btnModalCofigUser' data-target="#modalCofigUser">
              Configuração de Perfil </span>
            <hr>
            <span class="dropdown-item" id="btnSair">Sair</span>
          </div>
        </button>
      </div>
    </div>


    <!-- Modal de configurações do usuario -->

    <div class="modal fade" id="modalCofigUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">Configurações de Usuario </span></h6>
            <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="container-fluid">
              <div class="d-flex justify-content-center">
                <div class="row">
                  <div class="profile-header-img houver-img" id=''>
                    <img id="alterarFoto" class="img-circleModal rounded-circle img-hover" src="" />
                    <div class="middle">
                      <form id="formulario" action="" enctype="multipart/form-data">
                        <input id="upload" name="imagem" type="file" style="display:none" />
                      </form>
                      <p type="submit" style="height: 126px; padding-top: 40px;" id="upload_link" class="text">Alterar
                      </p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="d-flex justify-content-center mt-1">
                <div class="row">
                  <p id='nomeUsuario'></p><?php echo $_SESSION['nome_usuario']; ?></p>
                </div>
              </div>
              <hr>
              <input type="button" class="btn btn-link" id="linkAlterarSenha" value="- Alterar Senha" />
              <div id='alterarSenha' style="list-style-type: square;">
                <div class="form-group input-group mb-3">
                  <div class="input-group-prepend">
                    <span for='senhaAtual' class="input-group-text">Senha Atual: </span>
                  </div>
                  <input type="password" class="form-control" id="senhaAtual" name="senhaAtual" aria-label="Username" aria-describedby="basic-addon1">
                </div>
                <div class="form-group input-group mb-3">
                  <div class="input-group-prepend">
                    <span for="novaSenha" class="input-group-text">Nova senha: </span>
                  </div>
                  <input type="password" class="form-control" id="novaSenha" name="novaSenha" aria-label="Username" aria-describedby="basic-addon1">
                </div>
                <div class="form-group input-group mb-3">
                  <div class="input-group-prepend">
                    <span for="confirmSenha" class="input-group-text">Confirme a Nova senha: </span>
                  </div>
                  <input type="password" class="form-control" id="confirmSenha" name="confirmSenha" aria-label="Username" aria-describedby="basic-addon1">
                </div>           

              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" id='savarConfig' class="btn btn-primary">Salvar mudanças</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
          </div>
        </div>
      </div>
    </div>

    <nav class="navbar navbar-expand-lg navbar-light px-xl-5" style="background-color: rgba(61, 61, 61, 0.527);">

      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Alterna navegação">
        <span class="navbar-toggler-icon"></span>
      </button>
      <a class="navbar-brand font-color " href='paginas/FormularioCadastro.html' style="color: white;font-size: 24px"><img style="width: 60%" src="img/images/logo (2).png" /></a>


      <div class="collapse navbar-collapse mx-xl-5" id="navbarTogglerDemo03">

        <ul class="navbar-nav mr-auto mt-2 mt-lg-0 ">

          <li class="nav-item active mx-xl-3 agendamento-li" disabled>
            <div class="dropdown">
              <span class="nav-link btn font-color dropdown-toggle" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: white"><img src="img/images/icons/calendar.png" style="width: 11%;margin-bottom: 3px" disabled />
                Pendentes</span>
              <div class="dropdown-menu agendamento-menu li" aria-labelledby="dropdownMenuLink">
                <a class="dropdown-item agendamento-item" href="paginas/Agendamento.html">Desenv...</a>
              </div>
            </div>
          </li>

          <li class="nav-item active mx-xl-3">
            <a class="nav-link disabled font-color" id='cadastrar' href="paginas/FormularioCadastro.html" style="color: white"><img src="img/images/icons/edit.png" style="width: 11%;margin-bottom: 3px" />
              Cadastrar</a>
          </li>
          <li class="nav-item active mx-xl-3">
            <a class="nav-link disabled font-color" id='cadastrar' href="paginas/OrdensCadastradas.html" style="color: white"><img src="img/images/icons/cadastrar.png" style="width: 14%;margin-bottom: 3px;margin-right:2px" />
              Ordens</a>
          </li>
          <li class="nav-item mx-xl-3">
            <a id='pontos' class="nav-link font-color" href='paginas/ListarPontos.html' style="color: white"><img src="img/images/icons/scoreboard.png" style="width: 14%;margin-bottom: 5px;margin-right:5px" />Pontos</a>
          </li>
          <li class="nav-item mx-xl-3">
            <a id='atividade' class="nav-link font-color" href='paginas/DashBoard.html' style="color: white"><img src="img/images/icons/list-with-dots.png" style="width: 14%;margin-bottom: 5px;margin-right:5px" />Atividades
              <span class="sr-only">(Página
                atual)</span></a>
          </li>
          <li class="nav-item mx-xl-3 disabled">
            <?php if ($_SESSION['admin_user'] == 1) {
              echo "<a class='nav-link font-color' href='paginas/Admin.html' style='color: white;' ><img class='mr-1' src='img/images/icons/settings.png' style='width: 18.5%;margin-bottom: 5px'> ADM</a>";
            } else {
              echo "<a class='nav-link font-color' href='paginas/Admin.html' style='color: white; visibility: hidden;' ><img class='mr-1' src='img/images/icons/settings.png' style='width: 18.5%;margin-bottom: 5px'> ADM</a>";
            } ?>
          </li>
        </ul>
      </div>
    </nav>

    <div id='conteudo'>

    </div>

    <div align="center" class="mt-1">
      <p style="color:white">&copy; 2020 Rafael Silverio</p>
    </div>
  </div>

</body>

<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" onerror="location.reload()" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" onerror="location.reload()" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" onerror="location.reload()" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" onerror="location.reload()" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
<script type="text/javascript" charset="utf8" onerror="location.reload()" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
<script src='js/inputMask/inputMask.js' onerror="location.reload()"></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js' onerror="location.reload()"></script>


<script src="js/popup/Navegação.js"></script>
<script src="js/UserConfig.js"></script>

<script>
  $(document).ready(function() {

    $('#btnSair').click(function() {
      $.post('php/DestruirSassao.php').done(function(data) {
        document.location.reload(true)
      })
    })

    $("li.agendamento-li").hover(
      function() {
        $('div.agendamento-menu').show();
      },
      function() {
        $('div.agendamento-menu').hide();
      }
    );
  })
</script>
<script>
  $('#btnModalCofigUser').click(function() {
    $('#modalCofigUser').modal('show')
  })
</script>
<!-- Ativar modal de configuração de usuario -->

</html>