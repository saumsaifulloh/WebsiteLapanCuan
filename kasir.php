<?php
require_once 'includes/koneksi.php';
require_once 'includes/helpers.php';

// Update status pesanan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_status') {
    if (function_exists('updateDummyOrderStatus')) {
        updateDummyOrderStatus($_POST['order_code'], $_POST['new_status']);
    }
    header("Location: kasir.php?updated=1");
    exit;
}

// Tambah pesanan langsung dari kasir (Walk-in / Manual POS)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'kasir_manual_order') {
    $customer_name  = trim($_POST['customer_name'] ?? 'Pelanggan Kasir');
    $table_number   = trim($_POST['table_number'] ?? 'Takeaway');
    $payment_method = trim($_POST['payment_method'] ?? 'QRIS');
    $selected_items = $_POST['items'] ?? [];

    $catalog = getDummyCatalog();
    $productMap = [];
    foreach ($catalog as $cat) {
        foreach ($cat['products'] as $p) {
            $productMap[$p['id']] = $p;
        }
    }

    $orderItems = [];
    $total = 0;
    foreach ($selected_items as $prodId => $qty) {
        $qty = (int)$qty;
        if ($qty > 0 && isset($productMap[$prodId])) {
            $p = $productMap[$prodId];
            $orderItems[] = [
                'name'  => $p['name'],
                'qty'   => $qty,
                'price' => (float)$p['price']
            ];
            $total += ((float)$p['price'] * $qty);
        }
    }

    if (count($orderItems) > 0 && function_exists('saveDummyOrder')) {
        $code = 'LC-' . date('ymd') . '-' . rand(100, 999);
        saveDummyOrder([
            'code'           => $code,
            'name'           => $customer_name ?: 'Pelanggan Kasir',
            'phone'          => '-',
            'table'          => $table_number,
            'notes'          => 'Order langsung via Kasir POS',
            'payment_method' => $payment_method,
            'payment_status' => 'LUNAS',
            'status'         => 'preparing',
            'created_at'     => date('H:i'),
            'total'          => $total,
            'items'          => $orderItems
        ]);
        header("Location: kasir.php?created=1");
        exit;
    }
}

$orders = function_exists('getDummyOrders') ? getDummyOrders() : [];
$catalog = getDummyCatalog();
$todayRevenue = 0;
$activeCount = 0;
foreach ($orders as $o) {
    if (($o['payment_status'] ?? '') === 'LUNAS') {
        $todayRevenue += (float)$o['total'];
    }
    if (($o['status'] ?? '') !== 'completed') {
        $activeCount++;
    }
}

$current_page = 'kasir';
include 'includes/header.php';
?>

