<?php
include 'base.php';

// ----------------------------------------------------------------------------

if (is_post()) {
    $email    = req('email');
    $password = req('password');
    $confirm  = req('confirm');
    $name     = req('name');
    $f = get_file('photo');
    
    // Validate: email
    if (!$email) {
        $_err['email'] = 'Required';
    }
    else if (strlen($email) > 100) {
        $_err['email'] = 'Maximum 100 characters';
    }
    else if (!is_email($email)) {
        $_err['email'] = 'Invalid email';
    }
    else if (!is_unique($email, 'users', 'email')) { // Updated table name
        $_err['email'] = 'Duplicated';
    }

    // Validate: password
    if (!$password) {
        $_err['password'] = 'Required';
    }
    else if (strlen($password) < 5 || strlen($password) > 100) {
        $_err['password'] = 'Between 5-100 characters';
    }

    // Validate: confirm
    if (!$confirm) {
        $_err['confirm'] = 'Required';
    }
    else if (strlen($confirm) < 5 || strlen($confirm) > 100) {
        $_err['confirm'] = 'Between 5-100 characters';
    }
    else if ($confirm != $password) {
        $_err['confirm'] = 'Not matched';
    }

    // Validate: name
    if (!$name) {
        $_err['name'] = 'Required';
    }
    else if (strlen($name) > 100) {
        $_err['name'] = 'Maximum 100 characters';
    }

    // Validate: photo (file)
    if (!$f) {
        $_err['photo'] = 'Required';
    }
    else if (!str_starts_with($f->type, 'image/')) {
        $_err['photo'] = 'Must be image';
    }
    else if ($f->size > 1 * 1024 * 1024) {
        $_err['photo'] = 'Maximum 1MB';
    }

    // DB operation
    if (!$_err) {

        // (1) Save photo
        $photo = save_photo($f, 'user/images_user');

        // Generate new user_id (U001, U002, etc.)
        $last = $_db->query("SELECT user_id FROM users ORDER BY user_id DESC LIMIT 1")->fetchColumn();
        $num = intval(substr($last, 1)) + 1;
        $user_id = 'U' . str_pad($num, 3, '0', STR_PAD_LEFT);

        // Insert user (member)
        $stm = $_db->prepare('
            INSERT INTO users (user_id, email, password, name, photo, role)
            VALUES (?, ?, ?, ?, ?, "member")
        ');
        $stm->execute([$user_id, $email, password_hash($password, PASSWORD_DEFAULT), $name, $photo]);

        temp('info', 'Record inserted');
        redirect('login.php');
    }
}

$_title = 'User | Register Member';
include 'head.php';
?>

<div class="container">
<div class="left">
    <img src="images/logo1.png" alt="Tarumt cafeteria Logo">
    <h2>TARUMT CAFETERIA <br>FOOD ORDERING SYSTEM</h2>
        <p>Where Jelly meets Joy, It's love<br>— Sweeten Bring to You</p>
</div>

<div class="right">
    <div class="signup-box">
        <h2>Sign Up</h2>
        <hr>

        <form method="post" class="form" enctype="multipart/form-data">
        
            <?= html_text('email', 'maxlength="100" placeholder="Email"') ?>
            <?= err('email') ?>

            <?= html_password('password', 'maxlength="100" placeholder="Password"') ?>
            <?= err('password') ?>

            <?= html_password('confirm', 'maxlength="100" placeholder="Confirm"') ?>
            <?= err('confirm') ?>

            <?= html_text('name', 'maxlength="100" placeholder="Name"') ?>
            <?= err('name') ?>

            <label for="photo">Photo</label>
            <label class="upload" tabindex="0">
            <?= html_file('photo', 'image/*', 'hidden') ?>
            <img src="images/photo.jpg">
            </label>
            <?= err('photo') ?>
            
            <section>
                <button class="signup-btn">Sign up</button>
            </section>
        </form>
        <div style="text-align:center;"><hr/>
            <a href="login.php" class="login-link"><br/>Already have an account?</a>
        </div>
    </div>        
</div>
