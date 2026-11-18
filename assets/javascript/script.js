let cart = JSON.parse(localStorage.getItem('lapancuan_cart')) || [];

function formatRp(num) {
    return 'Rp ' + Number(num).toLocaleString('id-ID');
}

function saveCart() {
    localStorage.setItem('lapancuan_cart', JSON.stringify(cart));
    renderCartUI();
}

function addToCart(id, name, price) {
    const existing = cart.find(item => item.id === id);
    if (existing) {
        existing.qty += 1;
    } else {
        cart.push({ id: id, name: name, price: Number(price), qty: 1 });
    }
    saveCart();
    toggleCartModal(true);
}

function changeQty(id, delta) {
    const idx = cart.findIndex(item => item.id === id);
    if (idx !== -1) {
        cart[idx].qty += delta;
        if (cart[idx].qty <= 0) {
            cart.splice(idx, 1);
        }
        saveCart();
    }
}

function renderCartUI() {
    const badge = document.getElementById('headerCartCount');
    const container = document.getElementById('cartItemsContainer');
    const totalEl = document.getElementById('cartTotalDisplay');
    const jsonInput = document.getElementById('cartJsonInput');

    const totalQty = cart.reduce((acc, item) => acc + item.qty, 0);
    const totalPrice = cart.reduce((acc, item) => acc + (item.price * item.qty), 0);

    if (badge) badge.textContent = totalQty;
    if (totalEl) totalEl.textContent = formatRp(totalPrice);
    if (jsonInput) jsonInput.value = JSON.stringify(cart);

    if (!container) return;

    if (cart.length === 0) {
        container.innerHTML = `<p style="text-align:center; color:#6E6259; padding: 18px 0;">Keranjang pesananmu masih kosong. Silakan pilih menu favoritmu.</p>`;
        return;
    }

    container.innerHTML = cart.map(item => `
        <div class="cart-item-row">
            <div>
                <strong>${item.name}</strong>
                <div style="font-size:12px; color:#6E6259;">${formatRp(item.price)} x${item.qty}</div>
            </div>
            <div class="qty-controls" style="display:flex; align-items:center; gap:8px;">
                <button type="button" onclick="changeQty(${item.id}, -1)">-</button>
                <span>${item.qty}</span>
                <button type="button" onclick="changeQty(${item.id}, 1)">+</button>
            </div>
        </div>
    `).join('');
}

function toggleCartModal(forceOpen = null) {
    const modal = document.getElementById('cartModal');
    if (!modal) return;
    if (forceOpen === true) {
        modal.classList.add('open');
    } else if (forceOpen === false) {
        modal.classList.remove('open');
    } else {
        modal.classList.toggle('open');
    }
    renderCartUI();
}

function prepareCheckoutSubmit() {
    if (cart.length === 0) {
        alert('Keranjang pesanan masih kosong! Silakan pilih menu terlebih dahulu.');
        return false;
    }
    document.getElementById('cartJsonInput').value = JSON.stringify(cart);
    return true;
}

// Modal Detail Produk
function showProductDetail(prod) {
    const modal = document.getElementById('productDetailModal');
    document.getElementById('detailModalTitle').textContent = prod.name;
    document.getElementById('detailModalImg').src = prod.image_url;
    document.getElementById('detailModalDesc').textContent = prod.description;
    document.getElementById('detailModalPrice').textContent = formatRp(prod.price);

    const addBtn = document.getElementById('detailModalAddBtn');
    addBtn.onclick = function () {
        closeProductDetail();
        addToCart(Number(prod.id), prod.name, Number(prod.price));
    };

    modal.classList.add('open');
}

function closeProductDetail() {
    document.getElementById('productDetailModal').classList.remove('open');
}

// Filter Kategori di Halaman Menu
function filterCategory(catId, btn) {
    document.querySelectorAll('.cat-tab').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    const blocks = document.querySelectorAll('.menu-category-block');
    blocks.forEach(block => {
        if (catId === 'all' || block.id === catId) {
            block.style.display = 'block';
        } else {
            block.style.display = 'none';
        }
    });
}

// Pencarian Menu Real-time
function filterMenuItems() {
    const q = document.getElementById('menuSearchInput').value.toLowerCase();
    document.querySelectorAll('.menu-item-card').forEach(card => {
        const name = card.getAttribute('data-name') || '';
        card.style.display = name.includes(q) ? 'flex' : 'none';
    });
}

// Salin Koordinat di Halaman Location
function copyCoords(coords) {
    navigator.clipboard.writeText(coords);
    alert('Koordinat berhasil disalin: ' + coords);
}

document.addEventListener('DOMContentLoaded', renderCartUI);