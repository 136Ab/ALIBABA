<?php
include 'db.php';
session_start();
if(!isset($_SESSION['user_id'])){ header("Location: login.php"); exit(); }

$user_id = $_SESSION['user_id'];
$user_type = $_SESSION['user_type'];

if(!isset($_GET['id'])){ header("Location: dashboard.php"); exit(); }

$product_id = $_GET['id'];

// Fetch product details
$stmt = $conn->prepare("SELECT p.*, u.name as supplier_name FROM products p JOIN users u ON p.supplier_id=u.id WHERE p.id=:id");
$stmt->execute(['id'=>$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

$msg = '';
if(isset($_POST['request_quote'])){
    $quantity = $_POST['quantity'];
    $stmt = $conn->prepare("INSERT INTO quotations(product_id,buyer_id,supplier_id,requested_quantity) VALUES(:pid,:buyer,:supplier,:qty)");
    $stmt->execute(['pid'=>$product_id,'buyer'=>$user_id,'supplier'=>$product['supplier_id'],'qty'=>$quantity]);
    $msg = "Quotation requested successfully!";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($product['name']) ?> - Alibaba Clone</title>
    <style>
        body{font-family:Arial,sans-serif; background:#f4f4f4;}
        .container{width:90%; margin:20px auto; background:white; padding:20px; border-radius:10px; box-shadow:0 2px 5px rgba(0,0,0,0.1);}
        h2{color:#0073e6;}
        p{color:#555;}
        input[type=number]{width:100%; padding:10px; margin:10px 0; border-radius:5px; border:1px solid #ccc;}
        input[type=submit]{padding:10px 15px; background:#0073e6; color:white; border:none; border-radius:5px; cursor:pointer;}
        input[type=submit]:hover{background:#005bb5;}
        .msg{color:green;}
    </style>
</head>
<body>
<div class="container">
    <h2><?= htmlspecialchars($product['name']) ?></h2>
    <p><strong>Supplier:</strong> <?= htmlspecialchars($product['supplier_name']) ?></p>
    <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
    <p><strong>Price:</strong> $<?= $product['price'] ?> | <strong>Available Quantity:</strong> <?= $product['quantity'] ?></p>

    <?php if($user_type=='buyer'): ?>
    <form method="post">
        <input type="number" name="quantity" placeholder="Enter Quantity" required min="1" max="<?= $product['quantity'] ?>">
        <input type="submit" name="request_quote" value="Request Quotation">
    </form>
    <div class="msg"><?= $msg ?></div>
    <?php endif; ?>
</div>
</body>
</html>
