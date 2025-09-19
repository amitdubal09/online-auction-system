<?php
require 'db.php';
$id = (int) ($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT current_price FROM auctions WHERE id = ?");
$stmt->execute([$id]);
$r = $stmt->fetch();
header('Content-Type: application/json');
if ($r)
    echo json_encode(['current_price' => $r['current_price']]);
else
    echo json_encode(['error' => 'not found']);
