<?php
/**
 * Integração BravoPay
 * Documentação: https://bravopay.club/docs
 */
require_once(__DIR__ . "/db.php");

function getBravoPayConfig() {
    global $conn;
    $sql = mysqli_query($conn, "SELECT use_bravopay, bravopay_api_key, bravopay_webhook_secret FROM pix WHERE id='1' LIMIT 1");
    if ($sql && $cfg = mysqli_fetch_assoc($sql)) {
        return [
            'active' => (int)($cfg['use_bravopay'] ?? 0) === 1,
            'api_key' => trim($cfg['bravopay_api_key'] ?? ''),
            'webhook_secret' => trim($cfg['bravopay_webhook_secret'] ?? '')
        ];
    }
    return ['active' => false, 'api_key' => '', 'webhook_secret' => ''];
}

function gerarCpfFicticio() {
    $n = [];
    for ($i = 0; $i < 9; $i++) $n[$i] = rand(0, 9);
    $d1 = $n[8]*2 + $n[7]*3 + $n[6]*4 + $n[5]*5 + $n[4]*6 + $n[3]*7 + $n[2]*8 + $n[1]*9 + $n[0]*10;
    $d1 = 11 - ($d1 % 11);
    if ($d1 >= 10) $d1 = 0;
    $d2 = $d1*2 + $n[8]*3 + $n[7]*4 + $n[6]*5 + $n[5]*6 + $n[4]*7 + $n[3]*8 + $n[2]*9 + $n[1]*10 + $n[0]*11;
    $d2 = 11 - ($d2 % 11);
    if ($d2 >= 10) $d2 = 0;
    return implode('', $n) . $d1 . $d2;
}

function createBravoPayPix($valor, $customer_data, $product_data) {
    global $conn;
    
    $config = getBravoPayConfig();
    if (!$config['active'] || empty($config['api_key'])) {
        // Fallback constante caso não esteja no DB
        if (defined('BRAVOPAY_API_KEY_CONST') && !empty(BRAVOPAY_API_KEY_CONST)) {
            $config['api_key'] = BRAVOPAY_API_KEY_CONST;
        } else {
            return ['success' => false, 'error' => 'BravoPay não está ativado ou sem API Key configurada.'];
        }
    }

    $apiKey = $config['api_key'];
    
    // Normalizar o valor
    $amount_cents = (int) round((float)$valor * 100);
    if ($amount_cents <= 0) {
        return ['success' => false, 'error' => 'Valor inválido.'];
    }

    $cpf = preg_replace('/\D/', '', $customer_data['cpf'] ?? '');
    $phone = preg_replace('/\D/', '', $customer_data['telefone'] ?? '');
    
    // Auto-preenchimento
    if (empty($cpf) || strlen($cpf) != 11) {
        $cpf = gerarCpfFicticio();
    }
    
    $email = trim($customer_data['email'] ?? '');
    if (empty($email) || strpos($email, '@') === false) {
        $rand_str = substr(md5(uniqid(rand(), true)), 0, 8);
        $email = "cliente_{$rand_str}@comprador.com.br";
    }

    $nome = trim($customer_data['nome'] ?? '');
    if (empty($nome)) {
        $nome = 'Cliente ' . substr(md5(uniqid()), 0, 5);
    }

    $payload = [
        "amount_cents" => $amount_cents,
        "method" => "pix",
        "customer" => [
            "name" => $nome,
            "email" => $email,
            "phone" => !empty($phone) ? $phone : '5511999999999',
            "cpf" => $cpf
        ]
    ];

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => "https://bravopay.club/api/v1/transactions",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer " . $apiKey,
            "Content-Type: application/json",
            "Accept: application/json"
        ],
    ]);

    $response = curl_exec($ch);
    $err = curl_error($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($err) {
        return ['success' => false, 'error' => "cURL Error #:" . $err];
    }

    $res_data = json_decode($response, true);
    
    // Log para debug
    file_put_contents(__DIR__ . '/debug_bravopay.log', date('Y-m-d H:i:s') . " - HTTP: $http_code - Response: $response\n", FILE_APPEND);

    if ($http_code >= 200 && $http_code < 300 && isset($res_data['pix']['copy_paste'])) {
        $pix_code = $res_data['pix']['copy_paste'];
        $qr_base64 = $res_data['pix']['qr_base64'] ?? ''; // Verifica se retornam base64, se não, deixamos vazio pois o success.php gera via QRServer
        $payment_id = $res_data['id'] ?? '';
        $ext_ref = $res_data['external_reference'] ?? '';

        // Salvar na tabela pixgerado
        $ip_atual = get_real_ip();
        $hora = date('H:i:s');
        $tempo = time();
        $prod_codigo = addslashes($product_data['codigo'] ?? '');
        $prod_nome = addslashes($product_data['nome'] ?? '');
        $cli_nome = addslashes($customer_data['nome'] ?? '');
        $cli_email = addslashes($customer_data['email'] ?? '');
        $cli_cpf = addslashes($customer_data['cpf'] ?? '');
        $cli_phone = addslashes($customer_data['telefone'] ?? '');
        $variacoes = addslashes($_SESSION['cliente_dados']['variacoes'] ?? '');

        // Previne XSS/SQLI no retorno do pixcode se gravado
        $pix_code_safe = addslashes($pix_code);
        $qr_base64_safe = addslashes($qr_base64);

        mysqli_query($conn, "INSERT INTO pixgerado 
            (ip, valor, produto, produto_nome, cliente_nome, cliente_telefone, cliente_cpf, cliente_email, status, hora, time, variacoes, pix_code, pix_qr_base64, bravopay_payment_id, bravopay_status, bravopay_external_ref) 
            VALUES 
            ('$ip_atual', '$valor', '$prod_codigo', '$prod_nome', '$cli_nome', '$cli_phone', '$cli_cpf', '$cli_email', 'pendente', '$hora', '$tempo', '$variacoes', '$pix_code_safe', '$qr_base64_safe', '$payment_id', 'PENDING', '$ext_ref')");

        return [
            'success' => true,
            'pix_code' => $pix_code,
            'pix_qr_base64' => $qr_base64,
            'payment_id' => $payment_id
        ];
    } else {
        $raw_response_safe = strip_tags(substr($response, 0, 150));
        
        $msg_erro = 'Erro na API BravoPay.';
        if (isset($res_data['error']['message'])) {
            $msg_erro = $res_data['error']['message'];
        } elseif (isset($res_data['message'])) {
            $msg_erro = $res_data['message'];
        } else {
            $msg_erro = "HTTP $http_code. Retorno: " . $raw_response_safe;
        }

        return ['success' => false, 'error' => $msg_erro, 'response' => $response];
    }
}
?>
