<?php

namespace App\Models;

class Article extends ContentModel
{
    public static array $jsonFields = ['author', 'tags', 'bullets', 'highlights', 'facts', 'content'];

    protected $casts = ['featured' => 'boolean', 'date' => 'date'];
}
