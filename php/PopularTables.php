<?php

session_start();


if (isset($_GET['flag']) and ($_GET['flag'] == '1')) {
    popularOrdens();
} else if (isset($_GET['flag']) and ($_GET['flag'] == '2')) {
    popularAtividades();
} else  if (isset($_GET['flag']) and ($_GET['flag'] == '3')) {
    popularPontos();
} else  if (isset($_GET['flag']) and ($_GET['flag'] == '4')) {
    popularAgentes();
} else  if (isset($_GET['flag']) and ($_GET['flag'] == '5')) {
    poputarTecnico();
} else  if (isset($_GET['flag']) and ($_GET['flag'] == '6')) {
    removerEquipe();
}

function removerEquipe()
{

    include_once('../php/conexao.php');

    $sql = " DELETE FROM equipes where fk_tecnico = " . $_GET['fk_tecnico'] . " and fk_usuario = " . $_GET['fk_usuario'];

    if ($conn->query($sql) === TRUE) {
        echo "Record deleted successfully";
    } else {
        echo "Error deleting record: " . $conn->error;
    }

    $conn->close();
}

function poputarTecnico()
{

    include_once('../php/conexao.php');

    $sql = " SELECT tecnico.id_tecnico , tecnico.nome_tecnico FROM tecnico , equipes WHERE tecnico.id_tecnico = equipes.fk_tecnico AND equipes.fk_usuario = " . $_GET['id_usuario'] . "";

    //Consultando banco de dados
    $qryLista = mysqli_query($conn, $sql);

    while ($resultado = mysqli_fetch_assoc($qryLista)) {
        $vetor[] = array_map('utf8_encode', $resultado);
    }

    //Passando vetor em forma de json
    echo json_encode($vetor);
}

function popularAgentes()
{

    include_once('../php/conexao.php');

    $sql = " SELECT usuario.id_usuario AS id_usuario , usuario.nome_usuario AS nome_usuario FROM usuario WHERE modulo_agendamento=true";

    //Consultando banco de dados
    $qryLista = mysqli_query($conn, $sql);
    while ($resultado = mysqli_fetch_assoc($qryLista)) {
        $vetor[] = array_map('utf8_encode', $resultado);
    }

    //Passando vetor em forma de json
    echo json_encode($vetor);
}

function popularPontos()
{
    include_once('../php/conexao.php');

    $sql = " SELECT tec.id_tecnico as id_tecnico, tec.nome_tecnico , SUM(proc.pontucao) AS Pontos_normais , FORMAT(SUM(cad.ponto_extra),2) AS Pontos_adicionados , FORMAT(SUM( COALESCE(cad.ponto_extra,0)+ COALESCE(proc.pontucao,0)),2) AS pontos_totais FROM cadastro_agendamento AS cad , tecnico AS tec , procedimento_agendamento AS proc WHERE tec.id_tecnico = cad.fk_nome_tecnico AND ";


    if (intval($_SESSION['admin_user']) == 0) {
        $sql .= " cad.fk_usuario = " . intval($_SESSION['id_usuario']) . " AND";
    }

    $sql .= " cad.fk_procedimento_agendamento = proc.id_procedimento_agendamento AND ";

    if (date('d') < 20) {
        $sql .= " cad.data_execucao BETWEEN '" . date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m') . '-20'))) . "' AND '" . date('Y-m') . "-19'";
    } else {
        $sql .= " cad.data_execucao BETWEEN '" . date('Y-m') . "-20' AND '" . date('Y-m-d', strtotime('+1 month', strtotime(date('Y-m') . '-19'))) . "'";
    }

    $sql .= " GROUP BY tec.nome_tecnico";

    //Consultando banco de dados
    $qryLista = mysqli_query($conn, $sql);
    while ($resultado = mysqli_fetch_assoc($qryLista)) {
        $vetor[] = array_map('utf8_encode', $resultado);
    }

    //Passando vetor em forma de json
    echo json_encode($vetor);
}



