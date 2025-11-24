<?php
include 'db.php';
session_start();
if(!isset($_SESSION['user_id'])){ header("Location: login.php"); exit(); }

$user_id = $_SESSION['user_id'];
$msg = '';

if(isset($_POST['send'])){
    $receiver_id = $_POST['receiver_id'];
    $message = $_POST['message'];
    $stmt = $conn->prepare("INSERT INTO messages(sender_id,receiver_id,message) VALUES(:sender,:receiver,:message)");
    $stmt->execute(['sender'=>$user_id,'receiver'=>$receiver_id,'message'=>$message]);
}

$users = $conn->prepare("SELECT id,name FROM users WHERE id != :id");
$users->execute(['id'=>$user_id]);
$all_users = $users->fetchAll(PDO::FETCH_ASSOC);

$messages = $conn->prepare("SELECT m.*, u1.name as sender_name, u2.name as receiver_name FROM messages m 
                            JOIN users u1 ON m.sender_id=u1.id 
                            JOIN users u2 ON m.receiver_id=u2.id 
                            WHERE m.sender_id=:id OR m.receiver_id=:id ORDER BY m.created_at DESC");
$messages->execute(['id'=>$user_id]);
$all_messages = $messages->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Messages - Alibaba Clone</title>
    <style>
        body{font-family:Arial,sans-serif; background:#f4f4f4;}
        .container{width:90%; margin:20px auto;}
        .message-box{background:white; padding:15px; border-radius:10px; margin-bottom:10px; box-shadow:0 2px 5px rgba(0,0,0,0.1);}
        select, textarea{width:100%; padding:10px; margin:10px 0; border-radius:5px; border:1px solid #ccc;}
        input[type=submit]{padding:10px 15px; background:#0073e6; color:white; border:none; border-radius:5px; cursor:pointer;}
        input[type=submit]:hover{background:#005bb5;}
        h3{color:#0073e6;}
    </style>
</head>
<body>
<div class="container">
    <h2>Send Message</h2>
    <form method="post">
        <select name="receiver_id" required>
            <option value="">Select User</option>
            <?php foreach($all_users as $u): ?>
                <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['name']) ?></option>
            <?php endforeach; ?>
        </select>
        <textarea name="message" placeholder="Your message..." required></textarea>
        <input type="submit" name="send" value="Send">
    </form>

    <h2>Messages</h2>
    <?php foreach($all_messages as $m): ?>
    <div class="message-box">
        <p><strong>From:</strong> <?= htmlspecialchars($m['sender_name']) ?> | <strong>To:</strong> <?= htmlspecialchars($m['receiver_name']) ?></p>
        <p><?= htmlspecialchars($m['message']) ?></p>
        <small><?= $m['created_at'] ?></small>
    </div>
    <?php endforeach; ?>
</div>
</body>
</html>
