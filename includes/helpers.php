<?php
// Session berbasis cookie (Vercel serverless tidak menyimpan session PHP)
ob_start();
$_SESSION = [];
if (!empty($_COOKIE['lc_sess'])) {
    $decoded = json_decode(base64_decode($_COOKIE['lc_sess']), true);
    if (is_array($decoded)) { $_SESSION = $decoded; }
}
register_shutdown_function(function () {
    if (!headers_sent()) {
        setcookie('lc_sess', base64_encode(json_encode($_SESSION)), [
            'expires' => time() + 86400, 'path' => '/', 'httponly' => true, 'samesite' => 'Lax'
        ]);
    }
    if (ob_get_level() > 0) { ob_end_flush(); }
});

// Konfigurasi Informasi Lapan Cuan
$cafe_info = [
    'name'             => 'LAPAN CUAN',
    'subname'          => 'COFFEE & SPACE • MALANG',
    'address'          => 'Jl. Terusan Sudimoro No. 3, Mojolangu, Kec. Lowokwaru, Kota Malang, Jawa Timur',
    'short_addr'       => 'Jl. Terusan Sudimoro No.3, Malang',
    'hours'            => '12.00 – 00.00 WIB',
    'whatsapp'         => '6281234567890',
    'whatsapp_display' => '+62 812-3456-7890',
    'instagram'        => 'lapancuan.mlg',
    'gmaps_url'        => 'https://maps.google.com/?q=-7.9421,112.6328',
    'coords'           => '-7.9421° S, 112.6328° E'
];

function formatRupiah($angka) {
    return 'Rp ' . number_format((float)$angka, 0, ',', '.');
}

function cleanInput($conn, $data) {
    return htmlspecialchars(trim((string)$data), ENT_QUOTES, 'UTF-8');
}

