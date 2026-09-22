<?php
/**
 * Webhook para BravoPay
 * Recebe notificações de status (ex: "transaction.paid") via POST com JSON e HMAC SHA256.
 */
require_once(__DIR__ . "/db.php");

// Configurações do Banco para BravoPay
$bravopay_webhook_secret = '';
$sql = mysqli_query($conn, "SELECT bravopay_webhook_secret FROM pix WHERE id='1' LIMIT 1");
if ($sql && $cfg = mysqli_fetch_assoc($sql)) {
    $bravopay_webhook_secret = trim($cfg['bravopay_webhook_secret'] ?? '');
}

// Ler Payload e Headers
$payload = file_get_contents('php://input');
$signature_header = $_SERVER['HTTP_X_BRAVOPAY_SIGNATURE'] ?? '';

// Validação HMAC se o secret estiver configurado
if (!empty($bravopay_webhook_secret)) {
    if (empty($signature_header)) {
        http_response_code(401);
        echo json_encode(['error' => 'Missing signature header']);
        exit;
    }
    
    $computed_signature = hash_hmac('sha256', $payload, $bravopay_webhook_secret);
    if (!hash_equals($computed_signature, $signature_header)) {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid signature']);
        exit;
    }
}

// Decode JSON Payload
$data = json_decode($payload, true);
if (!$data || !isset($data['event']) || !isset($data['data']['transaction'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid payload format']);
    exit;
}

$event = $data['event'] ?? '';
$payment_id = '';
if (isset($data['data']['transaction']['id'])) {
    $payment_id = $data['data']['transaction']['id'];
} elseif (isset($data['data']['id'])) {
    $payment_id = $data['data']['id'];
} elseif (isset($data['transaction']['id'])) {
    $payment_id = $data['transaction']['id'];
} elseif (isset($data['id'])) {
    $payment_id = $data['id'];
}

$payment_id = addslashes((string)$payment_id);

if (empty($payment_id)) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing transaction ID']);
    exit;
}

if ($event === 'transaction.paid') {
    $q_check = mysqli_query($conn, "SELECT id FROM pixgerado WHERE bravopay_payment_id='$payment_id' LIMIT 1");
    if ($q_check && mysqli_num_rows($q_check) > 0) {
        $update = mysqli_query($conn, "UPDATE pixgerado SET bravopay_status='PAID', status='pago' WHERE bravopay_payment_id='$payment_id'");
        if ($update) {
            http_response_code(200);
            echo json_encode(['success' => true, 'message' => 'Status atualizado']);
            exit;
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'DB update failed']);
            exit;
        }
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Transaction not found in database']);
        exit;
    }
}

// Responde 200 OK para outros eventos para o BravoPay parar de tentar reenviar
http_response_code(200);
echo json_encode(['success' => true, 'message' => 'Event ignored']);
exit;
?>
