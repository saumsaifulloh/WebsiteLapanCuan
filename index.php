<?php
require_once 'includes/koneksi.php';
require_once 'includes/helpers.php';

$page = isset($_GET['page']) ? $_GET['page'] : 'gate';
$order_success = null;
$order_error   = null;

// SIMULASI PROSES ORDER UNTUK PROTOTYPE (TANPA DATABASE)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'place_order') {
    $customer_name  = cleanInput($conn, $_POST['customer_name'] ?? '');
    $customer_phone = cleanInput($conn, $_POST['customer_phone'] ?? '');
    $table_number   = cleanInput($conn, $_POST['table_number'] ?? 'Takeaway');
    $notes          = cleanInput($conn, $_POST['notes'] ?? '');
    $cart_json      = $_POST['cart_json'] ?? '[]';
    $cart_items     = json_decode($cart_json, true);

    if (!empty($customer_name) && !empty($customer_phone) && is_array($cart_items) && count($cart_items) > 0) {
        $total_amount = 0;
        foreach ($cart_items as $item) {
            $price = (float)($item['price'] ?? 0);
            $qty   = (int)($item['qty'] ?? 0);
            $total_amount += ($price * $qty);
        }

        $order_code = 'LC-' . date('ymd') . '-' . rand(100, 999);

        // Simpan ke Session PHP sementara untuk kebutuhan Prototype
        $_SESSION['prototype_orders'][] = [
            'code'  => $order_code,
            'name'  => $customer_name,
            'phone' => $customer_phone,
            'table' => $table_number,
            'notes' => $notes,
            'total' => $total_amount,
            'items' => $cart_items
        ];

        $order_success = [
            'code'  => $order_code,
            'name'  => $customer_name,
            'table' => $table_number,
            'total' => $total_amount
        ];
    } else {
        $order_error = "Mohon lengkapi nama, nomor WhatsApp, dan pastikan keranjang tidak kosong.";
    }
}

include 'includes/header.php';
?>

<!-- Notifikasi Simulasi Order Berhasil -->
<?php if ($order_success): ?>
<div class="container" style="margin-top: 20px;">
    <div class="order-alert-success">
        <div>
            <span class="badge-pill-green">✓ PESANAN PROTOTYPE DITERIMA</span>
            <h4>Terima kasih, <?= htmlspecialchars($order_success['name']) ?>!</h4>
            <p>
                Kode Pesanan: <strong><?= $order_success['code'] ?></strong> •
                Lokasi: <strong><?= htmlspecialchars($order_success['table']) ?></strong> •
                Total: <strong><?= formatRupiah($order_success['total']) ?></strong>
            </p>
            <small>Simulasi order berhasil! Data disimpan di sesi prototype tanpa memerlukan database.</small>
        </div>
        <button onclick="this.parentElement.remove()" class="btn-outline-sm">Tutup</button>
    </div>
</div>
<script>
    localStorage.removeItem('lapancuan_cart');
</script>
<?php endif; ?>

<?php if ($order_error): ?>
<div class="container" style="margin-top: 20px;">
    <div class="order-alert-error">
        <p>⚠️ <?= htmlspecialchars($order_error) ?></p>
    </div>
</div>
<?php endif; ?>

<?php
// =========================================================================
// 1. HALAMAN LANDING GATE (Sesuai Gambar 1)[cite: 1]
// =========================================================================
if ($page === 'gate'):
?>
<main class="gate-main">
    <div class="container">
        <div class="gate-hero-card" style="background-image: linear-gradient(90deg, rgba(20,14,12,0.82) 0%, rgba(20,14,12,0.45) 55%, rgba(20,14,12,0.3) 100%), url('assets/img/Lapan Cuan Artisanal Coffee Experience.jpg');">
            <div class="gate-content">
                <div class="gate-welcome-pill">👋 WELCOME TO LAPAN CUAN</div>
                <h1 class="gate-title">
                    LAPAN CUAN
                    <span class="gate-hanzi">八大赚</span>
                </h1>
                <p class="gate-subtitle">
                    Tempat singgah untuk menikmati kopi, berbagi cerita, dan menemukan jeda di tengah kesibukan.
                </p>
                <div class="gate-cta-row">
                    <a href="?page=home" class="btn-gate-play">
                        <span class="play-icon">▶</span> PLAY / EXPLORE
                    </a>
                    <span class="gate-step-text"><span class="dot-green"></span> LANGKAH MASUK KE BERANDA</span>
                </div>
            </div>

            <div class="gate-floating-card">
                <div class="gate-floating-icon">🌱</div>
                <div>
                    <strong>Artisanal Coffee & Green Space</strong>
                    <span>Lowokwaru, Kota Malang</span>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
