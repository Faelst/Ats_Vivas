<?php

if (isset($_GET['metodo'])) {
    $i = $_GET['metodo'];
} else if (isset($_POST['metodo'])) {
    $i = $_POST['metodo'];
} else {
    echo 'Metodo nao passado Corretamente';
    die;
}

switch ($i) {
    case 'carregarOperador':
        /* executar função */
        carregarOperador();
        break;
    case 'FiltrarTabela':
        popularOrdensFiltro();
        break;
}

function carregarOperador()
{
    session_start();

    include_once('../conexao.php');

    $sql = "Select id_usuario as id , nome_usuario as nome From usuario where modulo_agendamento = 1 ";

    if (intval($_SESSION['admin_user']) == 0) {
        $sql .= " AND id_usuario = " . intval($_SESSION['id_usuario']) . " ";
    }

    $qryresult = mysqli_query($conn, $sql);
    while ($callback = mysqli_fetch_assoc($qryresult)) {
        $vetor[] = array_map('utf8_encode', $callback);
    }
    echo json_encode($vetor);
    die();
}

function popularOrdensFiltro()
{
    session_start();
    include_once('../conexao.php');

    $json = $_POST['objetoFiltro'];

    $json = json_decode($json, true);

    $sql = "SELECT cad.id_cadastro_agendamento, cad.numero_ordem , usu.nome_usuario AS Nome_usuario , cid.nome_cidade AS Cidade , DATE_FORMAT(cad.data_execucao,'%d/%m/%Y') AS data_execucao ,
    tec.nome_tecnico AS Nome_tecnico , proc.nomeProcedimento as Nome_procedimento , cad.numero_ordem AS numero_ordem , cad.ponto_extra AS ponto_extra , cad.motivo_ponto_extra AS motivo_extra ,
    cad.observacao_agendamento AS obs FROM cadastro_agendamento AS cad , usuario AS usu , tecnico AS tec, cidade AS cid , procedimento_agendamento AS proc 
    WHERE usu.id_usuario=cad.fk_usuario AND tec.id_tecnico = cad.fk_nome_tecnico AND cid.id_cidade = cad.fk_cidade AND proc.id_procedimento_agendamento = cad.fk_procedimento_agendamento ";

    if ($json['somenteExtra'] == true) {
        $sql .= " AND ( cad.ponto_extra != 0 or cad.ponto_extra != '' ) ";
    }

    if (intval($_SESSION['admin_user']) == 0) {
        $sql .= " AND cad.fk_usuario = " . intval($_SESSION['id_usuario']) . " ";
    } else {
        if (count($json['operadoreSelecionados']) > 1) {
            $sql .= " AND ( ";
            foreach ($json['operadoreSelecionados'] as &$value) {
                $sql .= " cad.fk_usuario = $value OR";
            }
            $sql = rtrim($sql , "OR");
            $sql .= " ) ";
        } else if (count($json['operadoreSelecionados']) == 1) {
            $id = $json['operadoreSelecionados'][0];
            $sql .= " AND cad.fk_usuario = $id ";
        }
    }

    if (isset($json['dataInicia']) && isset($json['dataFinal'])) {
        $dataInicial =  date("Y-m-d", strtotime(str_replace('/', '-', $json['dataInicia'])));
        $dataFinal = date("Y-m-d", strtotime(str_replace('/', '-', $json['dataFinal'])));
        if ($dataInicial < $dataFinal) {
            $sql .= "AND cad.data_execucao BETWEEN '$dataInicial' AND '$dataFinal' ";
        } else {
            echo "Uma data esta maior que a outra";
            die;
        }
    } else {
        echo 'Digite as datas corretamente';
        die();
    }
    
    //Consultando banco de dados
    $qryLista = mysqli_query($conn, $sql);
    while ($resultado = mysqli_fetch_assoc($qryLista)) {
        $vetor[] = array_map('utf8_encode', $resultado);
    }

    //Passando vetor em forma de json
    echo json_encode($vetor);
}
