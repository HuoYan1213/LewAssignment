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
    else if (!is_exists($email, 'users', 'email')) {
        $_err['email'] = 'Not exists';
    }

    // Send reset token (if valid)
    if (!$_err) {
        // TODO: (1) Select user
        $stm = $_db->prepare('SELECT * FROM users WHERE email=?');
        $stm->execute([$email]);
        $u = $stm->fetch();

        // TODO: (2) Generate token id
        $id = sha1(uniqid() . rand());

        // TODO: (3) Delete old and insert new token
        $stm = $_db->prepare('
            DELETE FROM token WHERE user_id = ?;

            INSERT INTO token (token_id, expiry_time, user_id)
            VALUES (?, ADDTIME(NOW(), "00:05:00"), ?);
        ');
        $stm->execute([$u->user_id, $id, $u->user_id]);

        // TODO: (4) Generate token url
        $scheme = $_SERVER['REQUEST_SCHEME'] ?? 'http';
        $host = $_SERVER['HTTP_HOST'];
        $path = dirname($_SERVER['SCRIPT_NAME']); // e.g., /ass/app
        $url = "$scheme://$host$path/user/token.php?id=$id";

        // TODO: (5) Send email
        $m = get_mail();
        $m->addAddress($u->email, $u->name);

        // 檢查圖片是否存在，避免報錯
        $img_html = '';
        $photo_path = "user/images_user/$u->photo";
        if ($u->photo && file_exists($photo_path)) {
            $m->addEmbeddedImage($photo_path, 'photo');
            $img_html = "<img src='cid:photo' style='width: 200px; height: 200px; border: 1px solid #333; object-fit: cover'>";
        }

        $m->isHTML(true);
        $m->Subject = 'Reset Password';
        $m->Body = "
            $img_html
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
