<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: /login/login.php');
    exit;
}

// Using Aggregate Functions to fetch all the data needed
$stmt = $pdo->query("
    SELECT restaurants.*, users.username 
    FROM restaurants 
    JOIN users ON restaurants.added_by = users.user_id
");
$restaurants = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant List</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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

<div class="container mt-4">
    <div class="mb-4">
        <div class="input-group">
            <input type="text" id="search" class="form-control" placeholder="Search for restaurants..." aria-label="Search for restaurants">
            <div class="input-group-append">
                <span class="input-group-text">
                    <i class="fas fa-search"></i>
                </span>
            </div>
        </div>
    </div>
    <div class="row">
        <?php foreach ($restaurants as $restaurant): ?>
            <div class="col-md-4 mb-4">
                <div class="card restaurant-card">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($restaurant['name']); ?></h5>
                        <p class="card-text">
                            <strong>Address:</strong> <?php echo htmlspecialchars($restaurant['address']); ?><br>
                            <strong>Phone:</strong> <?php echo htmlspecialchars($restaurant['phone_number']); ?><br>
                            <strong>Email:</strong> <?php echo htmlspecialchars($restaurant['email']); ?><br>
                            <strong>Added By:</strong> <?php echo htmlspecialchars($restaurant['username']); ?><br>
                            <strong>Last Updated:</strong> <?php echo htmlspecialchars($restaurant['last_updated']); ?>
                        </p>
                        <div class="button-group">
                            <a href="view.php?id=<?php echo $restaurant['restaurant_id']; ?>" class="btn btn-primary">View</a>
                            <a href="edit.php?id=<?php echo $restaurant['restaurant_id']; ?>" class="btn btn-warning">Edit</a>
                            <a href="delete.php?id=<?php echo $restaurant['restaurant_id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this restaurant?');">Delete</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<footer>
    <p>&copy; <?php echo date("Y"); ?> Resto Lookups. All rights reserved.</p>
</footer>

<script>
    document.getElementById('search').addEventListener('input', function() {
        const searchQuery = this.value.toLowerCase();
        const cards = document.querySelectorAll('.card');

        cards.forEach(card => {
            const title = card.querySelector('.card-title').textContent.toLowerCase();
            if (title.includes(searchQuery)) {
                card.parentElement.style.display = ''; // Show card
            } else {
                card.parentElement.style.display = 'none'; // Hide card
            }
        });
    });
</script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
