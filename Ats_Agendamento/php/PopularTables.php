<?php



if (isset($_GET['flag']) and ($_GET['flag'] == '1')) {
    popularOrdens();
} else if (isset($_GET['flag']) and ($_GET['flag'] == '2'))  {
    popularAtividades();
}else  if (isset($_GET['flag']) and ($_GET['flag'] == '3')){
    popularPontos();
} 


function popularPontos()
{
    include_once('../php/conexao.php');

    $sql = " SELECT tec.id_tecnico as id_tecnico, tec.nome_tecnico , SUM(proc.pontucao) AS Pontos_normais , FORMAT(SUM(cad.ponto_extra),2) AS Pontos_adicionados , 
    FORMAT(SUM( COALESCE(cad.ponto_extra,0)+ COALESCE(proc.pontucao,0)),2) AS pontos_totais 
    FROM cadastro_agendamento AS cad , tecnico AS tec , procedimento_agendamento AS proc 
    WHERE tec.id_tecnico = cad.fk_nome_tecnico AND cad.fk_procedimento_agendamento = proc.id_procedimento_agendamento GROUP BY tec.nome_tecnico";


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
    WHERE usu.id_usuario=cad.fk_usuario AND tec.id_tecnico = cad.fk_nome_tecnico AND cid.id_cidade = cad.fk_cidade AND proc.id_procedimento_agendamento = cad.fk_procedimento_agendamento";


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
    COUNT(CASE WHEN c.fk_procedimento_agendamento = 1 THEN 1 END) AS instalacao,
    COUNT(CASE WHEN c.fk_procedimento_agendamento = 2 THEN 1 END) AS alta_dificuldade,
    COUNT(CASE WHEN c.fk_procedimento_agendamento = 3 THEN 1 END) AS manutencao,
    COUNT(CASE WHEN c.fk_procedimento_agendamento = 4 THEN 1 END) AS retirada,
    COUNT(CASE WHEN c.fk_procedimento_agendamento = 5 THEN 1 END) AS vistoria,
    COUNT(CASE WHEN c.fk_procedimento_agendamento = 6 THEN 1 END) AS rompimento,
    COUNT(CASE WHEN c.fk_procedimento_agendamento = 9 THEN 1 END) AS duplado,
    COUNT(CASE WHEN c.fk_procedimento_agendamento = 7 THEN 1 END) AS treinamento_nf,
    COUNT(CASE WHEN c.fk_procedimento_agendamento = 8 THEN 1 END) AS nao_executado
    FROM tecnico AS t , cadastro_agendamento AS c 
    WHERE t.id_tecnico=c.fk_nome_tecnico
    GROUP BY t.nome_tecnico";


    //Consultando banco de dados
    $qryLista = mysqli_query($conn, $sql);
    while ($resultado = mysqli_fetch_assoc($qryLista)) {
        $vetor[] = array_map('utf8_encode', $resultado);
    }

    //Passando vetor em forma de json
    echo json_encode($vetor);
}
