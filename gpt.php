<?php
header('Content-Type: application/json');

require __DIR__ . '/vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Get user input
$input = json_decode(file_get_contents("php://input"), true);
$userMessage = $input["message"] ?? "";

// Validate input
if (!$userMessage) {
    echo json_encode(["reply" => "請輸入訊息"]);
    exit;
}

// Get API key
$apiKey = $_ENV['OPENAI_API_KEY'] ?? '';
if (!$apiKey) {
    echo json_encode(["reply" => "API金鑰缺失，請稍後再試。"]);
    exit;
}

// Set up API call
$url = "https://api.openai.com/v1/chat/completions";

$data = [
    "model" => "gpt-4.1-nano",
    "messages" => [
        ["role" => "system", "content" => "你是一個幫助學生學習的 AI 助理。"],
        ["role" => "user", "content" => $userMessage]
    ]
];

$headers = [
    "Content-Type: application/json",
    "Authorization: Bearer $apiKey"
];

// Make request
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$response = curl_exec($ch);

if ($response === false) {
    echo json_encode(["reply" => "API請求失敗：" . curl_error($ch)]);
    curl_close($ch);
    exit;
}

curl_close($ch);

// Parse response
$responseData = json_decode($response, true);
$reply = $responseData["choices"][0]["message"]["content"] ?? "無法取得回覆，請稍後再試。";

// Return JSON
echo json_encode(["reply" => $reply]);
?>