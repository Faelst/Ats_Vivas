<?php

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>AT&s</title>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link rel="stylesheet" href="css/cssLogin.css">


</head>

<body style="background-image: url('img/images/background-agendamento.png'); background-repeat: no-repeat;background-size: cover;background-attachment: fixed;">


    <!------ Include the above in your HEAD tag ---------->

    <div class="container login-container">
        <div class="row">
            <div class="col-md-6 login-form-1">
                <h3>Bem-vindo ao Controle de Pontuação e Serviços</h3>
                <div class="form-group">
                    <input type="text" id="usuarioLogin" class="form-control" placeholder="Usuario *" value="" />
                </div>
                <div class="form-group">
                    <input type="password" id="senhaLogin" class="form-control" placeholder="Senha *" value="" />
                </div>
                <div class="form-group validaLogin"></div>
                <div class="form-group">
                    <button type="button" class="btn btn-success btnSubmit" id="btnLogin">Entrar</button>
                </div>


            </div>
            <div class="col-md-6 login-form-2">
                <div class="login-logo">
                    <img src="https://image.ibb.co/n7oTvU/logo_white.png" alt="" />
                </div>
                <div class="py-5 pl-5">
                    <div class="d-flex justify-content-center">
                        <span class="my-5">
                            <img src="img/images/AT&S.png" />
                        </span>
                    </div>
                </div>

            </div>
        </div>
    </div>


</body>

<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/es6-shim/0.35.5/es6-sham.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="js/Login/Login.js"></script>

</html>