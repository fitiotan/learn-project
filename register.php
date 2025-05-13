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
                        <li class="nav-item"><a class="nav-link active" aria-current="page" href="index.php">首頁</a></li>
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
                    <p class="lead fw-normal text-black-50 mb-0">With this shop hompeage template</p>
                </div>
            </div>
        </header>
        <br>
        <form method="POST" align="center">
            <label for="username">姓名：</label><br>
            <input type="text" name="username"><br>
            <label for="account">帳號：</label><br>
            <input type="text" name="account"><br>
            <label for="password">密碼：</label><br>
            <input type="password" name="password1"><br>
            <label for="password">確認密碼：</label><br>
            <input type="password" name="password2"><br>
            <label for="identity">身分證字號：</label><br>
            <input type="text" name="iden"><br>
            <label for="photo">大頭貼(選一個喜歡的)：</label><br>
            <input type="radio" name="photo" value="1">
            <img src="assets/person1.png" width='150' height='150' />
            <input type="radio" name="photo" value="2">
            <img src="assets/person2.jpg" width='150' height='150' /><br>
            <input type="radio" name="photo" value="3">
            <img src="assets/person3.jpg" width='150' height='150' />
            <input type="radio" name="photo" value="4">
            <img src="assets/person4.png" width='150' height='150' /><br>
            <label for="phone">手機號碼：</label><br>
            <input type="text"name="phone"><br>
            <label for="eamil">信箱：</label><br>
            <input type="text"name="email"><br><br>
            <input type="submit" value="註冊"><br><br>     
            已有帳號嗎？請按此來
            <a href="login.php">登入</a>
        </form>
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="js/scripts.js"></script>

        <?php
            $link=@mysqli_connect('127.0.0.1','root','','learn');
            if(!$link)
            {
              echo "資料庫連線錯誤";
            }
            else
            {
                if(isset($_POST['username']) && isset($_POST['account']) && isset($_POST['password1']) && isset($_POST['password2'])
                   && isset($_POST['iden']) && isset($_POST['photo']) && isset($_POST['phone']) && isset($_POST['email']))
                {
                    if($_POST['username']==null || $_POST['account']==null || $_POST['password1']==null || $_POST['password2']==null
                    || $_POST['iden']==null || $_POST['photo']==null || $_POST['phone']==null || $_POST['email']==null )
                    {
                        echo "<script>alert('資料未輸入完全');location.href='register.php';</script>";
                    }
                    else if($_POST['password1']!=$_POST['password2'])
                    {
                      echo "<script>alert('密碼與確認密碼不符');location.href='register.php';</script>";
                    }
                    else if(strlen($_POST['iden'])!=10)
                    {
                      echo "<script>alert('身分證長度必須等於10');location.href='register.php';</script>";
                    }
                    else if(substr($_POST['iden'],1,1)!="1" && substr($_POST['iden'],1,1)!="2")
                    {
                        echo "<script>alert('身分證第2碼必須為1或2');location.href='register.php';</script>";
                    }
                    else if(strlen($_POST['phone'])!=10)
                    {
                      echo "<script>alert('電話長度必須等於10');location.href='register.php';</script>";
                    }
                    else if(substr($_POST['phone'],0,2)!="09")
                    {
                      echo "<script>alert('電話開頭必須為09');location.href='register.php';</script>";
                    }
                    else
                    {
                        $sql="select * from `user` where `name`='".$_POST['username']."'";
                        $result=mysqli_query($link,$sql);
                        $sql2="select * from `user` where `account`='".$_POST['account']."'";
                        $result2=mysqli_query($link,$sql2);
                        if(mysqli_num_rows($result)>0)
                        {
                            echo "<script>alert('您已經註冊過囉');location.href='login.php';</script>";
                        }
                        else if(mysqli_num_rows($result2)>0)
                        {
                            echo "<script>alert('此帳號已經註冊過囉');location.href='register.php';</script>";
                        }
                        else
                        {
                            $gender = substr($_POST['iden'],1,1);
                            if( $gender == "1")
                            {
                                $gender = "男";
                            }
                            else if( $gender == "2")
                            {
                                $gender = "女";
                            }
                            $photo = $_POST['photo'];
                            if( $photo == "1")
                            {
                                $photo = "assets/person1.png";
                            }
                            else if( $photo == "2")
                            {
                                $photo = "assets/person2.jpg";
                            }
                            else if( $photo == "3")
                            {
                                $photo = "assets/person3.jpg";
                            }
                            else if( $photo == "4")
                            {
                                $photo = "assets/person4.png";
                            }
                            $sql3 = "INSERT INTO `user` (`name`, `account`, `password`, `identity`, `photo`, `gender`
                                    , `phone`, `email`)
                            VALUES ('".$_POST['username']."','".$_POST['account']."','".$_POST['password1']."'
                            ,'".$_POST['iden']."','".$photo."','".$gender."'
                            ,'".$_POST['phone']."','".$_POST['email']."')";
                            $result3=mysqli_query($link,$sql3);
                            if(mysqli_affected_rows($link)>0)
                            {
                            echo "<script>alert('註冊成功');location.href='login.php';</script>";
                            }
                            else
                            {
                            echo "<script>alert('註冊失敗，請重新註冊');location.href='register.php';</script>";
                            }
                        }
                        
                    }
                }
            }
            mysqli_close($link);
        ?>
    </body>
</html>