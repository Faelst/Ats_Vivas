<?php

    $servidor="localhost";
    $usuario="root";
    $senha="";
    $dbname="controle_ativacao";

    $conn = mysqli_connect($servidor,$usuario,$senha,$dbname);

        if(!$conn){
            die ("Falha na conexão".mysqli_connect_error());
        }
        
?>