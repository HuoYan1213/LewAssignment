<?php
require '../base.php';
require '_pager.php';

// Filters & pagination
$role       = $_GET['role'] ?? '';
$id         = $_GET['id'] ?? '';
$searchName = $_GET['name'] ?? '';
$page       = $_GET['page'] ?? 1;
$sort_by    = $_GET['sort_by'] ?? 'name';
$sort_order = $_GET['sort_order'] ?? 'ASC';

// Delete user
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $_db->prepare('DELETE FROM users WHERE user_id = ?')->execute([$delete_id]);
    header('Location: user.php?action=deleted&role=' . urlencode($role));
    exit;
}

// Build SQL query
$sql = "SELECT * FROM users WHERE 1";
$params = [];

// Apply role filter
if ($role && $role !== 'All') {
    $sql .= " AND role = ?";
    $params[] = $role;
}

// Apply ID filter
if ($id !== '') {
    $sql .= " AND user_id = ?";
    $params[] = $id;
}

// Apply name search
if ($searchName !== '') {
    $sql .= " AND name LIKE ?";
    $params[] = "%$searchName%";
}

// Sorting (whitelist)
$allowed_sort = ['user_id', 'name', 'email', 'role'];
if (!in_array($sort_by, $allowed_sort)) $sort_by = 'name';
$sort_order = strtoupper($sort_order) === 'DESC' ? 'DESC' : 'ASC';
$sql .= " ORDER BY $sort_by $sort_order";

// Pagination
$pager = new pager($sql, $params, 5, $page);
?>

<?php include '_head.php'; ?>

<div class="container">
    <h1>User List</h1>

    <?php if (isset($_GET['action'])): ?>
        <p class="success_msg">
            <?php
            if ($_GET['action'] == 'added') echo 'User added successfully!';
            if ($_GET['action'] == 'edited') echo 'User edited successfully!';
            if ($_GET['action'] == 'deleted') echo 'User deleted successfully!';
            ?>
        </p>
    <?php endif; ?>

    <div class="filter-buttons">
        <a href="user.php?role=admin" class="btn <?= $role == 'admin' ? 'btn_active' : '' ?>">Admin</a>
        <a href="user.php?role=member" class="btn <?= $role == 'member' ? 'btn_active' : '' ?>">Member</a>
    </div>

    <?php if ($role): ?>
        <form method="get" class="form_inline" style="margin-bottom:15px;">
            <input type="hidden" name="role" value="<?= htmlspecialchars($role) ?>">
            <input type="text" name="id" placeholder="Search user id..." value="<?= htmlspecialchars($id) ?>" class="search_box">
            <input type="text" name="name" placeholder="Search by name..." value="<?= htmlspecialchars($searchName) ?>" class="search_box">
            <button class="btn">Search</button>
        </form>

        <p><?= $pager->item_count ?> record(s) found</p>

        <div class="sort-buttons" style="margin-bottom:15px;">
            <a href="?<?= http_build_query(array_merge($_GET,['sort_by'=>'user_id','sort_order'=>$sort_order=='ASC'?'DESC':'ASC'])) ?>" class="btn_sort">Sort by ID</a>
            <a href="?<?= http_build_query(array_merge($_GET,['sort_by'=>'name','sort_order'=>$sort_order=='ASC'?'DESC':'ASC'])) ?>" class="btn_sort">Sort by Name</a>
            <a href="?<?= http_build_query(array_merge($_GET,['sort_by'=>'email','sort_order'=>$sort_order=='ASC'?'DESC':'ASC'])) ?>" class="btn_sort">Sort by Email</a>
        </div>

        <table class="user">
            <tr>
                <th>ID</th>
                <th>NAME</th>
                <th>EMAIL</th>
                <th>ROLE</th>
                <th>PHOTO</th>
                <th>ACTION</th>
            </tr>

            <?php foreach ($pager->result as $s): ?>
                <tr>
                    <td><?= htmlspecialchars($s->user_id) ?></td>
                    <td><?= htmlspecialchars($s->name) ?></td>
                    <td><?= htmlspecialchars($s->email) ?></td>
                    <td><?= htmlspecialchars($s->role) ?></td>
                    <td><img src="../user/images_user/<?= htmlspecialchars($s->photo) ?>" width="50"></td>
                    <td>
                        <a href="user_edit.php?edit_id=<?= htmlspecialchars($s->user_id) ?>" class="btn_edit">Edit</a>
                        <a href="?role=<?= htmlspecialchars($role) ?>&delete_id=<?= htmlspecialchars($s->user_id) ?>" class="btn_delete" onclick="return confirm('Are you sure to delete this user?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <?php
        $query = $_GET;
        unset($query['page'], $query['delete_id'], $query['action']);
        $pager->html(http_build_query($query));
        ?>

    <?php else: ?>
        <p>Please select a role filter (Admin or Member) to view users.</p>
    <?php endif; ?>

    <a href="user_edit.php" class="btn_add" style="margin-top: 20px; display: inline-block;">+ Add New User</a>
</div>
