<?php

namespace App\Models;

class Car extends ContentModel
{
    public static array $jsonFields = ['gallery', 'tags', 'features', 'specs', 'amenities', 'pickup_areas', 'addons', 'routes', 'reviews_list'];
}
