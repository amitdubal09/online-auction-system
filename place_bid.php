<?php
require 'db.php';
header('Content-Type: application/json');
if (empty($_SESSION['user_id'])) {
    echo json_encode(['ok' => false, 'msg' => 'Not logged in']);
    exit;
}
$uid = $_SESSION['user_id'];
$auction_id = (int) ($_POST['auction_id'] ?? 0);
$amount = (float) ($_POST['amount'] ?? 0);

// start transaction to avoid race conditions
$pdo->beginTransaction();
try {
    // lock auction row FOR UPDATE
    $stmt = $pdo->prepare("SELECT * FROM auctions WHERE id = ? FOR UPDATE");
    $stmt->execute([$auction_id]);
    $auction = $stmt->fetch();
    if (!$auction)
        throw new Exception('Auction not found.');
    $now = new DateTime();
    if ($now > new DateTime($auction['end_time']))
        throw new Exception('Auction ended.');
    if ($amount <= $auction['current_price'])
        throw new Exception('Bid must be higher than current price.');

    // insert bid
    $stmt = $pdo->prepare("INSERT INTO bids (auction_id, user_id, amount) VALUES (?, ?, ?)");
    $stmt->execute([$auction_id, $uid, $amount]);

    // update auction current_price
    $stmt = $pdo->prepare("UPDATE auctions SET current_price = ? WHERE id = ?");
    $stmt->execute([$amount, $auction_id]);

    $pdo->commit();
    echo json_encode(['ok' => true, 'msg' => 'Bid placed', 'new_price' => $amount]);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['ok' => false, 'msg' => $e->getMessage()]);
}
