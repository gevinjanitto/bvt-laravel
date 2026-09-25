<?php

$site = require __DIR__ . '/site.php';
$f = fn ($key, $label, $type = 'text', $extra = []) => array_merge(['key' => $key, 'label' => $label, 'type' => $type], $extra);
$L = fn ($key, $label, $itemLabel, $schema, $extra = []) => array_merge(['key' => $key, 'label' => $label, 'type' => 'list', 'itemLabel' => $itemLabel, 'schema' => $schema, 'span' => 2], $extra);
$s = fn ($key, $label, $type = 'text', $extra = []) => array_merge(['key' => $key, 'label' => $label, 'type' => $type], $extra);
$ICON = $s('icon', 'Ikon', 'icon');
$TONE = $s('tone', 'Warna', 'select', ['options' => ['brand', 'forest', 'sage', 'gold', 'sand']]);
$REVIEW = [$s('name', 'Nama'), $s('avatar', 'Foto profil', 'image'), $s('initials', 'Inisial'), $s('location', 'Lokasi'), $s('meta', 'Keterangan'), $s('date', 'Tanggal ulasan'), $s('text', 'Isi ulasan', 'textarea'), $TONE];

return [
    'tours' => [
        'model' => App\Models\Tour::class, 'title' => 'Tour Packages', 'singular' => 'Tour Package', 'title_key' => 'title', 'public' => '/tour-packages',
        'columns' => [['key' => 'category', 'label' => 'Category'], ['key' => 'duration', 'label' => 'Duration'], ['key' => 'price', 'label' => 'Price', 'type' => 'price'], ['key' => 'rating', 'label' => 'Rating', 'type' => 'rating'], ['key' => 'featured', 'label' => 'Featured', 'type' => 'bool']],
        'defaults' => ['category' => $site['tour_categories'][0], 'price_unit' => 'Person', 'rating' => 5, 'reviews' => 0, 'days' => 1, 'bestseller' => true, 'featured' => true],
        'sections' => [
            'Basic Info' => [$f('title', 'Title', 'text', ['required' => true, 'span' => 2]), $f('slug', 'Slug (auto if empty)'), $f('category', 'Category', 'select', ['options' => $site['tour_categories']]), $f('region', 'Region / Destination'), $f('badge', 'Badge (e.g. Bestseller)'), $f('duration', 'Duration label (e.g. 4 Days 3 Nights)'), $f('days', 'Days', 'number'), $f('rating', 'Rating (0-5)', 'number', ['step' => 0.1]), $f('reviews', 'Review count', 'number'), $f('bestseller', 'Tampilkan di Beranda (Bestselling)', 'switch'), $f('featured', 'Featured', 'switch')],
            'Pricing & Media' => [$f('price', 'Price (IDR)', 'number', ['required' => true]), $f('price_unit', 'Price unit', 'select', ['options' => ['Person', 'Family']]), $f('original_price', 'Original price (for discount)', 'number'), $f('image', 'Cover image', 'image', ['span' => 2]), $f('gallery', 'Gallery', 'gallery', ['span' => 2])],
            'Content' => [$f('subtitle', 'Subtitle', 'text', ['span' => 2]), $f('description', 'Short description', 'textarea', ['span' => 2]), $f('long_description', 'Long description', 'richtext', ['span' => 2]), $f('highlights', 'Highlights (one per line)', 'lines', ['span' => 2]), $f('inclusions', 'Inclusions (one per line)', 'lines'), $f('exclusions', 'Exclusions (one per line)', 'lines')],
            'Itinerary & Details' => [
                $L('features', 'Keunggulan paket (ditampilkan sebagai ikon + judul + deskripsi)', 'Keunggulan', [$ICON, $s('title', 'Judul'), $s('desc', 'Deskripsi singkat', 'textarea')]),
                $L('itinerary', 'Itinerary perjalanan (per hari)', 'Hari', [$s('day', 'Label hari', 'text', ['placeholder' => 'Day 1']), $s('title', 'Judul kegiatan'), $s('meals', 'Makan', 'text', ['placeholder' => 'Breakfast, Lunch']), $s('desc', 'Deskripsi', 'textarea'), $s('points', 'Poin kegiatan (satu per baris)', 'lines')]),
                $L('addons', 'Add-on / layanan tambahan berbayar', 'Add-on', [$s('title', 'Nama add-on'), $s('price', 'Harga (IDR)', 'number'), $s('desc', 'Deskripsi', 'textarea')]),
                $L('tips', 'Tips perjalanan', 'Tips', [$ICON, $s('title', 'Judul'), $s('desc', 'Deskripsi', 'textarea')]),
                $L('reviews_list', 'Ulasan wisatawan', 'Ulasan', $REVIEW),
            ],
        ],
    ],
    'cars' => [
        'model' => App\Models\Car::class, 'title' => 'Car Rental', 'singular' => 'Vehicle', 'title_key' => 'name', 'public' => '/car-rental',
        'columns' => [['key' => 'category', 'label' => 'Category'], ['key' => 'filter', 'label' => 'Group'], ['key' => 'price', 'label' => 'Price / 10h', 'type' => 'price'], ['key' => 'price12h', 'label' => 'Price / 12h', 'type' => 'price'], ['key' => 'badge', 'label' => 'Badge']],
        'defaults' => ['filter' => 'Family MPV'],
        'sections' => [
            'Basic Info' => [$f('name', 'Vehicle name', 'text', ['required' => true, 'span' => 2]), $f('slug', 'Slug (auto if empty)'), $f('category', 'Category label (e.g. Premium MPV)'), $f('filter', 'Filter group', 'select', ['options' => $site['car_filters']]), $f('badge', 'Badge (e.g. Most Popular)'), $f('headline', 'Detail headline', 'text', ['span' => 2]), $f('description', 'Short description', 'textarea', ['span' => 2]), $f('long_desc', 'Long description', 'richtext', ['span' => 2])],
            'Pricing & Media' => [$f('price', 'Price 10 hours (IDR)', 'number', ['required' => true]), $f('price12h', 'Price 12 hours (IDR)', 'number'), $f('image', 'Cover image', 'image', ['span' => 2]), $f('gallery', 'Gallery', 'gallery', ['span' => 2])],
            'Specifications' => [$f('capacity', 'Capacity'), $f('capacity_sub', 'Capacity note'), $f('luggage', 'Luggage'), $f('luggage_sub', 'Luggage note'), $f('drivetrain', 'Drivetrain'), $f('drivetrain_sub', 'Drivetrain note'), $f('seating', 'Seating'), $f('seating_sub', 'Seating note'), $f('tags', 'Tags (one per line)', 'lines'), $f('features', 'Key features (one per line)', 'lines'), $f('amenities', 'Amenities (one per line)', 'lines'), $f('pickup_areas', 'Pickup areas (one per line)', 'lines')],
            'Extras & Routes' => [
                $L('specs', 'Spesifikasi singkat (chip ikon di kartu)', 'Spesifikasi', [$ICON, $s('label', 'Teks', 'text', ['placeholder' => '4-5 VIP Guests'])]),
                $L('addons', 'Add-on / layanan tambahan berbayar', 'Add-on', [$s('title', 'Nama add-on'), $s('price', 'Harga (IDR)', 'number')]),
                $L('routes', 'Rekomendasi rute perjalanan', 'Rute', [$s('title', 'Judul rute'), $s('tag', 'Kategori', 'text', ['placeholder' => 'Culture & Jungle']), $s('hours', 'Durasi', 'text', ['placeholder' => '10 Hours']), $TONE, $s('stops', 'Tempat yang dikunjungi (satu per baris)', 'lines')]),
                $L('reviews_list', 'Ulasan penumpang', 'Ulasan', $REVIEW),
            ],
        ],
    ],
    'activities' => [
        'model' => App\Models\Activity::class, 'title' => 'Activities', 'singular' => 'Activity', 'title_key' => 'title', 'public' => '/activities',
        'columns' => [['key' => 'type', 'label' => 'Type'], ['key' => 'duration', 'label' => 'Duration'], ['key' => 'price', 'label' => 'Price', 'type' => 'price'], ['key' => 'rating', 'label' => 'Rating', 'type' => 'rating'], ['key' => 'badge', 'label' => 'Badge']],
        'defaults' => ['type' => $site['activity_types'][0], 'badge_tone' => 'brand', 'rating' => 5, 'reviews' => 0, 'slots' => [['label' => 'Morning Session', 'sub' => '07:30 - 08:30 AM Hotel Dispatch']]],
        'sections' => [
            'Basic Info' => [$f('title', 'Title', 'text', ['required' => true, 'span' => 2]), $f('slug', 'Slug (auto if empty)'), $f('type', 'Experience type', 'select', ['options' => $site['activity_types']]), $f('category', 'Category label (e.g. Adventure & Waters)'), $f('badge', 'Badge (e.g. Must Do)'), $f('badge_tone', 'Badge color', 'select', ['options' => ['brand', 'forest', 'sand', 'gold']]), $f('duration', 'Duration (e.g. 3 Hours)'), $f('rating', 'Rating (0-5)', 'number', ['step' => 0.1]), $f('reviews', 'Review count', 'number')],
            'Pricing & Media' => [$f('price', 'Price per person (IDR)', 'number', ['required' => true]), $f('image', 'Cover image', 'image', ['span' => 2]), $f('gallery', 'Gallery', 'gallery', ['span' => 2])],
            'Content' => [$f('headline', 'Detail headline', 'text', ['span' => 2]), $f('description', 'Short description', 'textarea', ['span' => 2]), $f('long_description', 'Long description', 'richtext', ['span' => 2]), $f('includes', 'Card includes (one per line)', 'lines'), $f('tags', 'Detail tags (one per line)', 'lines'), $f('inclusions', 'Inclusions (one per line)', 'lines'), $f('exclusions', 'Exclusions (one per line)', 'lines')],
            'Schedule & Details' => [
                $L('facts', 'Fakta singkat (info di bagian atas halaman)', 'Fakta', [$ICON, $s('label', 'Label', 'text', ['placeholder' => 'Duration']), $s('value', 'Nilai', 'text', ['placeholder' => '3 Hours']), $s('sub', 'Keterangan kecil')]),
                $L('highlights', 'Highlight pengalaman', 'Highlight', [$ICON, $s('title', 'Judul'), $s('desc', 'Deskripsi', 'textarea')]),
                $L('timeline', 'Rundown / jadwal kegiatan', 'Langkah', [$s('time', 'Waktu', 'text', ['placeholder' => '07:30']), $s('title', 'Judul'), $TONE, $s('desc', 'Deskripsi', 'textarea')]),
                $L('slots', 'Pilihan jam keberangkatan', 'Sesi', [$s('label', 'Nama sesi', 'text', ['placeholder' => 'Morning Session']), $s('sub', 'Keterangan', 'text', ['placeholder' => '07:30 - 08:30 AM Hotel Dispatch'])]),
                $L('addons', 'Add-on / layanan tambahan berbayar', 'Add-on', [$s('title', 'Nama add-on'), $s('price', 'Harga (IDR)', 'number'), $s('desc', 'Deskripsi', 'textarea')]),
                $L('packing', 'Panduan barang bawaan', 'Barang', [$ICON, $s('title', 'Judul'), $s('desc', 'Deskripsi', 'textarea')]),
                $L('reviews_list', 'Ulasan peserta', 'Ulasan', $REVIEW),
            ],
        ],
    ],
    'articles' => [
        'model' => App\Models\Article::class, 'title' => 'Articles', 'singular' => 'Article', 'title_key' => 'title', 'public' => '/articles',
        'columns' => [['key' => 'category', 'label' => 'Category'], ['key' => 'author.name', 'label' => 'Author'], ['key' => 'date', 'label' => 'Date', 'type' => 'date'], ['key' => 'read_time', 'label' => 'Read time'], ['key' => 'featured', 'label' => 'Featured', 'type' => 'bool']],
        'defaults' => ['category' => $site['article_categories'][0], 'read_time' => '5 min read', 'featured' => false, 'author' => ['name' => 'Bali Vision Editorial', 'role' => 'Concierge Team', 'avatar' => '', 'bio' => '']],
        'sections' => [
            'Basic Info' => [$f('title', 'Title', 'text', ['required' => true, 'span' => 2]), $f('slug', 'Slug (auto if empty)'), $f('category', 'Category', 'select', ['options' => $site['article_categories']]), $f('date', 'Publish date', 'date'), $f('read_time', 'Read time (e.g. 5 min read)'), $f('location', 'Location'), $f('featured', 'Featured guide of the month', 'switch'), $f('subtitle', 'Subtitle', 'text', ['span' => 2]), $f('excerpt', 'Excerpt', 'textarea', ['span' => 2, 'required' => true])],
            'Media & Author' => [$f('image', 'Cover image', 'image', ['span' => 2]), $f('image_caption', 'Image caption', 'text', ['span' => 2]), $f('author.name', 'Author name'), $f('author.role', 'Author role'), $f('author.avatar', 'Author avatar', 'image'), $f('author.license', 'Author license / badge'), $f('author.bio', 'Author bio', 'richtext', ['span' => 2])],
            'Content' => [$f('content', 'Isi artikel', 'richblocks', ['span' => 2]), $f('bullets', 'Poin artikel unggulan (satu per baris)', 'lines'), $f('tags', 'Header tags (one per line)', 'lines'), $f('highlights', "Curator's highlights (one per line)", 'lines'), $L('facts', 'Fakta singkat di sidebar', 'Fakta', [$s('label', 'Label', 'text', ['placeholder' => 'Best season']), $s('value', 'Nilai', 'text', ['placeholder' => 'April - October'])])],
        ],
    ],
];
