<?php include 'header.php';
if(empty($_SESSION['user']) || $_SESSION['user']['role']!='supplier'){ echo '<div class="card">Only suppliers can add products.</div>'; include 'footer.php'; exit; }
if($_SERVER['REQUEST_METHOD']=='POST'){
  $title = $_POST['title']; $desc = $_POST['description']; $price = floatval($_POST['price']); $moq = intval($_POST['min_order_quantity']); $stock = intval($_POST['stock']); $cat = intval($_POST['category']);
  $stmt = $pdo->prepare('INSERT INTO products (supplier_id,category_id,title,description,price,min_order_quantity,stock) VALUES (?,?,?,?,?,?,?)');
  $stmt->execute([$_SESSION['user']['id'],$cat,$title,$desc,$price,$moq,$stock]);
  echo '<div class="card">Product added. <button class="btn" onclick="window.location.href=\\'supplier_manage.php\\'">Manage Products</button></div>';
  include 'footer.php'; exit;
}
?>
<div class="card"><h3>Add Product</h3>
<form method="post">
  <div class="form-row"><label>Title</label><input class="input" name="title" required></div>
  <div class="form-row"><label>Description</label><textarea class="input" name="description"></textarea></div>
  <div class="form-row"><label>Price</label><input class="input" name="price" required></div>
  <div class="form-row"><label>MOQ</label><input class="input" name="min_order_quantity" value="1"></div>
  <div class="form-row"><label>Stock</label><input class="input" name="stock" value="0"></div>
  <div class="form-row"><label>Category</label>
    <select class="input" name="category">
      <?php foreach($pdo->query('SELECT * FROM categories') as $c) echo '<option value="'.$c['id'].'">'.$c['name'].'</option>'; ?>
    </select>
  </div>
  <button class="btn" type="submit">Save</button>
</form>
</div>
<?php include 'footer.php'; ?>
