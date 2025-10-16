<?php

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status' => 'erro', 
        'mensagem' => 'Método não permitido.'
    ]);
    exit(); 
}

$jsonRecebido = file_get_contents('php://input');
$dados = json_decode($jsonRecebido, true);

if ($dados === null) {
    echo json_encode([
        'status' => 'erro', 
        'mensagem' => 'Erro json.'
    ]);
    exit();
}

$id_questao = $dados['id_questao'];
$pergunta = $dados['pergunta'];
$opc1 = $dados['opc1'];
$opc2 = $dados['opc2'];
$opc3 = $dados['opc3'];
$opc4 = $dados['opc4'];
$resposta_certa = $dados['resposta_certa'];

$nomeDoArquivo = "questoes_multipla.txt";

if (!file_exists($nomeDoArquivo)) {
    $arq = fopen($nomeDoArquivo, "w");
    fclose($arq);
}

$arq = fopen($nomeDoArquivo, "a");
$linha = $id_questao . ";" . $pergunta . ";" . $opc1 . ";" . $opc2 . ";" . $opc3 . ";" . $opc4 . ";" . $resposta_certa . PHP_EOL;
fwrite($arq, $linha);
fclose($arq);

echo json_encode([
    'status' => 'sucesso',
    'mensagem' => 'Questão registrada'
]);
?>
