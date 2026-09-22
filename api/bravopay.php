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

    $payload = [
        "amount_cents" => $amount_cents,
        "method" => "pix",
        "customer" => [
            "name" => $customer_data['nome'] ?? 'Cliente Padrão',
            "email" => $customer_data['email'] ?? 'cliente@sememail.com',
            "phone" => !empty($phone) ? $phone : '5511999999999',
            "cpf" => !empty($cpf) ? $cpf : '00000000000'
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

    if ($http_code >= 200 && $http_code < 300 && isset($res_data['transaction']['pix_code'])) {
        $pix_code = $res_data['transaction']['pix_code'];
        $qr_base64 = $res_data['transaction']['pix_qr_base64'] ?? '';
        $payment_id = $res_data['transaction']['id'] ?? '';
        $ext_ref = $res_data['transaction']['external_reference'] ?? '';

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
        $msg_erro = $res_data['message'] ?? 'Erro desconhecido na API BravoPay.';
        return ['success' => false, 'error' => $msg_erro, 'response' => $response];
    }
}
?>
