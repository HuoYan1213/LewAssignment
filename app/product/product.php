<?php
require '../base.php';
require '_head.php';
?>

<div class="product-container">
  <div class="search-container">
      <form method="GET" action="" class="search-form">
          <span class="search-icon">🔍</span>
          <input type="text" name="search" placeholder="Search for delicious food..."
                value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
          <button type="submit">Search</button>
          <?php if(isset($_GET['search'])): ?>
              <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="clear-search">✖</a>
          <?php endif; ?>
      </form>
  </div>

  <div class="categories">
    <button class="category-btn" onclick="filterCategory('all')">All</button>
    <button class="category-btn" onclick="filterCategory('rice')">Rice</button>
    <button class="category-btn" onclick="filterCategory('noodles')">Noodles</button>
    <button class="category-btn" onclick="filterCategory('chicken')">Chicken</button>
    <button class="category-btn" onclick="filterCategory('burgers')">Burgers</button>
    <button class="category-btn" onclick="filterCategory('drinks')">Drinks</button>
    <button class="category-btn" onclick="filterCategory('desserts')">Desserts</button>
  </div>

  <?php
      $sql = "SELECT * FROM product";
      $params = [];

      if(isset($_GET['search']) && !empty($_GET['search'])) {
          $sql .= " WHERE product_name LIKE :search";
          $params[':search'] = "%{$_GET['search']}%";
      }

      $stmt = $_db->prepare($sql);
      $stmt->execute($params);
      $products = $stmt->fetchAll();

      foreach ($products as $product) {
          $categoryClass = strtolower($product->category);
          $isOut = $product->quantity <= 0;
          $disabledAttr = $isOut ? 'disabled' : '';
          $outLabel = $isOut ? "<span class='out-of-stock'>Out of Stock</span>" : "";

          echo "
          <div class='item-row {$categoryClass}'>
              <div class='item-image'>
                  <img src='images_product/{$product->photo}' alt='{$product->product_name}' />
              </div>
              <div class='item-text'>
                  <h3>{$product->product_name}</h3>
                  <p>{$product->product_description}</p>
                  <p><strong>Price:</strong> RM {$product->price}</p>
                  <span class='label {$categoryClass}'>{$product->category}</span>
                  {$outLabel}

                  <div class='quantity-control'>
                      <button onclick='decreaseQty(\"{$product->product_id}\")' {$disabledAttr}>–</button>
                      <input type='number' id='qty-{$product->product_id}' value='1' min='1' max='{$product->quantity}' class='quantity-input' {$disabledAttr}/>
                      <button onclick='increaseQty(\"{$product->product_id}\")' {$disabledAttr}>+</button>
                  </div>

                  <button class='small-btn' onclick='addToCart2(\"{$product->product_id}\", \"{$product->product_name}\", {$product->price}, {$product->quantity})' {$disabledAttr}>
                      🛒 Add to Cart
                  </button>
              </div>
          </div>";
      }
  ?>
</div>

<script src="../Cart/cart.js"></script>
<script>
function filterCategory(category) {
    const items = document.querySelectorAll('.item-row');
    items.forEach(item => {
        if (category === 'all' || item.classList.contains(category)) {
            item.style.display = ''; // 恢復預設顯示 (顯示)
        } else {
            item.style.display = 'none'; // 隱藏
        }
    });
}
function addToCart2(id, name, price, stock) {
    const qty = parseInt(document.getElementById('qty-' + id).value);
    if(qty > stock) {
        alert(`Only ${stock} ${name} available`);
        return;
    }
    const product = { id, name, price, stock };
    addItemToCart(product, qty);
}
function increaseQty(id) {
    const input = document.getElementById('qty-' + id);
    if(parseInt(input.value) < parseInt(input.max)) input.value = parseInt(input.value) + 1;
}
function decreaseQty(id) {
    const input = document.getElementById('qty-' + id);
    if(parseInt(input.value) > 1) input.value = parseInt(input.value) - 1;
}
</script>

<style>
/* Search box styling */
.search-container {
  width: 100%;
  display: flex;
  justify-content: center;
  margin: 30px 0;
}

.search-form {
  display: flex;
  align-items: center;
  background: #fff;
  border-radius: 50px;
  padding: 6px 12px;
  width: 100%;
  max-width: 480px;
  box-shadow: 0 6px 18px rgba(0,0,0,0.1);
}

.search-icon {
  font-size: 18px;
  margin-left: 10px;
  color: #888;
}

.search-form input {
  flex: 1;
  border: none;
  outline: none;
  padding: 10px 12px;
  font-size: 15px;
  background: transparent;
}

.search-form button {
  background: #ff6b35;
  color: #fff;
  border: none;
  border-radius: 30px;
  padding: 8px 18px;
  font-size: 14px;
  cursor: pointer;
  transition: 0.3s;
}

.search-form button:hover {
  background: #e85d28;
}

.clear-search {
  margin-left: 8px;
  color: #888;
  text-decoration: none;
  font-size: 14px;
}

.clear-search:hover {
  color: #ff6b35;
}
</style>
</body>
</html>
