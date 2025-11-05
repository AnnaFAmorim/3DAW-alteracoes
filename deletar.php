<?php
header('Content-Type: application/json');

$servidor = "localhost";
$usuario = "root";
$senha = "";
$banco = "av1bancoanna";

$conexao = new mysqli($servidor, $usuario, $senha, $banco);

$resposta_json = [
    'status' => 'erro',
    'mensagem' => 'Ocorreu um erro ao excluir.'
];

if ($conexao->connect_error) {
    $resposta_json['mensagem'] = 'Erro ao conectar: ' . $conexao->connect_error;
    echo json_encode($resposta_json);
    die();
}

$json_recebido = file_get_contents('php://input');
$dados = json_decode($json_recebido, true);

if ($dados && isset($dados['id']) && isset($dados['tipo'])) {
    
    $id_para_excluir = $dados['id'];
    $tipo = $dados['tipo'];
    $sql = "";

    if ($tipo == 'discursiva') {
        $sql = "DELETE FROM QuestoesDiscursivas WHERE id_questao = '" . $id_para_excluir . "'";
        
    } elseif ($tipo == 'multipla') {
        $sql = "DELETE FROM QuestoesMultiplas WHERE id_questao = '" . $id_para_excluir . "'";
        
    } else {
        $resposta_json['mensagem'] = 'Tipo de questão inválido.';
    }

    if ($sql != "") {
        if ($conexao->query($sql) === TRUE) {
            if ($conexao->affected_rows > 0) {
                $resposta_json['status'] = 'sucesso';
                $resposta_json['mensagem'] = 'Questão (' . $tipo . ') excluída com sucesso!';
            } else {
                $resposta_json['mensagem'] = 'Nenhuma questão encontrada com o ID fornecido.';
            }
        } else {
            $resposta_json['mensagem'] = 'Erro ao executar a exclusão: ' . $conexao->error;
        }
    }

} else {
    $resposta_json['mensagem'] = 'JSON inválido ou dados incompletos (ID ou Tipo) não recebidos.';
}

$conexao->close();
echo json_encode($resposta_json);
?>