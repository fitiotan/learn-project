<?php
session_start();

// Database connection
$link = @mysqli_connect('127.0.0.1', 'root', '', 'learn');
if (!$link) die("資料庫連線錯誤");

// Redirect if not logged in
if (!isset($_SESSION["account"])) {
    echo "<script>alert('請先登入帳號');location.href='login.php';</script>";
    exit;
}

// Retrieve resource file path if requested
$file_path = "";
if (isset($_POST["enter"])) {
    $subject = mysqli_real_escape_string($link, $_POST["enter"]);
    $sql = "SELECT * FROM `resource` WHERE `subject` = '$subject'";
    $result = mysqli_query($link, $sql);
    if ($row = mysqli_fetch_assoc($result)) {
        $file_path = $row["data"];
        $class = $row["class"];
        $subject = $row["subject"];

        // Record viewing
        $account = mysqli_real_escape_string($link, $_SESSION["account"]);
        $sql2 = "SELECT * FROM `record_resource` WHERE `account` = '$account' AND `subject` = '$subject'";
        $result2 = mysqli_query($link, $sql2);

        $sql3 = mysqli_num_rows($result2) > 0 ?
            "UPDATE `record_resource` SET `times` = `times` + 1 WHERE `account` = '$account' AND `subject` = '$subject'" :
            "INSERT INTO `record_resource` (`account`, `class`, `subject`, `times`) VALUES ('$account', '$class', '$subject', 1)";

        mysqli_query($link, $sql3);
    }
}

mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8" />
    <title>線上學習平台</title>
    <style>
        body { background-color: #ADD8E6; margin: 0; font-family: Arial, sans-serif; }
        #contentArea { padding: 20px; max-width: 900px; margin: 0 auto; }
        button.back-btn {
            margin-bottom: 20px;
            padding: 10px 20px;
            cursor: pointer;
        }

        /* Chat styles */
        #chatToggleBtn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 60px;
            height: 60px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 50%;
            font-size: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 999;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            cursor: pointer;
        }

        #chatContainer {
            position: fixed;
            bottom: 90px;
            right: 20px;
            width: 320px;
            max-height: 500px;
            background: white;
            border: 1px solid #ccc;
            border-radius: 10px;
            display: none;
            flex-direction: column;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
            z-index: 999;
        }
        #chatContainer.active {
            display: flex;
        }
        #chatbox {
            flex: 1;
            overflow-y: auto;
            padding: 10px;
            background: #f9f9f9;
            border-bottom: 1px solid #ccc;
            white-space: pre-wrap;
        }
        #chatInputArea {
            display: flex;
            flex-direction: column;
            padding: 10px;
        }
        #userInput {
            width: 100%;
            height: 60px;
            padding: 5px;
            resize: none;
            border: 1px solid #ccc;
            margin-bottom: 5px;
        }
        #fileUpload {
            margin-bottom: 5px;
        }
        #chatButtons {
            display: flex;
            gap: 5px;
        }
        #chatButtons button {
            flex: 1;
            padding: 8px;
            cursor: pointer;
        }
    </style>
</head>
<body>

<div id="contentArea">
    <button class="back-btn" onclick="window.location.href='class.php';">上一頁</button>

    <?php if (!empty($file_path) && file_exists($file_path)): ?>
        <?php if (strtolower(pathinfo($file_path, PATHINFO_EXTENSION)) === 'pdf'): ?>
            <embed src="<?= htmlspecialchars($file_path) ?>" width="100%" height="600px" type="application/pdf" />
        <?php else: ?>
            <p>不支持的文件類型。</p>
        <?php endif; ?>
    <?php else: ?>
        <p>檔案不存在或未選擇資料。</p>
    <?php endif; ?>
</div>

<!-- Floating Chat Toggle Button -->
<button id="chatToggleBtn" title="開啟聊天">💬</button>

<!-- Floating Chat Panel -->
<div id="chatContainer" role="region" aria-label="聊天視窗">
    <div id="chatbox" aria-live="polite" aria-relevant="additions"></div>
    <div id="chatInputArea">
        <textarea id="userInput" placeholder="輸入訊息 (Enter送出, Shift+Enter換行)" aria-label="聊天輸入"></textarea>
        <input type="file" id="fileUpload" aria-label="上傳檔案" />
        <div id="chatButtons">
            <button type="button" onclick="sendMessage()">送出</button>
            <button type="button" onclick="toggleChat()">✖</button>
        </div>
    </div>
</div>

<script>
const chatbox = document.getElementById("chatbox");
const userInput = document.getElementById("userInput");
const chatContainer = document.getElementById("chatContainer");
const chatToggleBtn = document.getElementById("chatToggleBtn");
const fileUpload = document.getElementById("fileUpload");

// Toggle chat visibility
chatToggleBtn.addEventListener("click", () => {
    chatContainer.classList.toggle("active");
});

// Close chat with button
function toggleChat() {
    chatContainer.classList.toggle("active");
}

// Send message on Enter (not Shift+Enter)
userInput.addEventListener("keydown", function (e) {
    if (e.key === "Enter" && !e.shiftKey) {
        e.preventDefault();
        sendMessage();
    }
});

// Append messages to chatbox
function appendMessage(sender, text) {
    const msg = document.createElement("div");
    msg.textContent = `${sender}: ${text}`;
    msg.style.marginBottom = "5px";
    msg.style.whiteSpace = "pre-wrap";
    chatbox.appendChild(msg);
    chatbox.scrollTop = chatbox.scrollHeight;
}

// Send message or file to server
function sendMessage() {
    const message = userInput.value.trim();
    const file = fileUpload.files[0];

    if (!message && !file) return;

    appendMessage("你", message || (file ? `[檔案] ${file.name}` : ""));
    userInput.value = "";
    fileUpload.value = "";

    const formData = new FormData();
    formData.append("message", message);
    if (file) formData.append("file", file);

    fetch("gpt.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        appendMessage("AI", data.reply || "發生錯誤，請稍後再試。");
    })
    .catch(() => appendMessage("AI", "無法連線到伺服器。"));
}
</script>

</body>
</html>