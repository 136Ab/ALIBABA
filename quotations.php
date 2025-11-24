<?php include 'header.php';
$action = $_GET['action'] ?? 'list';
if($action=='request'){
  $product_id = (int)($_GET['product_id'] ?? 0);
  $prod = $pdo->prepare('SELECT * FROM products WHERE id=?'); $prod->execute([$product_id]); $prod = $prod->fetch();
  if(!$prod){ echo '<div class="card">Product not found.</div>'; include 'footer.php'; exit; }
  ?>
  <div class="card">
    <h3>Request Quotation — <?=htmlspecialchars($prod['title'])?></h3>
    <form method="post" action="quotations.php">
      <input type="hidden" name="product_id" value="<?=$prod['id']?>">
      <div class="form-row"><label>Quantity</label><input class="input" name="qty" required></div>
      <div class="form-row"><label>Message (optional)</label><textarea class="input" name="message"></textarea></div>
      <div><button class="btn" type="submit">Send Request</button></div>
    </form>
  </div>
  <?php
  include 'footer.php';
  exit;
}

if($_SERVER['REQUEST_METHOD']=='POST'){
  if(empty($_SESSION['user'])){ echo '<div class="card">Please <a href="login.php">login</a> first.</div>'; include 'footer.php'; exit; }
  $buyer_id = $_SESSION['user']['id'];
  $product_id = (int)$_POST['product_id'];
  $qty = (int)$_POST['qty'];
  $message = $_POST['message'] ?? null;
  $p = $pdo->prepare('SELECT supplier_id FROM products WHERE id=?'); $p->execute([$product_id]); $row = $p->fetch();
  if(!$row){ echo '<div class="card">Invalid product.</div>'; include 'footer.php'; exit; }
  $supplier_id = $row['supplier_id'];
  $stmt = $pdo->prepare('INSERT INTO quotations (product_id,buyer_id,supplier_id,qty,message) VALUES (?,?,?,?,?)');
  $stmt->execute([$product_id,$buyer_id,$supplier_id,$qty,$message]);
  echo '<div class="card">Quotation request sent. <button class="btn" onclick="window.location.href=\\'index.php\\'">Back to Marketplace</button></div>';
  include 'footer.php'; exit;
}

if(empty($_SESSION['user'])){ echo '<div class="card">Please <a href="login.php">login</a> first.</div>'; include 'footer.php'; exit; }
$uid = $_SESSION['user']['id'];
$stmt = $pdo->prepare('SELECT q.*, p.title FROM quotations q JOIN products p ON q.product_id=p.id WHERE q.buyer_id=? OR q.supplier_id=? ORDER BY q.created_at DESC');
$stmt->execute([$uid,$uid]);
$qs = $stmt->fetchAll();
?>
<div class="card"><h3>Your Quotations</h3>
  <div class="grid">
  <?php foreach($qs as $q): ?>
    <div>
      <h4><?=htmlspecialchars($q['title'])?></h4>
      <p class="small">Qty: <?=$q['qty']?> • Status: <?=$q['status']?></p>
      <p><?=nl2br(htmlspecialchars($q['message']))?></p>
    </div>
  <?php endforeach; ?>
  </div>
</div>
<?php include 'footer.php'; ?>
