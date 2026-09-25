<?php

namespace App\Models;

class Tour extends ContentModel
{
    public static array $jsonFields = ['highlights', 'gallery', 'features', 'itinerary', 'inclusions', 'exclusions', 'addons', 'tips', 'reviews_list'];

    protected $casts = ['bestseller' => 'boolean', 'featured' => 'boolean', 'rating' => 'float'];
}
