<?php if ($current_page === 'gate' || $current_page === 'home'): ?>
    <!-- Bar Footer Ringkas (Sesuai Gambar 1 & Gambar 5) -->
    <footer class="simple-footer">
        <div class="container simple-footer-inner">
            <div class="sf-left">
                <span class="logo-circle-sm">8</span>
                <strong><?= $cafe_info['name'] ?></strong>
                <span class="sep">•</span>
                <span><?= $cafe_info['short_addr'] ?></span>
            </div>
            <div class="sf-right">
                <span>🕒 Jam Buka: <?= $cafe_info['hours'] ?></span>
                <span class="sep">•</span>
                <span>© 2026 Lapan Cuan Coffee & Space</span>
            </div>
        </div>
    </footer>
<?php else: ?>
    <!-- Footer Lengkap 3 Kolom (Sesuai Gambar 2 & Gambar 4) -->
    <footer class="full-footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <div class="brand-logo">
                        <div class="logo-circle">8</div>
                        <div class="brand-text">
                            <span class="brand-title"><?= $cafe_info['name'] ?></span>
                            <span class="brand-subtitle">Coffee & Space • Malang</span>
                        </div>
                    </div>
                    <p class="footer-desc">
                        An artisanal communal coffee space in East Java designed for unhurried conversations, thoughtful work, and authentic specialty brews crafted with care.
                    </p>
                    <span class="pill-badge"><span class="dot-green"></span> WELCOMING YOU DAILY</span>
                </div>

                <div class="footer-col">
                    <h4 class="footer-heading">Sanctuary & Hours</h4>
                    <p class="footer-addr"><?= $cafe_info['address'] ?></p>
                    <p class="footer-hours"><strong>Open Daily: <?= $cafe_info['hours'] ?></strong></p>
                    <p class="footer-tags">DINE-IN • OUTDOOR YARD • CREATIVE WORKSPACE</p>
                </div>

                <div class="footer-col">
                    <h4 class="footer-heading">Explore</h4>
                    <ul class="footer-links">
                        <li><a href="?page=home">Home Experience</a></li>
                        <li><a href="?page=menu">Artisanal Menu</a></li>
                        <li><a href="?page=about">About Us</a></li>
                        <li><a href="?page=location">Location</a></li>
                        <li><a href="?page=gate">Welcome Screen</a></li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <span>© 2026 Lapan Cuan Coffee & Space. All rights reserved.</span>
                <span class="footer-crafted">Handcrafted with care in Malang</span>
            </div>
        </div>
    </footer>
<?php endif; ?>

<!-- ==========================================
     MODAL KERANJANG & CHECKOUT ORDER DATABASE
     ========================================== -->
<div class="modal-overlay" id="cartModal">
    <div class="modal-card">
        <div class="modal-header">
            <div>
                <span class="section-eyebrow">LAPAN CUAN ORDER</span>
                <h3>Keranjang Pesananmu</h3>
            </div>
            <button type="button" class="close-modal-btn" onclick="toggleCartModal()">✕</button>
        </div>

        <div class="modal-body">
            <!-- Daftar Item Keranjang -->
            <div id="cartItemsContainer" class="cart-items-list"></div>

            <!-- Form Pemesanan -->
            <form id="checkoutForm" method="POST" action="?page=menu" onsubmit="return prepareCheckoutSubmit()">
                <input type="hidden" name="action" value="place_order">
                <input type="hidden" name="cart_json" id="cartJsonInput">

                <div class="cart-summary-row">
                    <span>Total Pembayaran</span>
                    <strong id="cartTotalDisplay">Rp 0</strong>
                </div>

                <div class="form-group">
                    <label>Nama Pemesan *</label>
                    <input type="text" name="customer_name" required placeholder="Contoh: Budi Santoso">
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label>Nomor WhatsApp *</label>
                        <input type="text" name="customer_phone" required placeholder="0812xxxx">
                    </div>
                    <div class="form-group">
                        <label>Nomor Meja / Tipe</label>
                        <input type="text" name="table_number" placeholder="Meja 04 / Takeaway" value="Meja 01">
                    </div>
                </div>

                <div class="form-group">
                    <label>Catatan Pesanan (Opsional)</label>
                    <textarea name="notes" rows="2" placeholder="Contoh: Less sugar, es dipisah..."></textarea>
                </div>

                <button type="submit" class="btn-maroon btn-full" id="submitOrderBtn">
                    Konfirmasi & Buat Pesanan →
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Modal Detail Produk -->
<div class="modal-overlay" id="productDetailModal">
    <div class="modal-card modal-sm">
        <div class="modal-header">
            <h3 id="detailModalTitle">Detail Menu</h3>
            <button type="button" class="close-modal-btn" onclick="closeProductDetail()">✕</button>
        </div>
        <div class="modal-body">
            <img id="detailModalImg" src="" alt="" class="detail-modal-img">
            <p id="detailModalDesc" class="detail-modal-desc"></p>
            <div class="detail-modal-footer">
                <strong id="detailModalPrice" class="detail-modal-price"></strong>
                <button type="button" class="btn-maroon" id="detailModalAddBtn">+ Tambah ke Pesanan</button>
            </div>
        </div>
    </div>
</div>

<?php
// Deteksi otomatis lokasi script.js
$js_candidates = [
    'assets/js/script.js',
    'assets/javascript/script.js',
    'assets/script.js',
    'js/script.js',
    'script.js'
];
$js_file = 'assets/js/script.js';
foreach ($js_candidates as $candidate) {
    if (file_exists(dirname(__DIR__) . '/' . $candidate)) {
        $js_file = $candidate;
        break;
    }
}
?>
<script src="<?= $js_file ?>?v=<?= time() ?>"></script>
<?php
$full_js_path = dirname(__DIR__) . '/' . $js_file;
if (file_exists($full_js_path)) {
    echo "<script>\n" . file_get_contents($full_js_path) . "\n</script>";
}
?>
</body>
</html>