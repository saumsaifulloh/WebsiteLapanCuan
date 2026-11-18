<?php
$current_page = isset($_GET['page']) ? $_GET['page'] : 'gate';

// Deteksi otomatis lokasi style.css (di folder utama, di assets/, atau di assets/css/)
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
    <!-- Google Fonts: Playfair Display & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,400;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Memanggil file CSS yang otomatis ditemukan -->
    <link rel="stylesheet" href="<?= $css_file ?>?v=<?= time() ?>">

    <?php
    // Cadangan ekstra: Jika server lokal masih memblokir path, injeksikan langsung CSS-nya
    $full_css_path = dirname(__DIR__) . '/' . $css_file;
    if (file_exists($full_css_path)) {
        echo "<style>\n" . file_get_contents($full_css_path) . "\n</style>";
    }
    ?>
</head>
<body class="page-<?= htmlspecialchars($current_page) ?>">

<?php if ($current_page === 'gate'): ?>
    <!-- Header Minimalis untuk halaman Landing Gate -->
    <header class="site-header gate-header">
        <div class="container header-inner">
            <a href="?page=home" class="brand-logo">
                <div class="logo-circle">☕</div>
                <div class="brand-text">
                    <span class="brand-title"><?= $cafe_info['name'] ?></span>
                    <span class="brand-subtitle"><?= $cafe_info['subname'] ?></span>
                </div>
            </a>
            <div class="header-right">
                <span class="pill-badge"><span class="dot-green"></span> A Cozy Haven in Malang</span>
            </div>
        </div>
    </header>
<?php else: ?>
    <!-- Header Utama untuk Home, Menu, About Us, Location (Persis Desain Figma) -->
    <header class="site-header">
        <div class="container header-inner">
            <!-- Kiri: Hanya Logo Lapan Cuan -->
            <div class="header-left">
                <a href="?page=gate" class="brand-logo" title="Lihat tampilan Welcome Gate">
                    <div class="logo-circle">☕</div>
                    <div class="brand-text">
                        <span class="brand-title"><?= $cafe_info['name'] ?></span>
                        <span class="brand-subtitle" style="line-height: 1.3;">COFFEE & SPACE •<br>MALANG</span>
                    </div>
                </a>
            </div>

            <!-- Kanan: Navigasi + A Cozy Haven in Malang -->
            <div class="header-right-group" style="display: flex; align-items: center; gap: 28px;">
                <nav class="main-nav">
                    <a href="?page=home" class="nav-link <?= $current_page === 'home' ? 'active' : '' ?>">Home</a>
                    <a href="?page=menu" class="nav-link <?= $current_page === 'menu' ? 'active' : '' ?>">Menu</a>
                    <a href="?page=about" class="nav-link <?= $current_page === 'about' ? 'active' : '' ?>">About Us</a>
                    <a href="?page=location" class="nav-link <?= $current_page === 'location' ? 'active-pill' : '' ?>">Location</a>
                </nav>

                <span class="cozy-text desktop-only" style="display: inline-flex; align-items: center; gap: 8px; font-size: 12px; color: var(--text-muted); font-weight: 500;">
                    <span class="dot-green"></span> A Cozy Haven in Malang
                </span>
            </div>
        </div>
    </header>
<?php endif; ?>