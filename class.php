<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
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
        #backToTopBtn {
            display: none;
            position: fixed;
            bottom: 40px;
            right: 40px;
            z-index: 99;
            width: 50px;
            height: 50px;
            font-size: 30px;
            border: none;
            outline: none;
            background-color: #343a40;
            color: white;
            cursor: pointer;
            border-radius: 50%;
            box-shadow: 0px 4px 8px rgba(0,0,0,0.3);
            transition: background-color 0.3s;
            text-align: center;
            line-height: 40px;
            padding: 0;
        }
        #backToTopBtn:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
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
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" data-bs-toggle="dropdown">修改資料</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="basic.php">基本資料</a></li>
                        <li><hr class="dropdown-divider" /></li>
                        <li><a class="dropdown-item" href="contact.php">聯絡資料</a></li>
                        <li><a class="dropdown-item" href="revise.php">修改密碼</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" data-bs-toggle="dropdown">課程</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="favorite.php">喜好課程</a></li>
                        <li><a class="dropdown-item" href="statistics.php">次數統計</a></li>
                    </ul>
                </li>
            </ul>
            <form class="d-flex" method="post" action="class.php">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                <button class="btn btn-outline-dark" name="logout" type="submit">登出</button>
            </form>
            <?php
                if (!isset($_SESSION['csrf_token'])) {
                    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                }
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
                    if (hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
                        session_destroy();
                        echo "<script>alert('已登出');location.href='index.php';</script>";
                        exit;
                    }
                }
            ?>
        </div>
    </div>
</nav>
<header class="py-2">
    <div class="container px-4 px-lg-5 my-2">
        <div class="text-center text-white">
            <h1 class="display-4 fw-bolder">選擇課程</h1>
            <p class="lead fw-normal text-white-50 mb-0">課程選擇</p>
        </div>
    </div>
</header>
<?php
if (!isset($_SESSION["account"])) {
    echo "<script>alert('請先登入帳號');location.href='login.php';</script>";
    exit;
}
$link = new mysqli('127.0.0.1', 'root', '', 'learn');
if ($link->connect_error) {
    die("資料庫連線錯誤: " . $link->connect_error);
}
$account = $link->real_escape_string($_SESSION["account"]);
$sql = "SELECT class.id, class.name, class.image, record_class.times
        FROM class
        INNER JOIN record_class ON class.name = record_class.class
        WHERE record_class.account = '$account'
        ORDER BY record_class.times DESC";
$result = $link->query($sql);
if ($result->num_rows > 0) {
    $sql2 = "SELECT * FROM class WHERE name NOT IN (
                SELECT class FROM record_class WHERE account = '$account')";
    $result2 = $link->query($sql2);
    echo '<section class="py-3"><div class="container px-4 px-lg-5 mt-3">';
    echo '<div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">';
    while ($row = $result->fetch_assoc()) {
        $img = htmlspecialchars($row['image'] ?: 'assets/default.jpg');
        $name = htmlspecialchars($row['name']);
        echo "<div class='col mb-5'>
                <div class='card h-100'>
                    <img class='card-img-top' src='$img' width='450' height='178' />
                    <div class='card-body p-4'>
                        <div class='text-center'>
                            <h5 class='fw-bolder'>$name</h5>
                        </div>
                    </div>
                    <div class='card-footer p-4 pt-0 border-top-0 bg-transparent'>
                        <div class='text-center'>
                            <form method='POST' action='resource.php'>
                                <button class='btn btn-outline-dark mt-auto' type='submit' name='enter' value='$name'>進入</button>
                            </form>
                        </div>
                    </div>
                </div>
              </div>";
    }
    echo '</div></div></section>';
    if ($result2->num_rows > 0) {
        echo '<h4><p class="text-center">您可能會喜歡的</p></h4><br>';
        echo '<section class="py-1"><div class="container px-4 px-lg-5 mt-1">';
        echo '<div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">';
        while ($row = $result2->fetch_assoc()) {
            $img = htmlspecialchars($row['image'] ?: 'assets/default.jpg');
            $name = htmlspecialchars($row['name']);
            echo "<div class='col mb-5'>
                    <div class='card h-100'>
                        <img class='card-img-top' src='$img' width='450' height='178' />
                        <div class='card-body p-4'>
                            <div class='text-center'>
                                <h5 class='fw-bolder'>$name</h5>
                            </div>
                        </div>
                        <div class='card-footer p-4 pt-0 border-top-0 bg-transparent'>
                            <div class='text-center'>
                                <form method='POST' action='resource.php'>
                                    <button class='btn btn-outline-dark mt-auto' type='submit' name='enter' value='$name'>進入</button>
                                </form>
                            </div>
                        </div>
                    </div>
                  </div>";
        }
        echo '</div></div></section>';
    }
} else {
    $sql3 = "SELECT * FROM class";
    $result3 = $link->query($sql3);
    echo '<section class="py-5"><div class="container px-4 px-lg-5 mt-5">';
    echo '<div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">';
    while ($row = $result3->fetch_assoc()) {
        $img = htmlspecialchars($row['image'] ?: 'assets/default.jpg');
        $name = htmlspecialchars($row['name']);
        echo "<div class='col mb-5'>
                <div class='card h-100'>
                    <img class='card-img-top' src='$img' width='450' height='178' />
                    <div class='card-body p-4'>
                        <div class='text-center'>
                            <h5 class='fw-bolder'>$name</h5>
                        </div>
                    </div>
                    <div class='card-footer p-4 pt-0 border-top-0 bg-transparent'>
                        <div class='text-center'>
                            <form method='POST' action='resource.php'>
                                <button class='btn btn-outline-dark mt-auto' type='submit' name='enter' value='$name'>進入</button>
                            </form>
                        </div>
                    </div>
                </div>
              </div>";
    }
    echo '</div></div></section>';
}
$link->close();
?>
<button onclick="scrollToTop()" id="backToTopBtn" title="回到頂部">↑</button>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/scripts.js"></script>
<script>
    window.onscroll = function () {
        const btn = document.getElementById("backToTopBtn");
        btn.style.display = (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) ? "block" : "none";
    };
    function scrollToTop() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
</script>
</body>
</html>