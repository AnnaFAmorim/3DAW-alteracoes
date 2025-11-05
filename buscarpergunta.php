<?php
header('Content-Type: application/json');

$servidor = "localhost";
$usuario = "root";
$senha = "";
$banco = "av1bancoanna";

$conexao = new mysqli($servidor, $usuario, $senha, $banco);

if ($conexao->connect_error) {
    die(json_encode(['erro' => 'Erro ao conectar: ' . $conexao->connect_error]));
}

$id_para_buscar = $_GET['id'];
$tipo = $_GET['tipo'];
$query_sql = "";

if ($tipo == 'discursiva') {
    $query_sql = "SELECT * FROM QuestoesDiscursivas WHERE id_questao = '" . $id_para_buscar . "'";
                  
} elseif ($tipo == 'multipla') {
    $query_sql = "SELECT * FROM questoesmultiplas WHERE id_questao = '" . $id_para_buscar . "'";
                  
} else {
    die(json_encode(['erro' => 'Tipo de questão inválido.']));
}

$resultado = $conexao->query($query_sql);

if ($resultado && $resultado->num_rows > 0) {
    $linha_dados = $resultado->fetch_row();
    echo json_encode($linha_dados);
} else {
    echo json_encode(['erro' => 'Questão com o ID ' . $id_para_buscar . ' não foi encontrada.']);
}

$conexao->close();
?>