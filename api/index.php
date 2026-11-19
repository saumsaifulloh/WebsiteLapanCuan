<?php
// Router: meneruskan semua request .php ke file di root project
$root = dirname(__DIR__);
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$file = basename($path);
$allowed = ['index.php', 'kasir.php', 'qr-meja.php', 'status-pesanan.php'];
if (!in_array($file, $allowed, true)) { $file = 'index.php'; }
chdir($root);
require $root . '/' . $file;
