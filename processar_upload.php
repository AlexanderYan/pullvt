<?php
header('Content-Type: text/html; charset=utf-8');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["arquivo_csv"])) {
    $csvFile = $_FILES["arquivo_csv"]["tmp_name"];
    if (is_uploaded_file($csvFile)) {
        $csvData = file_get_contents($csvFile);
        $csvDataUtf8 = iconv("ISO-8859-1", "UTF-8", $csvData); // Converte para UTF-8

        $mapeamento_colunas = [
            'Contrato' => 14,
            'Nome' => 15,
            'VT' => 16,
            'Data' => 4,
            'VAZIO1' => 17,
            'Status' => 10,
            'Cidade' => 3,
            'Bairro' => 0,
            'VAZIO2' => 11,
            'VAZIO3?' => 12,
            'VAZIO4' => 13,
            'SERVIÇO' => 7,
            'ANALISADA' => 19,
            'vazio5' => 18,
            'vazio6' => 2,
            'Empresa' => 1,
            'Vazio7' => 5,
            'Statusreal' => 20,
        ];

        $linhas = explode("\n", $csvDataUtf8);

        echo '<table>' . PHP_EOL;

        $primeira_linha = true; // Adicione essa linha no início do código

foreach ($linhas as $linha) {
    $dados = str_getcsv(trim($linha), ';');

    if (count($dados) >= 32) {
        if ($primeira_linha) {
            // Crie uma linha de cabeçalho com base no mapeamento de colunas
            echo '<thead><tr>';
            foreach (array_keys($mapeamento_colunas) as $titulo_coluna) {
                echo '<th>' . htmlspecialchars($titulo_coluna, ENT_QUOTES, 'UTF-8') . '</th>';
            }
            echo '</tr></thead>';
            $primeira_linha = false;
        } else {
            $dados[17] = '';
            $dados[10] = '=SEERRO(PROCV(INDIRETO("c"&LIN());AtualizacaoVT!S:V;4;0);"NÃO ENCONTRADO")';
            $dados[11] = '=SE(OU(INDIRETO("K"&LIN())="NAC | Marcelo");"CONCLUÍDA";"")';
            $dados[12] = '=SE(OU(INDIRETO("K"&LIN())="NAC | Marcelo");"CONCLUÍDA";"")';
            $dados[13] = '';
            $dados[18] = '=SE(OU(INDIRETO("K"&LIN())="NAC | Marcelo");"S";"")';
            $dados[2] = '';
            $dados[19] = '=SE(OU(INDIRETO("F"&LIN())="Executada");"S";SE(OU(INDIRETO("F"&LIN())="Sem Execução");"S";SE(OU(INDIRETO("j"&LIN())<>"");"S";SE(OU(INDIRETO("A"&LIN())<>"");"N";""))))';
            $dados[5] = '';

            echo '<tr>';
            foreach ($mapeamento_colunas as $indice) {
                $valor = $dados[$indice];
                echo '<td>' . htmlspecialchars($valor, ENT_QUOTES, 'UTF-8') . '</td>';
            }
            echo '</tr>' . PHP_EOL;
        }
            }
        }

        echo '</table>' . PHP_EOL;
    } else {
        echo "Erro ao carregar o arquivo CSV.";
    }
} else {
    echo "Por favor, selecione um arquivo CSV para enviar.";
}
?>
