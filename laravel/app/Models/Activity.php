<?php

namespace App\Models;

class Activity extends ContentModel
{
    public static array $jsonFields = ['includes', 'tags', 'inclusions', 'exclusions', 'gallery', 'facts', 'highlights', 'timeline', 'slots', 'addons', 'packing', 'reviews_list'];

    protected $casts = ['rating' => 'float'];
}
