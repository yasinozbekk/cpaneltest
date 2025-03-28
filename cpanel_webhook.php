<?php
// Sadece GitHub'ın çağırdığına emin ol
$secret = "112313213213243515431534153"; // Webhook oluştururken belirlediğin secret key
$signature = $_SERVER['HTTP_X_HUB_SIGNATURE_256'] ?? '';
$payload = file_get_contents("php://input");

// Güvenlik doğrulaması
$hash = "sha256=" . hash_hmac("sha256", $payload, $secret);
if (!hash_equals($hash, $signature)) {
    http_response_code(403);
    exit("Erişim reddedildi.");
}

// Deploy komutu
exec("cd /home/bakcupcal/public_html && git pull origin main", $output);

// Log tutmak için (isteğe bağlı)
file_put_contents("webhook.log", implode("\n", $output) . "\n", FILE_APPEND);

echo "Deploy işlemi tamamlandı.";
?>
