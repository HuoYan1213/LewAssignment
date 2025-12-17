<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $_title ?? 'Profile' ?></title>
    <link rel="shortcut icon" href="../images/logo1.png">
    <link rel="stylesheet" href="../css/app.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="../js/app.js"></script>
</head>
<body>
    <div id="info"><?= temp('info') ?></div>

    <header>
    <h1><img src="../images/logo1.png"onclick="window.location.href='../main.php'"><a href="../main.php">TARUMT Cafeteria Food Ordering System</a></h1>

<?php if ($_user): ?>
    <div class="user-info">
        <?= $_user->role ?>
        <img src="images_user/<?= $_user->photo ?>">
    </div>
<?php endif ?>
</header>

<nav>
<div class="nav-left">
    <a href="../main.php">Home</a>

    <?php if ($_user): ?>
                <a href="../top_sale.php">Top Sale</a>
            <?php endif ?>
    
    <?php if ($_user): ?>
        <a href="../product/product.php">Product</a>
    <?php endif ?>

    <?php if ($_user): ?>
        <a href="../Cart/cart.php">Cart</a>
    <?php endif ?>
    
    <?php if ($_user?->role == 'admin'): ?>
        <a href="../staff/home_pagae.php">Staff</a>
    <?php endif ?>
    </div>
<div class="nav-right">
<a href="../about_us/us.php">About Us</a>
    <div class="dropdown">
        <a href="profile.php"><?= $_user->name ?></a>
                <div class="dropdown-content"> 
                    <?php if ($_user): ?>   
                    <a href="profile.php">Profile</a>
                    <a href="password.php">Password</a>
                    <a href="history.php">History</a>
                    <a href="../logout.php">Logout</a>
                    <?php endif ?>
                </div>
    </div>
   
</div>
</nav>