// =========================================================================
// 2. HALAMAN HOME (Sesuai Gambar 5)[cite: 5]
// =========================================================================
elseif ($page === 'home'):
    $favorites = getFavoriteProducts();
?>
<main class="home-main">
    <section class="home-hero container">
        <div class="home-hero-grid">
            <div class="home-hero-left">
                <span class="eyebrow-pill">☕ WELCOME TO LAPAN CUAN</span>
                <h1 class="hero-display-title">
                    Good Coffee,<br>
                    <em>Better Moments.</em>
                </h1>
                <p class="hero-lead">
                    Temukan minuman favorit dan pastry lezat untuk menemani aktivitas, bersantai, dan menikmati waktu bersama.
                </p>
                <div class="hero-cta-group">
                    <a href="?page=menu" class="btn-maroon">Explore Menu →</a>
                    <span class="hero-note"><span class="dot-green"></span> Freshly brewed daily in Malang</span>
                </div>
                <div class="hero-meta-bar">
                    <span>🕒 Buka Setiap Hari: <?= $cafe_info['hours'] ?></span>
                    <span>📍 <?= $cafe_info['short_addr'] ?></span>
                </div>
            </div>

            <div class="home-hero-right">
                <div class="hero-image-frame">
                    <img src="assets/img/About Us/Home (1).png" alt="Barista Lapan Cuan">
                    <div class="floating-badge-top">
                        <span class="badge-dot">☕</span>
                        <div>
                            <strong>GOOD MOOD</strong>
                            <small>Starts with Fresh Brews</small>
                        </div>
                    </div>
                    <div class="floating-badge-bottom" style="display: inline-flex; align-items: center; gap: 8px; font-family: 'Playfair Display', Georgia, serif; letter-spacing: 0.08em;">
                        <img src="assets/icon/icon premium beans.jpg" alt="Icon" style="width: 18px; height: 18px; object-fit: contain; display: inline-block;">
                        <span>FRESHLY BREWED</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="container">
        <div class="features-strip">
            <div class="feature-item">
                 <img src="assets/icon/icon premium beans.png" alt="Icon" style="width: 48px; height: 48px; object-fit: contain;">
                <div>
                    <h4>PREMIUM BEANS</h4>
                    <p>Biji kopi Arabica dan Robusta pilihan dengan cita rasa khas dan profil roasting terbaik.</p>
                </div>
            </div>
            <div class="feature-item">
                <img src="assets/icon/freshly.png" alt="Icon" style="width: 48px; height: 48px; object-fit: contain;">
                <div>
                    <h4>FRESHLY BREWED</h4>
                    <p>Diseduh segar setiap saat dengan presisi barista untuk menjaga kekayaan aroma dan rasa.</p>
                </div>
            </div>
            <div class="feature-item">
                <img src="assets/icon/icon pastry.png" alt="Icon" style="width: 48px; height: 48px; object-fit: contain;">
                <div>
                    <h4>PASTRY TREATS</h4>
                    <p>Hokkaido Bun hangat bertekstur lembut dengan varian Coffee, Buttermilk, dan Cheese.</p>
                </div>
            </div>
            <div class="feature-item">
                <img src="assets/icon/icon cozy.png" alt="Icon" style="width: 48px; height: 48px; object-fit: contain;">
                <div>
                    <h4>COZY AMBIENCE</h4>
                    <p>Suasana hangat, tenang, dan asri di tengah rindangnya tanaman hijau untuk melepas penat.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="favorites-section container">
        <div class="section-header-row">
            <div>
                <span class="section-eyebrow">♦ SIGNATURE SELECTION</span>
                <h2 class="section-title">Our Favorites</h2>
                <p class="section-subtitle">Pilihan menu minuman dan pastry yang paling dicintai pelanggan Lapan Cuan.</p>
            </div>
            <a href="?page=menu" class="btn-outline">View All Menu →</a>
        </div>

        <div class="product-grid-4">
            <?php foreach ($favorites as $prod): ?>
            <div class="product-card">
                <div class="product-img-wrap">
                    <img src="<?= htmlspecialchars($prod['image_url']) ?>" alt="<?= htmlspecialchars($prod['name']) ?>">
                    <?php if (!empty($prod['badge_left'])): ?>
                        <span class="badge-overlay-left"><?= htmlspecialchars($prod['badge_left']) ?></span>
                    <?php endif; ?>
                    <?php if (!empty($prod['badge_right'])): ?>
                        <span class="badge-overlay-right"><?= htmlspecialchars($prod['badge_right']) ?></span>
                    <?php endif; ?>
                </div>
                <div class="product-body">
                    <h3 class="product-title"><?= htmlspecialchars($prod['name']) ?></h3>
                    <p class="product-desc"><?= htmlspecialchars($prod['description']) ?></p>
                    <div class="product-footer">
                        <div>
                            <span class="price-label">HARGA</span>
                            <strong class="price-val"><?= formatRupiah($prod['price']) ?></strong>
                        </div>
                        <div class="product-actions">
                            <button type="button" class="btn-detail-sm"
                                onclick='showProductDetail(<?= json_encode($prod) ?>)'>
                                + Detail
                            </button>
                            <button type="button" class="btn-order-sm"
                                onclick='addToCart(<?= (int)$prod["id"] ?>, <?= json_encode($prod["name"]) ?>, <?= (float)$prod["price"] ?>)'>
                                + Order
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="container">
        <div class="promo-banner">
            <div class="promo-left">
                <div class="promo-icon">🍞</div>
                <div>
                    <h4>Pair Your Coffee with Warm Hokkaido Bun</h4>
                    <p>Nikmati paket kopi pilihan bersama roti bun hangat dengan tekstur super lembut setiap hari.</p>
                </div>
            </div>
            <a href="?page=menu#cat-pastry" class="btn-maroon">LIHAT PILIHAN PASTRY</a>
        </div>
    </section>
