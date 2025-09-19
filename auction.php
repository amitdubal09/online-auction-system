<?php
require 'db.php';
$uid = $_SESSION['user_id'] ?? null;
$auction_id = (int) ($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT a.*, u.username FROM auctions a JOIN users u ON a.user_id=u.id WHERE a.id = ?");
$stmt->execute([$auction_id]);
$auction = $stmt->fetch();
if (!$auction) {
    exit('Auction not found');
}

// fetch latest bids
$bids = $pdo->prepare("SELECT b.*, u.username FROM bids b JOIN users u ON b.user_id=u.id WHERE b.auction_id = ? ORDER BY b.bid_time DESC");
$bids->execute([$auction_id]);
$bids = $bids->fetchAll();
?>
<!doctype html>
<html>

<head>
    <link rel="stylesheet" href="css/auction.css">
</head>

<body>
    <main>
        <h2><?= htmlspecialchars($auction['title']) ?></h2>
        <p><?= nl2br(htmlspecialchars($auction['description'])) ?></p>
        <p>Current price: ₹<span id="current-price"><?= number_format($auction['current_price'], 2) ?></span></p>
        <p>Ends: <?= htmlspecialchars($auction['end_time']) ?></p>

        <?php if ($uid): ?>
            <div id="bid-section">
                <input id="bid-amount" type="number" step="0.01" placeholder="Your bid (higher than current)">
                <button id="bid-btn">Place bid</button>
                <p id="bid-msg"></p>
            </div>
        <?php else: ?>
            <p><a href="login.php">Login</a> to bid.</p>
        <?php endif; ?>

        <h3>Bids</h3>
        <ul id="bids-list">
            <?php foreach ($bids as $b): ?>
                <li><?= htmlspecialchars($b['username']) ?> — ₹<?= number_format($b['amount'], 2) ?> (<?= $b['bid_time'] ?>)
                </li>
            <?php endforeach; ?>
        </ul>
    </main>

    <script>
        const auctionId = <?= json_encode($auction_id) ?>;
    </script>
    <script src="script.js"></script>
</body>

</html>