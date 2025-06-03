<?php
session_start();

if (isset($_POST["logout"])) {
    session_destroy();
    echo "<script>alert('已登出');location.href='index.php';</script>";
    exit;
}

if (!isset($_SESSION["account"])) {
    echo "<script>alert('請先登入帳號');location.href='login.php';</script>";
    exit;
}

$link = @mysqli_connect('127.0.0.1', 'root', '', 'learn');
if (!$link) {
    die("資料庫連線錯誤");
}

$file_path = "";
$ext = "";
if (isset($_POST["enter"])) {
    $subject = mysqli_real_escape_string($link, $_POST["enter"]);
    $sql = "SELECT * FROM `resource` WHERE `subject` = '$subject'";
    $result = mysqli_query($link, $sql);

    if ($row = mysqli_fetch_assoc($result)) {
        $file_path = $row["data"];
        $class = $row["class"];
        $subject = $row["subject"];

        $account = mysqli_real_escape_string($link, $_SESSION["account"]);
        $sql2 = "SELECT * FROM `record_resource` WHERE `account` = '$account' AND `subject` = '$subject'";
        $result2 = mysqli_query($link, $sql2);

        if (mysqli_num_rows($result2) > 0) {
            $row2 = mysqli_fetch_assoc($result2);
            $times = $row2["times"] + 1;
            $sql3 = "UPDATE `record_resource` SET `times` = '$times' WHERE `account` = '$account' AND `subject` = '$subject'";
        } else {
            $sql3 = "INSERT INTO `record_resource` (`account`, `class`, `subject`, `times`) VALUES ('$account', '$class', '$subject', 1)";
        }
        mysqli_query($link, $sql3);
        $ext = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
    }
}

mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8" />
    <title>線上學習平台</title>
    <link rel="stylesheet" href="css/styles.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js"></script>
    <script>
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.worker.min.js';
    </script>
    <style>
        body { background-color: #ADD8E6; }
        header { background-color: rgba(0, 0, 0, 0.2); }

        /* Chat container styles with animation */
        #chatContainer {
            position: fixed;
            bottom: 10px;
            right: 10px;
            width: 300px;
            max-height: 400px;
            background: white;
            border: 1px solid #ccc;
            border-radius: 8px;
            overflow: hidden;
            display: none;
            flex-direction: column;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            z-index: 9999;

            /* Animation styles */
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.3s ease, transform 0.3s ease;
        }
        #chatContainer.show {
            display: flex;
            opacity: 1;
            transform: translateY(0);
        }

        #chatbox {
            flex: 1;
            overflow-y: auto;
            padding: 10px;
            background: #f9f9f9;
        }
        #chatInputArea {
            display: flex;
            flex-direction: column;
            border-top: 1px solid #ccc;
        }
        #userInput {
            width: 100%;
            height: 60px;
            resize: none;
            padding: 5px;
            border: none;
            outline: none;
            font-size: 14px;
        }
        #chatButtons {
            display: flex;
        }
        #chatButtons button {
            flex: 1;
            padding: 5px;
            border: none;
            cursor: pointer;
            font-size: 14px;
            background: #333;
            color: white;
            margin: 2px;
            border-radius: 4px;
        }

        /* Chat toggle button */
        #toggleChatIcon {
            position: fixed;
            bottom: 10px;
            right: 10px;
            width: 50px;
            height: 50px;
            font-size: 24px;
            background: #333;
            color: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            z-index: 10000;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: opacity 0.3s ease;
        }
        #toggleChatIcon.hidden {
            opacity: 0;
            pointer-events: none;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container px-4 px-lg-5">
        <a class="navbar-brand">學習系統</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="切換導航">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                <li class="nav-item"><a class="nav-link active" href="class.php">首頁</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">修改資料</a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="basic.php">基本資料</a></li>
                        <li><a class="dropdown-item" href="contact.php">聯絡資料</a></li>
                        <li><a class="dropdown-item" href="revise.php">修改密碼</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown2" href="#" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">課程</a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown2">
                        <li><a class="dropdown-item" href="favorite.php">喜好課程</a></li>
                        <li><a class="dropdown-item" href="statistics.php">次數統計</a></li>
                    </ul>
                </li>
            </ul>
            <form class="d-flex" method="post">
                <button class="btn btn-outline-dark" name="logout" type="submit" value="登出">登出</button>
            </form>
        </div>
    </div>
</nav>

<div class="container px-4 px-lg-5 mt-2">
    <button class="btn btn-outline-dark mb-3" onclick="window.location.href='class.php';">上一頁</button>

    <?php if (!empty($file_path) && file_exists($file_path) && $ext === 'pdf'): ?>
        <button id="playBtn" class="btn btn-dark mb-3">📢 朗讀 PDF</button>
        <button id="pauseBtn" class="btn btn-secondary mb-3" disabled>⏸ 暫停</button>
        <button id="stopBtn" class="btn btn-secondary mb-3" disabled>⏹ 停止</button>
    <?php endif; ?>

    <?php if (!empty($file_path) && file_exists($file_path)): ?>
        <?php if ($ext === 'pdf'): ?>
            <embed id="pdfViewer" src="<?= htmlspecialchars($file_path) ?>" width="100%" height="600px" type="application/pdf" />
            <canvas id="pdf-canvas" style="display:none;"></canvas>
        <?php else: ?>
            <p>不支持的文件類型。</p>
        <?php endif; ?>
    <?php else: ?>
        <p>檔案不存在或未選擇資料。</p>
    <?php endif; ?>