</main>

<?php
// =========================================================================
// 3. HALAMAN MENU & ORDER (Sesuai Gambar 3)[cite: 3]
// =========================================================================
elseif ($page === 'menu'):
    $categories = getCategoriesWithProducts();
?>
<main class="menu-main container">
    <!-- Header Katalog Menu Sesuai Desain Figma -->
    <section class="menu-hero" style="padding: 32px 0 20px;">
        <div style="font-size: 12px; letter-spacing: 0.14em; color: var(--text-muted); text-transform: uppercase; margin-bottom: 16px; font-weight: 500;">
            KATALOG MENU • CURATED SIPS
        </div>

        <div class="menu-hero-row" style="display: flex; justify-content: space-between; align-items: flex-start; gap: 32px; margin-bottom: 28px; flex-wrap: wrap;">
            <div style="max-width: 540px;">
                <h1 style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 16px; font-weight: 600; color: var(--text-main); margin-bottom: 10px;">
                    Find Your Favorite
                </h1>
                <p class="section-subtitle" style="font-size: 14px; line-height: 1.6; color: var(--text-muted);">
                    Pilih minuman dan pastry favoritmu untuk menemani setiap momen. Diseduh dengan presisi biji kopi kurasi dan bahan-bahan segar berkualitas tinggi.
                </p>
            </div>

            <div style="text-align: left; min-width: 300px;">
                <div style="display: inline-block; font-size: 13px; font-weight: 500; color: var(--text-main); padding-bottom: 8px; margin-bottom: 8px; border-bottom: 1px solid var(--border-soft);">
                    Bar Espresso Aktif • Sedia Dine-in & Takeaway
                </div>
                <div style="font-size: 12px; letter-spacing: 0.06em; color: var(--text-muted); text-transform: uppercase; line-height: 1.5;">
                    PEMESANAN DI KASIR • JL. TERUSAN SUDIMORO NO.3<br>MALANG
                </div>
            </div>
        </div>

        <!-- Filter Tab Kategori (All Menu, Coffee (6), Non Coffee (7), dst) -->
       <div class="category-tabs" style="display: flex; align-items: center; justify-content: space-between; gap: 8px; padding: 14px 0; border-top: 1px solid var(--border-soft); border-bottom: 1px solid var(--border-soft); overflow-x: visible; flex-wrap: wrap;">
            <button type="button" class="cat-tab active" onclick="filterCategory('all', this)">All Menu</button>
            <?php foreach ($categories as $cat): 
                $count_label = isset($cat['count_display']) ? $cat['count_display'] : count($cat['products']);
            ?>
                <button type="button" class="cat-tab" onclick="filterCategory('cat-<?= $cat['slug'] ?>', this)">
                    <?= htmlspecialchars($cat['name']) ?>
                    <?php if ($count_label !== ''): ?>
                        <span style="font-size: 11px; opacity: 0.65; font-weight: 400;">(<?= $count_label ?>)</span>
                    <?php endif; ?>
                </button>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Daftar Kategori & Item Menu -->
    <?php foreach ($categories as $index => $cat): ?>
    <section class="menu-category-block" id="cat-<?= htmlspecialchars($cat['slug']) ?>" style="padding-top: 28px;">
        <div class="cat-block-header" style="display: flex; justify-content: space-between; align-items: flex-start; gap: 24px; margin-bottom: 28px;">
            <div>
                <span style="font-size: 13px; letter-spacing: 0.16em; color: var(--text-muted); text-transform: uppercase; display: block; margin-bottom: 8px;">
                    0<?= $index + 1 ?> &nbsp; <?= htmlspecialchars($cat['eyebrow'] ?? 'ARTISAN ROASTERY') ?>
                </span>
                <h2 style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 17px; font-weight: 500; color: var(--text-main);">
                    <?= htmlspecialchars($cat['series_title'] ?? $cat['name']) ?>
                </h2>
            </div>
            <p class="cat-block-sub" style="max-width: 440px; font-size: 14px; color: var(--text-muted); line-height: 1.65; text-align: left;">
                <?= htmlspecialchars($cat['subtitle']) ?>
            </p>
        </div>

        <div class="product-grid-4" style="grid-template-columns: repeat(3, 1fr);">
            <?php foreach ($cat['products'] as $prod): ?>
            <div class="product-card menu-item-card" data-name="<?= strtolower(htmlspecialchars($prod['name'] . ' ' . $prod['description'])) ?>">
                <div class="product-img-wrap">
                    <img src="<?= htmlspecialchars($prod['image_url']) ?>" alt="<?= htmlspecialchars($prod['name']) ?>">
                    <?php if (!empty($prod['badge_left'])): ?>
                        <span class="badge-overlay-left"><?= htmlspecialchars($prod['badge_left']) ?></span>
                    <?php endif; ?>
                    <?php if (!empty($prod['badge_right'])): ?>
                        <span class="badge-overlay-right"><?= htmlspecialchars($prod['badge_right']) ?></span>
                    <?php endif; ?>
                </div>
                <div class="product-body">
                    <h3 class="product-title"><?= htmlspecialchars($prod['name']) ?></h3>
                    <p class="product-desc"><?= htmlspecialchars($prod['description']) ?></p>
                    <div class="product-footer">
                        <div>
                            <span class="price-label">HARGA</span>
                            <strong class="price-val"><?= formatRupiah($prod['price']) ?></strong>
                        </div>
                        <div class="product-actions">
                            <button type="button" class="btn-detail-sm"
                                onclick='showProductDetail(<?= json_encode($prod) ?>)'>
                                Detail
                            </button>
                            <button type="button" class="btn-order-sm"
                                onclick='addToCart(<?= (int)$prod["id"] ?>, <?= json_encode($prod["name"]) ?>, <?= (float)$prod["price"] ?>)'>
                                + Order
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endforeach; ?>

    <section class="menu-bottom-note">
        <div>
            <h4>ℹ️ Informasi Alergen & Kustomisasi Minuman</h4>
            <p>Beritahu barista kami atau tulis di catatan order jika Anda memiliki alergi terhadap produk susu (dairy), kacang, atau gluten. Tersedia opsi Oat Milk dan pengaturan tingkat kemanisan (Normal / Less Sugar).</p>
        </div>
        <button type="button" class="btn-maroon" onclick="toggleCartModal()">Lihat Keranjang Order →</button>
    </section>
