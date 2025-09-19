<?php
require 'db.php';
$uid = $_SESSION['user_id'] ?? null;
$auction_id = (int) ($_GET['id'] ?? 0);

if (!$auction_id) {
    exit("Invalid auction ID.");
}

// Fetch auction details
$stmt = $pdo->prepare("SELECT a.*, u.username 
                       FROM auctions a 
                       JOIN users u ON a.user_id = u.id 
                       WHERE a.id = ?");
$stmt->execute([$auction_id]);
$auction = $stmt->fetch();

if (!$auction) {
    exit("Auction not found.");
}

// Fetch auction images from auction_images table
$imagesStmt = $pdo->prepare("SELECT image_path FROM auction_images WHERE auction_id = ?");
$imagesStmt->execute([$auction_id]);
$images = $imagesStmt->fetchAll(PDO::FETCH_COLUMN); // fetch only the image_path column

// Fetch bid history
$bidsStmt = $pdo->prepare("SELECT b.*, u.username 
                           FROM bids b 
                           JOIN users u ON b.user_id = u.id 
                           WHERE b.auction_id = ? 
                           ORDER BY b.bid_time DESC");
$bidsStmt->execute([$auction_id]);
$bids = $bidsStmt->fetchAll();

// Handle new bid submission
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $uid) {
    $amount = (float) ($_POST['bid_amount'] ?? 0);

    if ($amount > $auction['current_price']) {
        // Insert bid
        $pdo->prepare("INSERT INTO bids (auction_id, user_id, amount) VALUES (?, ?, ?)")
            ->execute([$auction_id, $uid, $amount]);

        // Update current price
        $pdo->prepare("UPDATE auctions SET current_price = ? WHERE id = ?")
            ->execute([$amount, $auction_id]);

        // Refresh page
        header("Location: view_auction.php?id=" . $auction_id);
        exit;
    } else {
        $error = "Your bid must be higher than the current price.";
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($auction['title']) ?> - Auction</title>
    <link rel="stylesheet" href="css/viewauction.css">
</head>

<body>
    <header>
        <h1>Mini Auction</h1>
        <nav>
            <a href="index.php">Home</a>
            <?php if ($uid): ?>
                <a href="create.php">Create Auction</a>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a> | <a href="register.php">Register</a>
            <?php endif; ?>
        </nav>
    </header>

    <main class="auction-details">
        <h2><?= htmlspecialchars($auction['title']) ?></h2>

        <!-- Display images -->
        <?php if ($images): ?>
            <div class="auction-images">
                <?php foreach ($images as $img): ?>
                    <img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($auction['title']) ?>">
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <p><strong>Description:</strong></p>
        <p><?= nl2br(htmlspecialchars($auction['description'])) ?></p>
        <p><strong>Seller:</strong> <?= htmlspecialchars($auction['username']) ?></p>
        <p><strong>Current Price:</strong> ₹<?= number_format($auction['current_price'], 2) ?></p>
        <p><strong>Ends:</strong> <?= htmlspecialchars($auction['end_time']) ?></p>

        <?php if ($uid): ?>
            <form method="post" class="bid-form">
                <?php if ($error): ?>
                    <p class="error"><?= htmlspecialchars($error) ?></p>
                <?php endif; ?>
                <input type="number" name="bid_amount" step="0.01" placeholder="Enter your bid" required>
                <button type="submit">Place Bid</button>
            </form>
        <?php else: ?>
            <p><a href="login.php">Login</a> to place a bid.</p>
        <?php endif; ?>

        <h3>Bid History</h3>
        <ul class="bid-history">
            <?php if ($bids): ?>
                <?php foreach ($bids as $b): ?>
                    <li>
                        <strong><?= htmlspecialchars($b['username']) ?></strong> — ₹<?= number_format($b['amount'], 2) ?>
                        (<?= $b['bid_time'] ?>)
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li>No bids yet. Be the first!</li>
            <?php endif; ?>
        </ul>
    </main>
</body>

</html>