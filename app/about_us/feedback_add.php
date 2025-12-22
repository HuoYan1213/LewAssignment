<?php
require '../base.php';
auth(); 

$error = null;
$success = null;

if (is_post()) {
  $id = $_user->user_id;
  $message = req('message');
  $rating = req('rating');

  if ($id && $message && $rating) {
    // Generate feedback_id manually
    $max = $_db->query("SELECT MAX(feedback_id) FROM feedback")->fetchColumn();
    $feedback_id = $max ? (int)$max + 1 : 1;

    // Ensure unique ID (skip existing IDs)
    while (is_exists($feedback_id, 'feedback', 'feedback_id')) {
        $feedback_id++;
    }

    $stm = $_db->prepare('INSERT INTO feedback (feedback_id, user_id, message, rating, status) VALUES (?, ?, ?, ?, ?)');
    $stm->execute([$feedback_id, $id, $message, $rating, 'Pending']);
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
<body>

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
    <script>
        alert('<?= $success ?>');
        window.location.href = 'feedback_view.php';
    </script>
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
