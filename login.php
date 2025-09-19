<?php
require 'db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        header('Location: index.php');
        exit;
    } else
        $error = "Invalid credentials.";
}
?>
<!doctype html>
<html>

<head>
    <link rel="stylesheet" href="css/login.css">
</head>

<body>
    <form method="post">
        <h2>Login</h2>
        <?php if (!empty($error))
            echo "<p class='error'>" . htmlspecialchars($error) . "</p>"; ?>
        <input name="email" type="email" placeholder="Email" required>
        <input name="password" type="password" placeholder="Password" required>
        <button type="submit">Login</button>
        <p><a href="register.php">Register</a></p>
    </form>
</body>

</html>