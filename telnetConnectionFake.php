<?php

// Define as informações do servidor
$host = '127.0.0.1';
$port = 12000; // Porta escolhida

// Cria o servidor de soquete TCP/IP
$socket = stream_socket_server("tcp://$host:$port", $errno, $errstr);

if (!$socket) {
    echo "Erro ao criar o servidor: $errstr ($errno)";
    exit;
}

echo "Servidor Telnet fake em execução em $host:$port\n";

// Loop infinito para aceitar conexões
while ($client = stream_socket_accept($socket, -1)) {
    echo "Nova conexão estabelecida\n";

    // Lê os dados recebidos do cliente
    $data = fread($client, 1024);

    // Processa o comando recebido e envia uma resposta de exemplo
    switch (trim($data)) {
    case 'get_rainfall_intensity':
        $response = "A intensidade da chuva é 10 mm/h";
        break;
    default:
        $response = "Comando não reconhecido";
        break;
    }

    // Envia a resposta para o cliente
    fwrite($client, $response . "\r\n");

    // Fecha a conexão com o cliente
    fclose($client);
}

// Fecha o servidor
fclose($socket);
