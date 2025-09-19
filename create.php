<?php
require 'db.php';

if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$uid = $_SESSION['user_id'];
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $desc = trim($_POST['description'] ?? '');
    $start_price = (float) ($_POST['start_price'] ?? 0);
    $start_time = $_POST['start_time'] ?? date('Y-m-d H:i:s');
    $end_time = $_POST['end_time'] ?? date('Y-m-d H:i:s', strtotime('+1 day'));

    if ($title && $end_time > $start_time) {

        // Insert auction into auctions table
        $stmt = $pdo->prepare("INSERT INTO auctions (user_id, title, description, start_price, current_price, start_time, end_time) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$uid, $title, $desc, $start_price, $start_price, $start_time, $end_time]);
        $auction_id = $pdo->lastInsertId();

        // Handle image uploads
        if (!empty($_FILES['images']['name'][0])) {
            $uploadDir = 'uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            foreach ($_FILES['images']['tmp_name'] as $index => $tmpName) {
                $filename = basename($_FILES['images']['name'][$index]);
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $newName = uniqid() . "." . $ext;
                $target = $uploadDir . $newName;

                if (move_uploaded_file($tmpName, $target)) {
                    // INSERT into auction_images table
                    $pdo->prepare("INSERT INTO auction_images (auction_id, image_path) VALUES (?, ?)")
                        ->execute([$auction_id, $target]);
                }
            }
        }

        header('Location: index.php');
        exit;
    } else {
        $error = "Invalid data. Make sure title is filled and end time is after start time.";
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Create Auction</title>
    <link rel="stylesheet" href="css/create.css">
</head>

<body>
    <form method="post" enctype="multipart/form-data">
        <h2>Create Auction</h2>
        <?php if (!empty($error))
            echo "<p class='error'>" . htmlspecialchars($error) . "</p>"; ?>

        <input name="title" placeholder="Title" required>
        <textarea name="description" placeholder="Description"></textarea>
        <input name="start_price" type="number" step="0.01" placeholder="Start price" required>

        <label>Start time <input name="start_time" type="datetime-local"></label>
        <label>End time <input name="end_time" type="datetime-local" required></label>

        <label>Upload Images</label>
        <input type="file" name="images[]" multiple accept="image/*">

        <button type="submit">Create</button>
    </form>
</body>

</html>