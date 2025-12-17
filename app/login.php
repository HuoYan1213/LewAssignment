<?php
session_start();
include 'base.php';

// ----------------------------------------------------------------------------
// Get redirect target (default to dashboard if not provided)
$redirect = $_GET['redirect'] ?? 'main.php';

// ----------------------------------------------------------------------------
// Handle POST (login submission)
if (is_post()) {
    $email    = trim(req('email'));
    $password = req('password');

    $_err = [];

    // Validate: email
    if ($email == '') {
        $_err['email'] = 'Required';
    } else if (!is_email($email)) {
        $_err['email'] = 'Invalid email';
    }

    // Validate: password
    if ($password == '') {
        $_err['password'] = 'Required';
    }

    // Attempt login if no errors
    if (empty($_err)) {
        $stm = $_db->prepare('SELECT * FROM users WHERE email = ?');
        $stm->execute([$email]);
        $u = $stm->fetch(PDO::FETCH_OBJ);

        if ($u && password_verify($password, $u->password)) {
            // Login successful
            $_SESSION['user_id'] = $u->user_id; // Save user_id to session
            temp('info', 'Login successfully');

            // Redirect to intended page
            header("Location: $redirect");
            exit;
        } else {
            $_err['password'] = 'Email or password not matched';
        }
    }
}

// ----------------------------------------------------------------------------
$_title = 'Login';
include 'head.php';
?>

<div id="info"><?= temp('info') ?></div>
<div class="container">
    <div class="left">
        <img src="images/logo1.png" alt="TARUMT Cafeteria Logo"><br/>
        <h2>TARUMT CAFETERIA<br>ORDERING SYSTEM</h2>
        <p>Where Jelly meets Joy, It's love<br>— Sweeten Bring to You</p>
    </div>

    <div class="right">
        <div class="login-box">
            <h2>Login to Your Account</h2>
            <form method="post" class="form">
                <?= html_text('email', 'maxlength="100" placeholder="Email" value="'.htmlspecialchars($email ?? '').'" autocomplete="email"') ?>
                <?= err('email') ?>

                <?= html_password('password', 'maxlength="100" placeholder="Password" autocomplete="current-password"') ?>
                <?= err('password') ?>
                
                <button class="btn-loginpage">Log in</button>   
            </form>
            <div class="extra-links">
                <a href="forget.php">Forgotten password?</a><hr/><br/>
                <button class="btn-create" onclick="window.location.href='signup.php'">Create new account</button>
            </div>
        </div>
    </div>
    <div class="bottom">
        <p> Your Very First Choice!</p>
    </div>
</div>
