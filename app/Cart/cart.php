<?php require '_head.php'; ?>

<div class="cart-page">
    <h1>Shopping Cart</h1>

    <!-- Error message -->
    <div id="cart-error" style="color: red; margin-bottom: 10px;"></div>

    <table>
        <tr>
            <th>Product</th>
            <th>Quantity</th>
            <th>Subtotal</th>
            <th>Action</th>
        </tr>
        <tbody id="cart-items">
        </tbody>
    </table>

    <div class="total-price">
        <table>
            <tr>
                <td>Subtotal</td>
                <td class="price-align" id="cart-subtotal">RM 0.00</td>
            </tr>
            <tr>
                <td>Tax</td>
                <td class="price-align" id="cart-tax">RM 0.00</td>
            </tr>
            <tr>
                <td>Total</td>
                <td class="price-align" id="cart-total">RM 0.00</td>
            </tr>
        </table>
    </div>

    <div class="checkout-btn-container">
        <button class="btn" id="checkout-btn">Proceed to Checkout</button>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // Render cart
    function renderCart(errorMsg = '') {
        const cartData = JSON.parse(localStorage.getItem('foodCart')) || { items: [], totalPrice: 0 };
        const cartItemsContainer = document.getElementById('cart-items');
        const subtotalElement = document.getElementById('cart-subtotal');
        const taxElement = document.getElementById('cart-tax');
        const totalElement = document.getElementById('cart-total');
        const errorElement = document.getElementById('cart-error');

        cartItemsContainer.innerHTML = '';
        errorElement.textContent = errorMsg;

        if (cartData.items.length === 0) {
            cartItemsContainer.innerHTML = '<tr><td colspan="4">Your cart is empty.</td></tr>';
            subtotalElement.textContent = 'RM 0.00';
            taxElement.textContent = 'RM 0.00';
            totalElement.textContent = 'RM 0.00';
            return;
        }

        let subtotal = 0;
        let hasInvalidQuantity = false;

        cartData.items.forEach((item, index) => {
            if (item.quantity < 1 || item.quantity > item.stock) {
                hasInvalidQuantity = true;
            }

            const itemTotal = item.price * item.quantity;
            subtotal += itemTotal;

            const disableIncrease = item.quantity >= item.stock ? 'disabled' : '';
            const disableDecrease = item.quantity <= 1 ? 'disabled' : '';

            cartItemsContainer.innerHTML += `
                <tr>
                    <td>${item.name}</td>
                    <td>
                        <button class="qty-btn" data-index="${index}" data-action="decrease" ${disableDecrease}>-</button>
                        <span id="qty-${index}">${item.quantity}</span>
                        <button class="qty-btn" data-index="${index}" data-action="increase" ${disableIncrease}>+</button>
                        <small>(In stock: ${item.stock})</small>
                    </td>
                    <td id="item-total-${index}">RM ${itemTotal.toFixed(2)}</td>
                    <td>
                        <button class="remove-btn" data-index="${index}">Remove</button>
                    </td>
                </tr>
            `;
        });

        const tax = subtotal * 0.06;
        const total = subtotal + tax;

        subtotalElement.textContent = `RM ${subtotal.toFixed(2)}`;
        taxElement.textContent = `RM ${tax.toFixed(2)}`;
        totalElement.textContent = `RM ${total.toFixed(2)}`;

        // Quantity buttons
        document.querySelectorAll('.qty-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const idx = parseInt(this.getAttribute('data-index'));
                const action = this.getAttribute('data-action');
                const item = cartData.items[idx];

                if (action === 'increase' && item.quantity < item.stock) {
                    item.quantity++;
                } else if (action === 'decrease' && item.quantity > 1) {
                    item.quantity--;
                }
                localStorage.setItem('foodCart', JSON.stringify(cartData));
                renderCart();
            });
        });

        // Remove buttons
        document.querySelectorAll('.remove-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const idx = parseInt(this.getAttribute('data-index'));
                cartData.items.splice(idx, 1);
                localStorage.setItem('foodCart', JSON.stringify(cartData));
                renderCart();
            });
        });

        // Disable checkout if invalid
        const checkoutBtn = document.getElementById('checkout-btn');
        if (hasInvalidQuantity) {
            checkoutBtn.disabled = true;
            errorElement.textContent = 'Cart has invalid quantities. Adjust before checkout.';
        } else {
            checkoutBtn.disabled = false;
        }

        cartData.totalPrice = subtotal;
        localStorage.setItem('foodCart', JSON.stringify(cartData));
    }

    renderCart();

    // Checkout click
    document.getElementById('checkout-btn').addEventListener('click', function () {
        const cartData = JSON.parse(localStorage.getItem('foodCart')) || { items: [] };
        if (cartData.items.length === 0) {
            alert('Your cart is empty.');
            return;
        }
        window.location.href = 'checkout.php';
    });

    // Add item to cart function (can be called from menu page)
    window.addItemToCart = function (product) {
        const cartData = JSON.parse(localStorage.getItem('foodCart')) || { items: [], totalPrice: 0 };
        const idx = cartData.items.findIndex(i => i.id === product.product_id);

        if (idx >= 0) {
            cartData.items[idx].quantity++;
        } else {
            cartData.items.push({
                id: product.product_id,
                name: product.product_name,
                price: parseFloat(product.price),
                quantity: 1,
                stock: parseInt(product.quantity)
            });
        }

        cartData.totalPrice = cartData.items.reduce((sum, i) => sum + i.price * i.quantity, 0);
        localStorage.setItem('foodCart', JSON.stringify(cartData));
        alert(`${product.product_name} added to cart`);
        renderCart();
    };
});
</script>
</body>
</html>
