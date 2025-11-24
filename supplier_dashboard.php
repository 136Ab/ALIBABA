<?php include 'header.php'; if(empty($_SESSION['user'])||$_SESSION['user']['role']!='supplier'){ echo '<div class="card">Only suppliers.</div>'; include 'footer.php'; exit; }
echo '<div class="card"><h3>Supplier Dashboard</h3><p class="small">Welcome, '.htmlspecialchars($_SESSION['user']['name']).'</p>
<div><button class="btn" onclick="window.location.href=\\'supplier_add_product.php\\'">Add Product</button>
<button class="btn" onclick="window.location.href=\\'supplier_manage.php\\'">Manage Products</button></div></div>';
include 'footer.php'; ?>
