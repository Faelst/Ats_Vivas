<?php

session_start();

if ($_GET['flag'] == 'setar') {
    selecionarAlterar();
} else if ($_GET['flag'] == 'excluir') {
    excluirOrdem();
} else if ($_GET['flag'] == 'alterar') {
    alterarOrdem();
} else if ($_GET['flag'] == 'intervalo') {
    intervalo();
}

function intervalo()
{
    include_once('../php/conexao.php');

    $sql =  "SELECT tec.id_tecnico as id_tecnico, tec.nome_tecnico , SUM(proc.pontucao) AS Pontos_normais , FORMAT(SUM(cad.ponto_extra),2) AS Pontos_adicionados , ";
    $sql .= " FORMAT(SUM( COALESCE(cad.ponto_extra,0)+ COALESCE(proc.pontucao,0)),2) AS pontos_totais ";
    $sql .= " FROM cadastro_agendamento AS cad , tecnico AS tec , procedimento_agendamento AS proc ";
    $sql .= " WHERE tec.id_tecnico = cad.fk_nome_tecnico ";

    if (intval($_SESSION['admin_user']) == 0) {
        $sql .= " AND cad.fk_usuario = " . intval($_SESSION['id_usuario']) . " ";
    }

    if (isset($_GET['dataInicial'])) {
        $dataInicial =  \DateTime::createFromFormat('d/m/Y', $_GET['dataInicial']);
        $sql .= " AND cad.data_execucao BETWEEN '{$dataInicial->format('Y-m-d')}' ";
    }
    if (isset($_GET['dataFinal'])) {
        $dataFinal =  \DateTime::createFromFormat('d/m/Y', $_GET['dataFinal']);
        $sql .= " AND '{$dataFinal->format('Y-m-d')}' ";
    }

    $sql .= " AND cad.fk_procedimento_agendamento = proc.id_procedimento_agendamento GROUP BY tec.nome_tecnico;";

    if ($dataInicial <= $dataFinal) {

        $qryLista = mysqli_query($conn, $sql);

        while ($resultado = mysqli_fetch_assoc($qryLista)) {
            $vetor[] = array_map('utf8_encode', $resultado);
        }
    } else {
        echo 'Digite as Datas Corretamente.';
        die();
    }


    if (!empty($vetor)) {
        echo json_encode($vetor);
        die();
    } else {
        echo '0';
    }


    //Passando vetor em forma de json

    die();
}

function alterarOrdem()
{

    include_once('../php/conexao.php');

    $sql = "UPDATE `cadastro_agendamento` SET ";



    if (isset($_GET['selectCIdade'])) {
        $sql .= "fk_cidade = " . $_GET['selectCIdade'] . ",";
    }
    if (isset($_GET['nomeTenico'])) {
        $sql .= "fk_nome_tecnico = (select id_tecnico from tecnico where nome_tecnico='" . $_GET['nomeTenico'] . "') ,";
    }
    if (isset($_GET['tipoOrdem'])) {
        $sqlOrdem = "SELECT id_procedimento_agendamento FROM procedimento_agendamento WHERE nomeProcedimento = '" . utf8_decode($_GET['tipoOrdem']) . "'";
        $qryresult = mysqli_query($conn, $sqlOrdem);
        $idTipoOrdem = mysqli_fetch_array($qryresult);
        $sql .= "fk_procedimento_agendamento = $idTipoOrdem[0] ,";
    }
    if (isset($_GET['txtPontosExtra'])) {
        $sql .= " ponto_extra = " . $_GET['txtPontosExtra'] . ",";
    }
    if (isset($_GET['txtAreaPontoExtra'])) {
        $sql .= " motivo_ponto_extra =  '" . utf8_decode($_GET['txtAreaPontoExtra']) . "' ,";
    }
    if (isset($_GET['txtObs'])) {
        $sql .= " observacao_agendamento = '" . utf8_decode($_GET['txtObs']) . "' ,";
    }

    $sql = rtrim($sql, ',');

    $sql .= " WHERE cadastro_agendamento.id_cadastro_agendamento = {$_GET['idOrdem']} AND cadastro_agendamento.numero_ordem = {$_GET['nOrdem']}";

    if (mysqli_query($conn, $sql)) {
        echo "1";
    } else {
        echo "Erro ao deletar: " . mysqli_error($conn);
    }
}


function excluirOrdem()
{

    if (isset($_GET['idOrdem'])) {
        $idOrdem = $_GET['idOrdem'];
    } else {
        echo 'Aconteceu algum erro Verifique com seu dev. o ocrrido';
        die();
    }

    if (isset($_GET['nOrdem'])) {
        $nOrdem = $_GET['nOrdem'];
    } else {
        echo 'Aconteceu algum erro Verifique com seu dev. o ocrrido';
        die();
    }

    $sql = "DELETE FROM `cadastro_agendamento` WHERE `cadastro_agendamento`.`id_cadastro_agendamento` = $idOrdem and `cadastro_agendamento`.`numero_ordem`= $nOrdem";

    include_once('../php/conexao.php');

    if (mysqli_query($conn, $sql)) {
        echo "1";
    } else {
        echo "Erro ao deletar: " . mysqli_error($conn);
    }
}





function selecionarAlterar()
{

    if (isset($_GET['tipoPesquisa'])) {
        $tipoPesquisa = $_GET['tipoPesquisa'];
    }

    if (isset($_GET['numeroOrdem'])) {
        $numeroOrdem = $_GET['numeroOrdem'];
    }

    $sql = "SELECT cad.numero_ordem AS numeroOrdem , cid.id_cidade AS cidade , cad.data_execucao AS data_execucao ,
cad.datafechamento_ordem AS data_fechamento , tec.nome_tecnico AS nomeTecnico , proc.nomeProcedimento AS procedimento ,
proc.pontucao AS pontos , cad.ponto_extra AS ponto_extra , cad.motivo_ponto_extra AS motivo,  cad.observacao_agendamento AS obs ";

    $sql .= "FROM cadastro_agendamento AS cad , cidade AS cid , tecnico as tec , procedimento_agendamento AS proc ";

    $sql .= "WHERE cad.id_cadastro_agendamento = $numeroOrdem
and cad.fk_cidade=cid.id_cidade and tec.id_tecnico=cad.fk_nome_tecnico and proc.id_procedimento_agendamento=cad.fk_procedimento_agendamento; ";

    include_once('../php/conexao.php');

    //Consultando banco de dados
    $qryLista = mysqli_query($conn, $sql);

    if (mysqli_num_rows($qryLista)) {

        while ($resultado = mysqli_fetch_assoc($qryLista)) {
            $vetor[] = array_map('utf8_encode', $resultado);
        }
        //Passando vetor em forma de json

        echo json_encode($vetor);

        die();
    } else {
        echo '';
        die();
    }
}
