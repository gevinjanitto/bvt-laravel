<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

abstract class ContentModel extends Model
{
    protected $guarded = [];

    public static array $jsonFields = [];

    public function getCasts(): array
    {
        $casts = ['order' => 'integer'];
        foreach (static::$jsonFields as $f) {
            $casts[$f] = 'array';
        }
        return array_merge(parent::getCasts(), $casts);
    }

    public function scopeOrdered($q)
    {
        return $q->orderBy('order')->orderBy('id');
    }

    public static function findBySlug(string $slug): ?static
    {
        return static::where('slug', $slug)->orWhere('id', is_numeric($slug) ? (int) $slug : 0)->first();
    }

    public static function uniqueSlug(string $base, ?int $ignoreId = null): string
    {
        $base = Str::slug($base) ?: Str::random(8);
        $slug = $base;
        $i = 2;
        while (static::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }
        return $slug;
    }

    public function displayTitle(): string
    {
        return $this->title ?? $this->name ?? '';
    }
}
