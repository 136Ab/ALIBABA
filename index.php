<?php
include 'db.php';

// Fetch products
$stmt = $conn->prepare("SELECT * FROM products ORDER BY created_at DESC");
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Alibaba Clone - Home</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {font-family: Arial, sans-serif; background:#f4f4f4; margin:0; padding:0;}
        header {background:#0073e6; color:white; padding:20px; text-align:center;}
        .container {width:90%; margin:20px auto;}
        .product {background:white; padding:15px; margin-bottom:15px; border-radius:10px; box-shadow:0 2px 5px rgba(0,0,0,0.1);}
        .product h3 {margin:0; color:#0073e6;}
        .product p {color:#555;}
        .btn {padding:8px 15px; background:#0073e6; color:white; border:none; border-radius:5px; cursor:pointer;}
        .btn:hover {background:#005bb5;}
    </style>
</head>
<body>
<header>
    <h1>Alibaba Clone</h1>
</header>
<div class="container">
    <?php foreach($products as $p): ?>
    <div class="product">
        <h3><?= htmlspecialchars($p['name']) ?></h3>
        <p><?= htmlspecialchars(substr($p['description'],0,100)) ?>...</p>
        <p>Price: $<?= $p['price'] ?> | Quantity: <?= $p['quantity'] ?></p>
        <button class="btn" onclick="redirect('product.php?id=<?= $p['id'] ?>')">View Product</button>
    </div>
    <?php endforeach; ?>
</div>

<script>
function redirect(url){
    window.location.href = url;
}
</script>
</body>
</html>