function popularOrdens()
{
    include_once('../php/conexao.php');

    $sql = "SELECT cad.id_cadastro_agendamento, cad.numero_ordem , usu.nome_usuario AS Nome_usuario , cid.nome_cidade AS Cidade , DATE_FORMAT(cad.data_execucao,'%d/%m/%Y') AS data_execucao ,  tec.nome_tecnico AS Nome_tecnico , proc.nomeProcedimento as Nome_procedimento 
    , cad.numero_ordem AS numero_ordem , cad.ponto_extra AS ponto_extra , cad.motivo_ponto_extra AS motivo_extra , cad.observacao_agendamento AS obs 
    FROM cadastro_agendamento AS cad , usuario AS usu , tecnico AS tec, cidade AS cid , procedimento_agendamento AS proc 
    WHERE usu.id_usuario=cad.fk_usuario AND tec.id_tecnico = cad.fk_nome_tecnico AND cid.id_cidade = cad.fk_cidade AND proc.id_procedimento_agendamento = cad.fk_procedimento_agendamento ";

    if (intval($_SESSION['admin_user']) == 0) {
        $sql .= " AND cad.fk_usuario = " . intval($_SESSION['id_usuario']) . "";
    }

    //Consultando banco de dados
    $qryLista = mysqli_query($conn, $sql);
    while ($resultado = mysqli_fetch_assoc($qryLista)) {
        $vetor[] = array_map('utf8_encode', $resultado);
    }

    //Passando vetor em forma de json
    echo json_encode($vetor);
}



function popularAtividades()
{
    include_once('../php/conexao.php');

    $sql = "SELECT t.id_tecnico AS id_tecnico, t.nome_tecnico AS Nome_tecnico ,
    (COUNT(CASE WHEN c.fk_procedimento_agendamento = 2 THEN 1 END)+COUNT(CASE WHEN c.fk_procedimento_agendamento = 1 THEN 1 END)) AS instalacao,
    COUNT(CASE WHEN c.fk_procedimento_agendamento = 3 THEN 1 END) AS manutencao,
    COUNT(CASE WHEN c.fk_procedimento_agendamento = 4 THEN 1 END) AS retirada,
    COUNT(CASE WHEN c.fk_procedimento_agendamento = 5 THEN 1 END) AS vistoria,
    COUNT(CASE WHEN c.fk_procedimento_agendamento = 6 THEN 1 END) AS rompimento,
    COUNT(CASE WHEN c.fk_procedimento_agendamento = 9 THEN 1 END) AS duplado,
    COUNT(CASE WHEN c.fk_procedimento_agendamento = 7 THEN 1 END) AS treinamento_nf,
    COUNT(CASE WHEN c.fk_procedimento_agendamento = 8 THEN 1 END) AS nao_executado
    FROM tecnico AS t , cadastro_agendamento AS c
    WHERE t.id_tecnico=c.fk_nome_tecnico AND ";

    if (intval($_SESSION['admin_user']) == 0) {
        $sql .= " c.fk_usuario = " . intval($_SESSION['id_usuario']) . " AND ";
    }

    if (!isset($_GET['dataInicialAtividades']) && !isset($_GET['dataFinalAtividades'])) {
        if (date('d') < 20) {
            $sql .= " c.data_execucao BETWEEN '" . date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m') . '-20'))) . "' AND '" . date('Y-m') . "-19' ";
        } else {
            $sql .= " c.data_execucao BETWEEN '" . date('Y-m') . "-20' AND '" . date('Y-m-d', strtotime('+1 month', strtotime(date('Y-m') . '-19'))) . "' ";
        }
    } else {

        $dataFinalAtividades = \DateTime::createFromFormat('d/m/Y', $_GET['dataFinalAtividades']);

        $dataInicialAtividades = \DateTime::createFromFormat('d/m/Y', $_GET['dataInicialAtividades']);

        if ($dataInicialAtividades > $dataFinalAtividades) {
            echo 'Datas incorretas';
            die;
        }

        $sql .= " c.data_execucao BETWEEN '" . $dataInicialAtividades->format('Y-m-d') . "' AND '" . $dataFinalAtividades->format('Y-m-d') . "'";
    }

    $sql .= " GROUP BY t.nome_tecnico";

    //Consultando banco de dados
    $qryLista = mysqli_query($conn, $sql);
    while ($resultado = mysqli_fetch_assoc($qryLista)) {
        $vetor[] = array_map('utf8_encode', $resultado);
    }

    //Passando vetor em forma de json
    echo json_encode($vetor);
}
