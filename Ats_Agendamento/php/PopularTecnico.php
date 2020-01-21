<?php
session_start();

if ($_GET['flag'] == '1') {
    popularEquipe();
} else if ($_GET['flag'] == '2') {
    popularTodosTecnico();
} else if ($_GET['flag'] == '3') {
    verificarTecnicoAdicional();
} else if ($_GET['flag'] == '4') {
    adicionarEquipe();
}else if ($_GET['flag'] == '5') {
    popularCidade();
}else if ($_GET['flag'] == '6') {
    cadastrarTecnico();
}


function cadastrarTecnico(){
    
    include_once('../php/conexao.php');

    if(isset($_GET['nomeTecnico'])){
        $nomeTecnico = $_GET['nomeTecnico'];
    }else {
        echo "Nome do tecnico nao encontrado";
        die;
    }
    
    if(isset($_GET['cidade'])){
        $cidade = $_GET['cidade'];
    }else {
        echo "Cidade nao econtrado";
        die;
    }

    if(isset($_GET['desc'])){
        $desc = $_GET['desc'];
    }else{
        $desc = null;
    }


    $sql = "SELECT `id_tecnico` FROM `tecnico` WHERE nome_tecnico LIKE '%" . utf8_decode($nomeTecnico) . "%'";
    $qryresult = mysqli_query($conn, $sql);
    $num_rows = mysqli_num_rows($qryresult);
    


    if ($num_rows >= 1) {
        echo "Este tecnico ja foi cadastrado";
        die;
    } else {
        $sql = "INSERT INTO `tecnico`(`id_tecnico`, `nome_tecnico`, `fk_cidade`, `des_tecnico`) VALUES (null,'".$nomeTecnico."',$cidade,'".$desc."')";
        if ($conn->query($sql) === TRUE) {
            echo true;
        } else {
            echo "Error: ".$sql."<br>".$conn->error;
        }
    }

    $conn->close();    

}

function popularCidade(){

    include_once('../php/conexao.php');
    
    $sql = " SELECT c.id_cidade as idCidade ,  C.nome_cidade AS nomeCidade FROM cidade AS C  ";

    $qryLista = mysqli_query($conn, $sql);
    while ($resultado = mysqli_fetch_assoc($qryLista)) {
        $vetor[] = array_map('utf8_encode', $resultado);
    }

    //Passando vetor em forma de json
    echo json_encode($vetor);
    $conn->close();
}

function adicionarEquipe()
{
    include_once('../php/conexao.php');

    // Futuramente Caso de errado alguma coisa com a $_SESSION['idTecnico'][0] substitua por um 'SELESCT' com 'mysqli_fetch_array($qryresult)';
    $sql = "SELECT id_tecnico from tecnico WHERE nome_tecnico LIKE '%" . utf8_decode($_GET['nomeTecnico']) . "%'";
    $qryresult = mysqli_query($conn, $sql);
    $idTecnico = mysqli_fetch_array($qryresult);

    $sql = "SELECT id_usuario from usuario WHERE nome_usuario LIKE '%" . utf8_decode($_GET['nomeAgente']) . "%'";
    $qryresult = mysqli_query($conn, $sql);
    $idAgente = mysqli_fetch_array($qryresult);

    $sql = "SELECT equipes.id_equipe FROM equipes 
    WHERE 
    equipes.fk_tecnico = $idTecnico[0] 
    AND
    equipes.fk_usuario = $idAgente[0]";

    $qryresult = mysqli_query($conn, $sql);
    $num_rows = mysqli_num_rows($qryresult);


    if ($num_rows >= 1) {
        echo "Este tecnico ja esta associado a essa 'Equipe'.";
        die;
    } else {

        $sql = "INSERT INTO `equipes`(`id_equipe`, `fk_usuario`, `fk_tecnico`) VALUES (null,$idAgente[0],$idTecnico[0])";

        if ($conn->query($sql) === TRUE) {
            echo "Adicionado com sucesso";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
    $conn->close();
}

function verificarTecnicoAdicional()
{


    include_once('../php/conexao.php');

    $nomeTecnico = utf8_decode($_GET['nomeTecnico']);

    $sql = "SELECT tecnico.id_tecnico AS id_tecnico FROM tecnico WHERE tecnico.nome_tecnico= '$nomeTecnico'";

    //Consultando banco de dados
    $qryresult = mysqli_query($conn, $sql);
    $num_rows = mysqli_num_rows($qryresult);

    //Passando vetor em forma de json
    echo $num_rows;
}


function popularEquipe()
{


    include_once('../php/conexao.php');

    $sql = "SELECT tecnico.id_tecnico , tecnico.nome_tecnico AS nome_tecnico FROM tecnico , equipes where equipes.fk_usuario = " . $_SESSION['id_usuario'] . " AND tecnico.id_tecnico = equipes.fk_tecnico";


    //Consultando banco de dados
    $qryLista = mysqli_query($conn, $sql);
    while ($resultado = mysqli_fetch_assoc($qryLista)) {
        $vetor[] = array_map('utf8_encode', $resultado);
    }

    //Passando vetor em forma de json
    echo json_encode($vetor);
}

function popularTodosTecnico()
{

    include_once('../php/conexao.php');

    $sql = "SELECT tecnico.id_tecnico AS id_tecnico , tecnico.nome_tecnico AS nome_tecnico FROM tecnico";


    //Consultando banco de dados
    $qryLista = mysqli_query($conn, $sql);
    while ($resultado = mysqli_fetch_assoc($qryLista)) {
        $vetor[] = array_map('utf8_encode', $resultado);
    }

    //Passando vetor em forma de json
    echo json_encode($vetor);
}
