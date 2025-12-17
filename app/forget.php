<?php
include 'base.php';

if (is_post()) {
    $email = req('email');

    // Validate: email
    if ($email == '') {
        $_err['email'] = 'Required';
    }
    else if (!is_email($email)) {
        $_err['email'] = 'Invalid email';
    }
    else if (!is_exists($email, 'user', 'email')) {
        $_err['email'] = 'Not exists';
    }

    // Send reset token (if valid)
    if (!$_err) {
        // TODO: (1) Select user
        $stm = $_db->prepare('SELECT * FROM user WHERE email=?');
        $stm->execute([$email]);
        $u = $stm->fetch();

        // TODO: (2) Generate token id
        $id = sha1(uniqid() . rand());

        // TODO: (3) Delete old and insert new token
        $stm = $_db->prepare('
            DELETE FROM token WHERE user_id = ?;

            INSERT INTO token (id, expire, user_id)
            VALUES (?, ADDTIME(NOW(), "00:05"), ?);
        ');
        $stm->execute([$u->id, $id, $u->id]);

        // TODO: (4) Generate token url
        $url = "http://localhost/ass/app/user/token.php?id=$id"; 


        // TODO: (5) Send email
        $m =get_mail();
        $m ->addAddress($u->email, $u->name);
        $m->addEmbeddedImage("user/images_user/$u->photo", 'photo');
        $m->isHTML(true);
        $m->Subject = 'Reset Password';
        $m->Body = "
            <img src='cid:photo'
                 style='width: 200px; height: 200px;
                        border: 1px solid #333'>
            <p>Dear $u->name,<p>
            <h1 style='color: red'>Reset Password</h1>
            <p>
                Please click <a href='$url'>here</a>
                to reset your password.
            </p>
            <p>From, 😺 JQC Admin</p>
        ";

        $m->send();
        temp('info', 'Email sent');
        redirect('login.php');
    }
}

$_title = 'Forgot Password';
include 'head.php';
?>
<div class="forget_container">
    <div class="forget_right">
            <h2>Forgot Your Password?</h2>
            <form method="post" class="form">
                <?= html_text('email', 'maxlength="100" placeholder="Enter your email"') ?>
                <?= err('email') ?>
                <button class="btn-loginpage">Send Reset Link</button>
            </form>
            <div class="extra-links">
                <a href="login.php">Back to Login</a>
            </div>
        
    </div>
</div>