</main>

<?php
// =========================================================================
// 4. HALAMAN ABOUT US (Sesuai Gambar 4)[cite: 4]
// =========================================================================
elseif ($page === 'about'):
?>
<main class="about-main container">
    <section class="about-hero-grid">
        <div class="about-story-left">
            <span class="eyebrow-pill">🌱 OUR STORY • ABOUT LAPAN CUAN</span>
            <h1 class="hero-display-title">More Than Just a Coffee Shop</h1>
            <p class="story-quote">“A Little Pause, A Better Day.”</p>
            <p class="hero-lead">
                Lapan Cuan hadir sebagai tempat untuk menikmati kopi, berbagi cerita, dan menemukan jeda di tengah kesibukan. Dari minuman favorit hingga pastry yang pas, setiap kunjungan dibuat terasa hangat dan sederhana.
            </p>
            <div class="about-tags-wrap">
                <span class="tag-chip">🪴 Botanical Vibe</span>
                <span class="tag-chip">☕ Manual Brew Specialty</span>
                <span class="tag-chip">💻 Peaceful Work Haven</span>
            </div>
        </div>

        <div class="about-Photo-right">
            <div class="photo-card-main">
                <img src="assets/About Us/Home (1).png" alt="Sudut Lapan Cuan">
                <span class="badge-overlay-left">📍 Artisanal Green Space in Malang</span>
            </div>
        </div>
    </section>

    <section class="about-collage-row">
        <div class="est-circle-badge">
            <span>☕</span>
            <small>EST. 2024</small>
        </div>
        <div class="collage-card">
            <img src="assets/About Us/Gambar 2.png" alt="Outdoor Rindang">
        </div>
        <div class="collage-card">
            <img src="assets/About Us/Gambarv 3.PNG" alt="Area Komunal">
            <div class="collage-caption-bar">
                <span>📍 <strong>Sudimoro, Lowokwaru</strong> • Sanctuary komunal di Kota Malang</span>
                <small>OPEN 12.00 - 00.00</small>
            </div>
        </div>
    </section>

    <section class="values-section">
        <div class="text-center">
            <span class="section-eyebrow">OUR FOUNDATIONS</span>
            <h2 class="section-title">Tiga Nilai Utama</h2>
            <p class="section-subtitle">Tiga pilar yang merangkul setiap cangkir, alunan musik, dan tatap muka di ruang komunal Lapan Cuan.</p>
        </div>

        <div class="values-grid-3">
            <div class="value-card">
                <div class="value-icon">🎯</div>
                <span class="value-num">01 / KETULUSAN RASA</span>
                <h3>Quality</h3>
                <p>Kualitas rasa dan pengalaman pelanggan melalui kurasi biji kopi dan bahan baku terbaik.</p>
                <div class="value-foot">☕ Selected Beans & Artisanal Bake</div>
            </div>

            <div class="value-card">
                <div class="value-icon">🛋️</div>
                <span class="value-num">02 / RUANG TEDUH</span>
                <h3>Comfort</h3>
                <p>Suasana nyaman untuk bersantai, mengerjakan tugas, maupun berkumpul tanpa rasa terburu-buru.</p>
                <div class="value-foot">🌿 Pencahayaan Hangat & Alami</div>
            </div>

            <div class="value-card">
                <div class="value-icon">🤝</div>
                <span class="value-num">03 / TITIK JUMPA</span>
                <h3>Connection</h3>
                <p>Tempat bertemu, berbagi cerita, dan membangun kebersamaan antar komunitas kreatif.</p>
                <div class="value-foot">💬 Komunitas Kreatif Malang</div>
            </div>
        </div>
    </section>

    <section class="event-highlight-card">
        <div class="event-img-col">
            <img src="https://images.unsplash.com/photo-1511192336575-5a79af67a629?w=800&q=80" alt="Kemisan Jazz">
            <span class="badge-overlay-left">🎵 LIVE ACOUSTIC & JAZZ SESSION</span>
        </div>
        <div class="event-info-col">
            <span class="section-eyebrow">📅 WEEKLY COMMUNITY EVENT</span>
            <h2>Kemisan Jazz</h2>
            <div class="event-time-badge">🕒 Setiap Kamis, mulai pukul 19.30 WIB</div>
            <p>
                Musik live acoustic & jazz yang santai untuk menemani malam santaimu bersama teman dan secangkir kopi hangat di ruang semi-outdoor kami.
            </p>
            <div class="event-mini-grid">
                <div class="event-mini-box">
                    <strong>🎷 Signature Night Vibe</strong>
                    <span>Suasana temaram teduh & intim</span>
                </div>
                <div class="event-mini-box">
                    <strong>🪑 Open Space Friendly</strong>
                    <span>Dapat dinikmati seluruh pengunjung</span>
                </div>
            </div>
        </div>
    </section>

    <section class="about-cta-box text-center">
        <div class="logo-circle-lg">☕</div>
        <span class="section-eyebrow">WELCOME TO OUR SANCTUARY</span>
        <h2>Ingin singgah atau menikmati menu favorit kami?</h2>
        <p>Temukan racikan kopi khas Sudimoro, camilan artisanal, serta kehangatan ruang hijau kami di Kota Malang.</p>
        <div class="cta-btn-group">
            <a href="?page=menu" class="btn-maroon">🍴 Explore Menu</a>
            <a href="?page=location" class="btn-outline">📍 Lihat Lokasi & Jam Buka</a>
        </div>
    </section>