function getDummyCatalog() {
    return [
        [
            'id' => 1,
            'name' => 'Coffee Series',
            'slug' => 'coffee',
            'eyebrow' => 'ARTISAN ROASTERY',
            'subtitle' => 'Fondasi espresso berkarakter khas dari biji kopi terpilih, diekstraksi sempurna menghasilkan crema pekat dan aroma memikat.',
            'products' => [
                [
                    'id' => 101,
                    'name' => 'Americano',
                    'description' => 'Ekstraksi double espresso murni yang seimbang dengan air mineral segar, bersih.',
                    'price' => 22000,
                    'image_url' => 'assets/img menu/Coffee/Americano.png',
                    'badge_left' => 'BEVERAGE',
                    'badge_right' => '★ Top Pick',
                    'is_favorite' => 1
                ],
                [
                    'id' => 102,
                    'name' => 'Caffe Latte',
                    'description' => 'Paduan espresso lembut dan steamed milk velvety dengan lapisan microfoam latte art.',
                    'price' => 18000,
                    'image_url' => 'assets/img menu/Coffee/Caffe Latte.png',
                    'badge_left' => 'SIGNATURE',
                    'badge_right' => '❤ Best Seller',
                    'is_favorite' => 1
                ],
                [
                    'id' => 103,
                    'name' => 'Bombon',
                    'description' => 'Espresso pekat khas Spanyol yang dipadukan dengan krimer kental manis.',
                    'price' => 24000,
                    'image_url' => 'assets/img menu/Coffee/Bombon.png',
                    'badge_left' => 'BEVERAGE',
                    'badge_right' => 'Favorite',
                    'is_favorite' => 1
                ],
                [
                    'id' => 104,
                    'name' => 'Salted Caramel Coffee',
                    'description' => 'Kombinasi espresso kaya rasa dengan sirup karamel gurih, sentuhan garam laut.',
                    'price' => 25000,
                    'image_url' => 'assets/img menu/Coffee/Salted Caramel.png',
                    'badge_left' => 'SIGNATURE',
                    'badge_right' => '',
                    'is_favorite' => 0
                ],
                [
                    'id' => 105,
                    'name' => 'Kopi Cuan',
                    'description' => 'Signature kopi susu gula aren autentik Lapan Cuan dengan rasa gurih legit yang khas.',
                    'price' => 25000,
                    'image_url' => 'assets/img menu/Coffee/Kopi Cuan.png',
                    'badge_left' => 'SIGNATURE',
                    'badge_right' => '',
                    'is_favorite' => 0
                ],
                [
                    'id' => 106,
                    'name' => 'Yin Yang',
                    'description' => 'Perpaduan unik kopi espresso aromatik dengan racikan teh pekat dan sentuhan creamy.',
                    'price' => 25000,
                    'image_url' => 'assets/img menu/Coffee/Yinyang.png',
                    'badge_left' => 'SIGNATURE',
                    'badge_right' => '',
                    'is_favorite' => 0
                ],
            ]
        ],
        [
            'id' => 2,
            'name' => 'Non Coffee Series',
            'slug' => 'non-coffee',
            'eyebrow' => 'PURE & VELVETY',
            'subtitle' => 'Minuman creamy dan menyegarkan tanpa kopi.',
            'products' => [
                [
                    'id' => 201,
                    'name' => 'Chocolate',
                    'description' => 'Cokelat artisan pekat kaya rasa dipadukan dengan susu segar hangat atau dingin.',
                    'price' => 22000,
                    'image_url' => 'assets/img menu/Non Coffee/Chocolate.png',
                    'badge_left' => 'NON-COFFEE',
                    'badge_right' => '',
                    'is_favorite' => 0
                ],
                [
                    'id' => 202,
                    'name' => 'Matcha',
                    'description' => 'Teh hijau Matcha Uji otentik dengan aroma vegetal lembut dan susu segar gurih creamy.',
                    'price' => 24000,
                    'image_url' => 'assets/img menu/Non Coffee/Matcha.png',
                    'badge_left' => 'NON-COFFEE',
                    'badge_right' => '★ Popular',
                    'is_favorite' => 0
                ],
                [
                    'id' => 203,
                    'name' => 'Black Velvet',
                    'description' => 'Minuman unik berbahan dasar black cocoa premium dan activated charcoal berpadu susu.',
                    'price' => 22000,
                    'image_url' => 'assets/img menu/Non Coffee/Black Velvet.png',
                    'badge_left' => 'NON-COFFEE',
                    'badge_right' => '',
                    'is_favorite' => 0
                ],
                [
                    'id' => 204,
                    'name' => 'Milk Tea',
                    'description' => 'Seduhan daun teh hitam pilihan beraroma wangi berpadu racikan susu gurih manis.',
                    'price' => 22000,
                    'image_url' => 'assets/img menu/Non Coffee/Milk Tea.png',
                    'badge_left' => 'NON-COFFEE',
                    'badge_right' => '',
                    'is_favorite' => 0
                ],
                [
                    'id' => 205,
                    'name' => 'Caramel Cereal Milk',
                    'description' => 'Nostalgia rasa susu sereal renyah dengan sirup karamel bakar keemasan yang manis.',
                    'price' => 22000,
                    'image_url' => 'assets/img menu/Non Coffee/Caramel Cereal Milk.png',
                    'badge_left' => 'NON-COFFEE',
                    'badge_right' => '',
                    'is_favorite' => 0
                ],
                [
                    'id' => 206,
                    'name' => 'Milky Sugus Mango',
                    'description' => 'Racikan susu lembut dengan sensasi permen manis-segar buah mangga tropis.',
                    'price' => 22000,
                    'image_url' => 'assets/img menu/Non Coffee/Milky Sugus Mango.png',
                    'badge_left' => 'NON-COFFEE',
                    'badge_right' => '',
                    'is_favorite' => 0
                ],
                [
                    'id' => 207,
                    'name' => 'Milky Sugus Blackcurrant',
                    'description' => 'Paduan susu segar dengan rasa buah blackcurrant berry segar manis yang memikat.',
                    'price' => 22000,
                    'image_url' => 'assets/img menu/Non Coffee/Milky Sugus Blackcurrant.png',
                    'badge_left' => 'NON-COFFEE',
                    'badge_right' => '',
                    'is_favorite' => 0
                ],
            ]
        ],
        [
            'id' => 3,
            'name' => 'Refreshment Series',
            'eyebrow' => 'SPARKLING & TROPICAL',
            'slug' => 'refreshment',
            'subtitle' => 'Perpaduan soda segar dan buah tropis.',
            'products' => [
                [
                    'id' => 301,
                    'name' => 'Manggo Coffee Soda',
                    'description' => 'Soda dingin menggelitik berpadu puree mangga manis segar dan float cold brew.',
                    'price' => 23000,
                    'image_url' => 'assets/img menu/Refreshment/Mango Coffe Soda.png',
                    'badge_left' => 'MOCKTAIL',
                    'badge_right' => '',
                    'is_favorite' => 0
                ],
                [
                    'id' => 302,
                    'name' => 'Americano Peach',
                    'description' => 'Double shot espresso dingin dipadukan sirup buah peach harum dan soda sparkling.',
                    'price' => 24000,
                    'image_url' => 'assets/img menu/Refreshment/Americano Peach.png',
                    'badge_left' => 'SPARKLER',
                    'badge_right' => '',
                    'is_favorite' => 0
                ],
                [
                    'id' => 303,
                    'name' => 'Yukari',
                    'description' => 'Mocktail segar berbasis perasan citrus, soda, sirup botani harum, dan garnish daun.',
                    'price' => 23000,
                    'image_url' => 'assets/img menu/Refreshment/Yukari.png',
                    'badge_left' => 'MOCKTAIL',
                    'badge_right' => '',
                    'is_favorite' => 0
                ],
                [
                    'id' => 304,
                    'name' => 'Nailong',
                    'description' => 'Kreasi minuman dingin berbasis buah tropis segar dan soda berkarbonasi.',
                    'price' => 23000,
                    'image_url' => 'assets/img menu/Refreshment/Nailong.png',
                    'badge_left' => 'MOCKTAIL',
                    'badge_right' => '',
                    'is_favorite' => 0
                ],
            ]
        ],
        [
            'id' => 4,
            'name' => 'Manual Brew Series',
            'eyebrow' => 'AUTENTIK & TRADISIONAL',
            'slug' => 'manual-brew',
            'subtitle' => 'Seduhan manual autentik dari biji kopi pilihan Nusantara.',
            'products' => [
                [
                    'id' => 401,
                    'name' => 'Tubruk Hitam',
                    'description' => 'Kopi hitam seduh tradisional dengan aroma kuat dan karakter rasa otentik.',
                    'price' => 23000,
                    'image_url' => 'assets/img menu/Manual Brew/Tubruk Hitam.png',
                    'badge_left' => 'MANUAL BREW',
                    'badge_right' => '',
                    'is_favorite' => 0
                ],
                [
                    'id' => 402,
                    'name' => 'Tubruk Susu',
                    'description' => 'Kopi tubruk pekat berpadu susu kental manis yang hangat dan nikmat.',
                    'price' => 24000,
                    'image_url' => 'assets/img menu/Manual Brew/Tubruk Susu.png',
                    'badge_left' => 'MANUAL BREW',
                    'badge_right' => '',
                    'is_favorite' => 0
                ],
                [
                    'id' => 403,
                    'name' => 'Filter Coffee V60',
                    'description' => 'Seduhan pour-over V60 yang bersih dengan profil aroma floral dan fruity.',
                    'price' => 24000,
                    'image_url' => 'assets/img menu/Manual Brew/Filter Coffe V60.png',
                    'badge_left' => 'POUR OVER',
                    'badge_right' => '',
                    'is_favorite' => 0
                ],
            ]
        ],
        [
            'id' => 5,
            'name' => 'Tea Series',
            'eyebrow' => 'BOTANICAL & REFRESHING',
            'slug' => 'tea',
            'subtitle' => 'Seduhan teh pilihan dengan kesegaran alami.',
            'products' => [
                [
                    'id' => 501,
                    'name' => 'Black Tea',
                    'description' => 'Seduhan teh hitam klasik yang harum, ringan, dan menyegarkan.',
                    'price' => 23000,
                    'image_url' => 'assets/img menu/Tea Series/Black Tea.png',
                    'badge_left' => 'TEA',
                    'badge_right' => '',
                    'is_favorite' => 0
                ],
                [
                    'id' => 502,
                    'name' => 'Lemon Tea',
                    'description' => 'Perpaduan teh hitam segar dengan perasan lemon asam manis yang menyegarkan.',
                    'price' => 24000,
                    'image_url' => 'assets/img menu/Tea Series/Lemon Tea.png',
                    'badge_left' => 'TEA',
                    'badge_right' => '',
                    'is_favorite' => 0
                ],
                [
                    'id' => 503,
                    'name' => 'Manggo Tea',
                    'description' => 'Teh dingin aromatik dengan sentuhan manis buah mangga tropis.',
                    'price' => 24000,
                    'image_url' => 'assets/img menu/Tea Series/Mango Tea.png',
                    'badge_left' => 'TEA',
                    'badge_right' => '',
                    'is_favorite' => 0
                ],
                [
                    'id' => 504,
                    'name' => 'Peach Tea',
                    'description' => 'Teh dingin harum berpadu manis lembut buah peach segar.',
                    'price' => 24000,
                    'image_url' => 'assets/img menu/Tea Series/Peach Tea.png',
                    'badge_left' => 'TEA',
                    'badge_right' => '',
                    'is_favorite' => 0
                ],
            ]
        ],
        [
            'id' => 6,
            'name' => 'Add-On Series',
            'eyebrow' => 'ADD-ON & CUSTOMIZATION',
            'slug' => 'add-on',
            'subtitle' => 'Tambahan ekstra untuk menyempurnakan minuman favoritmu.',
            'products' => [
                [
                    'id' => 601,
                    'name' => 'Air Mineral',
                    'description' => 'Air mineral dingin dan segar sebagai pendamping kopi.',
                    'price' => 23000,
                    'image_url' => 'assets/img menu/Add-On Series/Mineral Water.png',
                    'badge_left' => 'ADD-ON',
                    'badge_right' => '',
                    'is_favorite' => 0
                ],
                [
                    'id' => 602,
                    'name' => 'Espresso Shot',
                    'description' => 'Tambahan ekstra shot espresso untuk rasa kopi yang lebih bold.',
                    'price' => 24000,
                    'image_url' => 'assets/img menu/Add-On Series/Espresso Shot.png',
                    'badge_left' => 'ADD-ON',
                    'badge_right' => '',
                    'is_favorite' => 0
                ],
                [
                    'id' => 603,
                    'name' => 'Oat Milk',
                    'description' => 'Opsi pengganti susu nabati oat milk yang creamy dan ramah dairy.',
                    'price' => 23000,
                    'image_url' => 'assets/img menu/Add-On Series/Oat Milk.png',
                    'badge_left' => 'ADD-ON',
                    'badge_right' => '',
                    'is_favorite' => 0
                ],
            ]
        ],
        [
            'id' => 7,
            'name' => 'Pastry & Roti',
            'eyebrow' => 'ARTISANAL BAKERY',
            'slug' => 'pastry',
            'subtitle' => 'Dipanggang hangat setiap hari untuk teman ngopi.',
            'products' => [
                [
                    'id' => 701,
                    'name' => 'Hokkaido Bun Coffee',
                    'description' => 'Roti bun empuk bergaya Hokkaido dengan kerak kopi harum dan lelehan isian mentega hangat.',
                    'price' => 18000,
                    'image_url' => 'assets/img menu/Pastry/Hokkaido-Bun Coffe.png',
                    'badge_left' => 'PASTRY',
                    'badge_right' => '🔥 Fresh Baked',
                    'is_favorite' => 1
                ],
                [
                    'id' => 702,
                    'name' => 'Hokkaido Bun Cheese',
                    'description' => 'Pastry berlapis renyah di luar dan lembut bermentega dengan isian keju gurih.',
                    'price' => 20000,
                    'image_url' => 'assets/img menu/Pastry/Hokkaido-Bun Cheese.png',
                    'badge_left' => 'PASTRY',
                    'badge_right' => '',
                    'is_favorite' => 0
                ],
                [
                    'id' => 703,
                    'name' => 'Hokkaido Bun Buttermilk',
                    'description' => 'Roti bun empuk bergaya Hokkaido dengan aroma buttermilk lembut dan hangat.',
                    'price' => 18000,
                    'image_url' => 'assets/img menu/Pastry/Hokkaido-Bun Buttermilk.png',
                    'badge_left' => 'PASTRY',
                    'badge_right' => '🔥 Fresh Baked',
                    'is_favorite' => 1
                ],
            ]
        ]
    ];
}

