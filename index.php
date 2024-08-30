<?php

require_once __DIR__ . '/vendor/autoload.php';

// Função para formatar a data em dd/mm/aa e incluir o dia da semana
function formatarData($data) {
    $diasSemana = ['domingo', 'segunda-feira', 'terça-feira', 'quarta-feira', 'quinta-feira', 'sexta-feira', 'sábado'];
    $dataFormatada = DateTime::createFromFormat('Y-m-d', $data);
    
    if ($dataFormatada) {
        $diaSemana = $diasSemana[$dataFormatada->format('w')]; // Obtém o dia da semana (0 para domingo, 6 para sábado)
        return $dataFormatada->format('d/m/y') . " - ($diaSemana)";
    }
    
    return $data;
}

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capturando os dados do formulário (POST)
    $nome = isset($_POST['nome']) ? $_POST['nome'] : '';
    $horario = isset($_POST['horario']) ? $_POST['horario'] : '';
    $data = isset($_POST['data']) ? formatarData($_POST['data']) : '';
    $certidao = isset($_POST['certidao']) ? $_POST['certidao'] : '';

    // Criando o conteúdo HTML
    $html = "
    <!DOCTYPE html>
    <html lang='pt-BR'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Documento</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                font-size: 20px; /* Aumenta o tamanho da fonte do documento */
                margin: 20px;
            }
            h2 {
                font-size: 26px; /* Aumenta o tamanho da fonte para o nome e a data */
                font-weight: bold;
            }
            p, ul {
                font-size: 22px; /* Aumenta o tamanho da fonte dos parágrafos e listas */
            }
            strong {
                font-weight: bold;
            }
            .nome-data {
                margin-bottom: 8px; /* Ajusta o espaçamento de linha */
            }
        </style>
    </head>
    <body>
        
        <h2 class='nome-data'>$nome</h2>
        <h2 class='nome-data'>$horario</h2>
        <h2 class='nome-data'>$data</h2>

        <br>

        <p><strong>OBRIGATÓRIOS:</strong></p>
        <ul>
            <li><strong>Certidão de $certidao original e não plastificada.</strong></li>
            <li><strong>CPF</strong></li>
        </ul>

        <p><strong>OPCIONAIS:</strong></p>
        <ul>
            <li><strong>Título de Eleitor</strong></li>
            <li><strong>Numeração da Carteira de Trabalho e Previdência Social</strong></li>
            <li><strong>Carteira Nacional de Habilitação</strong></li>
            <li><strong>NIS/PIS/Pasep</strong></li>
            <li><strong>Certificado militar</strong></li>
            <li><strong>Documento de identidade profissional</strong></li>
            <li><strong>Carteira nacional de saúde</strong></li>
            <li><strong>Tipo Sanguíneo (Exame com CRM do Médico Carimbado)</strong></li>
        </ul>

    </body>
    </html>
    ";

    // Criando o PDF com o MPDF
    $mpdf = new \Mpdf\Mpdf();
    $mpdf->WriteHTML($html);
    $mpdf->Output();

} else {
    // Exibe o formulário se o método não for POST
    echo '
    <!DOCTYPE html>
    <html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Preencher Documento</title>
        <style>
            body, html {
                height: 100%;
                margin: 0;
                display: flex;
                justify-content: center;
                align-items: center;
                font-family: Arial, sans-serif;
            }
            .form-container {
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100%;
            }
            form {
                background-color: #f7f7f7;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            }
            label, input, select {
                display: block;
                margin-bottom: 10px;
            }
            input[type="text"],
            input[type="date"],
            input[type="time"],
            select {
                width: 100%;
                padding: 10px;
                margin-bottom: 20px;
                border: 1px solid #ccc;
                border-radius: 4px;
                font-size: 16px;
            }
            input[type="submit"] {
                background-color: #4CAF50;
                color: white;
                padding: 10px 20px;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                font-size: 16px;
            }
            input[type="submit"]:hover {
                background-color: #45a049;
            }
        </style>
    </head>
    <body>
        <div class="form-container">
            <form action="" method="POST">
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" required>

                <label for="horario">Horário:</label>
                <input type="time" id="horario" name="horario" required>

                <label for="data">Data:</label>
                <input type="date" id="data" name="data" required>

                <label for="certidao">Certidão de:</label>
                <select id="certidao" name="certidao">
                    <option value="nascimento">Nascimento</option>
                    <option value="casamento">Casamento</option>
                </select>

                <input type="submit" value="Gerar PDF">
            </form>
        </div>
    </body>
    </html>
    ';
}
?>