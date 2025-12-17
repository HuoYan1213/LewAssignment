<?php
require '_base.php';
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Member List</title>
    <link rel="stylesheet" href="../css/aboutUs.css">
</head>

<header class="header">
  <div class="header-left">
    <h1>FAQ</h1>
  </div>
  <div class="header-right">
    <a href="../main.php" class="home-btn">🏠 Home</a>
  </div>
</header>

<div class="faq-container">
    <div class="faq-introduction">
        <h2>✨Frequently Asked Questions (FAQ)✨</h2>
        <p>Welcome to our FAQ section! Here, you will find answers to the most common questions about our services, policies, and more. If you have any other inquiries, feel free to contact us directly.</p>
    </div>

    <div class="faq-gif">
        <img src="picture/faq.gif" alt="FAQ Animation" class="faq-gif-image">
    </div>
</div>

<div class="item-list">

    <div class="item-row">
        <div class="item-text">
        <h3><i class="icon">&#8594;</i> View Profile</h3>
        <p>Profile: This refers to the user's personal information.</p>
        </div>
        <a href="../user/profile.php" class="small-btn"><i class="icon">&#8594;</i> Go</a>
    </div>

    <div class="item-row">
        <div class="item-text">
        <h3><i class="icon">&#8594;</i> About Us</h3>
        <p>Introduction about the company or team.</p>
        </div>
        <a href="us.php" class="small-btn"><i class="icon">&#8594;</i> Go</a>
    </div>

    <div class="item-row">
        <div class="item-text">
        <h3><i class="icon">&#8594;</i> Terms and Policies</h3>
        <p>Terms of use and privacy policy.</p>
        </div>
        <a href="term_policies.php" class="small-btn"><i class="icon">&#8594;</i> Go</a>
    </div>

    <div class="item-row">
        <div class="item-text">
        <h3><i class="icon">&#8594;</i> Contact Us</h3>
        <p>Ways to reach us.</p>
        </div>
        <a href="contact.php" class="small-btn"><i class="icon">&#8594;</i> Go</a>
    </div>

    <div class="item-row">
        <div class="item-text">
        <h3><i class="icon">&#8594;</i> View Feedback</h3>
        <p>Check existing feedback from users.</p>
        </div>
        <a href="feedback_view.php" class="small-btn"><i class="icon">&#8594;</i> Go</a>
    </div>

    <div class="item-row">
        <div class="item-text">
        <h3><i class="icon">&#8594;</i> Add Feedback</h3>
        <p>Submit new feedback.</p>
        </div>
        <a href="feedback_add.php" class="small-btn"><i class="icon">&#8594;</i> Go</a>
    </div>

    <div class="item-row">
        <div class="item-text">
        <h3><i class="icon">&#8594;</i> Products Menu</h3>
        <p>Browse the available products to purchase.</p>
        </div>
        <a href="../product/product.php" class="small-btn"><i class="icon">&#8594;</i> Go</a>
    </div>

    <div class="item-row">
        <div class="item-text">
        <h3><i class="icon">&#8594;</i> My Cart</h3>
        <p>Check and manage the items you want to buy.</p>
        </div>
        <a href="../Cart/cart.php" class="small-btn"><i class="icon">&#8594;</i> Go</a>
    </div>

</div>
