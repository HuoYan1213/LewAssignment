<?php
require '../base.php'; // DB connection

// ---------------------------------------------
// Check if user is logged in
// ---------------------------------------------
$customer_id = $_user?->user_id;
if (!$customer_id) {
    die("You must be logged in to place an order.");
}

// ---------------------------------------------
// Only accept POST requests
// ---------------------------------------------
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../Cart/cart.php");
    exit;
}

// ---------------------------------------------
// Get POST data
// ---------------------------------------------
$cart_data_json  = $_POST['cart_data'] ?? '[]';
$payment_method  = $_POST['payment_method'] ?? '';
$address         = $_POST['address'] ?? '';
$latitude        = $_POST['latitude'] ?? '';
$longitude       = $_POST['longitude'] ?? '';

$cart_items = json_decode($cart_data_json, true);

if (!$cart_items || !isset($cart_items['items']) || count($cart_items['items']) === 0) {
    die("Your cart is empty.");
}

// ---------------------------------------------
// Calculate totals
// ---------------------------------------------
// Security: Recalculate total using database prices to prevent tampering
$total_amount = 0;
$stmt_price = $_db->prepare("SELECT price, product_name FROM product WHERE product_id = ?");

foreach ($cart_items['items'] as &$item) {
    $stmt_price->execute([$item['id']]);
    $prod = $stmt_price->fetch();
    if (!$prod) die("Product not found: " . htmlspecialchars($item['id']));
    
    // Overwrite the price from client with real DB price
    $item['price'] = $prod->price;
    
    $total_amount += $item['price'] * $item['quantity'];
}
unset($item); // Break reference

$packaging = 3.00;
$tax = $total_amount * 0.06;
$total_amount += $packaging + $tax;

// ---------------------------------------------
// Insert order & order items
// ---------------------------------------------
try {
    $_db->beginTransaction();

    // Generate new order_id inside transaction to prevent race conditions
    $stmt = $_db->query("SELECT order_id FROM orders ORDER BY order_id DESC LIMIT 1");
    $last_order = $stmt->fetch(PDO::FETCH_ASSOC);
    $lastId = $last_order ? intval(substr($last_order['order_id'], 1)) : 0;
    $order_id = 'O' . str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);

    // Insert into orders (without delivery_address, latitude, longitude)
    $stmt = $_db->prepare("
        INSERT INTO orders 
        (order_id, user_id, total_amount, status, order_date)
        VALUES (?, ?, ?, 'Pending', NOW())
    ");
    $stmt->execute([$order_id, $customer_id, $total_amount]);

    // Prepare statements for order items and stock update
    $stmt_item = $_db->prepare("
        INSERT INTO order_item 
        (order_item_id, order_id, product_id, quantity, unit_price, subtotal) 
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt_stock = $_db->prepare("
        UPDATE product 
        SET quantity = quantity - ? 
        WHERE product_id = ? AND quantity >= ?
    ");

    $counter = 1;
    foreach ($cart_items['items'] as $item) {
        // Generate order_item_id like OI007
        $stmt2 = $_db->query("SELECT order_item_id FROM order_item ORDER BY order_item_id DESC LIMIT 1");
        $last_item = $stmt2->fetch(PDO::FETCH_ASSOC);
        $lastItemId = $last_item ? intval(substr($last_item['order_item_id'], 2)) : 0;
        $order_item_id = 'OI' . str_pad($lastItemId + $counter, 3, '0', STR_PAD_LEFT);

        $subtotal = $item['price'] * $item['quantity'];

        $stmt_item->execute([$order_item_id, $order_id, $item['id'], $item['quantity'], $item['price'], $subtotal]);
        $stmt_stock->execute([$item['quantity'], $item['id'], $item['quantity']]);

        // Check if stock was actually deducted
        if ($stmt_stock->rowCount() === 0) {
            throw new Exception("Insufficient stock for product: " . $item['name']);
        }

        $counter++;
    }

    $_db->commit();

} catch (PDOException $e) {
    $_db->rollBack();
    die("Database error: " . $e->getMessage());
}

?>

<?php require '_head.php'; ?>

<div class="receipt-wrapper">
    <div class="receipt-card">
        <div class="receipt-header">
            <img src="../images/logo1.png" alt="Logo" class="logo">
            <h1>Thank You for Your Order!</h1>
            <p>Your meal will be ready in <strong>20 minutes</strong>.</p>
        </div>

        <div id="receipt-items" class="receipt-items"></div>

        <div class="totals">
            <div class="total-row"><span>Subtotal:</span><span id="subtotal"></span></div>
            <div class="total-row"><span>Packaging Fee:</span><span id="packaging"></span></div>
            <div class="total-row"><span>Tax (6%):</span><span id="tax"></span></div>
            <div class="total-row total"><span>Total:</span><span id="total"></span></div>
        </div>

        <div class="payment-method">
            Payment Method: 
            <strong>
                <?php
                $map = ['debit'=>'Debit Card','credit'=>'Credit Card','touchngo'=>"Touch 'n Go eWallet",'JQC'=>'TARUMT Pay'];
                echo $map[$payment_method] ?? 'Unknown';
                ?>
            </strong>
        </div>

        <div class="btn-container">
            <a href="../main.php" class="btn-back">Back to Home</a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const cartData = JSON.parse(localStorage.getItem('foodCart')) || {items: []};
    const receiptItems = document.getElementById('receipt-items');

    if (!cartData.items || cartData.items.length === 0) {
        receiptItems.innerHTML = '<p>Your cart is empty.</p>';
        return;
    }

    let subtotal = 0;
    receiptItems.innerHTML = `<div class="item-row"><div>Product</div><div>Price × Qty</div><div>Total</div></div>`;

    cartData.items.forEach(item => {
        const itemTotal = item.price * item.quantity;
        subtotal += itemTotal;
        receiptItems.innerHTML += `<div class="item-row"><div>${item.name}</div><div>RM${item.price.toFixed(2)} × ${item.quantity}</div><div>RM${itemTotal.toFixed(2)}</div></div>`;
    });

    const packaging = 3.00, tax = subtotal * 0.06, total = subtotal + packaging + tax;
    document.getElementById('subtotal').textContent = `RM ${subtotal.toFixed(2)}`;
    document.getElementById('packaging').textContent = `RM ${packaging.toFixed(2)}`;
    document.getElementById('tax').textContent = `RM ${tax.toFixed(2)}`;
    document.getElementById('total').textContent = `RM ${total.toFixed(2)}`;

    // Clear cart
    localStorage.removeItem('foodCart');
});
</script>

<style>
body { background:#f8f9fa; font-family:sans-serif; }
.receipt-wrapper { display:flex; justify-content:center; margin:20px; }
.receipt-card { background:#fff; padding:20px; border-radius:8px; box-shadow:0 6px 18px rgba(0,0,0,0.1); width:100%; max-width:600px; }
.receipt-header { text-align:center; }
.receipt-header .logo { max-width:80px; margin-bottom:10px; }
.receipt-items { margin-top:20px; }
.item-row { display:flex; justify-content:space-between; padding:5px 0; border-bottom:1px solid #eee; }
.totals { margin-top:20px; }
.total-row { display:flex; justify-content:space-between; padding:5px 0; }
.total-row.total { font-weight:bold; font-size:18px; }
.payment-method { margin-top:20px; text-align:center; font-weight:bold; }
.btn-container { margin-top:20px; text-align:center; }
.btn-back { background:#ff6b35; color:#fff; padding:10px 20px; border-radius:6px; text-decoration:none; }
.btn-back:hover { background:#e85d28; }
</style>
