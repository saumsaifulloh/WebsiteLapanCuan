<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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
            'slug' => 'Coffee Series',
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
                    'description' => 'Signature kopi susu gula aren autentik Lapan Cuan dengan rasa gurih legit yang.',
                    'price' => 25000,
                    'image_url' => 'assets/img menu/Coffee/Kopi Cuan.png',
                    'badge_left' => 'SIGNATURE',
                    'badge_right' => '',
                    'is_favorite' => 0
                ],
                [
                    'id' => 106,
                    'name' => 'Yin Yang',
                    'description' => 'Perpaduan unik kopi espresso aromatik dengan racikan teh pekat dan sentuhan.',
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
                    'description' => 'Cokelat artisan pekat kaya rasa dipadukan dengan susu segar hangat atau dingin yang.',
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
                    'description' => 'Minuman unik berbahan dasar black cocoa premium dan activated charcoal berpadu.',
                    'price' => 22000,
                    'image_url' => 'assets/img menu/Non Coffee/Black Velvet.png',
                    'badge_left' => 'NON-COFFEE',
                    'badge_right' => '',
                    'is_favorite' => 0
                ],
                [
                    'id' => 204,
                    'name' => 'Milk Tea',
                    'description' => 'Seduhan daun teh hitam pilihan beraroma wangi berpadu racikan susu gurih manis yang.',
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
                    'description' => 'Racikan susu lembut dengan sensasi permen manis-segar buah mangga tropis yang.',
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
            'slug' => 'Refreshment Series',
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
                    'description' => 'Kreasi minuman dingin berbasis buah tropis segar, soda berkarbonasi, dan.',
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
            'slug' => 'Manual Brew Series',
            'subtitle' => 'Perpaduan soda segar dan buah tropis.',
            'products' => [
                [
                    'id' => 301,
                    'name' => 'Tubruk Hitam',
                    'description' => 'Sparkling citrus dengan perpaduan sirup bunga telang, peach, dan daun mint segar.',
                    'price' => 23000,
                    'image_url' => 'assets/img menu/Manual Brew/Tubruk Hitam.png',
                    'badge_left' => 'MOCKTAIL',
                    'badge_right' => '',
                    'is_favorite' => 0
                ],
                [
                    'id' => 302,
                    'name' => 'Tubruk Susu',
                    'description' => 'Kesegaran air tonik dan perasan jeruk sunkist dengan ekstra shot espresso.',
                    'price' => 24000,
                    'image_url' => 'assets/img menu/Manual Brew/Tubruk Susu.png',
                    'badge_left' => 'SPARKLER',
                    'badge_right' => '',
                    'is_favorite' => 0
                ],
                [
                    'id' => 303,
                    'name' => 'Filter Coffee V60',
                    'description' => 'Kesegaran air tonik dan perasan jeruk sunkist dengan ekstra shot espresso.',
                    'price' => 24000,
                    'image_url' => 'assets/img menu/Manual Brew/Filter Coffe V60.png',
                    'badge_left' => 'SPARKLER',
                    'badge_right' => '',
                    'is_favorite' => 0
                ],
            ]
        ],
         [
            'id' => 5,
            'name' => 'Tea Series',
            'eyebrow' => 'BOTANICAL & REFRESHING',
            'slug' => 'Tea Series',
            'subtitle' => 'Perpaduan soda segar dan buah tropis.',
            'products' => [
                [
                    'id' => 301,
                    'name' => 'Black Tea',
                    'description' => 'Sparkling citrus dengan perpaduan sirup bunga telang, peach, dan daun mint segar.',
                    'price' => 23000,
                    'image_url' => 'assets/img menu/Tea Series/Black Tea.png',
                    'badge_left' => 'MOCKTAIL',
                    'badge_right' => '',
                    'is_favorite' => 0
                ],
                [
                    'id' => 302,
                    'name' => 'Lemon Tea',
                    'description' => 'Kesegaran air tonik dan perasan jeruk sunkist dengan ekstra shot espresso.',
                    'price' => 24000,
                    'image_url' => 'assets/img menu/Tea Series/Lemon Tea.png',
                    'badge_left' => 'SPARKLER',
                    'badge_right' => '',
                    'is_favorite' => 0
                ],
                [
                    'id' => 303,
                    'name' => 'Manggo Tea',
                    'description' => 'Kesegaran air tonik dan perasan jeruk sunkist dengan ekstra shot espresso.',
                    'price' => 24000,
                    'image_url' => 'assets/img menu/Tea Series/Mango Tea.png',
                    'badge_left' => 'SPARKLER',
                    'badge_right' => '',
                    'is_favorite' => 0
                ],
                [
                    'id' => 304,
                    'name' => 'Peach Tea',
                    'description' => 'Kesegaran air tonik dan perasan jeruk sunkist dengan ekstra shot espresso.',
                    'price' => 24000,
                    'image_url' => 'assets/img menu/Tea Series/Peach Tea.png',
                    'badge_left' => 'SPARKLER',
                    'badge_right' => '',
                    'is_favorite' => 0
                ],
            ]
            
        ],
        [
            'id' => 6,
            'name' => 'Add-On Series',
            'eyebrow' => 'ADD-ON & CUSTOMIZATION',
            'slug' => 'Add-On Series',
            'subtitle' => 'Perpaduan soda segar dan buah tropis.',
            'products' => [
                [
                    'id' => 301,
                    'name' => 'Air Mineral',
                    'description' => 'Sparkling citrus dengan perpaduan sirup bunga telang, peach, dan daun mint segar.',
                    'price' => 23000,
                    'image_url' => 'assets/img menu/Add-On Series/Mineral Water.png',
                    'badge_left' => 'MOCKTAIL',
                    'badge_right' => '',
                    'is_favorite' => 0
                ],
                [
                    'id' => 302,
                    'name' => 'Espresso Shot',
                    'description' => 'Kesegaran air tonik dan perasan jeruk sunkist dengan ekstra shot espresso.',
                    'price' => 24000,
                    'image_url' => 'assets/img menu/Add-On Series/Espresso Shot.png',
                    'badge_left' => 'SPARKLER',
                    'badge_right' => '',
                    'is_favorite' => 0
                ],
                [
                    'id' => 303,
                    'name' => 'Oat Milk',
                    'description' => 'Sparkling citrus dengan perpaduan sirup bunga telang, peach, dan daun mint segar.',
                    'price' => 23000,
                    'image_url' => 'assets/img menu/Add-On Series/Oat Milk.png',
                    'badge_left' => 'MOCKTAIL',
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
                    'description' => 'Pastry berlapis renyah di luar dan lembut bermentega di bagian dalam.',
                    'price' => 20000,
                    'image_url' => 'assets/img menu/Pastry/Hokkaido-Bun Cheese.png',
                    'badge_left' => 'PASTRY',
                    'badge_right' => '',
                    'is_favorite' => 0
                ],
                [
                    'id' => 703,
                    'name' => 'Hokkaido Bun Buttermilk',
                    'description' => 'Roti bun empuk bergaya Hokkaido dengan kerak kopi harum dan lelehan isian mentega hangat.',
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
?>