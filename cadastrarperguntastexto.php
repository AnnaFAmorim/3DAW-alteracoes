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
$texto_questao = $dados['texto_questao'];
$texto_resposta = $dados['texto_resposta'];

$nomeDoArquivo = "questoes_texto.txt";

if (!file_exists($nomeDoArquivo)) {
    $arq = fopen($nomeDoArquivo, "w");
    fclose($arq);
}


$arq = fopen($nomeDoArquivo, "a");
$linha = $id_questao . ";" . $texto_questao . ";" . $texto_resposta . PHP_EOL;
fwrite($arq, $linha);
fclose($arq);

echo json_encode([
    'status' => 'sucesso',
    'mensagem' => 'Questão registrada'
]);

?>