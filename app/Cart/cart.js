// Initialize cart in localStorage if not exists
if (!localStorage.getItem('foodCart')) {
    localStorage.setItem('foodCart', JSON.stringify({
        items: [],
        totalPrice: 0
    }));
}

// Load cart on page load
document.addEventListener('DOMContentLoaded', function() {
    updateCartCount();

    // Display cart items if on cart page
    if (document.querySelector('.cart-page')) {
        displayCartItems();
    }

    // Checkout button
    const checkoutBtn = document.getElementById('checkout-btn');
    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', function() {
            const cartData = JSON.parse(localStorage.getItem('foodCart'));
            if (!cartData || !cartData.items || cartData.items.length === 0) {
                alert('Your cart is empty. Please add items before checkout.');
            } else {
                window.location.href = 'checkout.php';
            }
        });
    }
});

// Update cart count in header
function updateCartCount() {
    const cartData = JSON.parse(localStorage.getItem('foodCart'));
    const totalItems = cartData?.items?.reduce((sum, item) => sum + item.quantity, 0) || 0;

    document.querySelectorAll('.cart-count').forEach(el => {
        el.textContent = totalItems;
    });
}

// Display cart items
function displayCartItems() {
    const cartItemsContainer = document.getElementById('cart-items');
    if (!cartItemsContainer) return;

    const cartData = JSON.parse(localStorage.getItem('foodCart'));

    if (!cartData || !cartData.items || cartData.items.length === 0) {
        cartItemsContainer.innerHTML = `<tr><td colspan="4" class="empty-cart">Your cart is empty</td></tr>`;
        document.getElementById('cart-subtotal').textContent = 'RM 0.00';
        document.getElementById('cart-tax').textContent = 'RM 0.00';
        document.getElementById('cart-total').textContent = 'RM 0.00';
        return;
    }

    let cartHTML = '';
    let subtotal = 0;

    cartData.items.forEach((item, index) => {
        const itemTotal = item.price * item.quantity;
        subtotal += itemTotal;

        cartHTML += `
            <tr>
                <td>${item.name}</td>
                <td>
                    <button onclick="updateQuantity(${index}, ${item.quantity - 1})" ${item.quantity <= 1 ? 'disabled' : ''}>-</button>
                    <span>${item.quantity}</span>
                    <button onclick="updateQuantity(${index}, ${item.quantity + 1})" ${item.quantity >= item.stock ? 'disabled' : ''}>+</button>
                    <small>In stock: ${item.stock}</small>
                </td>
                <td>RM ${itemTotal.toFixed(2)}</td>
                <td><button onclick="removeItem(${index})">Remove</button></td>
            </tr>
        `;
    });

    cartItemsContainer.innerHTML = cartHTML;

    const tax = subtotal * 0.06;
    const total = subtotal + tax;

    document.getElementById('cart-subtotal').textContent = `RM ${subtotal.toFixed(2)}`;
    document.getElementById('cart-tax').textContent = `RM ${tax.toFixed(2)}`;
    document.getElementById('cart-total').textContent = `RM ${total.toFixed(2)}`;

    localStorage.setItem('foodCart', JSON.stringify(cartData));
}

// Remove item
function removeItem(index) {
    const cartData = JSON.parse(localStorage.getItem('foodCart'));
    if (!cartData || !cartData.items) return;

    cartData.items.splice(index, 1);
    cartData.totalPrice = cartData.items.reduce((total, item) => total + item.price * item.quantity, 0);

    localStorage.setItem('foodCart', JSON.stringify(cartData));
    updateCartCount();
    displayCartItems();
}

// Update quantity
function updateQuantity(index, quantity) {
    const cartData = JSON.parse(localStorage.getItem('foodCart'));
    if (!cartData || !cartData.items) return;

    quantity = parseInt(quantity);
    if (quantity < 1) quantity = 1;
    if (quantity > cartData.items[index].stock) quantity = cartData.items[index].stock;

    cartData.items[index].quantity = quantity;
    cartData.totalPrice = cartData.items.reduce((total, item) => total + item.price * item.quantity, 0);

    localStorage.setItem('foodCart', JSON.stringify(cartData));
    updateCartCount();
    displayCartItems();
}

// Add to cart from product page
function addItemToCart(product, quantity = 1) {
    const cartData = JSON.parse(localStorage.getItem('foodCart')) || { items: [], totalPrice: 0 };

    if (quantity > product.stock) {
        alert(`Only ${product.stock} ${product.name} available.`);
        return;
    }

    const existingIndex = cartData.items.findIndex(item => item.id === product.id);
    if (existingIndex >= 0) {
        let newQty = cartData.items[existingIndex].quantity + quantity;
        if (newQty > product.stock) newQty = product.stock;
        cartData.items[existingIndex].quantity = newQty;
    } else {
        cartData.items.push({
            id: product.id,
            name: product.name,
            price: product.price,
            quantity: quantity,
            stock: product.stock
        });
    }

    cartData.totalPrice = cartData.items.reduce((total, item) => total + item.price * item.quantity, 0);

    localStorage.setItem('foodCart', JSON.stringify(cartData));
    updateCartCount();
    alert(`Added ${quantity} ${product.name} to cart`);
}