</div>

<!-- Chatbot Button + Container -->
<button id="toggleChatIcon" onclick="toggleChat()">💬</button>

<div id="chatContainer">
    <div id="chatbox"></div>
    <div id="chatInputArea">
        <textarea id="userInput" placeholder="輸入訊息 (Enter送出, Shift+Enter換行)"></textarea>
        <div id="chatButtons">
            <button onclick="sendMessage()">送出</button>
            <button onclick="toggleChat()">✖️</button>
        </div>
    </div>
</div>

<script>
let speechUtterance = null;
let fullText = '';
let isPaused = false;

async function loadPDFText(pdfPath) {
    const loadingTask = pdfjsLib.getDocument(pdfPath);
    const pdf = await loadingTask.promise;
    let text = '';
    for (let i = 1; i <= pdf.numPages; i++) {
        const page = await pdf.getPage(i);
        const content = await page.getTextContent();
        const strings = content.items.map(item => item.str).filter(s => s.trim() !== '');
        text += strings.join(' ') + '\n';
    }
    return text;
}

async function speakText(text) {
    if (!('speechSynthesis' in window)) {
        alert("您的瀏覽器不支援語音合成。請使用 Chrome 或 Edge。");
        return;
    }
    speechSynthesis.cancel();
    speechUtterance = new SpeechSynthesisUtterance(text);
    speechUtterance.lang = 'zh-TW';
    speechUtterance.rate = 1.0;
    speechUtterance.pitch = 1;
    speechUtterance.onend = () => {
        document.getElementById('pauseBtn').disabled = true;
        document.getElementById('stopBtn').disabled = true;
        document.getElementById('playBtn').disabled = false;
        isPaused = false;
        document.getElementById('pauseBtn').textContent = '⏸ 暫停';
    };
    speechSynthesis.speak(speechUtterance);
    document.getElementById('pauseBtn').disabled = false;
    document.getElementById('stopBtn').disabled = false;
    document.getElementById('playBtn').disabled = true;
}

document.addEventListener('DOMContentLoaded', () => {
    const playBtn = document.getElementById('playBtn');
    const pauseBtn = document.getElementById('pauseBtn');
    const stopBtn = document.getElementById('stopBtn');

    if (playBtn) {
        playBtn.addEventListener('click', async () => {
            if (!fullText) {
                fullText = await loadPDFText('<?= htmlspecialchars($file_path) ?>');
            }
            if (speechSynthesis.paused && isPaused) {
                speechSynthesis.resume();
                isPaused = false;
                pauseBtn.textContent = '⏸ 暫停';
                playBtn.disabled = true;
                pauseBtn.disabled = false;
                stopBtn.disabled = false;
            } else {
                speakText(fullText);
            }
        });
    }

    if (pauseBtn) {
        pauseBtn.addEventListener('click', () => {
            if (speechSynthesis.speaking && !speechSynthesis.paused) {
                speechSynthesis.pause();
                isPaused = true;
                pauseBtn.textContent = '▶️ 繼續';
            } else if (speechSynthesis.paused) {
                speechSynthesis.resume();
                isPaused = false;
                pauseBtn.textContent = '⏸ 暫停';
            }
        });
    }

    if (stopBtn) {
        stopBtn.addEventListener('click', () => {
            speechSynthesis.cancel();
            isPaused = false;
            pauseBtn.textContent = '⏸ 暫停';
            pauseBtn.disabled = true;
            stopBtn.disabled = true;
            playBtn.disabled = false;
        });
    }
});

// Chatbot toggle with hide/show toggle button and animation
function toggleChat() {
    const chatContainer = document.getElementById('chatContainer');
    const toggleBtn = document.getElementById('toggleChatIcon');
    if (chatContainer.classList.contains('show')) {
        // Hide chatbox with animation
        chatContainer.classList.remove('show');
        setTimeout(() => {
            chatContainer.style.display = 'none';
        }, 300);
        // Show toggle button
        toggleBtn.classList.remove('hidden');
    } else {
        // Show chatbox with animation
        chatContainer.style.display = 'flex';
        setTimeout(() => {
            chatContainer.classList.add('show');
        }, 10);
        // Hide toggle button
        toggleBtn.classList.add('hidden');
        document.getElementById('userInput').focus();
    }
}

// Send message placeholder
function sendMessage() {
    const input = document.getElementById('userInput');
    const message = input.value.trim();
    if (!message) return;
    const chatbox = document.getElementById('chatbox');
    const msgDiv = document.createElement('div');
    msgDiv.textContent = "你: " + message;
    chatbox.appendChild(msgDiv);
    input.value = '';
    chatbox.scrollTop = chatbox.scrollHeight;
    // Add your backend or AI API call here
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>