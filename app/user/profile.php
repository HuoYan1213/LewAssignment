<?php
include '../base.php';

// ----------------------------------------------------------------------------
// Authenticated users
auth();

// Fetch user data
if (is_get()) {
    $stm = $_db->prepare('SELECT * FROM users WHERE user_id = ?');
    $stm->execute([$_SESSION['user']->user_id]);
    $u = $stm->fetch();

    if (!$u) {
        redirect('../main.php');
    }

    extract((array)$u);
    $_SESSION['photo'] = $u->photo;
}

// Handle form submission
if (is_post()) {
    $email = req('email');
    $name  = req('name');
    $photo = $_SESSION['photo'];
    $f = get_file('photo');

    // Validate: email
    if (!$email) {
        $_err['email'] = 'Required';
    } else if (strlen($email) > 100) {
        $_err['email'] = 'Maximum 100 characters';
    } else if (!is_email($email)) {
        $_err['email'] = 'Invalid email';
    } else {
        $stm = $_db->prepare('SELECT COUNT(*) FROM users WHERE email = ? AND user_id != ?');
        $stm->execute([$email, $_SESSION['user']->user_id]);
        if ($stm->fetchColumn() > 0) {
            $_err['email'] = 'Duplicated';
        }
    }

    // Validate: name
    if (!$name) {
        $_err['name'] = 'Required';
    } else if (strlen($name) > 100) {
        $_err['name'] = 'Maximum 100 characters';
    }

    // Validate: photo (optional)
    if ($f) {
        if (!str_starts_with($f->type, 'image/')) {
            $_err['photo'] = 'Must be an image';
        } else if ($f->size > 1 * 1024 * 1024) {
            $_err['photo'] = 'Maximum 1MB';
        }
    }

    // DB update
    if (!$_err) {
        if ($f) {
            // Delete old photo if exists
            if ($photo && file_exists("images_user/$photo")) {
                unlink("images_user/$photo");
            }
            // Save new photo
            $photo = save_photo($f, 'images_user'); 
        }

        // Update user info
        $stm = $_db->prepare('UPDATE users SET email = ?, name = ?, photo = ? WHERE user_id = ?');
        $stm->execute([$email, $name, $photo, $_SESSION['user']->user_id]);

        // Update session user
        $_SESSION['user']->email = $email;
        $_SESSION['user']->name = $name;
        $_SESSION['user']->photo = $photo;

        temp('info', 'Profile updated successfully');
        redirect('profile.php');
    }
}

// ----------------------------------------------------------------------------
$_title = 'User | Profile';
include 'head_in_user.php';
?>

<div class="profile_form">
    <h2>My Profile</h2>
    <form method="post" enctype="multipart/form-data">
        <div class="form-field">
            <label for="email">Email</label>
            <?= html_text('email', 'type="email" maxlength="100" required') ?>
            <div class="err"><?= err('email') ?></div>
        </div>
        
        <div class="form-field">
            <label for="name">Name</label>
            <?= html_text('name', 'maxlength="100" required') ?>
            <div class="err"><?= err('name') ?></div>
        </div>
        
        <div class="photo-container">
            <label for="photo">Photo</label>
            <label class="upload">
                <?= html_file('photo', 'image/*', 'hidden') ?>
                <img src="images_user/<?= htmlspecialchars($photo) ?>" alt="Profile photo">
            </label>
            <span class="upload-hint">Click photo to change</span>
            <div class="err"><?= err('photo') ?></div>
        </div>
        
        <div class="buttons">
            <button type="submit">Save</button>
            <button type="reset">Cancel</button>
        </div>
    </form>
</div>

<style>
.profile_form {
    max-width: 500px;
    margin: 50px auto;
    padding: 30px;
    background-color: #fff0f0;
    border-radius: 12px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.08);
}

.profile_form h2 {
    text-align: center;
    color: #9e2a2a;
    margin-bottom: 20px;
}

.form-field {
    margin-bottom: 20px;
}

.form-field label {
    display: block;
    font-weight: bold;
    margin-bottom: 5px;
}

.form-field input[type="text"],
.form-field input[type="email"] {
    width: 100%;
    padding: 12px;
    border-radius: 8px;
    border: 1px solid #ccc;
}

.photo-container {
    margin-bottom: 20px;
    text-align: center;
}

.photo-container img {
    width: 120px;
    height: 120px;
    object-fit: cover;
    border-radius: 50%;
    cursor: pointer;
    border: 2px solid #9e2a2a;
}

.upload-hint {
    display: block;
    margin-top: 5px;
    font-size: 0.85rem;
    color: #532323;
}

.err {
    color: #b82e2e;
    font-size: 0.9rem;
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
