<?php
require '_base.php';

$rating_filter = isset($_GET['rating']) ? (int)$_GET['rating'] : 0;

if ($rating_filter >= 1 && $rating_filter <= 5) {
  $stm = $_db->prepare('SELECT * FROM feedback WHERE rating = ? ORDER BY id DESC');
  $stm->execute([$rating_filter]);
} else {
  $stm = $_db->query('SELECT * FROM feedback ORDER BY id DESC');
}

$feedbacks = $stm->fetchAll();

$count_5 = 0;
$count_4 = 0;
$count_3 = 0;
$count_2 = 0;
$count_1 = 0;

foreach ($feedbacks as $f) {
  switch ((int)$f->rating) {
    case 5: $count_5++; break;
    case 4: $count_4++; break;
    case 3: $count_3++; break;
    case 2: $count_2++; break;
    case 1: $count_1++; break;
  }
}

$total_feedbacks = count($feedbacks);
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

  <h2 class="page-title">Customer Feedbacks</h2>
  <p class="subtitle">We truly appreciate every message from our customers. 🌟</p>

  <!-- Filter Form -->
  <form method="get" style="margin: 20px 0; text-align: center;">
    <label for="rating_filter" style="font-weight: bold; font-size: 18px;">Filter by Rating:</label>
    <select name="rating" id="rating_filter" class="rating-filter" onchange="this.form.submit()">
      <option value="">All Ratings</option>
      <option value="5" <?= (isset($_GET['rating']) && $_GET['rating'] == '5') ? 'selected' : '' ?>>5 Stars</option>
      <option value="4" <?= (isset($_GET['rating']) && $_GET['rating'] == '4') ? 'selected' : '' ?>>4 Stars</option>
      <option value="3" <?= (isset($_GET['rating']) && $_GET['rating'] == '3') ? 'selected' : '' ?>>3 Stars</option>
      <option value="2" <?= (isset($_GET['rating']) && $_GET['rating'] == '2') ? 'selected' : '' ?>>2 Stars</option>
      <option value="1" <?= (isset($_GET['rating']) && $_GET['rating'] == '1') ? 'selected' : '' ?>>1 Star</option>
    </select>
  </form>

  <!-- Feedback Summary -->
  <div class="feedback-stats">
    <h3>📊 Feedback Summary</h3>
    <table class="feedback-summary-table">
      <tr>
        <th>Total Feedbacks</th>
        <td><?= $total_feedbacks ?></td>
      </tr>
      <tr>
        <th>5⭐</th>
        <td><?= $count_5 ?></td>
      </tr>
      <tr>
        <th>4⭐</th>
        <td><?= $count_4 ?></td>
      </tr>
      <tr>
        <th>3⭐</th>
        <td><?= $count_3 ?></td>
      </tr>
      <tr>
        <th>2⭐</th>
        <td><?= $count_2 ?></td>
      </tr>
      <tr>
        <th>1⭐</th>
        <td><?= $count_1 ?></td>
      </tr>
    </table>
  </div>

  <!-- Feedback List -->
  <div class="feedback-container" style="margin-top: 30px;">
    <?php if ($total_feedbacks == 0): ?>
      <p class="feedback-empty">No feedback yet. Be the first to leave one!</p>
    <?php else: ?>
      <?php foreach ($feedbacks as $f): ?>
        <div class="feedback-item">
          <div class="feedback-header">
            <strong>User ID: <?= $f->id ?></strong>
            <span class="stars"><?= str_repeat('⭐', (int)$f->rating) ?></span>
          </div>
          <div class="feedback-message">
            <?= $f->message ?>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>

</div>