import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();





// Toast Notification Logic
window.showToast = function (message, type = 'success') {
    console.log('[TOAST] showToast called:', { message, type });

    // Create toast container if it doesn't exist
    let container = document.getElementById('toast-container');
    if (!container) {
        console.log('[TOAST] Creating container...');
        container = document.createElement('div');
        container.id = 'toast-container';
        // Use inline styles instead of Tailwind classes
        container.style.cssText = 'position: fixed; bottom: 24px; left: 50%; transform: translateX(-50%); z-index: 9999; display: flex; flex-direction: column; gap: 12px; align-items: center; pointer-events: none; width: 100%; max-width: 400px; padding: 0 16px;';
        document.body.appendChild(container);
        console.log('[TOAST] Container created:', container);
    }

    // Create toast element
    const toast = document.createElement('div');
    const bgColor = type === 'success' ? '#10b981' : (type === 'error' ? '#ef4444' : '#3b82f6');

    // Use inline styles for guaranteed visibility
    toast.style.cssText = `
        background-color: ${bgColor};
        color: white;
        padding: 8px 16px;
        border-radius: 50px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        display: inline-flex;
        align-items: center;
        gap: 8px;
        pointer-events: auto;
        font-weight: 500;
        font-size: 13px;
        white-space: nowrap;
        max-width: 85vw;
        overflow: hidden;
        text-overflow: ellipsis;
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    `;

    // Icon based on type
    let icon = '';
    if (type === 'success') {
        icon = `<svg style="width: 20px; height: 20px; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>`;
    } else if (type === 'error') {
        icon = `<svg style="width: 20px; height: 20px; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>`;
    }

    toast.innerHTML = `${icon} <span>${message}</span>`;
    container.appendChild(toast);
    console.log('[TOAST] Toast created and appended:', toast);

    // Animate in - use setTimeout to force reflow
    setTimeout(() => {
        toast.style.opacity = '1';
        toast.style.transform = 'translateY(0)';
        console.log('[TOAST] Animation triggered');
    }, 10);

    // Remove after 3 seconds
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(20px)';
        setTimeout(() => {
            toast.remove();
            console.log('[TOAST] Toast removed');
        }, 300);
    }, 3000);
};

// Global Wishlist & Cart Logic
window.toggleWishlist = function (button, productId) {
    // Add click animation
    button.classList.add('scale-75');
    setTimeout(() => button.classList.remove('scale-75'), 150);

    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    const baseUrl = document.querySelector('meta[name="base-url"]');

    const url = baseUrl ? `${baseUrl.getAttribute('content')}/wishlist/toggle` : '/wishlist/toggle';

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken.getAttribute('content')
        },
        body: JSON.stringify({ product_id: productId })
    })
        .then(response => {
            if (response.status === 401) {
                const loginUrl = baseUrl ? `${baseUrl.getAttribute('content')}/login` : '/login';
                window.location.href = loginUrl;
                throw new Error('Unauthorized');
            }
            return response.json();
        })
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

                // Update ALL wishlist counts in navbar
                const wishlistCounts = document.querySelectorAll('.wishlist-count');
                wishlistCounts.forEach(countEl => {
                    countEl.textContent = data.count;
                    countEl.style.display = data.count > 0 ? 'inline-flex' : 'none';

                    // Badge pop animation
                    countEl.classList.add('scale-125');
                    setTimeout(() => countEl.classList.remove('scale-125'), 200);
                });
            }
        })
        .catch(error => {
            if (error.message !== 'Unauthorized') {
                console.error('[WISHLIST] Error:', error);
            }
        });
};

window.addToCart = function (productId, quantity = 1) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    const baseUrl = document.querySelector('meta[name="base-url"]');

    const url = baseUrl ? `${baseUrl.getAttribute('content')}/cart/add` : '/cart/add';

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken.getAttribute('content')
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: quantity
        })
    })
        .then(response => {
            if (response.status === 401) {
                const loginUrl = baseUrl ? `${baseUrl.getAttribute('content')}/login` : '/login';
                window.location.href = loginUrl;
                throw new Error('Unauthorized');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Update ALL cart counts in navbar
                const cartCounts = document.querySelectorAll('.cart-count');
                cartCounts.forEach(countEl => {
                    countEl.textContent = data.cartCount;
                    countEl.style.display = data.cartCount > 0 ? 'inline-flex' : 'none';

                    // Badge pop animation
                    countEl.classList.add('scale-125');
                    setTimeout(() => countEl.classList.remove('scale-125'), 200);
                });

                // Show success toast
                if (window.showToast) {
                    window.showToast(data.message || 'Produk berhasil ditambahkan', 'success');
                }
            } else {
                // Show error toast
                if (window.showToast) {
                    window.showToast(data.message || 'Gagal menambahkan produk', 'error');
                }
            }
        })
        .catch(error => {
            if (error.message !== 'Unauthorized') {
                console.error('[CART] Error:', error);
                if (window.showToast) {
                    window.showToast('Terjadi kesalahan sistem', 'error');
                }
            }
        });
};

window.removeFromWishlist = function (button, wishlistId) {
    // Find the container element to remove (adjust selector based on your layout)
    const itemContainer = button.closest('.group');

    // Add loading state
    const originalContent = button.innerHTML;
    button.innerHTML = '<svg class="animate-spin h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
    button.disabled = true;

    const baseUrl = document.querySelector('meta[name="base-url"]');
    const url = baseUrl ? `${baseUrl.getAttribute('content')}/wishlist/${wishlistId}` : `/wishlist/${wishlistId}`;

    fetch(url, {
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
                const wishlistCounts = document.querySelectorAll('.wishlist-count');
                wishlistCounts.forEach(countEl => {
                    countEl.textContent = data.count;
                    countEl.style.display = data.count > 0 ? 'inline-flex' : 'none';
                    countEl.classList.add('scale-125');
                    setTimeout(() => countEl.classList.remove('scale-125'), 200);
                });
            } else {
                button.innerHTML = originalContent;
                button.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            button.innerHTML = originalContent;
            button.disabled = false;
        });
};

window.removeFromCart = function (button, cartId) {
    const itemContainer = button.closest('.p-6'); // Adjust based on cart layout

    // Add loading state
    const originalContent = button.innerHTML;
    button.innerHTML = '<svg class="animate-spin h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Removing...';
    button.disabled = true;

    const baseUrl = document.querySelector('meta[name="base-url"]');
    const url = baseUrl ? `${baseUrl.getAttribute('content')}/cart/${cartId}` : `/cart/${cartId}`;

    fetch(url, {
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
                const cartCounts = document.querySelectorAll('.cart-count');
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
            } else {
                button.innerHTML = originalContent;
                button.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            button.innerHTML = originalContent;
            button.disabled = false;
        });
};
