<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'db.php';
include 'header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $company  = trim($_POST['company']) ?? null;
    $password = trim($_POST['password']);
    $role     = $_POST['role'];

    // Validate required fields
    if (empty($name) || empty($email) || empty($password)) {
        echo "<div class='card'>Please fill all required fields.</div>";
        include 'footer.php';
        exit;
    }

    // Check if email already exists
    $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $check->execute([$email]);
    if ($check->rowCount() > 0) {
        echo "<div class='card'>This email is already registered. Try login instead.</div>";
        include 'footer.php';
        exit;
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user safely
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role, company) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$name, $email, $hashed_password, $role, $company]);

    echo "<div class='card'>Signup successful! 
            <button class='btn' onclick=\"window.location.href='login.php'\">Login</button>
          </div>";

    include 'footer.php';
    exit;
}
?>

<div class="card" style="max-width:600px;margin:auto;">
    <h2>Create Your Account</h2>
    <form method="post">
        <label>Full Name</label>
        <input class="input" name="name" required>

        <label>Company Name (optional)</label>
        <input class="input" name="company">

        <label>Email Address</label>
        <input class="input" name="email" type="email" required>

        <label>Password</label>
        <input class="input" name="password" type="password" required>

        <label>Account Type</label>
        <select class="input" name="role">
            <option value="buyer">Buyer</option>
            <option value="supplier">Supplier</option>
        </select>

        <button class="btn" type="submit" style="width:100%;margin-top:10px;">
            Create Account
        </button>

        <p style="text-align:center;margin-top:12px;">
            Already have an account? <a href="login.php">Login</a>
        </p>
    </form>
</div>

<?php include 'footer.php'; ?>
