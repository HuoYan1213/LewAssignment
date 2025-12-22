<?php
require_once '../base.php';

$edit_id = $_GET['edit_id'] ?? null;
$order = null;
$errors = [];  // array to store validation errors

if ($edit_id) {
    $stm = $_db->prepare('SELECT * FROM orders WHERE order_id = ?');
    $stm->execute([$edit_id]);
    $order = $stm->fetch();
    if (!$order) {
        die('Order not found.');
    }
}

$allowed_statuses = ['Pending', 'Paid', 'Shipped', 'Completed', 'Cancelled'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $status = $_POST['status'] ?? '';
    
    // Validate status
    if (!in_array($status, $allowed_statuses)) {
        $errors['status'] = 'Invalid status selected.';
    }

    if ($edit_id) {
        // Edit Order Status only
        if (empty($errors)) {
            $_db->prepare('UPDATE orders SET status = ? WHERE order_id = ?')
                 ->execute([$status, $edit_id]);
            header('Location: order.php?action=edited');
            exit;
        }
    } else {
        // Add New Order
        $user_id = $_POST['id'] ?? '';
        $total_amount = $_POST['total_amount'] ?? '';

        // Validate User ID
        if (empty($user_id)) {
            $errors['id'] = 'User ID is required.';
        } else {
            $stm = $_db->prepare('SELECT COUNT(*) FROM users WHERE user_id = ?');
            $stm->execute([$user_id]);
            if ($stm->fetchColumn() == 0) {
                $errors['id'] = 'User ID does not exist.';
            }
        }

        // Validate total amount
        if (empty($total_amount)) {
            $errors['total_amount'] = 'Total Amount is required.';
        } elseif (!is_numeric($total_amount) || $total_amount <= 0) {
            $errors['total_amount'] = 'Total Amount must be a positive number.';
        }

        // Insert if no errors
        if (empty($errors)) {
            // Auto-generate order_id
            $stm = $_db->query("SELECT order_id FROM orders ORDER BY order_id DESC LIMIT 1");
            $last = $stm->fetch();
            if ($last) {
                $last_id_num = (int)substr($last->order_id, 1); // remove 'O' and convert to number
                $new_id_num = $last_id_num + 1;
            } else {
                $new_id_num = 1;
            }
            $new_order_id = 'O' . str_pad($new_id_num, 4, '0', STR_PAD_LEFT);

            $_db->prepare('INSERT INTO orders (order_id, user_id, total_amount, status) VALUES (?, ?, ?, ?)')
                 ->execute([$new_order_id, $user_id, $total_amount, $status]);
            header('Location: order.php?action=added');
            exit;
        }
    }
}
?>

<?php include '_head.php'; ?>

<div class="container">
    <h1><?= $edit_id ? 'Edit Order Status' : 'Add New Order' ?></h1>

    <?php if ($errors): ?>
        <div class="error_msg">
            <ul>
                <?php foreach ($errors as $e): ?>
                    <li><?= htmlspecialchars($e) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post">
        <?php if ($edit_id): ?>
            <p><strong>Order ID:</strong> <?= $order->order_id ?></p>
            <p><strong>Member ID:</strong> <?= $order->user_id ?></p>
            <p><strong>Total Amount:</strong> $<?= number_format($order->total_amount, 2) ?></p>
        <?php else: ?>
            <p><strong>Order ID:</strong> (auto-generated)</p>
            <input type="text" name="id" class="search_box" placeholder="User ID" value="<?= htmlspecialchars($_POST['id'] ?? '') ?>" required><br>
            <input type="number" step="0.01" name="total_amount" class="search_box" placeholder="Total Amount" value="<?= htmlspecialchars($_POST['total_amount'] ?? '') ?>" required><br>
        <?php endif; ?>

        <select name="status" class="search_box" required>
            <?php
                $current = $_POST['status'] ?? ($order->status ?? 'Pending');
                foreach ($allowed_statuses as $s) {
                    $selected = ($current === $s) ? 'selected' : '';
                    echo "<option value='$s' $selected>$s</option>";
                }
            ?>
        </select><br>

        <button type="submit" class="btn"><?= $edit_id ? 'Update Status' : 'Add Order' ?></button>
    </form>
</div>
