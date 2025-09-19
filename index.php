<?php
require 'db.php';
$user_id = $_SESSION['user_id'] ?? null;

// fetch active auctions (current time between start and end)
$stmt = $pdo->query("SELECT a.*, u.username FROM auctions a JOIN users u ON a.user_id=u.id ORDER BY a.end_time ASC");
$auctions = $stmt->fetchAll();
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Auction Home</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <header>
        <h1>Mini Auction</h1>
        <nav>
            <?php if ($user_id): ?>
                <a href="create.php">Create Auction</a>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a> | <a href="register.php">Register</a>
            <?php endif; ?>
        </nav>
    </header>

    <main>
        <section class="auctions">
            <?php foreach ($auctions as $a):
                $now = new DateTime();
                $end = new DateTime($a['end_time']);
                $is_active = $now < $end;
                ?>
                <a href="view_auction.php?id=<?= $a['id'] ?>" class="auction-card">
                    <h3><?= htmlspecialchars($a['title']) ?></h3>
                    <p><?= nl2br(htmlspecialchars($a['description'])) ?></p>
                    <p><strong>Seller:</strong> <?= htmlspecialchars($a['username']) ?></p>
                    <p><strong>Current price:</strong> ₹<?= number_format($a['current_price'], 2) ?></p>
                    <p><strong>Ends:</strong> <?= htmlspecialchars($a['end_time']) ?>     <?= $is_active ? '' : '(Ended)' ?></p>
                </a>
            <?php endforeach; ?>
        </section>
    </main>
    <script src="script.js"></script>
</body>

</html>