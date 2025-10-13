<?php

$db_file = "perguntas-texto.txt";
$request_method = $_SERVER['REQUEST_METHOD'];

if ($request_method === 'GET' && isset($_GET['codigo'])) {
    $id_busca = $_GET['codigo'];

    $handle = fopen($db_file, "r");
    if (!$handle) {
        echo json_encode(["erro" => "Nao foi possivel abrir o arquivo de dados."]);
        exit;
    }
   
    $item_encontrado = false;
    while (($linha = fgets($handle)) !== false) {
        $colunas = explode(";", trim($linha));

        if (count($colunas) === 3 && $colunas[0] === $id_busca) {
            echo json_encode([$colunas[0], $colunas[1], $colunas[2]]);
            $item_encontrado = true;
            break;
        }
    }

    fclose($handle);
   
    if (!$item_encontrado) {
        echo json_encode(["erro" => "Item com o codigo {$id_busca} nao localizado."]);
    }
    exit;
}

if ($request_method === 'POST') {
    $id_item = $_POST['id'] ?? '';
    $texto_pergunta = $_POST['pergunta'] ?? '';
    $texto_resposta = $_POST['resposta'] ?? '';

    if (empty($id_item) || empty($texto_pergunta)) {
        http_response_code(400);
        echo "Dados invalidos. ID e Pergunta sao obrigatorios.";
        exit;
    }

    $todas_linhas = file($db_file, FILE_IGNORE_NEW_LINES);
    $lista_atualizada = [];
    $foi_atualizado = false;

    foreach ($todas_linhas as $linha) {
        $dados = explode(";", $linha);
        if (count($dados) === 3 && $dados[0] === $id_item) {
            $lista_atualizada[] = "{$id_item};{$texto_pergunta};{$texto_resposta}";
            $foi_atualizado = true;
        } else {
            $lista_atualizada[] = $linha;
        }
    }
   
    if ($foi_atualizado) {
        file_put_contents($db_file, implode("\n", $lista_atualizada) . "\n");
        echo "O item {$id_item} foi atualizado com sucesso.";
    } else {
        http_response_code(404);
        echo "O item com codigo {$id_item} nao foi encontrado para atualizar.";
    }
    exit;
}

?>