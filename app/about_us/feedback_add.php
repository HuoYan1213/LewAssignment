<?php
session_start(); 
require '_base.php';

$error = null;
$success = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = $_SESSION['id'] ?? '';
  $message = $_POST['message'] ?? '';
  $rating = $_POST['rating'] ?? '';

  if ($id && $message && $rating) {
    $stm = $_db->prepare('INSERT INTO feedback (id, message, rating, status) VALUES (?, ?, ?, ?)');
    $stm->execute([$id, $message, $rating, 'Pending']);
    $success = "Thank you for your feedback!";
  } else {
    $error = "Please fill in all fields.";
  }
}
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
    <h1>📝 Feedback</h1>
  </div>
  <div class="header-right">
    <a href="us.php" class="home-btn">Back</a>
  </div>
</header>

<div class="container">
  <h1>Submit Feedback</h1>

  <?php if ($error): ?>
    <p class="error_msg"><?= $error ?></p>
  <?php endif; ?>

  <?php if ($success): ?>
    <p class="success_msg"><?= $success ?></p>
  <?php endif; ?>

  <form method="post">
    <table>
      <tr>
        <th>FEEDBACK</th>
        <td><textarea name="message" class="search_box" placeholder="Your Feedback" required></textarea><br></td>
      </tr>
      <tr>
        <th>RATING</th>
        <td>
          <select name="rating" class="search_box" required>
            <option value="">Select Rating</option>
            <option value="1">⭐</option>
            <option value="2">⭐⭐</option>
            <option value="3">⭐⭐⭐</option>
            <option value="4">⭐⭐⭐⭐</option>
            <option value="5">⭐⭐⭐⭐⭐</option>
          </select><br>
        </td>
      </tr>
    </table>
    <button type="submit" class="btn">Submit</button>
  </form>

  <br>
</div>
