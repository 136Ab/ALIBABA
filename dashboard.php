<?php
include 'db.php';
session_start();
if(!isset($_SESSION['user_id'])){ header("Location: login.php"); exit(); }

$user_id = $_SESSION['user_id'];
$user_type = $_SESSION['user_type'];

if($user_type == 'supplier'){
    // Fetch supplier products
    $stmt = $conn->prepare("SELECT * FROM products WHERE supplier_id=:id");
    $stmt->execute(['id'=>$user_id]);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Fetch all products for buyers
    $stmt = $conn->prepare("SELECT p.*, u.name as supplier_name FROM products p JOIN users u ON p.supplier_id=u.id ORDER BY p.created_at DESC");
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Alibaba Clone</title>
    <style>
        body{font-family:Arial,sans-serif; background:#f4f4f4; margin:0; padding:0;}
        header{background:#0073e6; color:white; padding:20px; text-align:center;}
        .container{width:90%; margin:20px auto;}
        .product{background:white; padding:15px; margin-bottom:15px; border-radius:10px; box-shadow:0 2px 5px rgba(0,0,0,0.1);}
        .product h3{margin:0; color:#0073e6;}
        .btn{padding:8px 15px; background:#0073e6; color:white; border:none; border-radius:5px; cursor:pointer;}
        .btn:hover{background:#005bb5;}
        .top-nav{text-align:right; padding:10px;}
        .top-nav span{color:white; cursor:pointer; margin-left:15px;}
    </style>
</head>
<body>
<header>
    <h1>Dashboard - <?= ucfirst($user_type) ?></h1>
    <div class="top-nav">
        <span onclick="redirect('message.php')">Messages</span>
        <span onclick="redirect('logout.php')">Logout</span>
    </div>
</header>
<div class="container">
    <?php foreach($products as $p): ?>
    <div class="product">
        <h3><?= htmlspecialchars($p['name']) ?></h3>
        <p><?= htmlspecialchars(substr($p['description'],0,100)) ?>...</p>
        <p>Price: $<?= $p['price'] ?> | Quantity: <?= $p['quantity'] ?></p>
        <?php if($user_type=='buyer'): ?>
            <p>Supplier: <?= htmlspecialchars($p['supplier_name']) ?></p>
            <button class="btn" onclick="redirect('product.php?id=<?= $p['id'] ?>')">Request Quote / Buy</button>
        <?php else: ?>
            <button class="btn" onclick="redirect('edit_product.php?id=<?= $p['id'] ?>')">Edit Product</button>
        <?php endif; ?>
    </div>
    <?php endforeach; ?>
</div>
<script>
function redirect(url){ window.location.href = url; }
</script>
</body>
</html>
