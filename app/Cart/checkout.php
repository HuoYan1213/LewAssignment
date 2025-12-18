<?php require '_head.php'; ?>

<div class="checkout-container">
    <form id="checkout-form" action="order_successful.php" method="POST">

        <!-- Hidden inputs -->
        <input type="hidden" name="cart_data" id="cart_data_input">
        <input type="hidden" name="payment_method" id="payment_method_input">
        <input type="hidden" name="address" id="address_input">
        <input type="hidden" name="latitude" id="latitude_input">
        <input type="hidden" name="longitude" id="longitude_input">

        <!-- Left column: Payment & Delivery -->
        <div class="checkout-column">
            <!-- Payment Method -->
            <div class="checkout-box">
                <h2>Payment Method</h2>
                <div class="payment-options">
                    <label><input type="radio" name="payment" value="debit" checked> Debit Card</label>
                    <label><input type="radio" name="payment" value="credit"> Credit Card</label>
                    <label><input type="radio" name="payment" value="touchngo"> Touch 'n Go eWallet</label>
                    <label><input type="radio" name="payment" value="JQC"> TARUMT Pay</label>
                </div>
            </div>

            <!-- Delivery Address -->
            <div class="checkout-box">
                <h2>Delivery Address</h2>
                <p>Select your delivery location:</p>
                <div id="map" class="map"></div>
                <label>Area / Road:</label>
                <input type="text" id="selected-address" readonly>
                <label>House No. / Unit / Floor:</label>
                <input type="text" id="house_input" placeholder="Enter your house/unit/floor">
            </div>
        </div>

        <!-- Right column: Order Summary -->
        <div class="checkout-column">
            <div class="checkout-box">
                <h2>Your Order</h2>
                <div id="checkout-summary" class="order-summary"></div>
                <button type="submit" class="checkout-btn">Place Order</button>
            </div>
        </div>

    </form>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const cartData = JSON.parse(localStorage.getItem('foodCart')) || { items: [], totalPrice: 0 };
    const summary = document.getElementById('checkout-summary');

    if(cartData.items.length === 0) { window.location.href='cart.php'; return; }

    // Render order summary
    let subtotal = 0;
    summary.innerHTML = '';
    cartData.items.forEach(item => {
        const total = item.price * item.quantity;
        subtotal += total;
        summary.innerHTML += `
            <div class="order-item">
                <div class="item-name">${item.name} × ${item.quantity}</div>
                <div class="item-total">RM ${total.toFixed(2)}</div>
            </div>
        `;
    });

    const packaging = 3.00;
    const tax = subtotal * 0.06;
    const total = subtotal + packaging + tax;

    summary.innerHTML += `
        <div class="order-totals">
            <div><span>Subtotal:</span><span>RM ${subtotal.toFixed(2)}</span></div>
            <div><span>Packaging Fee:</span><span>RM ${packaging.toFixed(2)}</span></div>
            <div><span>Tax (6%):</span><span>RM ${tax.toFixed(2)}</span></div>
            <div class="order-total"><span>Total:</span><span>RM ${total.toFixed(2)}</span></div>
        </div>
    `;

    // Initialize map
    const map = L.map('map').setView([4.3333, 101.15], 15);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);
    let marker = L.marker([4.3333, 101.15], { draggable: true }).addTo(map);

    function updateAddress(lat, lon) {
        document.getElementById('selected-address').value = "Locating...";
        
        fetch(`geocode.php?lat=${lat}&lon=${lon}`)
            .then(res => res.text())
            .then(text => {
                try {
                    const data = JSON.parse(text);
                    if (data.error) {
                        document.getElementById('selected-address').value = "Error: " + data.error;
                        console.error(data.error);
                    } else {
                        document.getElementById('selected-address').value = data.display_name || '';
                        document.getElementById('address_input').value = data.display_name || '';
                        document.getElementById('latitude_input').value = lat;
                        document.getElementById('longitude_input').value = lon;
                    }
                } catch (e) {
                    console.error("Server Response Error (Not JSON):", text); // 這裡會顯示 PHP 的錯誤訊息
                    document.getElementById('selected-address').value = "Server Error (Check Console)";
                }
            })
            .catch(err => {
                document.getElementById('selected-address').value = "Network Error - Please try again";
                console.error(err);
            });
    }

    updateAddress(4.3333, 101.15);
    marker.on('dragend', e => updateAddress(e.target.getLatLng().lat, e.target.getLatLng().lng));
    map.on('click', e => { marker.setLatLng(e.latlng); updateAddress(e.latlng.lat, e.latlng.lng); });

    // Form validation
    document.getElementById('checkout-form').addEventListener('submit', function(e) {
        const house = document.getElementById('house_input').value.trim();
        const address = document.getElementById('address_input').value.trim();

        if (!address) { alert('Please select an address from the map'); e.preventDefault(); return; }
        if (!house) { alert('Please enter your house/unit/floor'); e.preventDefault(); return; }

        document.getElementById('cart_data_input').value = localStorage.getItem('foodCart');
        document.getElementById('payment_method_input').value = document.querySelector('input[name="payment"]:checked').value;
    });
});
</script>

<style>
body {
    background:#f8f9fa;
    font-family:'Segoe UI', sans-serif;
    margin:0; padding:0;
}
.checkout-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap:20px;
    margin: 30px auto;
    max-width: 1200px;
}
.checkout-column {
    flex:1 1 400px;
}
.checkout-box {
    background:#fff;
    padding:25px;
    border-radius:12px;
    box-shadow:0 8px 20px rgba(0,0,0,0.12);
    margin-bottom:20px;
}
.checkout-box h2 {
    margin-bottom:15px;
    font-size:22px;
    border-bottom:2px solid #ff6b35;
    display:inline-block;
    padding-bottom:5px;
    color:#333;
}
.payment-options label {
    display:block;
    padding:8px 0;
    font-weight:500;
    cursor:pointer;
}
.payment-options input {
    margin-right:8px;
}
#map { width:100%; height:300px; border-radius:8px; margin-bottom:10px; }

.order-summary {
    border: 1px solid #eee;
    border-radius: 10px;
    padding: 15px;
    background: #fafafa;
    margin-bottom: 15px;
}
.order-item {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px solid #eee;
}
.order-item:last-child { border-bottom: none; }
.item-name { font-weight: 500; }
.item-total { font-weight: 600; color: #ff6b35; }
.order-totals {
    margin-top: 15px;
    border-top: 2px solid #ff6b35;
    padding-top: 10px;
}
.order-totals div {
    display: flex;
    justify-content: space-between;
    padding: 5px 0;
    font-weight: 500;
}
.order-total {
    font-size: 18px;
    font-weight: 700;
    color: #ff6b35;
}
.checkout-btn {
    background:#ff6b35;
    color:#fff;
    border:none;
    padding:14px 0;
    font-size:18px;
    border-radius:8px;
    cursor:pointer;
    width:100%;
    transition:0.3s;
}
.checkout-btn:hover { background:#e85d28; }
input[type=text] {
    width:100%;
    padding:8px;
    margin-bottom:10px;
    border:1px solid #ccc;
    border-radius:6px;
    font-size:14px;
}
@media (max-width:900px) {
    .checkout-column { flex:1 1 100%; }
}
</style>
