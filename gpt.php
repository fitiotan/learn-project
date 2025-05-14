<?php
// gpt.api
header('Content-Type: application/json');

// Load Composer autoload and .env
require __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Load input
$input = json_decode(file_get_contents("php://input"), true);
$userMessage = $input["message"] ?? "";

// Check if the user has provided a message
if (!$userMessage) {
    echo json_encode(["reply" => "請輸入訊息"]);
    exit;
}

// Use $_ENV (not getenv) to access values loaded via Dotenv
$apiKey = $_ENV['OPENAI_API_KEY'] ?? '';
if (!$apiKey) {
    echo json_encode(value: ["reply" => "API金鑰缺失，請稍後再試。"]);
    exit;
}

$url = "https://api.openai.com/v1/chat/completions";

$data = [
    "model" => "gpt-4.1-nano", // safer fallback; adjust as needed
    "messages" => [
        ["role" => "system", "content" => "你是一個幫助學生學習的 AI 助理。"],
        ["role" => "user", "content" => $userMessage]
    ]
];

$headers = [
    "Content-Type: application/json",
    "Authorization: Bearer $apiKey"
];

// Initialize cURL
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

// Execute cURL request
$response = curl_exec($ch);

// Check for cURL errors
if ($response === false) {
    echo json_encode(["reply" => "API請求失敗：" . curl_error($ch)]);
    curl_close($ch);
    exit;
}

curl_close($ch);

// Parse the API response
$responseData = json_decode($response, true);
$reply = $responseData["choices"][0]["message"]["content"] ?? "無法取得回覆，請稍後再試。";

// Return the reply as a JSON object
echo json_encode(["reply" => $reply]);
?>