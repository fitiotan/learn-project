<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
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
                                <li><a class="dropdown-item" href="hobby.php">喜好課程</a></li>
                                <li><a class="dropdown-item" href="statistics.php">次數統計</a></li>
                            </ul>
                        </li>
                    </ul>
                    <form class="d-flex" method="post">
                        <button class="btn btn-outline-dark" name="logout" type="submit" value="登出">
                            登出
                        </button>
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

        <!-- Header-->
        <header class="py-2">
            <div class="container px-4 px-lg-5 my-2">
                <div class="text-center text-black">
                    <h1 class="display-4 fw-bolder">學習系統</h1>
                    <p class="lead fw-normal text-black-50 mb-0">基本資料</p>
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
                        $link = @mysqli_connect('127.0.0.1', 'root', '', 'learn');
                        if (!$link) {
                            echo "資料庫連線錯誤";
                        } else {
                            $stmt = $link->prepare("SELECT * FROM `user` WHERE `account` = ?");
                            $stmt->bind_param("s", $_SESSION["account"]);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            if ($row = mysqli_fetch_assoc($result)) {
                                echo "<table border='0' width='100%'><tr>";
                                echo "<td align='left'>";
                                echo "<img src='" . htmlspecialchars($row["photo"]) . "' alt='User photo' width='350' height='350'>";
                                echo "</td>";
                                echo "<td align='center'>";
                                echo "<p class='fs-5'>姓名：" . htmlspecialchars($row["name"]) . "</p><br>";
                                echo "<p class='fs-5'>帳號：" . htmlspecialchars($_SESSION["account"]) . "</p><br>";
                                echo "<p class='fs-5'>身分證字號：" . htmlspecialchars($row["identity"]) . "</p><br>";
                                echo "</td>";
                                echo "<td align='center'>";
                                echo "<p class='fs-5'>性別：" . htmlspecialchars($row["gender"]) . "</p><br>";
                                echo "<p class='fs-5'>手機：" . htmlspecialchars($row["phone"]) . "</p><br>";
                                echo "<p class='fs-5'>信箱：" . htmlspecialchars($row["email"]) . "</p><br>";
                                echo "</td>";
                                echo "</tr></table>";
                            }
                        }
                        mysqli_close($link);
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