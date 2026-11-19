<?php
require_once 'includes/helpers.php';
$current_page = 'qr';
include 'includes/header.php';

// Deteksi otomatis base URL proyek saat ini
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
$host     = $_SERVER['HTTP_HOST'];
$basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
$baseUrl  = $protocol . $host . $basePath;

$tables = [1, 2, 3, 4, 5, 6, 7, 8];
?>

<main class="container" style="padding: 36px 20px 70px;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; margin-bottom: 28px;">
        <div>
            <span class="eyebrow-pill">📱 SELF-ORDER QR SYSTEM</span>
            <h1 class="section-title" style="margin-top: 8px;">QR Code Meja Pelanggan</h1>
            <p class="section-subtitle">Cetak dan tempel di setiap meja. Pelanggan cukup scan untuk memesan &amp; bayar via QRIS.</p>
        </div>
        <button type="button" onclick="window.print()" class="btn-maroon">🖨️ Cetak Semua QR Meja</button>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 22px;">
        <?php foreach ($tables as $num): 
            $orderLink = $baseUrl . '/index.php?page=menu&meja=' . $num;
        ?>
        <div class="product-card" style="text-align: center; padding: 24px 18px; border: 1.5px solid #3D1C1A;">
            <div style="font-size: 11px; letter-spacing: 0.14em; color: #8C6D62; font-weight: 700;">LAPAN CUAN • SELF ORDER</div>
            <h2 style="font-family: 'Playfair Display', serif; font-size: 24px; color: #3D1C1A; margin: 6px 0 12px;">MEJA #<?= $num ?></h2>

            <img src="https://api.qrserver.com/v1/create-qr-code/?size=190x190&data=<?= urlencode($orderLink) ?>"
                 alt="QR Meja <?= $num ?>"
                 style="width: 180px; height: 180px; margin: 0 auto 14px; border: 1px solid #EDE4DF; padding: 8px; border-radius: 12px; background: #FFF;">

            <p style="font-size: 13px; font-weight: 600; color: #2B1E1A; margin-bottom: 4px;">Scan untuk Pesan &amp; Bayar QRIS</p>
            <small style="display: block; font-size: 11px; color: #6B5B55; margin-bottom: 14px;">Pesanan selesai dapat langsung diambil di kasir</small>

            <a href="index.php?page=menu&meja=<?= $num ?>" class="btn-outline-sm" style="display: inline-block;">
                Simulasi Scan Meja #<?= $num ?> →
            </a>
        </div>
        <?php endforeach; ?>
    </div>
</main>

<style>
@media print {
    .site-header, footer, .btn-maroon, .btn-outline-sm { display: none !important; }
    body { background: #FFF !important; }
}
</style>

<?php include 'includes/footer.php'; ?>