function getFavoriteProducts($conn = null, $limit = 4) {
    $favorites = [];
    foreach (getDummyCatalog() as $cat) {
        foreach ($cat['products'] as $prod) {
            if (!empty($prod['is_favorite'])) {
                $favorites[] = $prod;
            }
        }
    }
    return array_slice($favorites, 0, $limit);
}

function getCategoriesWithProducts($conn = null) {
    return getDummyCatalog();
}

// === HELPER DUMMY JSON (TANPA DATABASE) ===
function getOrdersFilePath() {
    $dir = getenv('VERCEL') ? '/tmp/lapancuan_data' : dirname(__DIR__) . '/data';
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
    $file = $dir . '/orders.json';
    if (!file_exists($file)) {
        $initial = [
            [
                'code'           => 'LC-' . date('ymd') . '-101',
                'name'           => 'Raka Pratama',
                'phone'          => '08123456789',
                'table'          => 'Meja 2',
                'notes'          => 'Less sugar',
                'payment_method' => 'QRIS',
                'payment_status' => 'LUNAS',
                'status'         => 'preparing', // preparing | ready | completed
                'created_at'     => date('H:i'),
                'total'          => 43000,
                'items'          => [
                    ['name' => 'Kopi Cuan', 'qty' => 1, 'price' => 25000],
                    ['name' => 'Hokkaido Bun Coffee', 'qty' => 1, 'price' => 18000],
                ]
            ]
        ];
        file_put_contents($file, json_encode($initial, JSON_PRETTY_PRINT));
    }
    return $file;
}

