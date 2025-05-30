<?php
session_start();

// Logout handling - must be before any HTML output
if (isset($_POST["logout"])) {
    session_destroy();
    echo "<script>alert('已登出');location.href='index.php';</script>";
    exit;
}

// Redirect if not logged in
if (!isset($_SESSION["account"])) {
    echo "<script>alert('請先登入帳號');location.href='login.php';</script>";
    exit;
}

// Connect to database
$link = @mysqli_connect('127.0.0.1', 'root', '', 'learn');
if (!$link) {
    die("資料庫連線錯誤");
}

$file_path = "";
if (isset($_POST["enter"])) {
    $subject = mysqli_real_escape_string($link, $_POST["enter"]);
    $sql = "SELECT * FROM `resource` WHERE `subject` = '$subject'";
    $result = mysqli_query($link, $sql);

    if ($row = mysqli_fetch_assoc($result)) {
        $file_path = $row["data"];
        $class = $row["class"];
        $subject = $row["subject"];

        // Record user view
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
    <style>
        body { background-color: #ADD8E6; }
        header { background-color: rgba(0, 0, 0, 0.2); }
    </style>
</head>
<body>

<!-- Navbar -->
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

<!-- Content -->
<section class="py-2">
    <div class="container px-4 px-lg-5 mt-2">
        <button class="btn btn-outline-dark mb-3" onclick="window.location.href='class.php';">上一頁</button>

        <?php if (!empty($file_path) && file_exists($file_path)):
            $ext = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
            if ($ext === 'pdf'): ?>
                <embed src="<?= htmlspecialchars($file_path) ?>" width="100%" height="600px" type="application/pdf" />
            <?php else: ?>
                <p>不支持的文件類型。</p>
            <?php endif; ?>
        <?php else: ?>
            <p>檔案不存在或未選擇資料。</p>
        <?php endif; ?>
    </div>
</section>

<!-- Chatbot UI -->
<style>
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
    display: flex;
    flex-direction: column;
    box-shadow: 0 2px 10px rgba(0,0,0,0.2);
    transition: width 0.3s, height 0.3s;
}
#chatContainer.expanded {
    width: 500px;
    max-height: 600px;
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
}
#chatButtons {
    display: flex;
}
#chatButtons button {
    flex: 1;
    padding: 5px;
    border: none;
    cursor: pointer;
}
</style>

<div id="chatContainer">
    <div id="chatbox"></div>
    <div id="chatInputArea">
        <textarea id="userInput" placeholder="輸入訊息 (Enter送出, Shift+Enter換行)"></textarea>
        <div id="chatButtons">
            <button onclick="sendMessage()">送出</button>
            <button id="toggleChatbox">↕️ 展開/收合</button>
        </div>
    </div>
</div>

<script src="js/scripts.js"></script>
<script>
  // Clear chat history on page load
  window.addEventListener('load', () => {
    const chatbox = document.getElementById('chatbox');
    if (chatbox) {
      chatbox.innerHTML = '';  // Clear previous chat messages
    }
  });

  // Clear chatbox and localStorage/sessionStorage on page unload (before refresh/close)
  window.addEventListener('beforeunload', () => {
    // Clear chat UI (if needed)
    const chatbox = document.getElementById('chatbox');
    if (chatbox) chatbox.innerHTML = '';

    // Clear any saved chat history in storage
    localStorage.removeItem('chatHistory');
    sessionStorage.removeItem('chatHistory');
  });
</script>
</body>
</html>