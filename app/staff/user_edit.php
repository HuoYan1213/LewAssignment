<?php
require '../base.php';

$edit_id = $_GET['edit_id'] ?? null;
$user = null;
$_err = [];

// Fetch existing user if editing
if ($edit_id) {
    $stm = $_db->prepare('SELECT * FROM users WHERE user_id = ?');
    $stm->execute([$edit_id]);
    $user = $stm->fetch(PDO::FETCH_OBJ);
}

// Auto-generate user_id for new user
if (!$edit_id) {
    $stm = $_db->query("SELECT user_id FROM users ORDER BY user_id DESC LIMIT 1");
    $row = $stm->fetch(PDO::FETCH_ASSOC);

    if ($row && preg_match('/U0*(\d+)/', $row['user_id'], $matches)) {
        $last_id = (int)$matches[1];
        $new_id = 'U' . str_pad($last_id + 1, 3, '0', STR_PAD_LEFT);
    } else {
        $new_id = 'U001';
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $role = $_POST['role'];
    $password = $_POST['password'];

    // Validation
    if ($name == '') $_err['name'] = 'Name is required';
    if ($email == '') $_err['email'] = 'Email is required';
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $_err['email'] = 'Invalid email';

    // Password required for new user, optional for edit
    if (!$edit_id || $password !== '') {
        if ($password == '') $_err['password'] = 'Password is required';
        elseif (strlen($password) < 5 || strlen($password) > 100) $_err['password'] = 'Between 5-100 characters';
    }

    // Email uniqueness
    $check = $_db->prepare("SELECT COUNT(*) FROM users WHERE email = ? AND user_id != ?");
    $check->execute([$email, $edit_id ?? 0]);
    if ($check->fetchColumn() > 0) $_err['email'] = 'This email is already registered.';

    // Handle photo upload
    $photo = $user ? $user->photo : 'default.jpg';
    if (!empty($_FILES['photo']['name'])) {
        $photo = date('Ymd_His') . '_' . basename($_FILES['photo']['name']);
        $upload_dir = '../user/images_user/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        move_uploaded_file($_FILES['photo']['tmp_name'], $upload_dir . $photo);
    }

    // Insert or update
    if (empty($_err)) {
        if ($edit_id) {
            if ($password !== '') {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $_db->prepare('UPDATE users SET name=?, email=?, role=?, photo=?, password=? WHERE user_id=?')
                     ->execute([$name, $email, $role, $photo, $hashed_password, $edit_id]);
            } else {
                $_db->prepare('UPDATE users SET name=?, email=?, role=?, photo=? WHERE user_id=?')
                     ->execute([$name, $email, $role, $photo, $edit_id]);
            }
            header('Location: user.php?action=edited');
            exit;
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $_db->prepare('INSERT INTO users (user_id,name,email,role,photo,password) VALUES(?,?,?,?,?,?)')
                 ->execute([$new_id, $name, $email, $role, $photo, $hashed_password]);
            header('Location: user.php?action=added');
            exit;
        }
    }
}
?>

<?php include '_head.php'; ?>

<div class="container">
    <h1><?= $edit_id ? 'Edit' : 'Add' ?> User</h1>

    <form method="post" enctype="multipart/form-data">
        <table class="user">
            <tr>
                <th>ID</th>
                <td><?= $edit_id ?? $new_id ?></td>
            </tr>
            <tr>
                <th>Name</th>
                <td>
                    <input type="text" name="name" value="<?= htmlspecialchars($_POST['name'] ?? $user->name ?? '') ?>" required>
                    <div style="color:red"><?= $_err['name'] ?? '' ?></div>
                </td>
            </tr>
            <tr>
                <th>Password</th>
                <td>
                    <input type="password" name="password">
                    <?php if ($edit_id): ?><small>(Leave blank to keep current password)</small><?php endif; ?>
                    <div style="color:red"><?= $_err['password'] ?? '' ?></div>
                </td>
            </tr>
            <tr>
                <th>Email</th>
                <td>
                    <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? $user->email ?? '') ?>" required>
                    <div style="color:red"><?= $_err['email'] ?? '' ?></div>
                </td>
            </tr>
            <tr>
                <th>Role</th>
                <td>
                    <select name="role" required>
                        <option value="admin" <?= isset($user) && $user->role=='admin' ? 'selected' : '' ?>>Admin</option>
                        <option value="member" <?= isset($user) && $user->role=='member' ? 'selected' : '' ?>>Member</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th>Photo</th>
                <td>
                    <input type="file" name="photo">
                    <?php if (!empty($user->photo)): ?>
                        <br><img src="../user/images_user/<?= htmlspecialchars($user->photo) ?>" width="50">
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <th>Action</th>
                <td>
                    <button type="submit" class="btn"><?= $edit_id ? 'Update' : 'Add' ?></button>
                    <?php if ($edit_id): ?>
                        <a href="user.php" class="btn">Cancel</a>
                    <?php endif; ?>
                </td>
            </tr>
        </table>
    </form>
</div>
