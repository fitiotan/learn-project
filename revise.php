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
    <!-- Core theme CSS (includes Bootstrap) -->
    <link href="css/styles.css" rel="stylesheet" />
    <style>
        body {
            background-image: url('assets/background_straight.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100%;
        }
        header {
            background-color: rgba(0, 0, 0, 0.2);
        }
        form {
            max-width: 400px;
            margin: auto;
            background-color: rgba(255,255,255,0.9);
            padding: 20px;
            border-radius: 10px;
        }
        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            margin-bottom: 10px;
            padding: 8px;
            box-sizing: border-box;
        }
        img {
            cursor: pointer;
            vertical-align: middle;
        }
        input[type="radio"] {
            vertical-align: middle;
            margin-right: 5px;
        }

        /* Photo selection styling */
        .photo-selection {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: center;
            margin-top: 5px;
            margin-bottom: 20px;
        }
        .photo-selection input[type="radio"] {
            display: none;
        }
        .photo-selection label {
            cursor: pointer;
            border: 3px solid transparent;
            border-radius: 10px;
            transition: border-color 0.3s ease;
            display: inline-block;
        }
        .photo-selection label img {
            display: block;
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 10px;
        }
        .photo-selection input[type="radio"]:checked + label {
            border-color: #0d6efd; /* Bootstrap primary blue */
        }
    </style>
</head>
<body>
    <!-- Navigation-->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container px-4 px-lg-5">
            <a class="navbar-brand">學習系統</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" 
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                    <li class="nav-item"><a class="nav-link active" aria-current="page" href="index.php">首頁</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" 
                           data-bs-toggle="dropdown" aria-expanded="false">修改資料</a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="basic.php">基本資料</a></li>
                            <li><hr class="dropdown-divider" /></li>
                            <li><a class="dropdown-item" href="contact.php">聯絡資料</a></li>
                            <li><a class="dropdown-item" href="revise.php">修改密碼</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" 
                           data-bs-toggle="dropdown" aria-expanded="false">課程</a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="hobby.php">喜好課程</a></li>
                            <li><a class="dropdown-item" href="statistics.php">次數統計</a></li>
                        </ul>
                    </li>
                </ul>
                <form class="d-flex">
                    <button class="btn btn-outline-dark" name="login" type="button" onclick="window.location.href='login.php';">
                        登入
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Header-->
    <header class="py-2">
        <div class="container px-4 px-lg-5 my-2">
            <div class="text-center text-black">
                <h1 class="display-4 fw-bolder">Shop in style</h1>
                <p class="lead fw-normal text-black-50 mb-0">With this shop homepage template</p>
            </div>
        </div>
    </header>

    <br>

    <form method="POST" align="center" action="">
        <label for="username">姓名：</label>
        <input type="text" name="username" id="username" />

        <label for="account">帳號：</label>
        <input type="text" name="account" id="account" />

        <label for="password1">密碼：</label>
        <input type="password" name="password1" id="password1" />

        <label for="password2">確認密碼：</label>
        <input type="password" name="password2" id="password2" />

        <label for="iden">身分證字號：</label>
        <input type="text" name="iden" id="iden" />

        <label for="photo">大頭貼(選一個喜歡的)：</label>
        <div class="photo-selection">
            <input type="radio" name="photo" value="1" id="photo1" />
            <label for="photo1"><img src="assets/person1.png" alt="person1" /></label>

            <input type="radio" name="photo" value="2" id="photo2" />
            <label for="photo2"><img src="assets/person2.jpg" alt="person2" /></label>

            <input type="radio" name="photo" value="3" id="photo3" />
            <label for="photo3"><img src="assets/person3.jpg" alt="person3" /></label>

            <input type="radio" name="photo" value="4" id="photo4" />
            <label for="photo4"><img src="assets/person4.png" alt="person4" /></label>
        </div>

        <label for="phone">手機號碼：</label>
        <input type="text" name="phone" id="phone" />

        <label for="email">信箱：</label>
        <input type="text" name="email" id="email" />

        <br>
        <input type="submit" value="註冊" />
        <br><br>
        已有帳號嗎？請按此來
        <a href="login.php">登入</a>
    </form>

    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="js/scripts.js"></script>

<?php
    $link = @mysqli_connect('127.0.0.1', 'root', '', 'learn');
    if (!$link) {
        echo "資料庫連線錯誤";
    } else {
        if (
            isset($_POST['username']) && isset($_POST['account']) && isset($_POST['password1']) && isset($_POST['password2']) &&
            isset($_POST['iden']) && isset($_POST['photo']) && isset($_POST['phone']) && isset($_POST['email'])
        ) {
            if (
                $_POST['username'] == null || $_POST['account'] == null || $_POST['password1'] == null || $_POST['password2'] == null ||
                $_POST['iden'] == null || $_POST['photo'] == null || $_POST['phone'] == null || $_POST['email'] == null
            ) {
                echo "<script>alert('資料未輸入完全');location.href='register.php';</script>";
            } else if ($_POST['password1'] != $_POST['password2']) {
                echo "<script>alert('密碼與確認密碼不符');location.href='register.php';</script>";
            } else if (strlen($_POST['iden']) != 10) {
                echo "<script>alert('身分證長度必須等於10');location.href='register.php';</script>";
            } else if (substr($_POST['iden'], 1, 1) != "1" && substr($_POST['iden'], 1, 1) != "2") {
                echo "<script>alert('身分證第2碼必須為1或2');location.href='register.php';</script>";
            } else if (strlen($_POST['phone']) != 10) {
                echo "<script>alert('電話長度必須等於10');location.href='register.php';</script>";
            } else if (substr($_POST['phone'], 0, 2) != "09") {
                echo "<script>alert('電話開頭必須為09');location.href='register.php';</script>";
            } else {
                $sql = "select * from `user` where `name`='" . mysqli_real_escape_string($link, $_POST['username']) . "'";
                $result = mysqli_query($link, $sql);
                $sql2 = "select * from `user` where `account`='" . mysqli_real_escape_string($link, $_POST['account']) . "'";
                $result2 = mysqli_query($link, $sql2);

                if (mysqli_num_rows($result) > 0) {
                    echo "<script>alert('您已經註冊過囉');location.href='login.php';</script>";
                } else if (mysqli_num_rows($result2) > 0) {
                    echo "<script>alert('此帳號已經有人使用');location.href='register.php';</script>";
                } else {
                    $sql = "insert into `user`(`name`,`account`,`password`,`iden`,`photo`,`phone`,`email`) values ('" .
                        mysqli_real_escape_string($link, $_POST['username']) . "','" .
                        mysqli_real_escape_string($link, $_POST['account']) . "','" .
                        password_hash($_POST['password1'], PASSWORD_DEFAULT) . "','" .
                        mysqli_real_escape_string($link, $_POST['iden']) . "','" .
                        mysqli_real_escape_string($link, $_POST['photo']) . "','" .
                        mysqli_real_escape_string($link, $_POST['phone']) . "','" .
                        mysqli_real_escape_string($link, $_POST['email']) . "')";

                    if (mysqli_query($link, $sql)) {
                        echo "<script>alert('註冊成功');location.href='login.php';</script>";
                    } else {
                        echo "資料庫錯誤";
                    }
                }
            }
        }
    }
?>
</body>
</html>