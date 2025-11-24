<?php
include 'db.php';
$msg = '';
if(isset($_POST['register'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $type = $_POST['type'];

    $stmt = $conn->prepare("INSERT INTO users(name,email,password,type) VALUES(:name,:email,:password,:type)");
    if($stmt->execute(['name'=>$name,'email'=>$email,'password'=>$password,'type'=>$type])){
        $msg = "Registration successful! <span style='color:green;cursor:pointer;' onclick='redirect(\"login.php\")'>Login here</span>";
    } else {
        $msg = "Error! Please try again.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register - Alibaba Clone</title>
    <style>
        body{font-family:Arial,sans-serif; background:#f4f4f4;}
        .register-box{width:350px; margin:100px auto; padding:30px; background:white; border-radius:10px; box-shadow:0 2px 10px rgba(0,0,0,0.1);}
        h2{text-align:center; color:#0073e6;}
        input[type=text], input[type=email], input[type=password], select{width:100%; padding:10px; margin:10px 0; border-radius:5px; border:1px solid #ccc;}
        input[type=submit]{width:100%; padding:10px; background:#0073e6; border:none; color:white; border-radius:5px; cursor:pointer;}
        input[type=submit]:hover{background:#005bb5;}
        .msg{text-align:center; color:red;}
    </style>
</head>
<body>
<div class="register-box
