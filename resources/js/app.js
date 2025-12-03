import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();





// Toast Notification Logic
window.showToast = function (message, type = 'success') {
    // Create toast container if it doesn't exist
    let container = document.getElementById('toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'fixed bottom-4 right-4 z-50 flex flex-col gap-2';
        document.body.appendChild(container);
    }

    // Create toast element
    const toast = document.createElement('div');
    const bgColor = type === 'success' ? 'bg-green-500' : (type === 'error' ? 'bg-red-500' : 'bg-blue-500');
    toast.className = `${bgColor} text-white px-6 py-3 rounded-lg shadow-lg transform transition-all duration-300 translate-y-10 opacity-0 flex items-center gap-2`;

    // Icon based on type
    let icon = '';
    if (type === 'success') {
        icon = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>`;
    } else if (type === 'error') {
        icon = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>`;
    }

    toast.innerHTML = `${icon} <span class="font-medium">${message}</span>`;
    container.appendChild(toast);

    // Animate in
    requestAnimationFrame(() => {
        toast.classList.remove('translate-y-10', 'opacity-0');
    });

    // Remove after 3 seconds
    setTimeout(() => {
        toast.classList.add('translate-y-10', 'opacity-0');
        setTimeout(() => {
            toast.remove();
        }, 300);
    }, 3000);
};

// Global Wishlist & Cart Logic
window.toggleWishlist = function (button, productId) {
    // Add click animation
    button.classList.add('scale-75');
    setTimeout(() => button.classList.remove('scale-75'), 150);

    fetch('/wishlist/toggle', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ product_id: productId })
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update icon
                const svg = button.querySelector('svg');
                if (svg) {
                    if (data.added) {
                        button.classList.remove('text-gray-400', 'hover:text-red-500');
                        button.classList.add('text-red-500');
                        svg.setAttribute('fill', 'currentColor');

                        // Heart beat animation
                        button.classList.add('animate-ping-once');
                        setTimeout(() => button.classList.remove('animate-ping-once'), 500);
                    } else {
                        button.classList.add('text-gray-400', 'hover:text-red-500');
                        button.classList.remove('text-red-500');
                        svg.setAttribute('fill', 'none');
                    }
                }

                // Update ALL wishlist counts in navbar (desktop & mobile if any)
                const wishlistCounts = document.querySelectorAll('#wishlist-count');
                wishlistCounts.forEach(countEl => {
                    countEl.textContent = data.count;
                    countEl.style.display = data.count > 0 ? 'inline-flex' : 'none';

                    // Badge pop animation
                    countEl.classList.add('scale-125');
                    setTimeout(() => countEl.classList.remove('scale-125'), 200);
                });

                showToast(data.message, 'success');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Terjadi kesalahan, silakan coba lagi.', 'error');
        });
};

window.addToCart = function (productId, quantity = 1) {
    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: quantity
        })
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update ALL cart counts in navbar
                const cartCounts = document.querySelectorAll('#cart-count');
                cartCounts.forEach(countEl => {
                    countEl.textContent = data.cartCount;
                    countEl.style.display = data.cartCount > 0 ? 'inline-flex' : 'none';

                    // Badge pop animation
                    countEl.classList.add('scale-125');
                    setTimeout(() => countEl.classList.remove('scale-125'), 200);
                });

                showToast(data.message, 'success');
            } else {
                showToast(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Terjadi kesalahan, silakan coba lagi.', 'error');
        });
};

window.removeFromWishlist = function (button, wishlistId) {
    // Find the container element to remove (adjust selector based on your layout)
    const itemContainer = button.closest('.group');

    // Add loading state
    const originalContent = button.innerHTML;
    button.innerHTML = '<svg class="animate-spin h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
    button.disabled = true;

    fetch(`/wishlist/${wishlistId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Animate removal
                itemContainer.style.transition = 'all 0.5s ease';
                itemContainer.style.opacity = '0';
                itemContainer.style.transform = 'scale(0.9)';

                setTimeout(() => {
                    itemContainer.remove();

                    // Check if empty
                    if (data.count === 0) {
                        location.reload(); // Simple way to show empty state
                    }
                }, 500);

                // Update navbar counts
                const wishlistCounts = document.querySelectorAll('#wishlist-count');
                wishlistCounts.forEach(countEl => {
                    countEl.textContent = data.count;
                    countEl.style.display = data.count > 0 ? 'inline-flex' : 'none';
                    countEl.classList.add('scale-125');
                    setTimeout(() => countEl.classList.remove('scale-125'), 200);
                });

                showToast(data.message, 'success');
            } else {
                button.innerHTML = originalContent;
                button.disabled = false;
                showToast('Gagal menghapus produk', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            button.innerHTML = originalContent;
            button.disabled = false;
            showToast('Terjadi kesalahan', 'error');
        });
};

window.removeFromCart = function (button, cartId) {
    const itemContainer = button.closest('.p-6'); // Adjust based on cart layout

    // Add loading state
    const originalContent = button.innerHTML;
    button.innerHTML = '<svg class="animate-spin h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Removing...';
    button.disabled = true;

    fetch(`/cart/${cartId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Animate removal
                itemContainer.style.transition = 'all 0.5s ease';
                itemContainer.style.opacity = '0';
                itemContainer.style.transform = 'translateX(20px)';

                setTimeout(() => {
                    // Find parent containers before removing
                    const itemsWrapper = itemContainer.parentElement;
                    const storeContainer = itemsWrapper.closest('.bg-white.rounded-xl');

                    itemContainer.remove();

                    // Check if store is empty (no more items in the wrapper)
                    if (itemsWrapper.children.length === 0 && storeContainer) {
                        storeContainer.style.transition = 'all 0.5s ease';
                        storeContainer.style.opacity = '0';
                        storeContainer.style.height = '0';
                        storeContainer.style.marginBottom = '0';

                        setTimeout(() => {
                            storeContainer.remove();

                            // Update selected stores count
                            const selectedCount = document.querySelectorAll('.store-checkbox:checked').length;
                            const selectedStoresCountEl = document.getElementById('selectedStoresCount');
                            if (selectedStoresCountEl) {
                                selectedStoresCountEl.textContent = selectedCount;
                            }
                        }, 500);
                    }

                    // Check if cart is completely empty
                    if (data.isEmpty) {
                        location.reload();
                    }
                }, 500);

                // Update navbar counts
                const cartCounts = document.querySelectorAll('#cart-count');
                cartCounts.forEach(countEl => {
                    countEl.textContent = data.cartCount;
                    countEl.style.display = data.cartCount > 0 ? 'inline-flex' : 'none';
                    countEl.classList.add('scale-125');
                    setTimeout(() => countEl.classList.remove('scale-125'), 200);
                });

                // Update totals
                const grandTotalEl = document.getElementById('grandTotal');
                if (grandTotalEl) grandTotalEl.textContent = 'Rp ' + data.grandTotal;

                const totalItemsEl = document.getElementById('totalItems');
                if (totalItemsEl) totalItemsEl.textContent = data.totalItems;

                showToast(data.message, 'success');
            } else {
                button.innerHTML = originalContent;
                button.disabled = false;
                showToast('Gagal menghapus produk', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            button.innerHTML = originalContent;
            button.disabled = false;
            showToast('Terjadi kesalahan', 'error');
        });
};
