<?php
include 'db.php';
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'buyer'){ header("Location: login.php"); exit(); }

$user_id = $_SESSION['user_id'];
$msg = '';

if(isset($_POST['place_order'])){
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    $stmt = $conn->prepare("SELECT * FROM products WHERE id=:id");
    $stmt->execute(['id'=>$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if($quantity > 0 && $quantity <= $product['quantity']){
        $total_price = $quantity * $product['price'];
        $insert = $conn->prepare("INSERT INTO orders(buyer_id,product_id,quantity,total_price) VALUES(:buyer,:product,:qty,:total)");
        $insert->execute(['buyer'=>$user_id,'product'=>$product_id,'qty'=>$quantity,'total'=>$total_price]);
        $msg = "Order placed successfully!";
    } else {
        $msg = "Invalid quantity!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Place Order - Alibaba Clone</title>
    <style>
        body{font-family:Arial,sans-serif; background:#f4f4f4;}
        .container{width:90%; margin:20px auto; background:white; padding:20px; border-radius:10px; box-shadow:0 2px 5px rgba(0,0,0,0.1);}
        input[type=number]{width:100%; padding:10px; margin:10px 0; border-radius:5px; border:1px solid #ccc;}
        input[type=submit]{padding:10px 15px; background:#0073e6; color:white; border:none; border-radius:5px; cursor:pointer;}
        input[type=submit]:hover{background:#005bb5;}
        .msg{color:green;}
    </style>
</head>
<body>
<div class="container">
    <h2>Place Order</h2>
    <form method="post">
        <input type="number" name="product_id" placeholder="Product ID" required>
        <input type="number" name="quantity" placeholder="Quantity" required min="1">
        <input type="submit" name="place_order" value="Place Order">
    </form>
    <div class="msg"><?= $msg ?></div>
</div>
</body>
</html>
