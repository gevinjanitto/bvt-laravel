<?php

$site = require __DIR__ . '/site.php';
$f = fn ($key, $label, $type = 'text', $extra = []) => array_merge(['key' => $key, 'label' => $label, 'type' => $type], $extra);

return [
    'tours' => [
        'model' => App\Models\Tour::class, 'title' => 'Tour Packages', 'singular' => 'Tour Package', 'title_key' => 'title',
        'columns' => ['category' => 'Category', 'duration' => 'Duration', 'price' => 'Price', 'rating' => 'Rating', 'featured' => 'Featured'],
        'sections' => [
            'Basic Info' => [$f('title', 'Title', 'text', ['required' => true, 'span' => 2]), $f('slug', 'Slug (auto if empty)'), $f('category', 'Category', 'select', ['options' => $site['tour_categories']]), $f('region', 'Region / Destination'), $f('badge', 'Badge (e.g. Bestseller)'), $f('duration', 'Duration label (e.g. 4 Days 3 Nights)'), $f('days', 'Days', 'number'), $f('rating', 'Rating (0-5)', 'number', ['step' => 0.1]), $f('reviews', 'Review count', 'number'), $f('bestseller', 'Show on Home (Bestselling)', 'bool'), $f('featured', 'Featured', 'bool')],
            'Pricing & Media' => [$f('price', 'Price (IDR)', 'number', ['required' => true]), $f('price_unit', 'Price unit', 'select', ['options' => ['Person', 'Family']]), $f('original_price', 'Original price (for discount)', 'number'), $f('image', 'Cover image', 'image', ['span' => 2]), $f('gallery', 'Gallery — JSON list of {src,label}', 'json', ['span' => 2])],
            'Content' => [$f('subtitle', 'Subtitle', 'text', ['span' => 2]), $f('description', 'Short description', 'textarea', ['span' => 2]), $f('long_description', 'Long description (HTML allowed)', 'richtext', ['span' => 2]), $f('highlights', 'Highlights (one per line)', 'lines', ['span' => 2]), $f('inclusions', 'Inclusions (one per line)', 'lines'), $f('exclusions', 'Exclusions (one per line)', 'lines')],
            'Itinerary & Details' => [$f('features', 'Features — JSON list of {icon,title,desc}', 'json'), $f('itinerary', 'Itinerary — JSON list of {day,title,desc,points[],meals}', 'json'), $f('addons', 'Add-ons — JSON list of {title,price,desc}', 'json'), $f('tips', 'Tips — JSON list of {icon,title,desc}', 'json'), $f('reviews_list', 'Reviews — JSON list of {name,location,avatar,text,date}', 'json', ['span' => 2])],
        ],
    ],
    'cars' => [
        'model' => App\Models\Car::class, 'title' => 'Car Rental', 'singular' => 'Vehicle', 'title_key' => 'name',
        'columns' => ['category' => 'Category', 'filter' => 'Group', 'price' => 'Price / 10h', 'price12h' => 'Price / 12h', 'badge' => 'Badge'],
        'sections' => [
            'Basic Info' => [$f('name', 'Vehicle name', 'text', ['required' => true, 'span' => 2]), $f('slug', 'Slug (auto if empty)'), $f('category', 'Category label (e.g. Premium MPV)'), $f('filter', 'Filter group', 'select', ['options' => $site['car_filters']]), $f('badge', 'Badge (e.g. Most Popular)'), $f('headline', 'Detail headline', 'text', ['span' => 2]), $f('description', 'Short description', 'textarea', ['span' => 2]), $f('long_desc', 'Long description (HTML allowed)', 'richtext', ['span' => 2])],
            'Pricing & Media' => [$f('price', 'Price 10 hours (IDR)', 'number', ['required' => true]), $f('price12h', 'Price 12 hours (IDR)', 'number'), $f('image', 'Cover image', 'image', ['span' => 2]), $f('gallery', 'Gallery — JSON list of {src,label}', 'json', ['span' => 2])],
            'Specifications' => [$f('capacity', 'Capacity'), $f('capacity_sub', 'Capacity note'), $f('luggage', 'Luggage'), $f('luggage_sub', 'Luggage note'), $f('drivetrain', 'Drivetrain'), $f('drivetrain_sub', 'Drivetrain note'), $f('seating', 'Seating'), $f('seating_sub', 'Seating note'), $f('tags', 'Tags (one per line)', 'lines'), $f('features', 'Key features (one per line)', 'lines'), $f('amenities', 'Amenities (one per line)', 'lines'), $f('pickup_areas', 'Pickup areas (one per line)', 'lines')],
            'Extras & Routes' => [$f('specs', 'Specs chips — JSON list of {icon,label}', 'json'), $f('addons', 'Add-ons — JSON list of {title,price}', 'json'), $f('routes', 'Routes — JSON list of {title,tag,hours,tone,stops[]}', 'json'), $f('reviews_list', 'Reviews — JSON list of {name,initials,meta,text,tone}', 'json')],
        ],
    ],
    'activities' => [
        'model' => App\Models\Activity::class, 'title' => 'Activities', 'singular' => 'Activity', 'title_key' => 'title',
        'columns' => ['type' => 'Type', 'duration' => 'Duration', 'price' => 'Price', 'rating' => 'Rating', 'badge' => 'Badge'],
        'sections' => [
            'Basic Info' => [$f('title', 'Title', 'text', ['required' => true, 'span' => 2]), $f('slug', 'Slug (auto if empty)'), $f('type', 'Experience type', 'select', ['options' => $site['activity_types']]), $f('category', 'Category label (e.g. Adventure & Waters)'), $f('badge', 'Badge (e.g. Must Do)'), $f('badge_tone', 'Badge color', 'select', ['options' => ['brand', 'forest', 'sand', 'gold']]), $f('duration', 'Duration (e.g. 3 Hours)'), $f('rating', 'Rating (0-5)', 'number', ['step' => 0.1]), $f('reviews', 'Review count', 'number')],
            'Pricing & Media' => [$f('price', 'Price per person (IDR)', 'number', ['required' => true]), $f('image', 'Cover image', 'image', ['span' => 2]), $f('gallery', 'Gallery — JSON list of {src,label}', 'json', ['span' => 2])],
            'Content' => [$f('headline', 'Detail headline', 'text', ['span' => 2]), $f('description', 'Short description', 'textarea', ['span' => 2]), $f('long_description', 'Long description (HTML allowed)', 'richtext', ['span' => 2]), $f('includes', 'Card includes (one per line)', 'lines'), $f('tags', 'Detail tags (one per line)', 'lines'), $f('inclusions', 'Inclusions (one per line)', 'lines'), $f('exclusions', 'Exclusions (one per line)', 'lines')],
            'Schedule & Details' => [$f('facts', 'Facts — JSON list of {icon,label,value,sub}', 'json'), $f('highlights', 'Highlights — JSON list of {icon,title,desc}', 'json'), $f('timeline', 'Timeline — JSON list of {time,title,tone,desc}', 'json'), $f('slots', 'Time slots — JSON list of {label,sub}', 'json'), $f('addons', 'Add-ons — JSON list of {title,price,desc}', 'json'), $f('packing', 'Packing — JSON list of {icon,title,desc}', 'json'), $f('reviews_list', 'Reviews — JSON list of {name,initials,location,text,date,tone}', 'json', ['span' => 2])],
        ],
    ],
    'articles' => [
        'model' => App\Models\Article::class, 'title' => 'Articles', 'singular' => 'Article', 'title_key' => 'title',
        'columns' => ['category' => 'Category', 'date' => 'Date', 'read_time' => 'Read time', 'featured' => 'Featured'],
        'sections' => [
            'Basic Info' => [$f('title', 'Title', 'text', ['required' => true, 'span' => 2]), $f('slug', 'Slug (auto if empty)'), $f('category', 'Category', 'select', ['options' => $site['article_categories']]), $f('date', 'Publish date', 'date'), $f('read_time', 'Read time (e.g. 5 min read)'), $f('location', 'Location'), $f('featured', 'Featured guide of the month', 'bool'), $f('subtitle', 'Subtitle', 'text', ['span' => 2]), $f('excerpt', 'Excerpt', 'textarea', ['span' => 2, 'required' => true])],
            'Media & Author' => [$f('image', 'Cover image', 'image', ['span' => 2]), $f('image_caption', 'Image caption', 'text', ['span' => 2]), $f('author', 'Author — JSON {name,role,avatar,license,bio}', 'json', ['span' => 2])],
            'Content' => [$f('content', 'Article blocks — JSON list of {type:h2|p|quote|list|steps|gallery|cards, text, items[]}', 'json', ['span' => 2]), $f('bullets', 'Featured bullets (one per line)', 'lines'), $f('tags', 'Header tags (one per line)', 'lines'), $f('highlights', "Curator's highlights (one per line)", 'lines'), $f('facts', 'Sidebar facts — JSON list of {label,value}', 'json')],
        ],
    ],
];
