<?php
session_start();
?>
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>聯絡資料修改</title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Bootstrap icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <!-- Core theme CSS -->
    <link href="css/styles.css" rel="stylesheet" />
    <!-- Custom styles -->
    <style>
        body {
            background-color: #ADD8E6;
        }
        header {
            background-color: rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container px-4 px-lg-5">
            <a class="navbar-brand">學習系統</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                    <li class="nav-item"><a class="nav-link active" href="class.php">首頁</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" data-bs-toggle="dropdown">修改資料</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="basic.php">基本資料</a></li>
                            <li><hr class="dropdown-divider" /></li>
                            <li><a class="dropdown-item" href="contact.php">聯絡資料</a></li>
                            <li><a class="dropdown-item" href="revise.php">修改密碼</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" data-bs-toggle="dropdown">課程</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="favorite.php">喜好課程</a></li>
                            <li><a class="dropdown-item" href="statistics.php">次數統計</a></li>
                        </ul>
                    </li>
                </ul>
                <!-- Logout Button -->
                <form class="d-flex" method="post">
                    <button class="btn btn-outline-dark" name="logout" type="submit">登出</button>
                </form>
                <?php
                    if (isset($_POST["logout"])) {
                        session_destroy();
                        echo "<script>alert('已登出');location.href='index.php';</script>";
                    }
                ?>
            </div>
        </div>
    </nav>

    <!-- Header -->
    <header class="py-2">
        <div class="container px-4 px-lg-5 my-2">
            <div class="text-center text-black">
                <h1 class="display-4 fw-bolder">聯絡資料修改</h1>
                <p class="lead fw-normal text-black-50 mb-0">請更新您的聯絡資料</p>
            </div>
        </div>
    </header>

    <?php
    if (!isset($_SESSION["account"])) {
        echo "<script>alert('請先登入帳號');location.href='login.php';</script>";
    } else {
        $link = @mysqli_connect('127.0.0.1', 'root', '', 'learn');
        if (!$link) {
            echo "資料庫連線錯誤";
        } else {
            $sql = "SELECT * FROM `user` WHERE `account` = '" . $_SESSION["account"] . "'";
            $result = mysqli_query($link, $sql);
            if (mysqli_num_rows($result) > 0 && $row = mysqli_fetch_assoc($result)) {
                echo "<br><br><br><br><br><br>";
                echo "<form method='post' action='contact.php' align='center'>";
                echo "<h4>原始電子郵件：" . htmlspecialchars($row["email"]) . "</h4><br>";
                echo "<h4>更改後電子郵件：
                      <input type='text' name='new_email' size='25'><br><br></h4>";
                echo "<h4>手機號碼：
                      <input type='text' name='phone' size='25'><br><br></h4>";
                echo "<input type='submit' name='sure' value='提交'>";
                echo "</form>";

                if (isset($_POST["sure"])) {
                    $new_email = trim($_POST["new_email"]);
                    $phone = trim($_POST["phone"]);

                    if (empty($new_email) || empty($phone)) {
                        echo "<script>alert('資料未輸入完全');location.href='contact.php';</script>";
                    } else {
                        $sql2 = "UPDATE `user` 
                                 SET `email` = '" . htmlspecialchars($new_email) . "', 
                                     `phone` = '" . htmlspecialchars($phone) . "' 
                                 WHERE `account` = '" . $_SESSION["account"] . "'";
                        mysqli_query($link, $sql2);

                        if (mysqli_affected_rows($link) > 0) {
                            echo "<script>alert('修改成功');location.href='class.php';</script>";
                        }
                    }
                }
            }
        }
        mysqli_close($link);
    }
    ?>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/scripts.js"></script>
</body>
</html>