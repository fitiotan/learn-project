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
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <!-- Bootstrap icons-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="css/styles.css" rel="stylesheet" />
        <style>
            body 
            {
                background-image: url('assets/background_straight.jpg'); /* 替換成你的圖片路徑 */
                background-size: cover; /* 讓圖片覆蓋整個畫面 */
                background-position: center; /* 圖片置中 */
                background-repeat: no-repeat; /* 不重複顯示 */
                height: 100%; /* 設定高度填滿整個視窗 */
            }
            header 
            {
                background-color: rgba(0, 0, 0, 0.2); /* 黑色 (0,0,0) 並設為 50% 透明 */
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
                        <button class="btn btn-outline-dark" name="logout" type="submit" value='登出'>
                            登出
                        </button>
                    </form>
                    <?php
                        if(isset($_POST["logout"] ) == "登出")
                        {
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
                <div class="text-center text-white">
                    <h1 class="display-4 fw-bolder">Shop in style</h1>
                    <p class="lead fw-normal text-white-50 mb-0">With this shop hompeage template</p>
                </div>
            </div>
        </header>
        <?php
            if(!isset($_SESSION["account"]))
            {
                echo "<script>alert('請先登入帳號');location.href='login.php';</script>";
            }
            else
            {
                $link=@mysqli_connect('127.0.0.1','root','','learn');
                if(!$link)
                {
                   echo "資料庫連線錯誤";
                }
                else
                {
                    $sql = "select class.id, class.name, class.image, record_class.times
                    FROM `class`
                    inner join `record_class` on class.name = record_class.class where record_class.account = '".$_SESSION["account"]."' order by record_class.times desc";
                    $result = mysqli_query($link,$sql);
                    if( mysqli_num_rows($result) > 0)
                    {
                        echo '<section class="py-3">';
                        echo '<div class="container px-4 px-lg-5 mt-3">';
                        echo '<div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">';
                        for($i=1;$i<=mysqli_num_rows($result);$i++)
                        {
                            if( $row = mysqli_fetch_assoc($result) )
                            {
                                echo "<div class='col mb-5'>";
                                    echo "<div class='card h-100'>";
                                        echo "<img class='card-img-top' src=".$row["image"]." width='450' height='178' />";
                                        echo "<div class='card-body p-4'>";
                                            echo "<div class='text-center'>";
                                                echo "<h5 class='fw-bolder'>".$row["name"]."</h5>";
                                            echo "</div>";
                                        echo "</div>";
                                        echo "<div class='card-footer p-4 pt-0 border-top-0 bg-transparent'>";
                                            echo "<div class='text-center'>";
                                                echo "次數：".$row["times"];
                                            echo "</div>";;
                                        echo "</div>";
                                    echo "</div>";
                                echo "</div>";
                            }
                        }
                        echo '</div>';
                        echo '</div>';
                        echo '</section>';
                    }
                }
                mysqli_close($link);
            }
        ?>
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="js/scripts.js"></script>
    </body>
</html>