<main class="container" style="padding: 28px 20px 80px;">

    <!-- BAR NAVIGASI BALIK CEPAT UNTUK KASIR -->
    <div style="background: #FAF7F5; border: 1px solid #EDE4DF; border-radius: 14px; padding: 12px 18px; margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
        <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
            <a href="index.php?page=home" class="btn-outline-sm">← Kembali ke Home</a>
            <a href="index.php?page=menu" class="btn-outline-sm">🍴 Lihat Menu Pelanggan</a>
            <a href="qr-meja.php" class="btn-outline-sm">📱 Cetak QR Meja</a>
        </div>
        <div style="font-size: 12px; color: #6B5B55; font-weight: 600;">
            Antrean Aktif: <span style="color: #3D1C1A;"><?= $activeCount ?> Pesanan</span> • Auto-Refresh 6 dtk
        </div>
    </div>

    <!-- HEADER KASIR POS -->
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; margin-bottom: 22px;">
        <div>
            <span class="eyebrow-pill">🖥️ POS KASIR &amp; BARISTA</span>
            <h1 class="section-title" style="margin-top: 8px;">Dashboard Kasir Lapan Cuan</h1>
            <p class="section-subtitle">Kelola antrean pesanan dari scan QR meja maupun transaksi langsung di kasir.</p>
        </div>

        <div style="background: #3D1C1A; color: #FFF; padding: 14px 22px; border-radius: 14px;">
            <small style="opacity: 0.8; display: block; font-size: 11px; letter-spacing: 0.08em;">TOTAL OMZET HARI INI (DUMMY)</small>
            <strong style="font-size: 20px;"><?= formatRupiah($todayRevenue) ?></strong>
        </div>
    </div>

    <!-- TAB SWITCHER KASIR -->
    <div style="display: flex; gap: 10px; margin-bottom: 24px; border-bottom: 1px solid #EDE4DF; padding-bottom: 14px; flex-wrap: wrap;">
        <button type="button" id="tabBtnQueue" class="btn-maroon" onclick="switchKasirTab('queue')">
            📋 Antrean Pesanan Masuk (<?= count($orders) ?>)
        </button>
        <button type="button" id="tabBtnPos" class="btn-outline-sm" onclick="switchKasirTab('pos')" style="padding: 10px 18px; font-weight: 600;">
            ➕ Input Pesanan Baru (Kasir)
        </button>
    </div>

    <?php if (isset($_GET['updated'])): ?>
        <div class="order-alert-success" style="margin-bottom: 20px;">
            <span class="badge-pill-green">✓ Status pesanan berhasil diperbarui!</span>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['created'])): ?>
        <div class="order-alert-success" style="margin-bottom: 20px;">
            <span class="badge-pill-green">✓ Pesanan baru dari kasir berhasil masuk ke antrean!</span>
        </div>
    <?php endif; ?>

    <!-- TAB 1: ANTREAN PESANAN MEJA & QRIS -->
    <div id="sectionQueue">
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(330px, 1fr)); gap: 20px;">
            <?php foreach ($orders as $ord): 
                $status = $ord['status'] ?? 'preparing';
                $statusLabel = [
                    'preparing' => ['text' => '⏳ SEDANG DIBUAT', 'bg' => '#FFF4E5', 'color' => '#B25E09'],
                    'ready'     => ['text' => '🔔 SIAP DIAMBIL DI KASIR', 'bg' => '#E7F6EC', 'color' => '#1E6B38'],
                    'completed' => ['text' => '✓ SUDAH DIAMBIL', 'bg' => '#F1EFEF', 'color' => '#5C5452'],
                ][$status] ?? ['text' => strtoupper($status), 'bg' => '#EEE', 'color' => '#333'];
            ?>
            <div class="product-card" style="padding: 20px; display: flex; flex-direction: column; justify-content: space-between; opacity: <?= $status === 'completed' ? '0.72' : '1' ?>;">
                <div>
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px;">
                        <div>
                            <strong style="font-size: 16px; color: #3D1C1A;"><?= htmlspecialchars($ord['code']) ?></strong>
                            <div style="font-size: 13px; color: #6B5B55;">
                                <strong><?= htmlspecialchars($ord['name']) ?></strong> • <?= htmlspecialchars($ord['table']) ?> • <?= htmlspecialchars($ord['created_at'] ?? '') ?>
                            </div>
                        </div>
                        <span class="badge-pill-green" style="font-size: 11px;">LUNAS • <?= htmlspecialchars($ord['payment_method'] ?? 'QRIS') ?></span>
                    </div>

                    <div style="display: inline-block; background: <?= $statusLabel['bg'] ?>; color: <?= $statusLabel['color'] ?>; padding: 5px 10px; border-radius: 8px; font-size: 11px; font-weight: 700; margin-bottom: 12px;">
                        <?= $statusLabel['text'] ?>
                    </div>

                    <div style="border-top: 1px dashed #E0D6D1; border-bottom: 1px dashed #E0D6D1; padding: 10px 0; margin-bottom: 12px;">
                        <?php foreach ($ord['items'] as $it): ?>
                            <div style="display: flex; justify-content: space-between; font-size: 14px; margin-bottom: 4px;">
                                <span><?= (int)$it['qty'] ?>x <?= htmlspecialchars($it['name']) ?></span>
                                <span><?= formatRupiah($it['price'] * $it['qty']) ?></span>
                            </div>
                        <?php endforeach; ?>
                        <?php if (!empty($ord['notes'])): ?>
                            <div style="font-size: 12px; color: #8C6D62; margin-top: 6px;">Catatan: <em><?= htmlspecialchars($ord['notes']) ?></em></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div>
                    <div style="display: flex; justify-content: space-between; font-weight: 700; font-size: 15px; color: #3D1C1A; margin-bottom: 14px;">
                        <span>Total Bayar</span>
                        <span><?= formatRupiah($ord['total']) ?></span>
                    </div>

                    <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                        <?php if ($status === 'preparing'): ?>
                            <form method="POST" style="flex: 1;">
                                <input type="hidden" name="action" value="update_status">
                                <input type="hidden" name="order_code" value="<?= htmlspecialchars($ord['code']) ?>">
                                <input type="hidden" name="new_status" value="ready">
                                <button type="submit" class="btn-maroon" style="width: 100%; padding: 10px;">🔔 Tandai Siap Diambil</button>
                            </form>
                        <?php elseif ($status === 'ready'): ?>
                            <form method="POST" style="flex: 1;">
                                <input type="hidden" name="action" value="update_status">
                                <input type="hidden" name="order_code" value="<?= htmlspecialchars($ord['code']) ?>">
                                <input type="hidden" name="new_status" value="completed">
                                <button type="submit" class="btn-outline-sm" style="width: 100%; padding: 10px; font-weight: 700;">✓ Pesanan Sudah Diambil</button>
                            </form>
                        <?php else: ?>
                            <span style="font-size: 12px; color: #6B5B55;">Transaksi selesai.</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- TAB 2: INPUT PESANAN MANUAL DI KASIR -->
    <div id="sectionPos" style="display: none;">
        <form method="POST" class="product-card" style="padding: 24px;">
            <input type="hidden" name="action" value="kasir_manual_order">
            <h3 style="margin-bottom: 16px; color: #3D1C1A;">Input Pesanan Langsung di Kasir</h3>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 14px; margin-bottom: 20px;">
                <div>
                    <label style="display:block; font-size:13px; font-weight:600; margin-bottom:6px;">Nama Pelanggan</label>
                    <input type="text" name="customer_name" placeholder="Contoh: Budi" required style="width:100%; padding:10px; border:1px solid #D6C7C1; border-radius:8px;">
                </div>
                <div>
                    <label style="display:block; font-size:13px; font-weight:600; margin-bottom:6px;">Lokasi / Nomor Meja</label>
                    <select name="table_number" style="width:100%; padding:10px; border:1px solid #D6C7C1; border-radius:8px;">
                        <option value="Takeaway">Takeaway</option>
                        <?php for ($m = 1; $m <= 8; $m++): ?>
                            <option value="Meja <?= $m ?>">Meja <?= $m ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div>
                    <label style="display:block; font-size:13px; font-weight:600; margin-bottom:6px;">Metode Pembayaran</label>
                    <select name="payment_method" style="width:100%; padding:10px; border:1px solid #D6C7C1; border-radius:8px;">
                        <option value="QRIS">QRIS</option>
                        <option value="Tunai">Tunai (Cash)</option>
                    </select>
                </div>
            </div>

            <?php foreach ($catalog as $cat): ?>
                <h4 style="margin: 18px 0 10px; color: #3D1C1A; border-bottom: 1px solid #EDE4DF; padding-bottom: 6px;">
                    <?= htmlspecialchars($cat['name']) ?>
                </h4>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 12px;">
                    <?php foreach ($cat['products'] as $p): ?>
                        <div style="display: flex; justify-content: space-between; align-items: center; border: 1px solid #EDE4DF; padding: 10px 12px; border-radius: 10px; background: #FAF7F5;">
                            <div>
                                <strong style="font-size: 13px; display: block;"><?= htmlspecialchars($p['name']) ?></strong>
                                <small style="color: #6B5B55;"><?= formatRupiah($p['price']) ?></small>
                            </div>
                            <input type="number" name="items[<?= (int)$p['id'] ?>]" min="0" value="0" style="width: 58px; padding: 6px; text-align: center; border: 1px solid #C8B7B0; border-radius: 8px; background: #FFF;">
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>

            <div style="margin-top: 24px; text-align: right;">
                <button type="submit" class="btn-maroon" style="padding: 13px 28px; font-size: 14px;">
                    ✓ Simpan &amp; Masukkan ke Antrean Kasir
                </button>
            </div>
        </form>
    </div>

</main>

<script>
function switchKasirTab(tab) {
    const queueSec = document.getElementById('sectionQueue');
    const posSec   = document.getElementById('sectionPos');
    const btnQueue = document.getElementById('tabBtnQueue');
    const btnPos   = document.getElementById('tabBtnPos');

    if (tab === 'queue') {
        queueSec.style.display = 'block';
        posSec.style.display   = 'none';
        btnQueue.className     = 'btn-maroon';
        btnPos.className       = 'btn-outline-sm';
    } else {
        queueSec.style.display = 'none';
        posSec.style.display   = 'block';
        btnPos.className       = 'btn-maroon';
        btnQueue.className     = 'btn-outline-sm';
    }
}

// Auto-refresh antrean kasir tiap 6 detik (hanya saat berada di tab Antrean)
setInterval(function () {
    const queueSec = document.getElementById('sectionQueue');
    if (queueSec && queueSec.style.display !== 'none') {
        window.location.reload();
    }
}, 6000);
</script>

<?php include 'includes/footer.php'; ?>x