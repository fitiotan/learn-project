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
                height: 100vh; /* 設定高度填滿整個視窗 */
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
                                <li><a class="dropdown-item" href="favorite.php">喜好課程</a></li>
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
                        if (isset($_POST["logout"]) && $_POST["logout"] == "登出") {
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
                    <h1 class="display-4 fw-bolder">章節選擇</h1>
                    <p class="lead fw-normal text-white-50 mb-0">選擇章節</p>
                </div>
            </div>
        </header>
        <br>
        <section class="py-2">
            <div class="container px-4 px-lg-5 mt-2">
                <div class="row gx-4 gx-lg-5 row-cols-3 row-cols-md-3 row-cols-xl-4 justify-content-center">
        <form class="d-flex">
            <button class="btn btn-outline-dark" name="before" align="center" type="button" onclick="window.location.href='class.php';">
                上一頁
            </button>
        </form>
                </div>
            </div>
        </section>
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
                    if(isset($_POST["enter"]))
                    {
                        if( $_POST["enter"] == "其他課程" )
                        {
                            echo '<form action="point.php" method="post" enctype="multipart/form-data">';
                            echo '選擇上傳檔案：<input type="file" name="file"/><hr>';
                            echo '<input type="submit" value="上傳檔案"/>';
                            echo '</form>';
                        }
                        else
                        {
                            $sql = "select * from `class` where `name`='".$_POST["enter"]."'";
                            $result = mysqli_query($link,$sql);
                            if( $row = mysqli_fetch_assoc($result) )
                            {
                                $image = $row["image"];
                                $sql4 = "select * from `resource` where `class`='".$row["name"]."'";
                                $result4 = mysqli_query($link,$sql4);
                                echo '<section class="py-1">';
                                echo '<div class="container px-4 px-lg-5 mt-1">';
                                echo '<div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">';
                                for($i=1;$i<=mysqli_num_rows($result4);$i++)
                                {
                                    if( $row = mysqli_fetch_assoc($result4) )
                                    {                                  
                                        echo "<div class='col mb-5'>";
                                            echo "<div class='card h-100'>";
                                                echo "<img class='card-img-top' src=".$image." width='450' height='178' />";
                                                echo "<div class='card-body p-4'>";
                                                    echo "<div class='text-center'>";
                                                        echo "<h5 class='fw-bolder'>".$row["subject"]."</h5>";
                                                    echo "</div>";
                                                echo "</div>";
                                                echo "<div class='card-footer p-4 pt-0 border-top-0 bg-transparent'>";
                                                    echo "<div class='text-center'>";
                                                        echo "<form method='POST' action='point.php'>";  
                                                            echo "<button class='btn btn-outline-dark mt-auto' type='submit' name='enter' value=".$row['subject'].">";
                                                                echo "查看";
                                                            echo "</button>";
                                                        echo "</form>";
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
                    }
                    if(isset($_POST["enter"]))
                    {
                        $sql = "select * from `class` where `name`='".$_POST["enter"]."'";
                        $result = mysqli_query($link,$sql);
                        if( $row = mysqli_fetch_assoc($result) )
                        {
                            $name = $row["name"];
                            $sql2 = "select * from `record_class` where `account`='".$_SESSION["account"]."' AND `class`='".$name."'";
                            $result2 = mysqli_query($link,$sql2);
                            if( mysqli_num_rows($result2) > 0)
                            {
                                if( $row = mysqli_fetch_assoc($result2) )
                                {
                                    $times2 = $row["times"] + 1;
                                    $sql3 = "update `record_class` set `times`='".$times2."' where `account`='".$_SESSION["account"]."' AND `class`='".$name."'";
                                    $result3 = mysqli_query($link,$sql3);
                                }
                            }
                            else
                            {
                                $sql3 = "insert into `record_class` (`account`, `class`, `times`)
                                VALUES ('".$_SESSION["account"]."', '".$name."', '1')";
                                $result3 = mysqli_query($link,$sql3);
                            }
                        }
                    }
                }
                mysqli_close($link);
            }
        ?>
                </div>
            </div>
        </section>
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="js/scripts.js"></script>
    </body>
</html>