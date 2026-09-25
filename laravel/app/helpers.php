<?php

use App\Models\Setting;
use Illuminate\Support\Str;

function site(?string $key = null, $default = null)
{
    static $cache = null;
    if ($cache === null) {
        $cache = config('site');
        try {
            foreach (Setting::all() as $row) {
                if ($row->key === 'blocks') {
                    $cache['blocks'] = array_replace($cache['blocks'], $row->value ?? []);
                } elseif (isset($cache[$row->key]) && is_array($row->value)) {
                    $cache[$row->key] = array_replace($cache[$row->key], array_filter($row->value, fn ($v) => $v !== null));
                }
            }
        } catch (\Throwable $e) {
        }
    }
    return $key ? data_get($cache, $key, $default) : $cache;
}

function blocks(string $key, $default = [])
{
    return site("blocks.$key", $default);
}

function img(string $key): string
{
    return site("blocks.images.$key", '');
}

function fmt_idr($n): string
{
    if ($n === null || $n === '') {
        return '-';
    }
    return 'Rp ' . number_format((float) $n, 0, ',', '.');
}

function fmt_date($d): string
{
    if (!$d) {
        return '';
    }
    try {
        return \Carbon\Carbon::parse($d)->format('M d, Y');
    } catch (\Throwable $e) {
        return (string) $d;
    }
}

function icon(?string $name, string $class = 'w-4 h-4'): string
{
    $name = $name ?: 'circle';
    $kebab = strtolower(preg_replace(['/([a-z])([A-Z])/', '/([a-zA-Z])(\d)/'], '$1-$2', $name));
    return '<i data-lucide="' . e($kebab) . '" class="' . e($class) . '"></i>';
}

function wa_url(?string $message = null): string
{
    $text = $message ?? site('contact.whatsappMessage', '');
    return 'https://wa.me/' . site('contact.whatsapp') . '?text=' . rawurlencode($text);
}

function safe_link($value, string $fallback = '#'): string
{
    $v = trim((string) $value);
    if ($v === '') {
        return $fallback;
    }
    if (str_starts_with($v, '#') || (str_starts_with($v, '/') && !str_starts_with($v, '//') && !str_contains($v, '\\'))) {
        return $v;
    }
    $scheme = strtolower((string) parse_url($v, PHP_URL_SCHEME));
    return in_array($scheme, ['http', 'https', 'mailto', 'tel']) ? $v : $fallback;
}

function rich_html($value): string
{
    if (!$value) {
        return '';
    }
    if (is_array($value)) {
        return implode('', array_map(fn ($p) => '<p>' . e($p) . '</p>', array_filter($value)));
    }
    $s = (string) $value;
    if (!preg_match('/<[a-z][\s\S]*>/i', $s)) {
        return implode('', array_map(fn ($p) => '<p>' . nl2br(e(trim($p))) . '</p>', preg_split('/\n\s*\n/', $s)));
    }
    $s = strip_tags($s, '<p><br><strong><b><em><i><u><s><h2><h3><h4><ul><ol><li><blockquote><a><hr><span>');
    $s = preg_replace('/\s+on\w+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $s);
    $s = preg_replace('/(href|src)\s*=\s*(["\']?)\s*(javascript|data|vbscript):[^"\'\s>]*\2/i', '$1="#"', $s);
    $s = preg_replace('/\s+style\s*=\s*("[^"]*"|\'[^\']*\')/i', '', $s);
    return $s;
}

function media_url(?string $url): string
{
    return $url ?: '';
}

function tone_class(?string $tone, string $variant = 'soft'): string
{
    $soft = ['brand' => 'bg-brand-50 text-brand', 'sage' => 'bg-sage text-sage-700', 'sand' => 'bg-cream-200 text-sand', 'forest' => 'bg-forest text-white', 'gold' => 'bg-gold-100 text-gold'];
    $solid = ['brand' => 'bg-brand-800', 'forest' => 'bg-forest', 'gold' => 'bg-gold', 'sand' => 'bg-sand', 'sage' => 'bg-sage-700'];
    $map = $variant === 'solid' ? $solid : $soft;
    return $map[$tone] ?? $map['brand'];
}
