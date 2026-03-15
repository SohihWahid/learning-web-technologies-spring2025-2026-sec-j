const productSelect = document.getElementById('productSelect');
const quantityInput = document.getElementById('quantity');
const totalDisplay = document.getElementById('totalPrice');
const purchaseBtn = document.getElementById('purchaseBtn');

function updatePrice() {
    const price = parseFloat(productSelect.value);
    const qty = parseInt(quantityInput.value) || 0;
    
    if (qty < 0) {
        alert("Please enter a valid quantity!");
        quantityInput.value = 0;
        return;
    }

    const total = price * qty;
    totalDisplay.value = `$${total.toFixed(2)}`;

    if (total > 1000) {
        setTimeout(() => alert("Gift Coupon Unlocked! 🎉"), 200);
    }
}

productSelect.addEventListener('change', updatePrice);
quantityInput.addEventListener('input', updatePrice);

purchaseBtn.addEventListener('click', () => {
    if (parseInt(quantityInput.value) > 0) {
        alert(`Order Placed! Final Total: ${totalDisplay.value}`);
        quantityInput.value = "";
        totalDisplay.value = "$0.00";
    } else {
        alert("Add some items to your cart first!");
    }
});