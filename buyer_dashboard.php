<?php include 'header.php'; if(empty($_SESSION['user'])||$_SESSION['user']['role']!='buyer'){ echo '<div class="card">Only buyers.</div>'; include 'footer.php'; exit; }
echo '<div class="card"><h3>Buyer Dashboard</h3><p class="small">Welcome, '.htmlspecialchars($_SESSION['user']['name']).'</p></div>'; include 'footer.php'; ?>
