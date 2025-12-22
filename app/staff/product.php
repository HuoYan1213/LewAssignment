<?php
require '../base.php';
require '_pager.php';

// Filters & pagination
$product_id = isset($_GET['product_id']) ? trim($_GET['product_id']) : '';
$product_name = isset($_GET['product_name']) ? trim($_GET['product_name']) : '';
$product_category = $_GET['product_category'] ?? 'All';
$page = $_GET['page'] ?? 1;
$sort_by = $_GET['sort_by'] ?? 'product_name';
$sort_order = $_GET['sort_order'] ?? 'ASC';

// Categories
$categories = ['Rice','Noodles','Chicken','Burgers','Drinks','Desserts'];

// Delete product
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $_db->prepare('DELETE FROM product WHERE product_id = ?')->execute([$delete_id]);
    header('Location: product.php?action=deleted');
    exit;
}

// Build SQL query with filters
$sql = 'SELECT * FROM product WHERE 1';
$params = [];

if ($product_id !== '') {
    $sql .= ' AND product_id = ?';
    $params[] = $product_id;
}

if ($product_name !== '') {
    $sql .= ' AND product_name LIKE ?';
    $params[] = "%$product_name%";
}

if ($product_category !== 'All' && in_array($product_category, $categories)) {
    $sql .= ' AND category = ?';
    $params[] = $product_category;
}

// Sorting (whitelist)
$allowed_sort = ['product_name','price','quantity'];
if (!in_array($sort_by, $allowed_sort)) $sort_by = 'product_name';
$sort_order = strtoupper($sort_order) === 'DESC' ? 'DESC' : 'ASC';
$sql .= " ORDER BY $sort_by $sort_order";

// Pagination
$pager = new pager($sql, $params, 5, $page);
?>

<?php include '_head.php'; ?>

<div class="container">
    <h1>Product List</h1>

    <?php if (isset($_GET['action'])): ?>
        <p class="success_msg">
            <?php
                if ($_GET['action'] == 'added') echo 'Product added successfully!';
                if ($_GET['action'] == 'edited') echo 'Product edited successfully!';
                if ($_GET['action'] == 'deleted') echo 'Product deleted successfully!';
            ?>
        </p>
    <?php endif; ?>

    <form method="get" style="margin-bottom:15px;">
        <input type="text" name="product_id" placeholder="Search product ID..." value="<?= htmlspecialchars($product_id) ?>" class="search_box">
        <input type="text" name="product_name" placeholder="Search product name..." value="<?= htmlspecialchars($product_name) ?>" class="search_box">
        <select name="product_category" class="search_box">
            <option value="All" <?= $product_category === 'All' ? 'selected' : '' ?>>All Categories</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat ?>" <?= $product_category === $cat ? 'selected' : '' ?>><?= $cat ?></option>
            <?php endforeach; ?>
        </select>
        <button class="btn">Search</button>
    </form>

    <p><?= $pager->item_count ?> record(s) found</p>

    <div class="sort-buttons" style="margin-bottom:15px;">
        <a href="?<?= http_build_query(array_merge($_GET, ['sort_by'=>'product_name','sort_order'=>$sort_order=='ASC'?'DESC':'ASC'])) ?>" class="btn_sort">Sort by Name</a>
        <a href="?<?= http_build_query(array_merge($_GET, ['sort_by'=>'price','sort_order'=>$sort_order=='ASC'?'DESC':'ASC'])) ?>" class="btn_sort">Sort by Price</a>
        <a href="?<?= http_build_query(array_merge($_GET, ['sort_by'=>'quantity','sort_order'=>$sort_order=='ASC'?'DESC':'ASC'])) ?>" class="btn_sort">Sort by Quantity</a>
    </div>

    <table class="product">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Category</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Description</th>
            <th>Photo</th>
            <th>Action</th>
        </tr>

        <?php foreach ($pager->result as $s): ?>
        <tr>
            <td><?= htmlspecialchars($s->product_id) ?></td>
            <td><?= htmlspecialchars($s->product_name) ?></td>
            <td><?= htmlspecialchars($s->category) ?></td>
            <td><?= number_format($s->price, 2) ?></td>
            <td><?= htmlspecialchars($s->quantity) ?></td>
            <td><?= htmlspecialchars($s->product_description) ?></td>
            <td>
                <img src="../product/images_product/<?= htmlspecialchars($s->photo ?: 'default.jpg') ?>" width="50" style="border-radius:8px;">
            </td>
            <td>
                <a href="product_edit.php?edit_id=<?= htmlspecialchars($s->product_id) ?>" class="btn_edit">Edit</a>
                <a href="?delete_id=<?= htmlspecialchars($s->product_id) ?>" class="btn_delete" onclick="return confirm('Are you sure to delete this product?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <?php
    $query = $_GET;
    unset($query['page'], $query['delete_id']);
    $href = http_build_query($query);
    $pager->html($href);
    ?>

    <a href="product_edit.php" class="btn_add" style="margin-top:20px;">+ Add New Product</a>
</div>
