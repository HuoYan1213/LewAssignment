<?php
include '../base.php';

// ----------------------------------------------------------------------------

// TODO: (1) Delete expired tokens
$_db->query('DELETE FROM token WHERE expiry_time < NOW()');

$id = req('id');

// TODO: (2) Is token id valid?
if (!is_exists($id, 'token', 'token_id')) {
    temp('info', 'Invalid token. Try again');
    redirect('../login.php');
}

if (is_post()) {
    $password = req('password');
    $confirm  = req('confirm');

    // Validate: password
    if ($password == '') {
        $_err['password'] = 'Required';
    }
    else if (strlen($password) < 5 || strlen($password) > 100) {
        $_err['password'] = 'Between 5-100 characters';
    }

    // Validate: confirm
    if ($confirm == '') {
        $_err['confirm'] = 'Required';
    }
    else if (strlen($confirm) < 5 || strlen($confirm) > 100) {
        $_err['confirm'] = 'Between 5-100 characters';
    }
    else if ($confirm != $password) {
        $_err['confirm'] = 'Not matched';
    }

    // DB operation
    if (!$_err) {
        // TODO: Update user (password) based on token id + delete token
        $stm = $_db->prepare('
            UPDATE users
            SET password = ?
            WHERE user_id = (SELECT user_id FROM token WHERE token_id = ?);

            DELETE FROM token WHERE token_id = ?;
        ');
        $stm->execute([password_hash($password, PASSWORD_DEFAULT), $id, $id]);

        temp('info', 'Record updated');
        redirect('../login.php');
    }
}

// ----------------------------------------------------------------------------

$_title = 'User | Reset Password';
include '../head.php';


?>

<form method="post" class="form">
    <label for="password">Password</label>
    <?= html_password('password', 'maxlength="100"') ?>
    <?= err('password') ?>

    <label for="confirm">Confirm</label>
    <?= html_password('confirm', 'maxlength="100"') ?>
    <?= err('confirm') ?>

    <section>
        <button>Submit</button>
        <button type="reset">Reset</button>
    </section>
</form>
