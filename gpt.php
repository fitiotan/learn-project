<?php
// chat_api.php
header('Content-Type: application/json');

// Load input
$input = json_decode(file_get_contents("php://input"), true);
$userMessage = $input["message"] ?? "";

if (!$userMessage) {
    echo json_encode(["reply" => "請輸入訊息"]);
    exit;
}

$apiKey = "this is your api key";
$url = "https://api.openai.com/v1/chat/completions";

$data = [
    "model" => "gpt-4.1-mini",
    "messages" => [
        ["role" => "system", "content" => "你是一個幫助學生學習的 AI 助理。"],
        ["role" => "user", "content" => $userMessage]
    ]
];

$options = [
    "http" => [
        "method"  => "POST",
        "header"  => "Content-type: application/json\r\nAuthorization: Bearer $apiKey\r\n",
        "content" => json_encode($data)
    ]
];

$context = stream_context_create($options);
$result = file_get_contents($url, false, $context);
$response = json_decode($result, true);

$reply = $response["choices"][0]["message"]["content"] ?? "無法取得回覆，請稍後再試。";
echo json_encode(["reply" => $reply]);
