<?php
header('Content-Type: application/json');
$servidor = "localhost";
$usuario = "root";
$senha = "";
$banco = "av1bancoanna";

$conexao = new mysqli($servidor, $usuario, $senha, $banco);
$resposta_json = [
    'status' => 'erro',
    'mensagem' => 'Ocorreu um erro ao salvar.'
];

if ($conexao->connect_error) {
    $resposta_json['mensagem'] = 'Erro ao conectar: ' . $conexao->connect_error;
    echo json_encode($resposta_json);
    die();
}

$json_recebido = file_get_contents('php://input');
$dados = json_decode($json_recebido, true);

if ($dados && isset($dados['tipo'])) {
    $tipo = $dados['tipo'];
    $query_sql = "";

    if ($tipo == 'discursiva') {
        $id_para_salvar = $dados['id_questao'];
        $nova_pergunta = $dados['texto_questao'];
        $nova_resposta = $dados['texto_resposta'];
        
        $query_sql = "UPDATE QuestoesDiscursivas SET texto_questao = '" . $nova_pergunta . "', texto_resposta = '" . $nova_resposta . "' WHERE id_questao = '" . $id_para_salvar . "'";
                      
    } elseif ($tipo == 'multipla') {
        // Pega os dados da múltipla escolha
        $id_para_salvar = $dados['id_questao'];
        $nova_pergunta = $dados['pergunta'];
        $opc1 = $dados['opc1'];
        $opc2 = $dados['opc2'];
        $opc3 = $dados['opc3'];
        $opc4 = $dados['opc4'];
        $correta = $dados['resposta_certa'];
        
        $query_sql = "UPDATE questoesmultiplas  SET pergunta = '" . $nova_pergunta . "', opc1 = '" . $opc1 . "', opc2 = '" . $opc2 . "', opc3 = '" . $opc3 . "', opc4 = '" . $opc4 . "', resposta_certa = '" . $correta . "' 
        WHERE id_questao = '" . $id_para_salvar . "'";
                      
    } else {
        $resposta_json['mensagem'] = 'Tipo de questão inválido no JSON.';
    }

    if ($query_sql != "") {
        if ($conexao->query($query_sql) === TRUE) {
            $resposta_json['status'] = 'sucesso';
            $resposta_json['mensagem'] = 'Questão ('. $tipo .') atualizada com sucesso!';
        } else {
            $resposta_json['mensagem'] = 'Erro ao executar o SQL: ' . $conexao->error;
        }
    }

} else {
    $resposta_json['mensagem'] = 'Nenhum dado JSON válido ou "tipo" foi recebido.';
}
$conexao->close();
echo json_encode($resposta_json);
?>