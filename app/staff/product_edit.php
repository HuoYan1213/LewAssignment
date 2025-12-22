<?php
require '../base.php';

$edit_id = $_GET['edit_id'] ?? null;
$product = null;
$error = null;

// Fetch product if editing
if ($edit_id) {
    $stm = $_db->prepare('SELECT * FROM product WHERE product_id = ?');
    $stm->execute([$edit_id]);
    $product = $stm->fetch();
}

// Auto-generate new product ID
if (!$edit_id) {
    $stm = $_db->query("SELECT product_id FROM product ORDER BY product_id DESC LIMIT 1");
    $last = $stm->fetch();
    $new_id_num = $last ? (int)substr($last->product_id, 1) + 1 : 1;
    $new_product_id = 'P' . str_pad($new_id_num, 4, '0', STR_PAD_LEFT);
}

// Valid categories
$categories = ['Rice','Noodles','Chicken','Burgers','Drinks','Desserts'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['p_name']);
    $category = $_POST['p_category'];
    $price = $_POST['p_price'];
    $quantity = $_POST['p_quantity'];
    $description = trim($_POST['p_description']);

    // Validation
    if (!$name) {
        $error = "Product name cannot be empty.";
    } elseif (!in_array($category, $categories)) {
        $error = "Invalid category selected.";
    } elseif (!is_numeric($price) || $price < 0) {
        $error = "Price must be a positive number.";
    } elseif (!is_numeric($quantity) || $quantity < 0) {
        $error = "Quantity must be a positive number.";
    } elseif (!$description) {
        $error = "Description cannot be empty.";
    }

    // Handle photo upload
    $photo = $product ? $product->photo : 'default.jpg';
    if (!$error && !empty($_FILES['p_photo']['name'])) {
        $photo = date('Ymd_His') . '_' . basename($_FILES['p_photo']['name']);
        $upload_dir = '../product/images_product/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        if (!move_uploaded_file($_FILES['p_photo']['tmp_name'], $upload_dir . $photo)) {
            $error = "Failed to upload photo.";
        }
    }

    // Insert or update
    if (!$error) {
        if ($edit_id) {
            $_db->prepare('UPDATE product SET product_name=?, category=?, price=?, quantity=?, product_description=?, photo=? WHERE product_id=?')
                ->execute([$name, $category, $price, $quantity, $description, $photo, $edit_id]);
            header('Location: product.php?action=edited');
        } else {
            $_db->prepare('INSERT INTO product (product_id, product_name, category, price, quantity, product_description, photo) VALUES (?, ?, ?, ?, ?, ?, ?)')
                ->execute([$new_product_id, $name, $category, $price, $quantity, $description, $photo]);
            header('Location: product.php?action=added');
        }
        exit;
    }
}
?>

<?php include '_head.php'; ?>

<div class="container">
    <h1><?= $edit_id ? 'Edit' : 'Add' ?> Product</h1>

    <?php if ($error): ?>
        <p class="error_msg"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <table class="product">
            <tr>
                <th>ID</th>
                <td><?= $edit_id ? htmlspecialchars($edit_id) : htmlspecialchars($new_product_id) ?></td>
            </tr>
            <tr>
                <th>Name</th>
                <td><input type="text" name="p_name" value="<?= $product ? htmlspecialchars($product->product_name) : '' ?>" required></td>
            </tr>
            <tr>
                <th>Category</th>
                <td>
                    <select name="p_category" required>
                        <option value="">-- Select Category --</option>
                        <?php foreach ($categories as $cat): 
                            $selected = ($product && $product->category === $cat) ? 'selected' : '';
                        ?>
                            <option value="<?= $cat ?>" <?= $selected ?>><?= $cat ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th>Price</th>
                <td><input type="number" step="0.01" name="p_price" value="<?= $product ? $product->price : '' ?>" required></td>
            </tr>
            <tr>
                <th>Quantity</th>
                <td><input type="number" name="p_quantity" value="<?= $product ? $product->quantity : '' ?>" required></td>
            </tr>
            <tr>
                <th>Description</th>
                <td><textarea name="p_description" required><?= $product ? htmlspecialchars($product->product_description) : '' ?></textarea></td>
            </tr>
            <tr>
                <th>Photo</th>
                <td>
                    <input type="file" name="p_photo">
                    <?php if ($product && !empty($product->photo)): ?>
                        <br><img src="../product/images_product/<?= htmlspecialchars($product->photo) ?>" width="80" style="border-radius: 8px;">
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <th>Action</th>
                <td>
                    <button type="submit" class="btn"><?= $edit_id ? 'Update Product' : 'Add Product' ?></button>
                    <?php if ($edit_id): ?>
                        <a href="product.php" class="btn">Cancel</a>
                    <?php endif; ?>
                </td>
            </tr>
        </table>
    </form>
</div>
