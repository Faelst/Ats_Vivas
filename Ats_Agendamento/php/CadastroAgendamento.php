<?php
header('Content-Type: text/html; charset=utf-8');
session_start();


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
    $nomeTecnico = $_GET['nomeTecnico'];
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
            $motivoPontoExtra ="'".$_GET['motivoPontoExtra']."'";
            $pontoExtra = $_GET['pontuacaoExtra'];
        }
    }
} else {

    $motivoPontoExtra = 'null';
    $pontoExtra = 'null';
}

if($_GET['tipoOrdenServico']=='Duplado'){
    $pontoExtra = $_GET['pontuacaoExtra'];
    $motivoPontoExtra = "'" . utf8_decode($_GET['motivoPontoExtra'])."'";
}

if (isset($_GET['observacao']) && strlen($_GET['observacao']) > 5) {
    $observacao = "'" . utf8_decode($_GET['observacao']) . "'";
} else {
    print_r('Observação e nula');
    $observacao = null;
}

$sql = "INSERT INTO cadastro_agendamento ";
$sql .= " (id_cadastro_agendamento,fk_usuario , fk_cidade, fk_nome_tecnico, fk_procedimento_agendamento, numero_ordem, data_execucao, datafechamento_ordem, ponto_extra, motivo_ponto_extra, observacao_agendamento)";
$sql .= "VALUES ";
$sql .= "(null,22,$cidade,(SELECT id_tecnico FROM tecnico where nome_tecnico = '$nomeTecnico'),";
$sql .= "(SELECT id_procedimento_agendamento FROM procedimento_agendamento where nomeProcedimento='$tipoOrdenServico'),";
$sql .= " $numeroOrdem,'{$dataExecucao->format('Y-m-d')}','{$dataFechamento}',$pontoExtra,$motivoPontoExtra,$observacao)";


include_once('../php/conexao.php');
utf8_decode($sql);

$flag = true;

if (mysqli_query($conn, $sql)) {
    echo true;
} else {
    echo "ERRO AO CADASTRAR.\nInforme o Rafael(ramal 407).\n";
    printf("Error: %s\n", $conn->error);
}

mysqli_close($conn);