</main>

<?php
// =========================================================================
// 5. HALAMAN LOCATION (Sesuai Gambar 2)[cite: 2]
// =========================================================================
elseif ($page === 'location'):
?>
<main class="location-main container">
    <section class="loc-header">
        <span class="eyebrow-pill">📍 VISIT OUR SANCTUARY • MALANG</span>
        <h1 class="hero-display-title">Come Say Hello</h1>
        <p class="loc-highlight-sub">Temukan tempat singgah favoritmu berikutnya.</p>
        <p class="section-subtitle">Sebuah ruang hangat di Lowokwaru untuk menikmati kopi, merajut percakapan, dan menemukan ketenangan di tengah hiruk pikuk kota.</p>
    </section>

    <section class="location-grid">
        <div class="loc-left-col">
            <div class="loc-card">
                <div class="loc-card-head">
                    <span class="loc-icon">📍</span>
                    <div>
                        <small>LOKASI SANCTUARY</small>
                        <h3>Alamat Lengkap</h3>
                    </div>
                </div>
                <div class="loc-inner-box">
                    <span class="inner-label">TITIK PRESISI</span>
                    <strong><?= $cafe_info['address'] ?></strong>
                    <p>Kawasan tenang dan asri di Malang, mudah diakses kendaraan roda dua maupun roda empat dengan area parkir nyaman.</p>
                </div>
                <div class="loc-btn-row">
                    <a href="<?= $cafe_info['gmaps_url'] ?>" target="_blank" class="btn-maroon">🧭 Get Directions ↗</a>
                    <span class="tag-chip">🗺️ Google Maps Ready</span>
                </div>
            </div>

            <div class="loc-card">
                <div class="loc-card-head">
                    <span class="loc-icon">🕒</span>
                    <div>
                        <small>WAKTU KUNJUNGAN</small>
                        <h3>Jam Operasional</h3>
                    </div>
                    <span class="pill-badge-right"><span class="dot-green"></span> BUKA SETIAP HARI</span>
                </div>
                <div class="loc-hours-box">
                    <div>
                        <span class="inner-label">JADWAL SEDUH</span>
                        <strong class="big-hours"><?= $cafe_info['hours'] ?></strong>
                    </div>
                    <div class="ready-pill">
                        ✨ Ready to serve your favorite brew
                    </div>
                </div>
            </div>

            <div class="loc-card">
                <h3>Terhubung Bersama Kami</h3>
                <div class="contact-grid-2">
                    <div class="contact-box">
                        <small>WHATSAPP OFFICIAL</small>
                        <strong><?= $cafe_info['whatsapp_display'] ?></strong>
                        <a href="https://wa.me/<?= $cafe_info['whatsapp'] ?>" target="_blank" class="btn-outline-sm">Chat WhatsApp ↗</a>
                    </div>
                    <div class="contact-box">
                        <small>INSTAGRAM OFFICIAL</small>
                        <strong>@<?= $cafe_info['instagram'] ?></strong>
                        <a href="https://instagram.com/<?= $cafe_info['instagram'] ?>" target="_blank" class="btn-outline-sm">Ikuti @<?= $cafe_info['instagram'] ?> ↗</a>
                    </div>
                </div>
            </div>

            <div class="loc-card">
                <div class="loc-card-head-between">
                    <h3>Fasilitas & Kenyamanan</h3>
                    <small>SANCTUARY COMFORT</small>
                </div>
                <div class="facility-chips">
                    <span class="tag-chip">🌳 Area Semi-Outdoor Teduh</span>
                    <span class="tag-chip">🔌 Banyak Colokan / Power Outlets</span>
                    <span class="tag-chip">📶 Wi-Fi Cepat untuk WFC</span>
                    <span class="tag-chip">🕌 Musholla Bersih</span>
                    <span class="tag-chip">🤝 Ramah Komunitas</span>
                </div>
            </div>
        </div>

        <div class="loc-right-col">
            <div class="map-Showcase-card">
                <div class="map-floating-info">
                    <span class="dot-green"></span> <strong>SANCTUARY POINT: LAPAN CUAN</strong>
                    <p><?= $cafe_info['address'] ?></p>
                    <small>🕒 Buka • <?= $cafe_info['hours'] ?></small>
                </div>

                <div class="map-canvas">
                   <iframe 
                    src="https://maps.google.com/maps?q=Lapan+Cuan+Jl.+Terusan+Sudimoro+No.3+Malang&ll=-7.9299828,112.6248867&z=18&output=embed" 
                    width="100%" 
                    height="100%" 
                    style="border:0; min-height: 380px; border-radius: 16px;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>

                <div class="map-footer-bar">
                    <span>📍 LOWOKWARU SECTOR • KOTA MALANG • EAST JAVA</span>
                    <div class="map-actions">
                        <button type="button" class="btn-outline-sm" onclick="copyCoords('<?= $cafe_info['coords'] ?>')">📋 Salin Koordinat</button>
                        <a href="<?= $cafe_info['gmaps_url'] ?>" target="_blank" class="btn-maroon-sm">Buka di Google Maps ↗</a>
                    </div>
                </div>
            </div>

            <div class="ambience-highlight-card">
                <img src="assets/About Us/Gambar 2.png" alt="Outdoor Courtyard">
                <div class="ambience-text">
                    <span class="section-eyebrow">🏡 RUANG KOMUNAL RAMAH IDE</span>
                    <h3>Suasana Nyaman Tanpa Terburu-buru</h3>
                    <p>Dari sudut hening untuk menuangkan ide tulisan dan tugas harian, hingga halaman terbuka beralaskan semilir angin sejuk Malang di kala petang.</p>
                    <div class="ambience-stats">
                        <div><strong>60+</strong><small>KAPASITAS KURSI</small></div>
                        <div><strong>100%</strong><small>BIJI KOPI KURASI</small></div>
                        <div><strong>Free</strong><small>HIGH-SPEED WIFI</small></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="tips-section">
        <span class="eyebrow-pill">💡 PANDUAN LOKASI</span>
        <h2 class="section-title">Tips Berkunjung ke Lapan Cuan</h2>
        <p class="section-subtitle">Pengalaman bersantai terbaik tercipta dari waktu dan sudut yang tepat. Berikut panduan ringkas untuk kunjunganmu.</p>

        <div class="values-grid-3">
            <div class="value-card">
                <div class="value-icon">🌅</div>
                <span class="value-num">GOLDEN HOUR VIBES</span>
                <h3>Waktu Terbaik Berkunjung</h3>
                <p>Sore menjelang senja untuk menikmati semilir angin sejuk dan suasana lampu temaram yang syahdu di area semi-outdoor kami.</p>
                <div class="value-foot">🕒 Rekomendasi: 15.30 – 19.00 WIB</div>
            </div>

            <div class="value-card">
                <div class="value-icon">🎷</div>
                <span class="value-num">COMMUNITY NIGHT</span>
                <h3>Kemisan Jazz & Komunitas</h3>
                <p>Datang lebih awal setiap Kamis malam untuk mendapatkan sudut duduk favorit saat sesi live acoustic & jazz intim berlangsung.</p>
                <div class="value-foot">📅 Setiap Kamis Mulai 19.30 WIB</div>
            </div>

            <div class="value-card">
                <div class="value-icon">📋</div>
                <span class="value-num">GATHERING & WFC</span>
                <h3>Reservasi & Sewa Ruang</h3>
                <p>Hubungi WhatsApp <strong><?= $cafe_info['whatsapp_display'] ?></strong> untuk reservasi grup kecil, diskusi komunitas, workshop, atau private gathering.</p>
                <div class="value-foot"><a href="https://wa.me/<?= $cafe_info['whatsapp'] ?>" target="_blank">Hubungi Sekarang →</a></div>
            </div>
        </div>
    </section>
</main>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>