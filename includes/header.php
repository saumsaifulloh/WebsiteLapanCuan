<?php
$current_page = isset($_GET['page']) ? $_GET['page'] : 'gate';
$current_file = basename($_SERVER['PHP_SELF']);
$is_customer_order_mode = (!empty($_SESSION['scanned_table']) && $current_file === 'index.php') || $current_file === 'status-pesanan.php';

// Deteksi otomatis lokasi style.css
$css_candidates = [
    'assets/css/style.css',
    'assets/style.css',
    'css/style.css',
    'style.css'
];
$css_file = 'assets/css/style.css';
foreach ($css_candidates as $candidate) {
    if (file_exists(dirname(__DIR__) . '/' . $candidate)) {
        $css_file = $candidate;
        break;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lapan Cuan — Coffee & Space Malang</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,400;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="<?= $css_file ?>?v=<?= time() ?>">
    <?php
    $full_css_path = dirname(__DIR__) . '/' . $css_file;
    if (file_exists($full_css_path)) {
        echo "<style>\n" . file_get_contents($full_css_path) . "\n</style>";
    }
    ?>
</head>
<body class="page-<?= htmlspecialchars($current_page) ?>">

<?php if ($current_file === 'index.php' && $current_page === 'gate'): ?>
    <!-- Header Minimalis untuk halaman Landing Gate -->
    <header class="site-header gate-header">
        <div class="container header-inner">
            <a href="index.php?page=home" class="brand-logo">
                <div class="logo-circle">☕</div>
                <div class="brand-text">
                    <span class="brand-title"><?= $cafe_info['name'] ?></span>
                    <span class="brand-subtitle"><?= $cafe_info['subname'] ?></span>
                </div>
            </a>
            <div class="header-right" style="display:flex; gap:10px; align-items:center;">
                <a href="qr-meja.php" class="nav-link">QR Meja</a>
                <a href="kasir.php" class="nav-link active-pill">Kasir POS</a>
            </div>
        </div>
    </header>
<?php else: ?>
    <!-- Header Utama -->
    <header class="site-header">
        <div class="container header-inner">
            <div class="header-left">
                <a href="index.php?page=gate" class="brand-logo" title="Kembali ke Welcome Gate">
                    <div class="logo-circle">☕</div>
                    <div class="brand-text">
                        <span class="brand-title"><?= $cafe_info['name'] ?></span>
                        <span class="brand-subtitle" style="line-height: 1.3;">COFFEE & SPACE •<br>MALANG</span>
                    </div>
                </a>
            </div>

            <div class="header-right-group" style="display: flex; align-items: center; gap: 20px; flex-wrap: wrap;">
                <nav class="main-nav" style="display: flex; align-items: center; gap: 14px; flex-wrap: wrap;">
                    <a href="index.php?page=home" class="nav-link <?= ($current_file === 'index.php' && $current_page === 'home') ? 'active' : '' ?>">Home</a>
                    <a href="index.php?page=menu" class="nav-link <?= ($current_file === 'index.php' && $current_page === 'menu') ? 'active' : '' ?>">Menu</a>
                    <a href="index.php?page=about" class="nav-link <?= ($current_file === 'index.php' && $current_page === 'about') ? 'active' : '' ?>">About Us</a>
                    <a href="index.php?page=location" class="nav-link <?= ($current_file === 'index.php' && $current_page === 'location') ? 'active' : '' ?>">Location</a>

                    <?php if (!$is_customer_order_mode): ?>
                        <a href="qr-meja.php" class="nav-link <?= $current_file === 'qr-meja.php' ? 'active' : '' ?>">QR Meja</a>
                        <a href="kasir.php" class="nav-link <?= $current_file === 'kasir.php' ? 'active-pill' : '' ?>">Kasir POS</a>
                    <?php endif; ?>
                </nav>
            </div>
        </div>
    </header>
<?php endif; ?>