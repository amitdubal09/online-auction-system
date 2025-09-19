<?php
require 'db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($username && $email && $password) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
        try {
            $stmt->execute([$username, $email, $hash]);
            $_SESSION['user_id'] = $pdo->lastInsertId();
            header('Location: index.php');
            exit;
        } catch (PDOException $e) {
            $error = "Registration failed: " . $e->getMessage();
        }
    } else
        $error = "Fill all fields.";
}
?>
<!-- simple registration form -->
<!doctype html>
<html>

<head>
    <link rel="stylesheet" href="css/register.css">
</head>

<body>
    <form method="post">
        <h2>Register</h2>
        <?php if (!empty($error))
            echo "<p class='error'>" . htmlspecialchars($error) . "</p>"; ?>
        <input name="username" placeholder="Username" required>
        <input name="email" type="email" placeholder="Email" required>
        <input name="password" type="password" placeholder="Password" required>
        <button type="submit">Register</button>
        <p><a href="login.php">Login</a></p>
    </form>
</body>

</html>