function ordersRedisCmd($cmd) {
    $url = getenv('UPSTASH_REDIS_REST_URL'); $tok = getenv('UPSTASH_REDIS_REST_TOKEN');
    if (!$url || !$tok) { return null; }
    $ch = curl_init($url);
    curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => ["Authorization: Bearer $tok", 'Content-Type: application/json'],
        CURLOPT_POSTFIELDS => json_encode($cmd), CURLOPT_TIMEOUT => 8]);
    $res = json_decode(curl_exec($ch), true); curl_close($ch);
    return $res['result'] ?? null;
}

function getDummyOrders() {
    if (getenv('UPSTASH_REDIS_REST_URL')) {
        $raw = ordersRedisCmd(['GET', 'lapancuan_orders']);
        $data = $raw ? json_decode($raw, true) : null;
        if (!is_array($data)) { $data = json_decode(file_get_contents(getOrdersFilePath()), true); }
        return is_array($data) ? $data : [];
    }
    $data = json_decode(file_get_contents(getOrdersFilePath()), true);
    return is_array($data) ? $data : [];
}

function writeOrders($orders) {
    if (getenv('UPSTASH_REDIS_REST_URL')) {
        ordersRedisCmd(['SET', 'lapancuan_orders', json_encode($orders)]);
        return;
    }
    file_put_contents(getOrdersFilePath(), json_encode($orders, JSON_PRETTY_PRINT));
}

function getDummyOrderByCode($code) {
    foreach (getDummyOrders() as $ord) {
        if ($ord['code'] === $code) { return $ord; }
    }
    return null;
}

function saveDummyOrder($newOrder) {
    $orders = getDummyOrders();
    array_unshift($orders, $newOrder);
    writeOrders($orders);
}

function updateDummyOrderStatus($code, $newStatus) {
    $orders = getDummyOrders();
    foreach ($orders as &$ord) {
        if ($ord['code'] === $code) { $ord['status'] = $newStatus; break; }
    }
    writeOrders($orders);
}
?>