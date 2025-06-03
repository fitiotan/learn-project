<?php
session_start();

$link = @mysqli_connect('127.0.0.1', 'root', '', 'learn');
if (!$link) {
    die("資料庫連線錯誤");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize inputs
    $name = mysqli_real_escape_string($link, $_POST["username"]);
    $account = mysqli_real_escape_string($link, $_POST["account"]);
    $password = $_POST["password1"];  // Will hash later
    $identity = mysqli_real_escape_string($link, $_POST["iden"]);
    $photo = mysqli_real_escape_string($link, $_POST["photo"]);
    $gender = mysqli_real_escape_string($link, $_POST["gender"]);
    $phone = mysqli_real_escape_string($link, $_POST["phone"]);
    $email = mysqli_real_escape_string($link, $_POST["email"]);

    // Basic backend validation
    if (empty($name) || empty($account) || empty($password) || empty($identity) || empty($photo) || empty($gender) || empty($phone) || empty($email)) {
        echo "<script>alert('請完整填寫所有欄位');</script>";
    } else {
        // Check for existing account
        $checkSql = "SELECT * FROM user WHERE account = '$account'";
        $checkResult = mysqli_query($link, $checkSql);
        if (mysqli_num_rows($checkResult) > 0) {
            echo "<script>alert('帳號已存在');</script>";
        } else {
            // Insert new user
            $sql = "INSERT INTO user (name, account, password, identity, photo, gender, phone, email) VALUES ('$name', '$account', '$hashed_password', '$identity', '$photo', '$gender', '$phone', '$email')";
            if (mysqli_query($link, $sql)) {
                echo "<script>alert('註冊成功！');location.href='login.php';</script>";
                exit;
            } else {
                echo "<script>alert('註冊失敗，請稍後再試');</script>";
            }
        }
    }
}

mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>註冊頁面</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet" />
  <link href="css/styles.css" rel="stylesheet" />
  <style>
    body {
      margin: 0;
      font-family: "Segoe UI", sans-serif;
      background: url('assets/background_straight.jpg') no-repeat center center fixed;
      background-size: cover;
    }

    .container {
      max-width: 85%;
      margin: auto;
      padding: 10px 20px;
      background-color: rgba(255, 255, 255, 0.95);
      margin-top: 30px;
    }

    form {
      display: grid;
      grid-template-columns: 1fr 1fr;
      grid-gap: 15px 20px;
    }

    .full-width {
      grid-column: 1 / -1;
    }

    label {
      font-weight: 500;
      display: block;
      margin-bottom: 4px;
    }

    input[type="text"],
    input[type="password"] {
      width: 100%;
      padding: 6px;
      box-sizing: border-box;
    }

    .error-msg {
      color: red;
      font-size: 12px;
      margin-top: -4px;
      margin-bottom: 4px;
    }

    .gender-options label {
      margin-right: 10px;
      font-weight: normal;
    }

    .photo-selection {
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
    }

    .photo-selection input[type="radio"] {
      display: none;
    }

    .photo-selection label img {
      width: 80px;
      height: 80px;
      border-radius: 6px;
      object-fit: cover;
      border: 2px solid transparent;
      cursor: pointer;
    }

    .photo-selection input[type="radio"]:checked + label img {
      border-color: #007bff;
    }

    input[type="submit"] {
      padding: 10px;
      background-color: #007bff;
      color: white;
      border: none;
      cursor: pointer;
      font-size: 1em;
      border-radius: 4px;
    }

    .login-link {
      font-size: 0.9em;
    }

    @media (max-width: 768px) {
      form {
        grid-template-columns: 1fr;
      }
    }
    .gender-options {
        display: flex;
        gap: 1.5rem;
        margin-top: 0.5rem;
    }
    .gender-options label {
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }

  </style>
