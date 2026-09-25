<?php

$u = fn ($id, $w = 1200) => "https://images.unsplash.com/{$id}?auto=format&fit=crop&w={$w}&q=80";

$images = [
    'ubud' => $u('photo-1555400038-63f5ba517a47'), 'bedugul' => $u('photo-1711609110590-5ad5c4599e56'), 'riceMist' => $u('photo-1558005530-a7958896ec60'),
    'kelingking' => $u('photo-1557106160-c533b9d00071'), 'diamond' => $u('photo-1541666282672-5f4aad922c63'), 'penidaBay' => $u('photo-1604500693431-647f9e76dafc'),
    'ulunDanu' => $u('photo-1604999333679-b86d54738315'), 'templeLake' => $u('photo-1537996194471-e657df975ab4'), 'uluwatu' => $u('photo-1604842937136-1648761a6256'),
    'uluwatuCliff' => $u('photo-1636619306699-2d27a100605e'), 'tanahLot' => $u('photo-1553902000-e036b7d05af5'), 'tanahLotSunset' => $u('photo-1588625232507-a337a3bd2e43'),
    'kintamani' => $u('photo-1518730573647-359c73385dc5'), 'agung' => $u('photo-1508591086314-d7deb00cede9'), 'tegenungan' => $u('photo-1554931670-4ebfabf6e7a9'),
    'waterfall2' => $u('photo-1576878176876-517cdb8006ac'), 'lempuyang' => $u('photo-1530080338378-25af8876ae2e'), 'lempuyang2' => $u('photo-1575573330964-db3dad170190'),
    'penglipuran' => $u('photo-1671080749889-19f8a69deb2b'), 'jungle' => $u('photo-1557093793-d149a38a1be8'), 'beachClub' => $u('photo-1577717903315-1691ae25ab3f'),
    'hero' => $u('photo-1537996194471-e657df975ab4', 1600), 'rafting' => $u('photo-1641584495089-5914d85d9bcc'), 'suv2' => $u('photo-1622893288761-823ba60f17a6'),
    'group' => $u('photo-1539635278303-d4002c07eae3'), 'staff1' => $u('photo-1516011362164-3095a82b0af9', 800), 'staff2' => $u('photo-1631814029262-e25e779f941f', 800),
    'staff3' => $u('photo-1629425733761-caae3b5f2e50', 800), 'staff4' => $u('photo-1700553856089-1ea9261143a7', 800), 'av1' => $u('photo-1627923146572-5e3d07f32980', 200),
    'av2' => $u('photo-1664312572933-0563f14484a1', 200), 'av3' => $u('photo-1573496359142-b8d87734a5a2', 200), 'av4' => $u('photo-1627161683077-e34782c24d81', 200),
];

