<?php

header('Content-Type: text/html; charset=utf-8');

session_start();

switch (intval($_GET['switchFlag'])) {
    case 1:
        cadastrarOrdem();
        break;
    case 2:
        verificarDuplicidade();
        break;
}

function verificarDuplicidade()
{

    //verificação se o campos estão vazios
    if (isset($_GET['nOrdem']) && strlen($_GET['nOrdem']) <= 3) {
        
        echo '0';
        die();

    } else {

        include_once('../php/conexao.php');

        $sql = "SELECT * FROM cadastro_agendamento WHERE numero_ordem=" . $_GET['nOrdem'];

        $result = mysqli_query($conn, $sql);
        $rows = mysqli_num_rows($result);

        echo $rows;
    }
}


function cadastrarOrdem()
{
    include_once('../php/conexao.php');

    //verificação se o campos estão vazios
    if (isset($_GET['numeroOrdem']) && strlen($_GET['numeroOrdem']) <= 3) {
        echo 'Informe NUMERO DA ONDEM DE SERVIÇO corretamente.';
        die();
    } else {
        $numeroOrdem = $_GET['numeroOrdem'];
    }

    if (isset($_GET['cidade']) && strlen($_GET['cidade']) < 1) {
        echo 'Selecione a cidade corretamente';
        die();
    } else {
        $cidade = $_GET['cidade'];
    }

    if (isset($_GET['dataExecucao']) && !strlen($_GET['dataExecucao']) == '8') {
        echo 'Informe a data de abertura corretamente';
        die();
    } else {
        $dataExecucao = \DateTime::createFromFormat('d/m/Y',  $_GET['dataExecucao']);
    }

    if (isset($_GET['dataFechamento']) && !strlen($_GET['dataFechamento']) == '8') {
        echo 'Informe a DATA DE FECHAMENTO corretamente';
        die();
    } else {
        $dataFechamento = date('Y/m/d');
    }

    if (isset($_GET['tipoOrdenServico']) && strlen($_GET['tipoOrdenServico']) < 3) {
        echo 'selecione o TIPO DE SERVIÇO corretamente';
        die();
    } else {
        $tipoOrdenServico = utf8_decode($_GET['tipoOrdenServico']);
    }

    if (isset($_GET['nomeTecnico']) && ($_GET['nomeTecnico']) == "" || strlen($_GET['nomeTecnico']) < '5') {
        echo 'Informe o nome do tecnico corretamente';
        die();
    } else {
        $nomeTecnico = utf8_decode($_GET['nomeTecnico']);
    }

    if (isset($_GET['check']) && $_GET['check'] == 'true') {
        if (isset($_GET['pontuacaoExtra']) && !is_numeric($_GET['pontuacaoExtra'])) {
            echo 'Informe o NUMERO DE PONTUAÇÃO EXTRA corretamente';
            die();
        } else {
            if (isset($_GET['motivoPontoExtra']) && strlen($_GET['motivoPontoExtra']) < 5) {
                echo 'Informe o MOTIVO DO PONTO EXTRA';
                die();
            } else {
                $motivoPontoExtra = "'" . utf8_decode($_GET['motivoPontoExtra']) . "'";
                $pontoExtra = $_GET['pontuacaoExtra'];
            }
        }
    } else {

        $motivoPontoExtra = 'null';
        $pontoExtra = 'null';
    }

    if (isset($_GET['observacao']) && strlen($_GET['observacao']) > 12) {
        $observacao = "'" . utf8_decode($_GET['observacao']) . "'";
    } else {
        echo 'Informe a OBSERVAÇÃO DO CADASTRO corretamente.';
        $observacao = null;
        die();
    }

    if ($_GET['tipoOrdenServico'] == 'Duplado') {
        $motivoPontoExtra = "'" . utf8_decode($_GET['motivoPontoExtra']) . "'";
        
        if (isset($_GET['nomeTenicoDuplado'])) {
            $nomeTenicoDuplado = $_GET['nomeTenicoDuplado'];
            if(floatval($_GET['pontuacaoExtra']) > 0){
                $pontoExtra = ($_GET['pontuacaoExtra']) / 2;
                $flag = cadastrarSegundoTecnicoDuplado($nomeTenicoDuplado, $cidade, $tipoOrdenServico, $numeroOrdem, $dataExecucao, $dataFechamento, $pontoExtra, $motivoPontoExtra, $observacao, $conn);
            }else {
                echo 'Informe o Ponto Corretamente';
                die;
            }
        } else {
            echo 'Insira o 2º Tecnico corretamente.';
            die;
        }
    } else {
        $flag = true;
    }


    if ($flag == true) {
        $sql = "INSERT INTO cadastro_agendamento ";
        $sql .= " (id_cadastro_agendamento,fk_usuario , fk_cidade, fk_nome_tecnico, fk_procedimento_agendamento, numero_ordem, data_execucao, datafechamento_ordem, ponto_extra, motivo_ponto_extra, observacao_agendamento)";
        $sql .= "VALUES ";
        $sql .= "(null," . $_SESSION['id_usuario'] . ",$cidade,(SELECT id_tecnico FROM tecnico where nome_tecnico = '$nomeTecnico'), ";
        $sql .= "(SELECT id_procedimento_agendamento FROM procedimento_agendamento where nomeProcedimento='$tipoOrdenServico'), ";
        $sql .= " $numeroOrdem,'{$dataExecucao->format('Y-m-d')}','{$dataFechamento}',$pontoExtra,$motivoPontoExtra,$observacao) ";

        if (mysqli_query($conn, $sql)) {
            echo true;
        } else {
            echo "ERRO AO CADASTRAR.\nInforme o Rafael(ramal 407).\n";
            printf("Error: %s\n", $conn->error);
        }

        mysqli_close($conn);
    } else {
        echo 'Algo deu Errado. Verifique as informações ou Reporte ao Desenvolvedor.';
    }
}

function cadastrarSegundoTecnicoDuplado($nomeTenicoDuplado, $cidade, $tipoOrdenServico, $numeroOrdem, $dataExecucao, $dataFechamento, $pontoExtra, $motivoPontoExtra, $observacao, $conn)
{
    $sql = "INSERT INTO cadastro_agendamento ";
    $sql .= " (id_cadastro_agendamento,fk_usuario , fk_cidade, fk_nome_tecnico, fk_procedimento_agendamento, numero_ordem, data_execucao, datafechamento_ordem, ponto_extra, motivo_ponto_extra, observacao_agendamento)";
    $sql .= "VALUES ";
    $sql .= "(null," . $_SESSION['id_usuario'] . ",$cidade,(SELECT id_tecnico FROM tecnico where nome_tecnico = '$nomeTenicoDuplado'),";
    $sql .= "(SELECT id_procedimento_agendamento FROM procedimento_agendamento where nomeProcedimento='$tipoOrdenServico'),";
    $sql .= " $numeroOrdem,'{$dataExecucao->format('Y-m-d')}','{$dataFechamento}',$pontoExtra,$motivoPontoExtra,$observacao)";

    if (mysqli_query($conn, $sql)) {
        return true;
    } else {
        echo "ERRO AO CADASTRAR.\nInforme o Rafael(ramal 407).\n";
        printf("Error: %s\n", $conn->error);
    }
}
