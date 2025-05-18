<?php
// Start session if user clicks the "enter" button
if (isset($_POST['enter'])) {
    if ($_POST["enter"] == "進入") {
        session_start();
        if (!isset($_SESSION['account'])) {
            // Redirect to login page if not logged in
            echo "<script>alert('請先登入帳號');location.href='login.php';</script>";
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
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
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container px-4 px-lg-5">
            <a class="navbar-brand">學習系統</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" 
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="切換導航">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                    <li class="nav-item"><a class="nav-link active" href="index.php">首頁</a></li>
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
                    <button class="btn btn-outline-dark" type="button" onclick="window.location.href='login.php';">登入</button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <header class="py-2">
        <div class="container px-4 px-lg-5 my-2">
            <div class="text-center text-black">
                <h1 class="display-4 fw-bolder">學習風格</h1>
                <p class="lead fw-normal text-black-50 mb-0">使用這個模板開始您的學習之旅</p>
            </div>
        </div>
    </header>

    <!-- Course Section -->
    <section class="py-5">
        <div class="container px-4 px-lg-5 mt-5">
            <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
                <?php
                // Connect to database
                $link = @mysqli_connect('127.0.0.1', 'root', '', 'learn');
                if (!$link) {
                    echo "資料庫連線錯誤";
                } else {
                    $sql = "SELECT * FROM `class`";
                    $result = mysqli_query($link, $sql);

                    // Display each class card
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<div class='col mb-5'>";
                        echo "<div class='card h-100'>";
                        echo "<img class='card-img-top' src='" . $row["image"] . "' width='450' height='178' />";
                        echo "<div class='card-body p-4'>";
                        echo "<div class='text-center'>";
                        echo "<h5 class='fw-bolder'>" . $row["name"] . "</h5>";
                        echo "</div></div>";
                        echo "<div class='card-footer p-4 pt-0 border-top-0 bg-transparent'>";
                        echo "<div class='text-center'>";
                        echo "<form method='POST' action='index.php'>";
                        echo "<button class='btn btn-outline-dark mt-auto' type='submit' name='enter' value='進入'>進入</button>";
                        echo "</form>";
                        echo "</div></div></div></div>";
                    }
                    mysqli_close($link);
                }
                ?>
            </div>
        </div>
    </section>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/scripts.js"></script>
</body>
</html>