</head>
<body>
  <div class="container">
    <form method="POST" action="" onsubmit="return validateForm();">
      <div>
        <label for="username">姓名</label>
        <input type="text" name="username" id="username" />
        <div class="error-msg" id="username-error"></div>
      </div>

      <div>
        <label for="account">帳號</label>
        <input type="text" name="account" id="account" />
        <div class="error-msg" id="account-error"></div>
      </div>

      <div>
        <label for="password1">密碼</label>
        <input type="password" name="password1" id="password1" />
        <div class="error-msg" id="password1-error"></div>
      </div>

      <div>
        <label for="password2">確認密碼</label>
        <input type="password" name="password2" id="password2" />
        <div class="error-msg" id="password2-error"></div>
      </div>

      <div>
        <label for="iden">身分證字號</label>
        <input type="text" name="iden" id="iden" />
        <div class="error-msg" id="iden-error"></div>
      </div>

      <div>
        <label>性別</label>
        <div class="gender-options">
          <input type="radio" name="gender" value="男" id="male" /><label for="male">男</label>
          <input type="radio" name="gender" value="女" id="female" /><label for="female">女</label>
          <input type="radio" name="gender" value="不願透露" id="prefer-not" /><label for="prefer-not">不願透露</label>
        </div>
        <div class="error-msg" id="gender-error"></div>
      </div>

      <div class="full-width">
        <label>選擇大頭貼</label>
        <div class="photo-selection">
          <input type="radio" name="photo" value="1" id="photo1" /><label for="photo1"><img src="assets/person1.png" /></label>
          <input type="radio" name="photo" value="2" id="photo2" /><label for="photo2"><img src="assets/person2.jpg" /></label>
          <input type="radio" name="photo" value="3" id="photo3" /><label for="photo3"><img src="assets/person3.jpg" /></label>
          <input type="radio" name="photo" value="4" id="photo4" /><label for="photo4"><img src="assets/person4.png" /></label>
        </div>
        <div class="error-msg" id="photo-error"></div>
      </div>

      <div>
        <label for="phone">手機號碼</label>
        <input type="text" name="phone" id="phone" />
        <div class="error-msg" id="phone-error"></div>
      </div>

      <div>
        <label for="email">信箱</label>
        <input type="text" name="email" id="email" />
        <div class="error-msg" id="email-error"></div>
      </div>

      <div class="full-width">
        <input type="submit" value="註冊" />
        <div class="login-link">已有帳號？<a href="login.php">登入</a></div>
      </div>
    </form>
  </div>

  <script>
    function validateForm() {
      let valid = true;
      const get = id => document.getElementById(id).value.trim();
      document.querySelectorAll('.error-msg').forEach(el => el.textContent = '');

      if (!get('username')) {
        document.getElementById('username-error').textContent = '請輸入姓名';
        valid = false;
      }
      if (!get('account')) {
        document.getElementById('account-error').textContent = '請輸入帳號';
        valid = false;
      }
      if (!get('password1')) {
        document.getElementById('password1-error').textContent = '請輸入密碼';
        valid = false;
      }
      if (get('password1') !== get('password2')) {
        document.getElementById('password2-error').textContent = '密碼不一致';
        valid = false;
      }
      if (!get('iden')) {
        document.getElementById('iden-error').textContent = '請輸入身分證字號';
        valid = false;
      }
      if (!document.querySelector('input[name="gender"]:checked')) {
        document.getElementById('gender-error').textContent = '請選擇性別';
        valid = false;
      }
      if (!document.querySelector('input[name="photo"]:checked')) {
        document.getElementById('photo-error').textContent = '請選擇大頭貼';
        valid = false;
      }
      const phone = get('phone');
      if (!/^\d{10}$/.test(phone) || !phone.startsWith('09')) {
        document.getElementById('phone-error').textContent = '請輸入正確的手機號碼';
        valid = false;
      }
      const email = get('email');
      if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        document.getElementById('email-error').textContent = '請輸入正確的信箱格式';
        valid = false;
      }

      return valid;
    }
  </script>
</body>
</html>