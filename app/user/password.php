<?php
include '../base.php';

// ----------------------------------------------------------------------------
// Authenticated users
auth();

// ----------------------------------------------------------------------------
if (is_post()) {
    $password     = req('password');
    $new_password = req('new_password');
    $confirm      = req('confirm');

    // Validate: current password
    if (!$password) {
        $_err['password'] = 'Required';
    } else if (strlen($password) < 5 || strlen($password) > 100) {
        $_err['password'] = 'Between 5-100 characters';
    } else {
        // Check current password against DB
        $stm = $_db->prepare('SELECT password FROM users WHERE user_id = ?');
        $stm->execute([$_SESSION['user']->user_id]);
        $u = $stm->fetch(PDO::FETCH_OBJ);

        if (!$u || !password_verify($password, $u->password)) {
            $_err['password'] = 'Not matched';
        }
    }

    // Validate: new password
    if (!$new_password) {
        $_err['new_password'] = 'Required';
    } else if (strlen($new_password) < 5 || strlen($new_password) > 100) {
        $_err['new_password'] = 'Between 5-100 characters';
    }

    // Validate: confirm
    if (!$confirm) {
        $_err['confirm'] = 'Required';
    } else if ($confirm != $new_password) {
        $_err['confirm'] = 'Not matched';
    }

    // DB operation: update password
    if (!$_err) {
        $stm = $_db->prepare('UPDATE users SET password = ? WHERE user_id = ?');
        $stm->execute([password_hash($new_password, PASSWORD_DEFAULT), $_SESSION['user']->user_id]);

        temp('info', 'Password updated successfully');
        redirect('../main.php');
    }
}

// ----------------------------------------------------------------------------
$_title = 'User | Change Password';
include 'head_in_user.php';
?>

<div class="password-form">
    <h2>Change Password</h2>
    <form method="post">
        <div class="form-field">
            <label for="password">Current Password</label>
            <?= html_password('password', 'maxlength="100" required id="password"') ?>
            <span class="toggle-password" onclick="togglePassword('password')">Show</span>
            <div class="err"><?= err('password') ?></div>
        </div>

        <div class="form-field">
            <label for="new_password">New Password</label>
            <?= html_password('new_password', 'maxlength="100" required id="new_password" onkeyup="checkPasswordStrength(this.value)"') ?>
            <span class="toggle-password" onclick="togglePassword('new_password')">Show</span>
            <div class="password-strength">
                <div id="strength-meter"></div>
            </div>
            <div class="password-requirements">
                Must be at least 8 characters with uppercase, lowercase, numbers, and symbols
            </div>
            <div class="err"><?= err('new_password') ?></div>
        </div>

        <div class="form-field">
            <label for="confirm">Confirm New Password</label>
            <?= html_password('confirm', 'maxlength="100" required id="confirm"') ?>
            <span class="toggle-password" onclick="togglePassword('confirm')">Show</span>
            <div class="err"><?= err('confirm') ?></div>
        </div>

        <div class="buttons">
            <button type="submit">Update Password</button>
            <button type="reset">Cancel</button>
        </div>
    </form>
</div>

<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const button = field.nextElementSibling;
    
    if (field.type === "password") {
        field.type = "text";
        button.textContent = "Hide";
    } else {
        field.type = "password";
        button.textContent = "Show";
    }
}

function checkPasswordStrength(password) {
    const meter = document.getElementById('strength-meter');
    meter.className = '';
    
    if (!password) {
        meter.style.width = '0%';
        return;
    }
    
    let strength = 0;
    if (password.length >= 8) strength += 25;
    if (password.match(/[A-Z]/)) strength += 25;
    if (password.match(/[0-9]/)) strength += 25;
    if (password.match(/[^A-Za-z0-9]/)) strength += 25;
    
    meter.style.width = strength + '%';
    
    if (strength < 50) {
        meter.classList.add('strength-weak');
    } else if (strength < 75) {
        meter.classList.add('strength-medium');
    } else {
        meter.classList.add('strength-strong');
    }
}
</script>

<style>
.password-form {
    max-width: 500px;
    margin: 50px auto;
    background: #fff0f0;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.08);
}

.password-form h2 {
    text-align: center;
    color: #9e2a2a;
    margin-bottom: 20px;
}

.form-field {
    margin-bottom: 20px;
    position: relative;
}

.form-field label {
    display: block;
    font-weight: bold;
    margin-bottom: 5px;
}

.form-field input[type="password"] {
    width: 100%;
    padding: 12px;
    border-radius: 8px;
    border: 1px solid #ccc;
}

.toggle-password {
    position: absolute;
    right: 10px;
    top: 36px;
    cursor: pointer;
    font-size: 0.9rem;
    color: #9e2a2a;
}

.err {
    color: #b82e2e;
    font-size: 0.9rem;
    margin-top: 5px;
}

.password-strength {
    width: 100%;
    height: 8px;
    background: #eee;
    border-radius: 4px;
    margin-top: 5px;
    overflow: hidden;
}

#strength-meter {
    height: 100%;
    width: 0%;
    background: red;
    transition: width 0.3s ease;
}

#strength-meter.strength-weak { background: #ff4d4d; }
#strength-meter.strength-medium { background: #ffbf00; }
#strength-meter.strength-strong { background: #4caf50; }

.password-requirements {
    font-size: 0.85rem;
    color: #532323;
    margin-top: 5px;
}

.buttons {
    display: flex;
    justify-content: space-between;
}

.buttons button {
    padding: 12px 20px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: bold;
    background-color: #9e2a2a;
    color: white;
    transition: background 0.3s ease;
}

.buttons button:hover {
    background-color: #660000;
}
</style>
