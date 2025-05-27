<?php
header('Content-Type: application/json');
require __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpWord\IOFactory as WordIOFactory;
use PhpOffice\PhpPresentation\IOFactory as PPTIOFactory;
use Smalot\PdfParser\Parser as PdfParser;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$apiKey = $_ENV['OPENAI_API_KEY'] ?? '';

if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['error' => '檔案上傳失敗']);
    exit;
}

$filePath = $_FILES['file']['tmp_name'];
$fileName = $_FILES['file']['name'];
$fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

$responseData = ['file' => $fileName];

// Extract content based on file type
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
            $responseData['content'] = trim($text);
            break;

        case 'pptx':
            $ppt = PPTIOFactory::load($filePath);
            $slide = $ppt->getSlide(0);
            $shapes = $slide->getShapeCollection();
            $texts = [];
            foreach ($shapes as $shape) {
                if (method_exists($shape, 'getText')) {
                    $texts[] = $shape->getText();
                }
            }
            $responseData['content'] = implode("\n", $texts);
            break;

        case 'pdf':
            $parser = new PdfParser();
            $pdf = $parser->parseFile($filePath);
            $text = $pdf->getText();
            $responseData['content'] = $text;
            break;

        default:
            $responseData['content'] = '不支援的檔案格式，無法擷取內容';
            break;
    }
} catch (Exception $e) {
    $responseData['error'] = '檔案解析錯誤: ' . $e->getMessage();
}

// Upload file to OpenAI
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://api.openai.com/v1/files");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $apiKey"
]);

$postFields = [
    'purpose' => 'assistants',
    'file' => new CURLFile($filePath, mime_content_type($filePath), $fileName)
];

curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);

$uploadResponse = curl_exec($ch);
if ($uploadResponse === false) {
    $responseData['upload_error'] = curl_error($ch);
} else {
    $responseData['upload_result'] = json_decode($uploadResponse, true);
}

curl_close($ch);

echo json_encode($responseData);
?>