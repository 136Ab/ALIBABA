<?php
require_once '../db.php';
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role']!=='supplier') { header('Location: ../auth/login.php'); exit; }
$cats = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();
$err='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $title = trim($_POST['title']); $desc = trim($_POST['description']); $price = floatval($_POST['price']);
    $minq = intval($_POST['min_order_qty']); $stock = intval($_POST['stock']); $cat = $_POST['category']?:null;
    if (!$title || $price<=0) $err = "Title and price required";
    else {
      $ins = $pdo->prepare("INSERT INTO products (supplier_id,category_id,title,description,price,min_order_qty,stock) VALUES (:s,:c,:t,:d,:p,:m,:st)");
      $ins->execute([':s'=>$_SESSION['user']['id'],':c'=>$cat,':t'=>$title,':d'=>$desc,':p'=>$price,':m'=>$minq,':st'=>$stock]);
      header('Location: dashboard.php'); exit;
    }
}
?>
<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Add Product</title>
<style>body{font-family:Inter,Arial;background:#f6f8fb}.box{max-width:700px;margin:30px auto;background:#fff;padding:18px;border-radius:12px;box-shadow:0 8px 24px rgba(10,20,40,0.06)}input,textarea,select{width:100%;padding:10px;border:1px solid #eef5ff;border-radius:8px;margin-top:8px}button{margin-top:12px;padding:10px;background:#0b74de;color:#fff;border-radius:8px;border:none}</style>
</head><body>
<div class="box">
  <h3>Add Product</h3>
  <?php if($err) echo "<div style='color:red'>$err</div>"; ?>
  <form method="post">
    <label>Title</label><input name="title">
    <label>Category</label>
    <select name="category"><option value="">-- select --</option>
      <?php foreach($cats as $c): ?><option value="<?php echo $c['id'] ?>"><?php echo htmlspecialchars($c['name']) ?></option><?php endforeach ?>
    </select>
    <label>Description</label><textarea name="description" rows="5"></textarea>
    <label>Price (USD)</label><input name="price" type="number" step="0.01">
    <label>Min Order Qty</label><input name="min_order_qty" type="number" value="1">
    <label>Stock</label><input name="stock" type="number" value="0">
    <button type="submit">Add Product</button>
  </form>
</div>
</body></html>
