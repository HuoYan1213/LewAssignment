<?php
require '_base.php';
require '_pager.php';

// Filters & pagination
$feedback_id = isset($_GET['feedback_id']) ? trim($_GET['feedback_id']) : '';
$user_id     = isset($_GET['user_id']) ? trim($_GET['user_id']) : '';
$status      = $_GET['status'] ?? 'All';
$page        = $_GET['page'] ?? 1;
$sort_by     = $_GET['sort_by'] ?? 'feedback_id';
$sort_order  = $_GET['sort_order'] ?? 'ASC';

// Delete feedback
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $_db->prepare('DELETE FROM feedback WHERE feedback_id = ?')->execute([$delete_id]);
    header('Location: feedback_control.php?action=deleted');
    exit;
}

// Update feedback status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_id'])) {
    $edit_id = $_POST['edit_id'];
    $new_status = $_POST['new_status'];
    $_db->prepare('UPDATE feedback SET status = ? WHERE feedback_id = ?')->execute([$new_status, $edit_id]);
    header('Location: feedback_control.php?action=updated');
    exit;
}

// Build SQL query with filters
$sql = "SELECT * FROM feedback WHERE 1";
$params = [];

if ($feedback_id !== '') {
    $sql .= " AND feedback_id LIKE ?";
    $params[] = "%$feedback_id%";
}

if ($user_id !== '') {
    $sql .= " AND user_id LIKE ?";
    $params[] = "%$user_id%";
}

if ($status !== 'All') {
    $sql .= " AND status = ?";
    $params[] = $status;
}

// Sorting (whitelist)
$allowed_sort = ['feedback_id','user_id','created_at','status'];
if (!in_array($sort_by, $allowed_sort)) $sort_by = 'feedback_id';
$sort_order = strtoupper($sort_order) === 'DESC' ? 'DESC' : 'ASC';
$sql .= " ORDER BY $sort_by $sort_order";

// Pagination
$pager = new pager($sql, $params, 5, $page);
?>

<?php require '_head.php'; ?>

<div class="container">
    <h1>Feedback List</h1>

    <?php if (isset($_GET['action'])): ?>
        <p class="success_msg">
            <?php
            if ($_GET['action'] == 'updated') echo 'Feedback status updated successfully!';
            if ($_GET['action'] == 'deleted') echo 'Feedback deleted successfully!';
            ?>
        </p>
    <?php endif; ?>

    <form method="get" style="display: flex; gap: 8px; align-items: center; margin-bottom: 15px;">
        <input type="text" name="feedback_id" placeholder="Search feedback id..." value="<?= htmlspecialchars($feedback_id) ?>" class="search_box">
        <input type="text" name="user_id" placeholder="Search member id..." value="<?= htmlspecialchars($user_id) ?>" class="search_box">
        <select name="status" class="search_box">
            <option value="All" <?= $status === 'All' ? 'selected' : '' ?>>All Status</option>
            <option value="Pending" <?= $status === 'Pending' ? 'selected' : '' ?>>Pending</option>
            <option value="Reviewed" <?= $status === 'Reviewed' ? 'selected' : '' ?>>Reviewed</option>
            <option value="Resolved" <?= $status === 'Resolved' ? 'selected' : '' ?>>Resolved</option>
        </select>
        <button type="submit" class="btn">Search</button>
    </form>

    <p><?= $pager->item_count ?> record(s) found</p>

    <div class="sort-buttons" style="margin-bottom:15px;">
        <a href="?<?= http_build_query(array_merge($_GET, ['sort_by'=>'feedback_id','sort_order'=>$sort_order=='ASC'?'DESC':'ASC'])) ?>" class="btn_sort">Sort by Feedback ID</a>
        <a href="?<?= http_build_query(array_merge($_GET, ['sort_by'=>'user_id','sort_order'=>$sort_order=='ASC'?'DESC':'ASC'])) ?>" class="btn_sort">Sort by Member ID</a>
        <a href="?<?= http_build_query(array_merge($_GET, ['sort_by'=>'created_at','sort_order'=>$sort_order=='ASC'?'DESC':'ASC'])) ?>" class="btn_sort">Sort by Date</a>
        <a href="?<?= http_build_query(array_merge($_GET, ['sort_by'=>'status','sort_order'=>$sort_order=='ASC'?'DESC':'ASC'])) ?>" class="btn_sort">Sort by Status</a>
    </div>

    <table class="feedback">
        <tr>
            <th>FEEDBACK ID</th>
            <th>MEMBER ID</th>
            <th>MESSAGE</th>
            <th>DATE TIME</th>
            <th>STATUS</th>
            <th>ACTION</th>
        </tr>

        <?php foreach ($pager->result as $s): ?>
        <tr>
            <td><?= htmlspecialchars($s->feedback_id) ?></td>
            <td><?= htmlspecialchars($s->user_id) ?></td>
            <td><?= htmlspecialchars($s->message) ?></td>
            <td><?= $s->created_at ?></td>
            <td>
                <form method="post" style="display:flex; gap:8px; align-items:center;">
                    <input type="hidden" name="edit_id" value="<?= htmlspecialchars($s->feedback_id) ?>">
                    <select name="new_status" class="search_box" style="width:auto; padding:8px; font-size:14px;">
                        <option value="Pending" <?= $s->status=='Pending'?'selected':'' ?>>Pending</option>
                        <option value="Reviewed" <?= $s->status=='Reviewed'?'selected':'' ?>>Reviewed</option>
                        <option value="Resolved" <?= $s->status=='Resolved'?'selected':'' ?>>Resolved</option>
                    </select>
                    <button type="submit" class="btn" style="padding:8px 12px; font-size:14px; border-radius:8px;">Save</button>
                </form>
            </td>
            <td>
                <a href="?<?= http_build_query(array_merge($_GET,['delete_id'=>$s->feedback_id])) ?>" 
                   class="btn_delete" 
                   onclick="return confirm('Are you sure you want to delete this feedback?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <?php
    $query = $_GET;
    unset($query['page'], $query['delete_id']);
    $pager->html(http_build_query($query));
    ?>
</div>
