<?php
/*** Página de tratamento do arquivo recebido via input ***/

// Função para checar extensão de arquivo
function checarArquivo($nome)
{
    $extencao = strtolower(pathinfo($nome, PATHINFO_EXTENSION));

    if (!strstr('.xml', $extencao)) {
        return FALSE;
    }else{
        return TRUE;
    }
}

// Função para retornar o caminho e o valor de uma tag. 
// Recebe 2 parâmetros: um array de linhas e o caminho atual de diretórios
function retornaValores($tags, $caminhoAtual)
{
    $caminho = $caminhoAtual;
    $i = 0;

    // Para cada linha extraida no 'explode'
    foreach ($tags as $tag) {
        //verifica se é valor, caminho ou fechamento de tag
        if (strpos($tag, '<') !== false && strpos($tag, '>') !== false) { //se contém '>' e '<' é valor
            //retirando '<' e '>' para ficar apenas o valor
            $valor = substr($tag, strpos($tag, ">") + 1, (strpos($tag, "<") - strpos($tag, ">")) - 1);
            //pegando o nome da tag correspondente ao valor
            $nomeTag = substr($tag, 0, strpos($tag, ">"));
            //remove tag da lista
            unset($tags[$i]);
            break;
        } elseif ($tag[0] == '/') {  // se começa com '/' é fechamento de tag
            $valor = 'false';
            $nomeTag = 'false';
            //retorna um diretório no caminho
            $caminho = substr($caminho, 0, strripos($caminho, "/"));
            //remove tag da lista
            unset($tags[$i]);
            break;
        } else { // caminho
            //verifica se existem outros campos na tag
            if ($posicaoFinal = strpos($tag, " ")) {
                $caminho .= '/' . substr($tag, 0, $posicaoFinal);
            } else {
                $caminho .= '/' . $tag;
            }
            //remove tag da lista
            unset($tags[$i]);
        }
        $i++;
    }

    //reordena a lista de tags no array
    $tags = array_values($tags);

    // retorna valor, caminho, nome da Tag e o novo array de tags
    return $array = [
        'valor' => $valor,
        'caminho' => $caminho,
        'nomeTag' => $nomeTag,
        'novaTags' => $tags
    ];
}

/*** Início ***/
// Abrir arquivo para leitura
$arquivo = fopen($_FILES['arquivo']['tmp_name'], "r");

// Checar se extensão é .xml
if (checarArquivo($_FILES['arquivo']['name']) == FALSE) {
    echo json_encode(
        array(
            'mensagem' => 'Formato de arquivo inválido'
        )
    );
    exit();
}

// Colocar em uma variável
while (!feof($arquivo)) {
    $arquivoLinha = fgets($arquivo);
}
// Fechar arquivo
fclose($arquivo);

// Criar array de retorno
$retornoTags = array();

// Criar um array separando as linhas quando encontrar '><'
$tags = explode("><", $arquivoLinha);
// Retirar primeira linha (?xml)
unset($tags[0]);
// Reordenar array
$tags = array_values($tags);

// variável resposta recebe o retorno da função
$resposta = retornaValores($tags, '');
$array = [
    'caminho' => $resposta['caminho'] . '/' . $resposta['nomeTag'],
    'valor' => $resposta['valor']
];

// adiciona primeira resposta ao array retornoTags
$retornoTags[] = $array;

// Loop para retornar a resposta das outras linhas
for ($i = 0; $i <= count($tags); $i++) {
    $resposta = retornaValores($resposta['novaTags'], $resposta['caminho']);
    if ($resposta['valor'] != 'false' && $resposta['valor'] != '') {
        $arr = [
            'caminho' => $resposta['caminho'] . '/' . $resposta['nomeTag'],
            'valor' => $resposta['valor']
        ];
        // adiciona resposta ao array retornoTags
        $retornoTags[] = $arr;
    }
}

// retorno ao arquivo custom.js
echo json_encode($retornoTags);
exit();
