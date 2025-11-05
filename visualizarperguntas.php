<?php
$servidor = "localhost";
$usuario = "root";
$senha = "";
$banco = "av1bancoanna";
$conexao = new mysqli($servidor, $usuario, $senha, $banco);

if ($conexao->connect_error) {
    $mensagem_erro = "Falha grave na conexão com o banco";
    $lista_discursivas = [];
    $lista_multipla = [];
} else {
    $sql_discursivas = "SELECT id_questao, texto_questao, texto_resposta FROM questoesdiscursivas";
    $retorno_dis = $conexao->query($sql_discursivas);

    if ($retorno_dis) { 
        $lista_discursivas = $retorno_dis->fetch_all(MYSQLI_ASSOC);
    } else {
        $mensagem_erro = "Erro ao buscar questões discursivas: " . $conexao->error;
        $lista_discursivas = [];
    }

    $sql_multipla = "SELECT id_questao, pergunta, opc1, opc2, opc3, opc4, resposta_certa FROM questoesmultiplas";
    $retorno_mult = $conexao->query($sql_multipla);

    if ($retorno_mult) { 
        $lista_multipla = $retorno_mult->fetch_all(MYSQLI_ASSOC);
    } else {
        $mensagem_erro = "Erro ao buscar questões múltiplas: " . $conexao->error;
        $lista_multipla = [];
    }

    $conexao->close();
}

$discursivas = json_encode($lista_discursivas);
$multipla = json_encode($lista_multipla);

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Listagem de Questões</title>
</head>
<body>
    <h3>Perguntas Discursivas</h3>
    <div id="discursivas"></div>

    <h3>Perguntas Múltipla Escolha</h3>
    <div id="multipla"></div>

    <script>
    window.onload = function() {

        const perguntasDis = <?php echo $discursivas; ?>;
        const perguntasMult = <?php echo $multipla; ?>;

        const containerTexto = document.getElementById("discursivas");
        const containerMultipla = document.getElementById("multipla");

        perguntasDis.forEach((pergunta) => {
            const linha = `
            <div>
                <h4>(ID: ${pergunta.id_questao}) ${pergunta.texto_questao}</h4>
                <p><strong>Resposta:</strong> ${pergunta.texto_resposta}</p>
                <div>
                </div>
            </div>
            <p>--------</p>
            `;
            containerTexto.innerHTML += linha;
        });


        perguntasMult.forEach((pergunta) => {
            const linha = `
            <div>
                <h4>(ID: ${pergunta.id_questao}) ${pergunta.pergunta}</h4>
                <ul>
                    <li><strong>A:</strong> ${pergunta.opc1}</li>
                    <li><strong>B:</strong> ${pergunta.opc2}</li>
                    <li><strong>C:</strong> ${pergunta.opc3}</li>
                    <li><strong>D:</strong> ${pergunta.opc4}</li>
                </ul>
                <p><strong>Gabarito:</strong> ${pergunta.resposta_certa}</p>
                <div>
                </div>
            </div>
            <p>--------</p>
            `;
            containerMultipla.innerHTML += linha;
        });
        
        <?php if (isset($mensagem_erro)): ?>
            alert("<?php echo $mensagem_erro; ?>");
        <?php endif; ?>
    }
    </script>
</body>
</html>