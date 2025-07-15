document.addEventListener('DOMContentLoaded', function () {
    // Quantity update with debounce on input and immediate update on change
    document.querySelectorAll('.cart-qty-input').forEach(input => {
        let debounceTimer;
        input.addEventListener('input', function () {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                const key = this.dataset.cartKey;
                const quantity = this.value;

                fetch(wc_cart_params.ajax_url, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams({
                        action: 'update_cart_item',
                        cart_item_key: key,
                        quantity: quantity,
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                })
                .catch(error => console.error('Cart update failed:', error));
            }, 750);
        });

        // Immediate update on arrow changes (change event)
        input.addEventListener('change', function () {
            clearTimeout(debounceTimer);
            const key = this.dataset.cartKey;
            const quantity = this.value;

            fetch(wc_cart_params.ajax_url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({
                    action: 'update_cart_item',
                    cart_item_key: key,
                    quantity: quantity,
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => console.error('Cart update failed:', error));
        });
    });

    // Remove item
    document.querySelectorAll('.remove-item').forEach(button => {
        button.addEventListener('click', function () {
            const key = this.dataset.cartKey;

            fetch(wc_cart_params.ajax_url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({
                    action: 'remove_cart_item',
                    cart_item_key: key,
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        });
    });
});

console.log('Cart AJAX script loaded successfully.');