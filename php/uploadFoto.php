<?php

session_start();

switch ($_GET['flag']) {
   case 1:
      carregarFotoUsuario();
      break;
   case 2:
      editarFoto();
      break;
   case 3:
      consultarSenhaAtual();
      break;
   case 4:
      updateSenha();
      break;
}

function updateSenha(){

   include_once('../php/conexao.php');

   $senhaAtual = $_POST['novaSenha'];

   $sql = "UPDATE usuario SET senha_usuario = '$senhaAtual' WHERE usuario.nome_usuario = '" . $_SESSION['nome_usuario'] . "'";

   if (mysqli_query($conn, $sql)) {
      echo "1";
   } else {
      echo "Error updating record: " . mysqli_error($conn);
   }
   mysqli_close($conn);

}


function consultarSenhaAtual(){

   include_once('../php/conexao.php');
   $senhaAtual = $_POST['senhaAtual'];

   $sql = "SELECT id_usuario AS id FROM usuario WHERE usuario.nome_usuario = '" . $_SESSION['nome_usuario'] . "' AND usuario.senha_usuario = '$senhaAtual'";

   $result = mysqli_query($conn, $sql);

   if ($result) 
    { 
        // it return number of rows in the table. 
        $row = mysqli_num_rows($result); 
          
        echo utf8_encode($row); 
    
        // close the result. 
        mysqli_free_result($result); 
    } 
  
    // Connection close  
    mysqli_close($conn); 

}

function carregarFotoUsuario()
{

   include_once('../php/conexao.php');

   $sql = "SELECT foto_usuario AS foto_perfil FROM user_profile WHERE fk_usuario = (SELECT id_usuario FROM usuario where nome_usuario = '" . $_SESSION['nome_usuario'] . "')LIMIT 1";

   $result = mysqli_query($conn, $sql);

   $obj = $result->fetch_object();

   /* free result set */
   if (is_object($obj)) {
      echo utf8_encode($obj->foto_perfil);
   } else {
      echo "";
   }
}


function editarFoto()
{
   $filename = $_FILES['file']['name'];

   /* Location */
   $location = "upload/" . $filename;
   $uploadOk = 1;
   $imageFileType = pathinfo($location, PATHINFO_EXTENSION);

   /* Valid Extensions */
   $valid_extensions = array("jpg", "jpeg", "png", "gif");

   /* Check file extension */
   if (!in_array(strtolower($imageFileType), $valid_extensions)) {
      $uploadOk = 0;
   }

   if ($uploadOk == 0) {
      echo 0;
   } else {

      if (!file_exists("img")) :
         mkdir("img");
      endif;

      /* Upload file */
      if (move_uploaded_file($_FILES['file']['tmp_name'], "img/" . $_FILES['file']['name'])) {
         CarregarFotoBanco($_FILES['file']['name']);
      } else {
         echo 0;
      }
   }
}

function CarregarFotoBanco($nomeFoto)
{

   include_once('../php/conexao.php');

   $sql = 'SELECT * FROM user_profile WHERE fk_usuario = (SELECT id_usuario FROM usuario WHERE nome_usuario = "' . $_SESSION['nome_usuario'] . '")';

   $result = mysqli_query($conn, $sql);

   $row_cnt = $result->num_rows;

   if ($row_cnt >= 1) {

      $sql = "UPDATE `user_profile` SET `foto_usuario` = '$nomeFoto' WHERE fk_usuario = (SELECT id_usuario FROM usuario WHERE nome_usuario = '" . $_SESSION['nome_usuario'] . "')";

      mysqli_query($conn, $sql);

      echo 'Foto Alterarda Com sucesso';
   } else {

      $sql = "INSERT INTO `user_profile`(`id_usuario_perfil`, `fk_usuario`, `foto_usuario`) VALUES (null,(SELECT id_usuario FROM usuario WHERE nome_usuario = '" . $_SESSION['nome_usuario'] . "'),'$nomeFoto')";

      mysqli_query($conn, $sql);
      echo $sql;
   }
}
