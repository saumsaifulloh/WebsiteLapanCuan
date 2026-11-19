<?php
require_once 'includes/koneksi.php';
require_once 'includes/helpers.php';

$code = trim($_GET['code'] ?? '');
$order = ($code && function_exists('getDummyOrderByCode')) ? getDummyOrderByCode($code) : null;

// Endpoint ringan untuk cek status otomatis tanpa reload halaman
if (isset($_GET['ajax']) && $_GET['ajax'] === '1') {
    header('Content-Type: application/json');
    echo json_encode(['status' => $order['status'] ?? 'not_found']);
    exit;
}

$current_page = 'status';
include 'includes/header.php';
?>

<main class="container" style="padding: 28px 20px 80px; max-width: 620px; margin: 0 auto;">

    <!-- BAR NAVIGASI KEMBALI -->
    <div style="background: #FAF7F5; border: 1px solid #EDE4DF; border-radius: 14px; padding: 12px 16px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
        <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
            <a href="index.php?page=menu" class="btn-outline-sm">← Kembali ke Menu</a>
            <a href="index.php?page=home" class="btn-outline-sm">🏠 Home</a>
        </div>
        <a href="index.php?reset_meja=1" style="font-size: 12px; color: #8C6D62; text-decoration: underline;">Keluar Mode Meja</a>
    </div>

    <?php if (!$order): ?>
        <div class="product-card" style="padding: 32px; text-align: center;">
            <h2 style="color: #3D1C1A; margin-bottom: 10px;">Pesanan Tidak Ditemukan</h2>
            <p style="color: #6B5B55; margin-bottom: 20px;">Kode pesanan tidak valid atau sudah kedaluwarsa.</p>
            <a href="index.php?page=menu" class="btn-maroon">← Kembali ke Menu</a>
        </div>
    <?php else: 
        $status = $order['status'] ?? 'preparing';
    ?>
        <?php if (isset($_GET['new'])): ?>
        <script>
            localStorage.removeItem('lapancuan_cart');
        </script>
        <?php endif; ?>

        <div class="product-card" style="padding: 28px; text-align: center; border: 2px solid #3D1C1A;">
            <span class="badge-pill-green" style="font-size: 12px;">✓ PEMBAYARAN QRIS LUNAS</span>

            <div style="margin-top: 16px; font-size: 12px; letter-spacing: 0.12em; color: #8C6D62; font-weight: 700;">
                NOMOR ANTREAN / KODE PENGAMBILAN
            </div>
            <h1 style="font-family: 'Playfair Display', serif; font-size: 34px; color: #3D1C1A; margin: 6px 0 4px;">
                <?= htmlspecialchars($order['code']) ?>
            </h1>
            <div style="font-size: 14px; color: #6B5B55; margin-bottom: 22px;">
                Atas Nama: <strong><?= htmlspecialchars($order['name']) ?></strong> • <strong><?= htmlspecialchars($order['table']) ?></strong>
            </div>

            <?php if ($status === 'preparing'): ?>
                <div style="background: #FFF4E5; border: 1.5px solid #F5C27A; color: #9A4D00; padding: 18px; border-radius: 14px; margin-bottom: 22px;">
                    <div style="font-size: 26px; margin-bottom: 4px;">☕⏳</div>
                    <strong style="font-size: 16px; display: block;">Pesanan Sedang Dibuat Barista</strong>
                    <small style="display: block; margin-top: 4px;">Mohon tunggu sebentar di meja Anda. Halaman ini akan otomatis berubah saat pesanan siap diambil.</small>
                </div>
            <?php elseif ($status === 'ready'): ?>
                <div style="background: #E7F6EC; border: 2px solid #2D8A4E; color: #155724; padding: 20px; border-radius: 14px; margin-bottom: 22px;">
                    <div style="font-size: 28px; margin-bottom: 4px;">🔔✨</div>
                    <strong style="font-size: 18px; display: block;">PESANAN SIAP DIAMBIL DI KASIR!</strong>
                    <small style="display: block; margin-top: 4px;">Silakan menuju meja kasir dan tunjukkan kode <strong><?= htmlspecialchars($order['code']) ?></strong>.</small>
                </div>
            <?php else: ?>
                <div style="background: #F4F2F1; border: 1.5px solid #D6C7C1; color: #4A3E3B; padding: 18px; border-radius: 14px; margin-bottom: 22px;">
                    <div style="font-size: 26px; margin-bottom: 4px;">✅</div>
                    <strong style="font-size: 16px; display: block;">Pesanan Sudah Diambil</strong>
                    <small style="display: block; margin-top: 4px;">Terima kasih sudah berkunjung ke Lapan Cuan. Selamat menikmati!</small>
                </div>
            <?php endif; ?>

            <div style="text-align: left; border-top: 1px dashed #D6C7C1; border-bottom: 1px dashed #D6C7C1; padding: 16px 0; margin-bottom: 18px;">
                <div style="font-size: 12px; font-weight: 700; color: #8C6D62; margin-bottom: 10px; letter-spacing: 0.08em;">RINCIAN PESANAN</div>
                <?php foreach ($order['items'] as $it): ?>
                    <div style="display: flex; justify-content: space-between; font-size: 14px; margin-bottom: 6px;">
                        <span><?= (int)$it['qty'] ?>x <?= htmlspecialchars($it['name']) ?></span>
                        <strong><?= formatRupiah($it['price'] * $it['qty']) ?></strong>
                    </div>
                <?php endforeach; ?>
                <?php if (!empty($order['notes'])): ?>
                    <div style="font-size: 12px; color: #8C6D62; margin-top: 8px;">Catatan: <em><?= htmlspecialchars($order['notes']) ?></em></div>
                <?php endif; ?>
            </div>

            <div style="display: flex; justify-content: space-between; font-size: 17px; font-weight: 700; color: #3D1C1A; margin-bottom: 24px;">
                <span>Total Dibayar (QRIS)</span>
                <span><?= formatRupiah($order['total']) ?></span>
            </div>

            <div style="display: flex; gap: 10px; justify-content: center; flex-wrap: wrap;">
                <a href="index.php?page=menu" class="btn-maroon" style="flex: 1; min-width: 160px;">+ Pesan Menu Lagi</a>
                <button type="button" onclick="window.location.reload()" class="btn-outline-sm" style="padding: 10px 16px;">🔄 Cek Status</button>
            </div>
        </div>

        <?php if ($status !== 'completed'): ?>
        <script>
            let currentStatus = <?= json_encode($status) ?>;
            setInterval(function () {
                fetch('status-pesanan.php?code=<?= urlencode($order['code']) ?>&ajax=1')
                    .then(r => r.json())
                    .then(data => {
                        if (data.status && data.status !== currentStatus) {
                            window.location.reload();
                        }
                    })
                    .catch(() => {});
            }, 4000);
        </script>
        <?php endif; ?>
    <?php endif; ?>
</main>

<?php include 'includes/footer.php'; ?>