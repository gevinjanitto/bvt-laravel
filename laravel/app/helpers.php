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
                } elseif ($row->key === 'texts') {
                    $cache['texts'] = is_array($row->value) ? $row->value : [];
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

function cms_text(string $key, string $default): string
{
    $texts = site('texts', []);
    return array_key_exists($key, $texts) ? (string) $texts[$key] : $default;
}

function cms_tpl(string $key, string $default, array $parts): string
{
    $segments = preg_split('/\{(\d+)\}/', cms_text($key, $default), -1, PREG_SPLIT_DELIM_CAPTURE);
    $out = '';
    foreach ($segments as $i => $seg) {
        $out .= $i % 2 ? ($parts[(int) $seg - 1] ?? '') : e($seg);
    }
    return $out;
}

function fa_icon(string $name, string $class = 'w-4 h-4'): string
{
    $paths = [
        'instagram' => [448, 'M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z'],
        'facebook' => [320, 'M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z'],
        'youtube' => [576, 'M549.655 124.083c-6.281-23.65-24.787-42.276-48.284-48.597C458.781 64 288 64 288 64S117.22 64 74.629 75.486c-23.497 6.322-42.003 24.947-48.284 48.597-11.412 42.867-11.412 132.305-11.412 132.305s0 89.438 11.412 132.305c6.281 23.65 24.787 41.5 48.284 47.821C117.22 448 288 448 288 448s170.78 0 213.371-11.486c23.497-6.321 42.003-24.171 48.284-47.821 11.412-42.867 11.412-132.305 11.412-132.305s0-89.438-11.412-132.305zm-317.51 213.508V175.185l142.739 81.205-142.739 81.201z'],
        'tiktok' => [448, 'M448,209.91a210.06,210.06,0,0,1-122.77-39.25V349.38A162.55,162.55,0,1,1,185,188.31V278.2a74.62,74.62,0,1,0,52.23,71.18V0l88,0a121.18,121.18,0,0,0,1.86,22.17h0A122.18,122.18,0,0,0,381,102.39a121.43,121.43,0,0,0,67,20.14Z'],
        'whatsapp' => [448, 'M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z'],
    ];
    [$w, $d] = $paths[$name] ?? $paths['instagram'];
    return '<svg viewBox="0 0 ' . $w . ' 512" class="' . e($class) . ' fill-current" aria-hidden="true"><path d="' . $d . '"/></svg>';
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
