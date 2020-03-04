<?php

require_once '../../phpPDF/dompdf/lib/html5lib/Parser.php';
require_once '../../phpPDF/dompdf/lib/php-font-lib/src/FontLib/Autoloader.php';
require_once '../../phpPDF/dompdf/lib/php-svg-lib/src/autoload.php';
require_once '../../phpPDF/dompdf/src/Autoloader.php';
Dompdf\Autoloader::register();

//use Dompdf\Dompdf;
//use Dompdf\Options;
//use Dompdf\Dompdf;
use Dompdf\Dompdf;

include_once('../conexao.php');

$sql = " SELECT tec.id_tecnico as id_tecnico, tec.nome_tecnico AS nome_tecnico , SUM(proc.pontucao) AS Pontos_normais , /*FORMAT(*/SUM(cad.ponto_extra)/*,2)*/ AS Pontos_adicionados , FORMAT(SUM( COALESCE(cad.ponto_extra,0)+ COALESCE(proc.pontucao,0)),2) AS pontos_totais FROM cadastro_agendamento AS cad , tecnico AS tec , procedimento_agendamento AS proc WHERE tec.id_tecnico = cad.fk_nome_tecnico AND ";

$sql .= " cad.fk_procedimento_agendamento = proc.id_procedimento_agendamento AND ";

$dataInicial = $_POST['dataInicial'];
$dataFinal = $_POST['dataFinal'];

$sql .= " cad.data_execucao BETWEEN '" . $dataInicial . "' AND '" . $dataFinal ."'";

/*if (date('d') < 20) {
    $sql .= " cad.data_execucao BETWEEN '" . date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m') . '-20'))) . "' AND '" . date('Y-m') . "-19'";
} else {
    $sql .= " cad.data_execucao BETWEEN '" . date('Y-m') . "-20' AND '" . date('Y-m-d', strtotime('+1 month', strtotime(date('Y-m') . '-19'))) . "'";
}*/

$sql .= " GROUP BY tec.nome_tecnico ORDER BY Pontos_adicionados DESC;";


//Consultando banco de dados
$qryLista = mysqli_query($conn, $sql);

$html = '';

while($dados = mysqli_fetch_array($qryLista)){
    $id_tecnico = $dados['id_tecnico'];
    $nome_tecnico = $dados['nome_tecnico']; 
    $Pontos_normais = $dados['Pontos_normais'];
    $Pontos_adicionados = number_format($dados['Pontos_adicionados'],2,".","");
    $pontos_totais = $dados['pontos_totais'];

    $html .= "<tr role='row' class='odd'><td>$id_tecnico</td><td>$nome_tecnico</td><td>$Pontos_normais</td><td>$Pontos_adicionados</td><td class='sorting_1'>$pontos_totais</td></tr>";
    
    
}
    // instantiate and use the dompdf class
    $dompdf = new Dompdf();

    $dompdf->loadHtml("<html>

    <head>
        <meta charset='utf-8'>
        <link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css'
            integrity='sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh' crossorigin='anonymous'>
              
    <script src='https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js'
    integrity='sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo'
    crossorigin='anonymous'></script>
<script src='https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js'
    integrity='sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6'
    crossorigin='anonymous'></script>
    </head>
    
    <body>
        <div class='cotainer-fluid'>
            <div class='d-flex '>
                <div class='justify-content-start mt-4'>
                    <div class='ml-5'>
                        <img style='width: 200px;' src='../../img/images/logo.png' />
                    </div>
                </div>
                  <div style='margin-left:250px;'>
                    <p class='m-0'>Nome do(a) Responsavel: Leticia Fernandes </p>
                    <p class='m-0'>E-mail: leticia.fernandes@vivasinternet.com.br</p>
                    <p class='m-0'>Data: ".date('d/m/Y H:i:s')."</p>
                  </div>  
            </div>
            <hr class='my-0 mb-4'>
            <div class='ml-5'>
                <h5>Relatorio de Pontuação Mensal dos Tecnicos</h5>
                <p class='font-weight-normal'>- Periodo: ".date_create($dataInicial)->format('d/m/Y')." a ".date_create($dataFinal)->format('d/m/Y')."</p>
            </div>
    
            <div class='ml-5'>
                <table class='table table-striped w-25'>
                    <thead>
                        <tr>
                            <th scope='col'>Id</th>
                            <th scope='col'>Nome Completo:</th>
                            <th scope='col'>Pontuação</th>
                            <th scope='col'>Pontos Extras</th>
                            <th scope='col'>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        $html
                    </tbody>
                </table>
            </div>
        </div>
    ");

    // (Optional) Setup the paper size and orientation
    $dompdf->setPaper('A4', 'portrait');

    // Render the HTML as PDF
    $dompdf->render();
    $output = $dompdf->output();
    // Output the generated PDF to Browser
    file_put_contents('Relatorio_ATS('.date("d").'-' . date("m") . '-' . date("Y") . ').pdf', $output);

    echo json_encode('Relatorio_ATS('.date("d").'-' . date("m") . '-' . date("Y") . ').pdf');
