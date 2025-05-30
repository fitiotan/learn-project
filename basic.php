<?php
    session_start();
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
?>
<!DOCTYPE html>
<html lang="zh-Hant">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>線上學習平台</title>
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
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
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container px-4 px-lg-5">
                <a class="navbar-brand">學習系統</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                        <li class="nav-item"><a class="nav-link active" aria-current="page" href="class.php">首頁</a></li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">修改資料</a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="basic.php">基本資料</a></li>
                                <li><hr class="dropdown-divider" /></li>
                                <li><a class="dropdown-item" href="contact.php">聯絡資料</a></li>
                                <li><a class="dropdown-item" href="revise.php">修改密碼</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">課程</a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="favorite.php">喜好課程</a></li>
                                <li><a class="dropdown-item" href="statistics.php">次數統計</a></li>
                            </ul>
                        </li>
                    </ul>
                    <form class="d-flex" method="post">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                        <button class="btn btn-outline-dark" name="logout" type="submit" value="登出">
                            登出
                        </button>
                    </form>
                    <?php
                        if (isset($_POST["logout"])) {
                            if (hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
                                session_destroy();
                                echo "<script>alert('已登出');location.href='index.php';</script>";
                            } else {
                                echo "<script>alert('CSRF 驗證失敗');</script>";
                            }
                        }
                    ?>
                </div>
            </div>
        </nav>

        <!-- Header-->
        <header class="py-2">
            <div class="container px-4 px-lg-5 my-2">
                <div class="text-center text-black">
                    <h1 class="display-4 fw-bolder">基本資料</h1>
                </div>
            </div>
        </header>

        <!-- Section-->
        <section class="py-5">
            <div class="container px-4 px-lg-5 mt-5">
                <?php
                    if (!isset($_SESSION["account"])) {
                        echo "<script>alert('請先登入帳號');location.href='login.php';</script>";
                    } else {
                        $mysqli = new mysqli('127.0.0.1', 'root', '', 'learn');
                        if ($mysqli->connect_error) {
                            die("資料庫連線錯誤：" . $mysqli->connect_error);
                        }
                        $stmt = $mysqli->prepare("SELECT * FROM `user` WHERE `account` = ?");
                        $stmt->bind_param("s", $_SESSION["account"]);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if ($row = $result->fetch_assoc()) {
                            $photo = htmlspecialchars($row["photo"]);
                            $name = htmlspecialchars($row["name"]);
                            $identity = htmlspecialchars($row["identity"]);
                            $gender = htmlspecialchars($row["gender"]);
                            $phone = htmlspecialchars($row["phone"]);
                            $email = htmlspecialchars($row["email"]);
                            $account = htmlspecialchars($_SESSION["account"]);

                            echo "<table border='0' width='100%'><tr>";
                            echo "<td align='left'>";
                            if (!empty($photo) && preg_match('/\\.(jpg|jpeg|png|gif)$/i', $photo)) {
                                echo "<img src='$photo' alt='User photo' width='350' height='350'>";
                            } else {
                                echo "<img src='assets/default.png' alt='No photo' width='350' height='350'>";
                            }
                            echo "</td>";
                            echo "<td align='center'>";
                            echo "<p class='fs-5'>姓名：$name</p><br>";
                            echo "<p class='fs-5'>帳號：$account</p><br>";
                            echo "<p class='fs-5'>身分證字號：$identity</p><br>";
                            echo "</td>";
                            echo "<td align='center'>";
                            echo "<p class='fs-5'>性別：$gender</p><br>";
                            echo "<p class='fs-5'>手機：$phone</p><br>";
                            echo "<p class='fs-5'>信箱：$email</p><br>";
                            echo "</td>";
                            echo "</tr></table>";
                        }
                        $mysqli->close();
                    }
                ?>
            </div>
        </section>

        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="js/scripts.js"></script>
    </body>
</html>