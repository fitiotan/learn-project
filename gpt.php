<?php
header('Content-Type: application/json');
require __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpWord\IOFactory as WordIOFactory;
use Smalot\PdfParser\Parser as PdfParser;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$apiKey = $_ENV['OPENAI_API_KEY'] ?? '';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Invalid request method']);
    exit;
}

$message = trim($_POST['message'] ?? '');
$fileText = '';

// Handle file upload & extract text
if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
    $filePath = $_FILES['file']['tmp_name'];
    $fileName = $_FILES['file']['name'];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    try {
        switch ($fileExt) {
            case 'docx':
                $phpWord = WordIOFactory::load($filePath);
                $text = '';
                foreach ($phpWord->getSections() as $section) {
                    $elements = $section->getElements();
                    foreach ($elements as $element) {
                        if (method_exists($element, 'getText')) {
                            $text .= $element->getText() . "\n";
                        }
                    }
                }
                $fileText = trim($text);
                break;

            case 'pdf':
                $parser = new PdfParser();
                $pdf = $parser->parseFile($filePath);
                $fileText = $pdf->getText();
                break;

            default:
                $fileText = ''; // Unsupported file type for now
                break;
        }
    } catch (Exception $e) {
        echo json_encode(['error' => 'Failed to parse file: ' . $e->getMessage()]);
        exit;
    }
}

// Combine user message and file text for prompt
$combinedPrompt = $message;
if ($fileText !== '') {
    if ($combinedPrompt !== '') {
        $combinedPrompt .= "\n\n附加檔案內容：\n" . $fileText;
    } else {
        $combinedPrompt = $fileText;
    }
}

if ($combinedPrompt === '') {
    if ($fileText !== '') {
        // Automatically summarize the file if there's no user message
        $combinedPrompt = "請幫我總結以下檔案內容：\n\n" . $fileText;
    } else {
        echo json_encode(['error' => '沒有輸入文字或檔案內容']);
        exit;
    }
}


// Prepare OpenAI chat request
$postData = [
    'model' => 'gpt-4o-mini', // or another model you prefer
    'messages' => [
        ['role' => 'user', 'content' => $combinedPrompt]
    ],
    'max_tokens' => 1000,
    'temperature' => 0.7,
];

$ch = curl_init('https://api.openai.com/v1/chat/completions');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer $apiKey"
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));

$response = curl_exec($ch);
if ($response === false) {
    echo json_encode(['error' => 'OpenAI API request failed: ' . curl_error($ch)]);
    curl_close($ch);
    exit;
}
curl_close($ch);

$responseData = json_decode($response, true);
if (isset($responseData['choices'][0]['message']['content'])) {
    $reply = trim($responseData['choices'][0]['message']['content']);
    echo json_encode(['reply' => $reply]);
} else {
    echo json_encode(['error' => 'OpenAI API returned unexpected response']);
}