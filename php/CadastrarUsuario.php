<?php

switch ($_GET['flag']) {
    case 1:
        cadastrarUsuario();
    break;
}

function cadastrarUsuario (){

    include_once('../php/conexao.php');
    
    // Login='+segundoNomeLogin+'&primeiroNomeLogin='+primeiroNomeLogin+'&senha='+senha+'&checkboxAdmin='+checkboxAdmin)


    $nomeCompleto = $_GET['nomeCompleto'];
    $segundoNomeLogin = $_GET['segundoNomeLogin'];
    $primeiroNomeLogin = $_GET['primeiroNomeLogin'];
    $senha = $_GET['senha'];
    $checkboxAdmin = $_GET['checkboxAdmin'];
   
    $sql = "INSERT INTO `usuario`(`id_usuario`, `nome_usuario`, `nick_usuario`, `senha_usuario`, `admin_user`, `modulo_agendamento`) 
    VALUES (null,'".$nomeCompleto."','$primeiroNomeLogin.$segundoNomeLogin','".$senha."','".$checkboxAdmin."',true)";

if (mysqli_query($conn, $sql)) {
    echo true;
} else {
    echo "ERRO AO CADASTRAR.\nInforme o Rafael(ramal 407).\n";
    printf("Error: %s\n", $conn->error);
}
}

?>