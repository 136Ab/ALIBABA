<?php
include 'db.php';
session_start();
$msg = '';
if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email=:email");
    $stmt->execute(['email'=>$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if($user && password_verify($password, $user['password'])){
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_type'] = $user['type'];
        header("Location: dashboard.php");
        exit();
    } else {
        $msg = "Invalid Email or Password!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - Alibaba Clone</title>
    <style>
        body{font-family:Arial,sans-serif; background:#f4f4f4;}
        .login-box{width:350px; margin:100px auto; padding:30px; background:white; border-radius:10px; box-shadow:0 2px 10px rgba(0,0,0,0.1);}
        h2{text-align:center; color:#0073e6;}
        input[type=text], input[type=password]{width:100%; padding:10px; margin:10px 0; border-radius:5px; border:1px solid #ccc;}
        input[type=submit]{width:100%; padding:10px; background:#0073e6; border:none; color:white; border-radius:5px; cursor:pointer;}
        input[type=submit]:hover{background:#005bb5;}
        .msg{color:red; text-align:center;}
        .register-link{text-align:center; margin-top:10px;}
    </style>
</head>
<body>
<div class="login-box">
    <h2>Login</h2>
    <form method="post">
        <input type="text" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="submit" name="login" value="Login">
    </form>
    <div class="msg"><?= $msg ?></div>
    <div class="register-link">
        <p>Don't have an account? <span style="color:#0073e6;cursor:pointer;" onclick="redirect('register.php')">Register</span></p>
    </div>
</div>
<script>
function redirect(url){ window.location.href = url; }
</script>
</body>
</html>
