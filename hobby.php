<?php
session_start();
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
            background-image: url('assets/background_straight.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100%;
        }
        header {
            background-color: rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container px-4 px-lg-5">
            <a class="navbar-brand">學習系統</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="切換導覽選單"><span class="navbar-toggler-icon"></span></button>
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
                    if(isset($_POST["logout"])) {
                        session_destroy();
                        header("Location: index.php");
                        exit;
                    }
                ?>
            </div>
        </div>
    </nav>
    <header class="py-2">
        <div class="container px-4 px-lg-5 my-2">
            <div class="text-center text-white">
                <h1 class="display-4 fw-bolder">線上學習平台</h1>
                <p class="lead fw-normal text-white-50 mb-0">歡迎使用您的學習系統</p>
            </div>
        </div>
    </header>

    <?php
        if(!isset($_SESSION["account"])) {
            echo "<script>alert('請先登入帳號');location.href='login.php';</script>";
            exit;
        } else {
            $link = @mysqli_connect('127.0.0.1', 'root', '', 'learn');
            if(!$link) {
                echo "資料庫連線錯誤";
                exit;
            }

            // Using prepared statements to prevent SQL injection
            $sql = "SELECT class.id, class.name, class.image, record_class.times
                    FROM `class`
                    INNER JOIN `record_class` ON class.name = record_class.class 
                    WHERE record_class.account = ?
                    ORDER BY record_class.times DESC";
            $stmt = mysqli_prepare($link, $sql);
            mysqli_stmt_bind_param($stmt, 's', $_SESSION["account"]);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if(mysqli_num_rows($result) > 0) {
                echo '<section class="py-3">';
                echo '<div class="container px-4 px-lg-5 mt-3">';
                echo '<div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">';
                while($row = mysqli_fetch_assoc($result)) {
                    echo "<div class='col mb-5'>";
                        echo "<div class='card h-100'>";
                            echo "<img class='card-img-top' src='".$row["image"]."' width='450' height='178' />";
                            echo "<div class='card-body p-4'>";
                                echo "<div class='text-center'>";
                                    echo "<h5 class='fw-bolder'>".$row["name"]."</h5>";
                                echo "</div>";
                            echo "</div>";
                            echo "<div class='card-footer p-4 pt-0 border-top-0 bg-transparent'>";
                                echo "<div class='text-center'>";
                                    echo "次數：".$row["times"];
                                echo "</div>";
                            echo "</div>";
                        echo "</div>";
                    echo "</div>";
                }
                echo '</div>';
                echo '</div>';
                echo '</section>';
            }
            mysqli_stmt_close($stmt);
            mysqli_close($link);
        }
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/scripts.js"></script>
</body>
</html>