return [
    'contact' => [
        'whatsapp' => '6282247479695', 'phone' => '+62 822 4747 9695', 'email' => 'hello@balivisiontour.com', 'emailLink' => '',
        'address' => 'Denpasar, Bali, Indonesia', 'addressLink' => '', 'whatsappMessage' => 'Halo Bali Vision Tour! Saya ingin bertanya tentang layanan Anda.',
    ],
    'social' => ['instagram' => '', 'facebook' => '', 'youtube' => '', 'tiktok' => ''],
    'brand' => ['name' => 'Bali Vision Tour', 'title' => 'Bali Vision', 'tagline' => 'TOUR & TRAVEL', 'legal' => 'PT. Bali Vision Tour', 'logo' => '/logo-icon.png', 'logoLight' => '', 'logoMode' => 'icon', 'favicon' => '/logo-icon.png'],
    'blocks' => [
        'images' => $images,
        'navigation' => [
            ['label' => 'Home', 'to' => '/'], ['label' => 'Tour Packages', 'to' => '/tour-packages'], ['label' => 'Car Rental', 'to' => '/car-rental'],
            ['label' => 'Activities', 'to' => '/activities'], ['label' => 'About Us', 'to' => '/about'], ['label' => 'Articles', 'to' => '/articles'],
        ],
        'homeStats' => [
            ['value' => '10,000+', 'label' => 'Happy Travelers', 'icon' => 'Smile', 'tone' => 'brand'], ['value' => '150+', 'label' => 'Handpicked Destinations', 'icon' => 'Map', 'tone' => 'sage'],
            ['value' => '4.9 / 5.0', 'label' => 'Average Guest Rating', 'icon' => 'Star', 'tone' => 'brand'], ['value' => '24/7 Dedicated', 'label' => 'Concierge & Care', 'icon' => 'Headphones', 'tone' => 'sage'],
        ],
        'homeCategories' => [
            ['index' => '01', 'tag' => 'Curated Tours', 'title' => 'Complete Tour Packages', 'desc' => 'Curated itineraries with premier destinations and private local guides for an unforgettable holiday.', 'cta' => 'View Packages', 'to' => '/tour-packages', 'image' => $images['penglipuran']],
            ['index' => '02', 'tag' => 'Adventure', 'title' => 'Thrilling Adventures', 'desc' => 'Immerse in exciting cultural and outdoor excursions across Bali, from Ayung river rafting to volcanic trails.', 'cta' => 'Explore Activities', 'to' => '/activities', 'image' => $images['rafting']],
            ['index' => '03', 'tag' => 'Private Fleet', 'title' => 'Private Chauffeur & Fleet', 'desc' => 'Travel in comfort and peace of mind with our executive vehicles and experienced drivers.', 'cta' => 'Choose Fleet', 'to' => '/car-rental', 'image' => $images['suv2']],
        ],
        'destinations' => [
            ['name' => 'Ubud', 'tag' => 'TOP PICK', 'tagStyle' => 'brand', 'desc' => "Lush terraced hills, spiritual zen sanctuaries, and Bali's heart of fine art & craft.", 'image' => $images['ubud']],
            ['name' => 'Nusa Penida', 'tag' => 'Island Hopping', 'tagStyle' => 'glass', 'desc' => 'Dramatic cliff headlands, emerald ridges, and pristine turquoise ocean waters.', 'image' => $images['kelingking']],
            ['name' => 'Uluwatu', 'tag' => 'Sunset Sanctuary', 'tagStyle' => 'brand', 'desc' => 'Hypnotic Kecak fire dance against crashing waves and golden cliffside sunsets.', 'image' => $images['uluwatu']],
            ['name' => 'Kintamani', 'tag' => 'Volcano & Lakes', 'tagStyle' => 'glass', 'desc' => 'Crisp highland breezes, Mount Batur caldera peaks, and sunrise lake reflections.', 'image' => $images['kintamani']],
            ['name' => 'Lovina', 'tag' => 'Dolphin Coast', 'tagStyle' => 'glass', 'desc' => 'Calm northern waters, playful wild dolphin pods, and peaceful volcanic sand shores.', 'image' => $images['beachClub']],
            ['name' => 'Bedugul', 'tag' => 'Serene Water Temple', 'tagStyle' => 'glass', 'desc' => 'Majestic Ulun Danu temple nestled over foggy mountain waters and cool botanical gardens.', 'image' => $images['bedugul']],
        ],
        'homeFeatures' => [
            ['index' => '01', 'title' => 'Curated Destinations', 'desc' => 'From secluded tropical beaches to historic majestic temples, all tailored in one place.', 'icon' => 'Map'],
            ['index' => '02', 'title' => 'Best Value Guarantee', 'desc' => 'Transparent, competitive rates with no hidden costs or surprise fees throughout your journey.', 'icon' => 'BadgeDollarSign'],
            ['index' => '03', 'title' => 'Seamless Instant Booking', 'desc' => 'Instant confirmation via WhatsApp. Book packages, activities, and rentals in minutes.', 'icon' => 'Zap'],
            ['index' => '04', 'title' => '24/7 Dedicated Guest Care', 'desc' => 'Our island concierge is ready to assist you from arrival until departure.', 'icon' => 'Headphones'],
        ],
        'testimonials' => [
            ['name' => 'Abdul Basith', 'location' => 'Surabaya', 'avatar' => $images['av1'], 'text' => 'Holidaying in Bali was so much easier thanks to Bali Vision Tour. The driver arrived right on time, polite and exceptionally helpful. Our itinerary was perfectly spaced without any rush. Truly a memorable trip, will definitely book again next time!'],
            ['name' => 'Pahrurrozi', 'location' => 'Lombok', 'avatar' => $images['av2'], 'text' => 'Booking our private vehicle was effortless. The car was spotlessly clean, comfortable, and airport pickup was punctual. Outstanding professional service from start to finish.'],
            ['name' => 'David Joseph', 'location' => 'Surabaya', 'avatar' => $images['av3'], 'text' => "From booking to the actual journey, everything was smooth and well-organized. The guides were knowledgeable and courteous. I'll definitely use Bali Vision Tour again!"],
            ['name' => 'Sarah Louis', 'location' => 'Bandung', 'avatar' => $images['av4'], 'text' => 'I loved how simple and fast the process was. The concierge support team answered every query promptly on WhatsApp and customized our trip perfectly!'],
        ],
        'homeMarquee' => ['Ubud Rice Terraces', 'Nusa Penida Cliffs', 'Uluwatu Kecak Sunset', 'Mount Batur Sunrise', 'Tirta Empul Blessing', 'Jimbaran Seafood', 'Lempuyang Gate of Heaven', 'Manta Ray Snorkeling'],
        'about' => [
            'stats' => [
                ['value' => '10+', 'label' => 'Years Curating Bali', 'sub' => 'Founded in 2014 in Denpasar'], ['value' => '45,000+', 'label' => 'Delighted Travelers', 'sub' => 'Domestic & global voyagers'],
                ['value' => '100%', 'label' => 'Certified Native Guides', 'sub' => 'HPPWD licensed Balinese drivers', 'tone' => 'forest'], ['value' => '4.9', 'suffix' => '/5', 'label' => 'Average Guest Rating', 'sub' => 'Over 3,200 verified reviews'],
            ],
            'pillars' => [
                ['title' => 'Authentic Cultural Connection', 'desc' => 'Native Balinese guides who share local etiquette, sacred temple lore, and hidden culinary gems passed down through generations—not rehearsed textbook scripts.', 'link' => 'Grassroots Storytelling', 'icon' => 'Landmark', 'tone' => 'brand'],
                ['title' => 'Uncompromised Safety & Luxury', 'desc' => 'Modern hybrid fleet, stringent scheduled maintenance, comprehensive traveler insurance policies, and straightforward pricing without hidden shopping traps.', 'link' => 'Modern Premium Fleet', 'icon' => 'Shield', 'tone' => 'sage'],
                ['title' => 'Community-First Tourism', 'desc' => 'Direct economic contributions to village banjars, patronage of organic highland farms, and active sponsorship of ethical marine sanctuaries in Lovina and Nusa Penida.', 'link' => 'Sustainable Impact', 'icon' => 'Leaf', 'tone' => 'sand'],
                ['title' => '24/7 White-Glove Concierge', 'desc' => 'Real-time support via WhatsApp from the moment of your flight landing at Ngurah Rai to your final departure, ensuring seamless adaptations to weather or whim.', 'link' => 'Instant Dispatch', 'icon' => 'Headphones', 'tone' => 'brand'],
            ],
            'team' => [
                ['name' => 'Wayan Sudiarta', 'role' => 'Founder & Managing Director', 'tag' => '10+ YRS EXP', 'desc' => 'Denpasar native dedicated to ethical Balinese tourism and elevating guest journeys across all regencies.', 'image' => $images['staff1']],
                ['name' => 'Ni Ketut Saraswati', 'role' => 'Head of Curated Experiences', 'tag' => 'CULTURAL SPECIALIST', 'desc' => 'Designs our exclusive wellness retreats, private temple blessings, and secluded culinary expeditions.', 'image' => $images['staff2']],
                ['name' => 'Made Arya', 'role' => 'Fleet Operations & Safety Lead', 'tag' => 'SAFETY CERTIFIED', 'desc' => 'Supervises vehicle telemetry, daily sanitization, driver safety schooling, and eco-fleet maintenance.', 'image' => $images['staff3']],
                ['name' => 'Ketut Aris', 'role' => 'Senior Guest Concierge', 'tag' => '24/7 CONCIERGE', 'desc' => 'First point of contact for custom bookings, flight rescheduling, and responsive in-trip guest inquiries.', 'image' => $images['staff4']],
            ],
            'sustainability' => [
                ['title' => 'Clean Coastal & Coral Reef Drives', 'desc' => 'Monthly community beach cleans in Nusa Dua and funding artificial reef nurseries in Amed & Menjangan.', 'icon' => 'Droplets'],
                ['title' => 'Direct Banjar & Artisan Guild Support', 'desc' => 'Fair wage guarantees for local silver smiths in Celuk, wood carvers in Mas, and organic coffee cultivators in Kintamani.', 'icon' => 'Users'],
                ['title' => 'Eco-Fleet & Carbon Offsets', 'desc' => 'Progressive fleet electrification and active reforestation planting in the Bedugul highlands for every 500km journeyed.', 'icon' => 'Leaf'],
            ],
            'voices' => [
                ['name' => 'Emma & Liam Walker', 'meta' => 'Melbourne, Australia • 7-Day Bespoke Tour', 'initials' => 'EW', 'text' => 'Bali Vision Tour transformed our honeymoon. Wayan took us to temples where we were the only foreigners, arranged a private water blessing, and our driver Made drove like an angel. Truly pristine service.'],
                ['name' => 'Hendra Pratama', 'meta' => 'Jakarta, Indonesia • Family Private Charter', 'initials' => 'HP', 'text' => 'Pelayanan Bali Vision Tour luar biasa! Mobil Alphard dan Innova Zenix sangat bersih dan wangi. Pak Ketut siap 24 jam di WhatsApp merekomendasikan beach club dan resto seafood terbaik di Jimbaran.'],
                ['name' => 'Sophie & Julian Dupont', 'meta' => 'Geneva, Switzerland • Curated Villa Journey', 'initials' => 'SD', 'text' => "Total peace of mind. Transparent upfront pricing with no awkward surprise commissions at souvenir shops. Saraswati's culinary route in Ubud was the highlight of our three weeks in Southeast Asia."],
            ],
        ],
        'airportRates' => [
            ['route' => 'DPS Airport → Kuta / Seminyak', 'meta' => 'Approx. 25 - 40 Mins • Toll included', 'price' => 175000], ['route' => 'DPS Airport → Uluwatu / Nusa Dua', 'meta' => 'Approx. 35 - 50 Mins • Toll included', 'price' => 250000],
            ['route' => 'DPS Airport → Canggu / Pererenan', 'meta' => 'Approx. 45 - 65 Mins • Coastal route', 'price' => 275000], ['route' => 'DPS Airport → Ubud Cultural Center', 'meta' => 'Approx. 75 - 90 Mins • Bypass highway', 'price' => 350000],
        ],
        'carInclusions' => [
            ['title' => 'English-Speaking Driver', 'desc' => 'Knowledgeable local drivers with spotless safety records. They act as your informal island guide, navigating hidden scenic routes with ease.', 'icon' => 'Users', 'tone' => 'sage'],
            ['title' => 'Fuel & Parking Covered', 'desc' => 'Clear upfront rates with standard gasoline included for 10 or 12 hours. No surprise charges or awkward tip obligations at the end of the day.', 'icon' => 'Fuel', 'tone' => 'brand'],
            ['title' => '100% Sanitized Fleet', 'desc' => 'Vehicles less than 3 years old, routinely serviced, vacuumed daily, and fully air-conditioned before your pickup arrives.', 'icon' => 'SprayCan', 'tone' => 'sage'],
            ['title' => 'Flexible Itinerary & Free Cancel', 'desc' => 'Create your own stops anywhere in Bali. Modify timing on the go or cancel free of charge up to 24 hours prior to departure.', 'icon' => 'Route', 'tone' => 'brand'],
        ],
        'activityWhy' => [
            ['title' => 'Certified Safety Standards', 'desc' => 'All operators adhere to international rescue standards, CE-marked safety gear, and licensed English-speaking instructors.', 'icon' => 'ShieldCheck', 'tone' => 'sage'],
            ['title' => 'Best Price Guarantee', 'desc' => 'Direct partnerships with local communities mean transparent prices with zero hidden platform markups or surprise gate fees.', 'icon' => 'BadgeDollarSign', 'tone' => 'brand'],
            ['title' => 'Instant Confirmation', 'desc' => 'Receive your digital travel voucher and pick-up time slot directly via WhatsApp and email within 5 minutes of checkout.', 'icon' => 'Zap', 'tone' => 'brand'],
            ['title' => 'Free Equipment & Insurance', 'desc' => 'Clean sanitized equipment, lockers, fresh shower towels, and up to Rp 500M personal medical coverage on every trip.', 'icon' => 'Shield', 'tone' => 'sand'],
        ],
        'tourPerks' => [
            ['title' => 'Private AC Fleet', 'sub' => 'Toyota Innova / HiAce VIP', 'icon' => 'Car'], ['title' => 'English Guide', 'sub' => 'Warm Balinese Hospitality', 'icon' => 'UserRound'], ['title' => 'All Entry Tickets', 'sub' => 'Zero Hidden Charges', 'icon' => 'Ticket'],
            ['title' => 'Bottled Water', 'sub' => 'Fresh Chilled Daily', 'icon' => 'Droplets'], ['title' => 'Flexible Pace', 'sub' => 'Tailored to Your Flow', 'icon' => 'CalendarClock'],
        ],
        'faqFacts' => [
            ['title' => 'When is the Best Travel Season?', 'desc' => 'Dry Season (April to October): Ideal for surfing, hiking Batur, and Nusa Penida boat transits. Green Season (Nov to March): Best for spa retreats, lush rice terrace photography, and fewer crowds.', 'icon' => 'Sun'],
            ['title' => 'Visa On Arrival (e-VOA)', 'desc' => 'Available for 90+ nationalities (IDR 500,000 / ~USD $35). Valid for 30 days and extendable once. We advise filling out the official e-Customs declaration form 3 days before arrival.', 'icon' => 'Luggage'],
            ['title' => 'Currency & Payment Tips', 'desc' => 'Indonesian Rupiah (IDR). Visa & Mastercard are accepted in upscale restaurants/hotels (often with a 2-3% fee). Keep IDR cash for temple donations, beach coconuts, and road tolls.', 'icon' => 'Banknote'],
            ['title' => 'Scooter vs Chauffeur Safety', 'desc' => 'Traffic in Canggu, Ubud, and Seminyak can be intense. For couples and families, chartered private air-conditioned transport eliminates accident liabilities and navigating narrow hillside alleys.', 'icon' => 'Car'],
        ],
        'trendingTags' => ['#NusaPenidaCliffs', '#KintamaniCafes', '#NyepiSilence2025', '#JimbaranSeafood', '#TirtaEmpulBlessing', '#LuxuryVillasUluwatu', '#PrivateDriverRates'],
        'footer' => [
            'description' => 'Premier provider of handpicked tour packages, cultural experiences, and trusted transportation for your bespoke journey in Bali.',
            'menuTitle' => 'Menu', 'servicesTitle' => 'Services & Policies', 'contactTitle' => 'Contact Us', 'paymentTitle' => 'Secure Payment Guaranteed:',
            'services' => [
                ['label' => 'Curated Itineraries', 'url' => '/tour-packages'], ['label' => 'Private Villas & Escapes', 'url' => '/tour-packages'], ['label' => 'Custom Experiences', 'url' => '/activities'],
                ['label' => 'Sustainable Travel', 'url' => '/about'], ['label' => 'Privacy Policy', 'url' => '/policies/privacy'], ['label' => 'Terms of Service', 'url' => '/policies/terms'],
            ],
            'payments' => ['BCA', 'MANDIRI', 'VISA', 'MASTERCARD'],
        ],
        'policies' => [
            'privacy' => ['title' => 'Privacy Policy', 'body' => 'Information submitted with your booking request is used to arrange your trip and contact you about availability. Please contact us for requests concerning your personal information.'],
            'terms' => ['title' => 'Terms of Service', 'body' => 'Bookings are subject to availability and confirmation by our team. Final prices, inclusions, payment and cancellation terms will be confirmed before you accept your booking.'],
        ],
    ],
    'tour_categories' => ['Family Trip', 'Private Tour', 'Cultural & Heritage', 'Adventure & Eco', 'Romantic Honeymoon'],
    'car_filters' => ['Family MPV', 'VIP Luxury', 'Group Vans'],
    'activity_types' => ['Water Sports & Marine', 'Adventure & Trekking', 'Culture & Workshops', 'Wellness & Spa', 'Wildlife & Nature'],
    'article_categories' => ['Island Itineraries', 'Cultural Etiquette', 'Food & Dining', 'Nusa Penida Guides', 'Sustainable Travel', 'Luxury Stays'],
];
