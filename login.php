<?php
session_start();
session_regenerate_id(true); // after successful login
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>線上學習平台</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Bootstrap icons-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="css/styles.css" rel="stylesheet" />
    <style>
        body {
            background-color: #ADD8E6;
        }
        header {
            background-color: rgba(0, 0, 0, 0.2); /* 黑色 (0,0,0) 並設為 50% 透明 */
        }
    </style>
</head>
<body>
    <!-- Navigation-->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container px-4 px-lg-5">
            <a class="navbar-brand">學習系統</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span
                    class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                    <li class="nav-item"><a class="nav-link active" aria-current="page" href="index.php">首頁</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">修改資料</a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="basic.php">基本資料</a></li>
                            <li>
                                <hr class="dropdown-divider" />
                            </li>
                            <li><a class="dropdown-item" href="contact.php">聯絡資料</a></li>
                            <li><a class="dropdown-item" href="revise.php">修改密碼</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">課程</a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="favorite.php">喜好課程</a></li>
                            <li><a class="dropdown-item" href="statistics.php">次數統計</a></li>
                        </ul>
                    </li>
                </ul>
                <form class="d-flex">
                    <button class="btn btn-outline-dark" name="login" type="button" onclick="window.location.href='register.php';">
                        註冊
                    </button>
                </form>
            </div>
        </div>
    </nav>
    <br><br><br><br><br><br><br>
    <form action="login.php" method="POST" align="center">
        <label for="account">
            <h2><font color="black">帳號：</font></h2>
        </label>
        <input type="text" name="account"><br><br>
        <label for="password">
            <h2><font color="black">密碼：</font></h2>
        </label>
        <input type="password" name="password"><br><br>
        <input type="submit" name="login" value="登入">
    </form>
    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="js/scripts.js"></script>
    <?php
    if (isset($_POST["login"]) && $_POST["login"] == "登入") {
        $link = @mysqli_connect('127.0.0.1', 'root', '', 'learn');
        if (!$link) {
            echo "資料庫連線錯誤";
        } else {
            if (empty($_POST['account']) || empty($_POST['password'])) {
                echo "<script>alert('請輸入帳號及密碼');location.href='login.php';</script>";
            } else {
                $account = $_POST['account'];
                $password = $_POST['password'];

                // Use prepared statement to avoid SQL injection
                $stmt = mysqli_prepare($link, "SELECT * FROM `user` WHERE `account` = ? AND `password` = ?");
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "ss", $account, $password);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    if (mysqli_num_rows($result) > 0) {
                        $_SESSION["account"] = $account;  // set session after success
                        echo "<script>alert('登入成功');location.href='class.php';</script>";
                    } else {
                        echo "<script>alert('帳號或密碼輸入錯誤');location.href='login.php';</script>";
                    }
                    mysqli_stmt_close($stmt);
                } else {
                    echo "SQL準備失敗";
                }
            }
            mysqli_close($link);
        }
    }
    ?>
</body>
</html>