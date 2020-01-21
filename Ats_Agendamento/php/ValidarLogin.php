<?php

session_start();

switch (intval($_GET['switchFlag'])) {
    case 0:
        fazerLogin();
        break;
    case 1:
        echo $_SESSION['admin_user'];
        break;
    case 2:
        echo "i equals 2";
        break;
}


function fazerLogin()
{

    include_once('conexao.php');

    $usuario = mysqli_real_escape_string($conn, $_GET['usuarioLogin']);
    $senha = mysqli_real_escape_string($conn, $_GET['senhaLogin']);

    $sql = "SELECT id_usuario , nome_usuario , admin_user FROM usuario WHERE nick_usuario = '{$usuario}' AND senha_usuario = '{$senha}'";

    $result = mysqli_query($conn, $sql);
    $rows = mysqli_num_rows($result);

    if (mysqli_num_rows($result) > 0) {
        // output data of each row
        while ($row = mysqli_fetch_assoc($result)) {
            $_SESSION['id_usuario'] = $row["id_usuario"];
            $_SESSION['nome_usuario'] = $row["nome_usuario"];
            $_SESSION['admin_user'] = $row["admin_user"];
            $json = array(
                'rows' => $rows,
                'admin' => $row["admin_user"]
            );
        }
    }

    if (isset($json)) {
        $_SESSION['validaLogin'] = $rows;
        $json = json_encode($json);
        echo $json;
    } else {
        $json = array(
            'rows' => 0,
            'admin' => 0
        );
        $json = json_encode($json);
        echo $json;
    }
}
