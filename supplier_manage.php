<?php include 'header.php';
if(empty($_SESSION['user']) || $_SESSION['user']['role']!='supplier'){ echo '<div class="card">Only suppliers can view this.</div>'; include 'footer.php'; exit; }
$stm = $pdo->prepare('SELECT * FROM products WHERE supplier_id=?'); $stm->execute([$_SESSION['user']['id']]); $ps = $stm->fetchAll();
?>
<div class="card"><h3>Your Products</h3>
  <?php foreach($ps as $p): ?>
    <div style="border-bottom:1px solid #eee;padding:8px 0">
      <strong><?=htmlspecialchars($p['title'])?></strong>
      <p class="small">Price: <?=number_format($p['price'],2)?> • Stock: <?=$p['stock']?></p>
      <div><button class="btn" onclick="window.location.href='product.php?id=<?=$p['id']?>'">View</button></div>
    </div>
  <?php endforeach; ?>
</div>
<?php include 'footer.php'; ?>
