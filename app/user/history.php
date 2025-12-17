<?php
require '../base.php';
require 'head_in_user.php';

// ----------------------------------------------------------------------------
// Authenticated users
auth();

// Set page title
$_title = 'User | History';

// Get current user ID from session
$user_id = $_SESSION['user']->user_id;

// Fetch orders for this user
$sql = "SELECT order_id, total_amount, status, order_date 
        FROM orders 
        WHERE user_id = ? 
        ORDER BY order_date DESC";
$stm = $_db->prepare($sql);
$stm->execute([$user_id]);
$orders = $stm->fetchAll(PDO::FETCH_OBJ);
?>

<div class="page-wrapper">
    <h1 class="history-title">My Order History</h1>

    <?php if (empty($orders)): ?>
        <p>You have no orders yet.</p>
    <?php else: ?>
        <table class="order-history">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?= htmlspecialchars($order->order_id) ?></td>
                    <td>RM <?= number_format($order->total_amount, 2) ?></td>
                    <td>
                        <span class="status <?= strtolower($order->status) ?>">
                            <?= htmlspecialchars($order->status) ?>
                        </span>
                    </td>
                    <td><?= date('d M Y H:i', strtotime($order->order_date)) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<style>
body {
    margin: 0;
    padding: 0;
    background: linear-gradient(to bottom right, #f8f0ee, #fdf6f3);
    font-family: 'Segoe UI', 'Helvetica Neue', sans-serif;
    color: #532323;
}

.page-wrapper {
    padding: 30px;
    margin-top: 20px;
}

h1.history-title {
    text-align: center;
    font-size: 36px;
    color: #9e2a2a;
    margin-bottom: 30px;
    font-weight: bold;
    position: relative;
}

h1.history-title::after {
    content: '';
    display: block;
    width: 80px;
    height: 5px;
    background: #9e2a2a;
    margin: 10px auto 0;
    border-radius: 3px;
}

.order-history {
    width: 100%;
    border-collapse: collapse;
    background-color: #fdf6f3;
    border-radius: 12px;
    overflow: hidden;
    margin-top: 20px;
    box-shadow: 0 6px 20px rgba(131,31,31,0.08);
}

.order-history th {
    background-color: #e2b4b4;
    color: #532323;
    font-weight: 600;
    font-size: 18px;
    padding: 15px;
}

.order-history td {
    padding: 15px 20px;
    text-align: left;
    border-bottom: 1px solid #ebd5d5;
    font-size: 16px;
}

.order-history tr:hover {
    background-color: #f3e1e1;
}

.status {
    font-weight: bold;
    padding: 8px 12px;
    border-radius: 8px;
    display: inline-block;
}

.status.pending {
    background-color: #f9e3e3;
    color: #8f2222;
}

.status.paid {
    background-color: #fff0b3;
    color: #b38600;
}

.status.shipped {
    background-color: #e3f9e7;
    color: #1f8f3e;
}

.status.completed {
    background-color: #d9f0ff;
    color: #0f4c81;
}

.status.cancelled {
    background-color: #fbeaea;
    color: #b82e2e;
}
</style>
