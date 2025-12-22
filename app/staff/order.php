<?php
require '../base.php';
require '_pager.php';

$order_id = $_GET['order_id'] ?? '';
$user_id  = $_GET['user_id'] ?? '';
$status   = $_GET['status'] ?? 'All';
$page     = $_GET['page'] ?? '1';
$sort_by  = $_GET['sort_by'] ?? 'order_id';
$sort_order = $_GET['sort_order'] ?? 'ASC';

// Delete order
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $_db->prepare('DELETE FROM orders WHERE order_id = ?')->execute([$delete_id]);
    header('Location: order.php?action=deleted');
    exit;
}

// Build query
$sql = 'SELECT * FROM orders WHERE 1';
$params = [];

if ($order_id !== '') {
    $sql .= ' AND order_id = ?';
    $params[] = $order_id;
}

if ($user_id !== '') {
    $sql .= ' AND user_id = ?';
    $params[] = $user_id;
}

if ($status !== 'All') {
    $sql .= ' AND status = ?';
    $params[] = $status;
}

$sql .= " ORDER BY $sort_by $sort_order";

$pager = new pager($sql, $params, '5', $page);
?>

<?php include '_head.php'; ?>

<?php if (isset($_GET['action'])): ?>
    <p class="success_msg">
        <?php
        if ($_GET['action'] == 'added') echo 'Order added successfully!';
        if ($_GET['action'] == 'edited') echo 'Order edited successfully!';
        if ($_GET['action'] == 'deleted') echo 'Order deleted successfully!';
        ?>
    </p>
<?php endif; ?>

<div class="container">
    <h1>Order List</h1>

    <form method="get">
        <input type="text" name="order_id" placeholder="Search order id..." value="<?= htmlspecialchars($order_id) ?>" class="search_box">
        <input type="text" name="user_id" placeholder="Search member id..." value="<?= htmlspecialchars($user_id) ?>" class="search_box">
        <select name="status" class="search_box">
            <option value="All" <?= $status == 'All' ? 'selected' : '' ?>>All status</option>
            <option value="Pending" <?= $status == 'Pending' ? 'selected' : '' ?>>Pending</option>
            <option value="Paid" <?= $status == 'Paid' ? 'selected' : '' ?>>Paid</option>
            <option value="Shipped" <?= $status == 'Shipped' ? 'selected' : '' ?>>Shipped</option>
            <option value="Completed" <?= $status == 'Completed' ? 'selected' : '' ?>>Completed</option>
            <option value="Cancelled" <?= $status == 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
        </select>
        <button class="btn">Search</button>
    </form>

    <p><?= $pager->item_count ?> record(s) found</p>

    <div class="sort-buttons">
        <a href="?<?= http_build_query(array_merge($_GET, ['sort_by' => 'order_id', 'sort_order' => $sort_order == 'ASC' ? 'DESC' : 'ASC'])) ?>" class="btn_sort">Sort by Order ID</a>
        <a href="?<?= http_build_query(array_merge($_GET, ['sort_by' => 'user_id', 'sort_order' => $sort_order == 'ASC' ? 'DESC' : 'ASC'])) ?>" class="btn_sort">Sort by Member ID</a>
        <a href="?<?= http_build_query(array_merge($_GET, ['sort_by' => 'total_amount', 'sort_order' => $sort_order == 'ASC' ? 'DESC' : 'ASC'])) ?>" class="btn_sort">Sort by Total Amount</a>
    </div>

    <table class="order">
        <tr>
            <th>ORDER ID</th>
            <th>MEMBER ID</th>
            <th>TOTAL AMOUNT</th>
            <th>ORDER DATE</th>
            <th>STATUS</th>
            <th>ACTION</th>
        </tr>

        <?php foreach ($pager->result as $s): ?>
        <tr>
            <td><?= $s->order_id ?></td>
            <td><?= $s->user_id ?></td>
            <td><?= $s->total_amount ?></td>
            <td><?= $s->order_date ?></td>
            <td><?= $s->status ?></td>
            <td>
                <a href="order_edit.php?edit_id=<?= $s->order_id ?>" class="btn_edit">Edit</a>
                <a href="?delete_id=<?= $s->order_id ?>" class="btn_delete" onclick="return confirm('Are you sure to delete this order?')">Delete</a>
            </td>
        </tr>
        <?php endforeach ?>
    </table>

    <?php
    $query = $_GET;
    unset($query['page'], $query['delete_id']);
    $href = http_build_query($query);
    $pager->html($href);
    ?>

    <a href="order_edit.php" class="btn_add" style="margin-top: 20px; display: inline-block;">+ Add New Order</a>
</div>
