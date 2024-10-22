<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: /login/login.php');
    exit;
}

$restaurant_id = '';
$name = '';
$address = '';
$phone = '';
$email = '';
$added_by = $_SESSION['user_id']; // Store the logged-in user ID as the one adding or updating the restaurant

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM restaurants WHERE restaurant_id = ?");
    $stmt->execute([$id]);
    $restaurant = $stmt->fetch();

    $restaurant_id = $restaurant['restaurant_id'];
    $name = $restaurant['name'];
    $address = $restaurant['address'];
    $phone = $restaurant['phone_number'];
    $email = $restaurant['email'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];

    if ($restaurant_id) {
        // Update restaurant and set the last_updated field to the current timestamp
        $stmt = $pdo->prepare("UPDATE restaurants SET name = ?, address = ?, phone_number = ?, email = ?, last_updated = NOW() WHERE restaurant_id = ?");
        $stmt->execute([$name, $address, $phone, $email, $restaurant_id]);
    } else {
        // Insert a new restaurant and set the added_by field to the logged-in user and last_updated to NOW
        $stmt = $pdo->prepare("INSERT INTO restaurants (name, address, phone_number, email, added_by, last_updated) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$name, $address, $phone, $email, $added_by]);
    }

    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $restaurant_id ? 'Edit' : 'Add'; ?> Restaurant</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="#">
            <img src="/img/logo.png" alt="Restaurant Manager Logo" style="width: 50px; height: 50px; margin-right: 10px;">
            Resto Lookups
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>" href="index.php">🏠 Home</a>
            </li>
                <li class="nav-item">
                    <a class="nav-link" href="edit.php">➕ Add Restaurant</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/login/logout.php">
                        <i class="fas fa-sign-out-alt"></i> 🔒 Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="container mt-5">
    <h1 class="text-center mb-4"><?php echo $restaurant_id ? 'Edit' : 'Add'; ?> Restaurant</h1>
    
    <div class="card restaurant-card">
        <div class="card-body">
            <form method="POST" action="">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" class="form-control" id="address" name="address" value="<?php echo htmlspecialchars($address); ?>" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary btn-lg">Save</button>
                    <a href="index.php" class="btn btn-secondary btn-lg">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
