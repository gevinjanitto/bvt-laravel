<?php

namespace App\Support;

class CmsText
{
    public const SCOPES = [
        'site/home' => 'Home', 'site/tours' => 'TourPackages', 'site/tour-detail' => 'TourDetail', 'site/cars' => 'CarRental',
        'site/car-detail' => 'CarDetail', 'site/activities' => 'Activities', 'site/activity-detail' => 'ActivityDetail', 'site/about' => 'About',
        'site/articles' => 'Articles', 'site/article-detail' => 'ArticleDetail', 'site/policy' => 'PolicyPage', 'components/newsletter' => 'Layout',
        'components/page-hero' => 'Layout', 'layouts/site' => 'Navbar', 'components/tour-card' => 'Cards', 'components/car-card' => 'Cards',
        'components/activity-card' => 'Cards', 'components/article-card' => 'Cards', 'components/booking-dialog' => 'BookingDialog',
    ];

    private const ATTRS = ['title', 'title-accent', 'desc', 'eyebrow', 'placeholder', 'alt', 'cta', 'label'];
    private const TAG_RE = '/(<\/?[a-zA-Z][^<>"\']*(?:"[^"]*"[^<>"\']*|\'[^\']*\'[^<>"\']*)*\/?>)/';
    private const SKIP_TAGS = ['script', 'style', 'option', 'title', 'select', 'svg', 'textarea'];

    public static function scopeOf(string $path): ?string
    {
        $rel = str_replace('\\', '/', $path);
        if (!preg_match('#resources/views/(.+)\.blade\.php$#', $rel, $m)) return null;
        return self::SCOPES[$m[1]] ?? null;
    }

    public static function key(string $scope, string $kind, string $value): string
    {
        return $scope . '-' . $kind . '-' . substr(sha1($value), 0, 12);
    }

    private static function clean(string $v): string
    {
        return trim(preg_replace('/\s+/', ' ', html_entity_decode($v, ENT_QUOTES | ENT_HTML5, 'UTF-8')));
    }

    private static function role(string $kind, string $tag, string $cls): string
    {
        if ($kind === 'eyebrow' || preg_match('/(^|\s)eyebrow(\s|$)/', $cls) || (str_contains($cls, 'uppercase') && str_contains($cls, 'tracking'))) return 'eyebrow';
        if (in_array($kind, ['title', 'title-accent']) || in_array($tag, ['h1', 'h2', 'h3', 'h4'])) return 'heading';
        if (in_array($tag, ['b', 'strong'])) return 'bold';
        if (in_array($tag, ['i', 'em']) || str_contains($cls, 'italic')) return 'italic';
        if ($tag === 'span' && preg_match('/text-(brand|gold|forest|sage)/', $cls)) return 'accent';
        if ($kind === 'cta' || in_array($tag, ['button', 'a'])) return 'button';
        if (in_array($kind, ['placeholder', 'alt', 'label'])) return $kind;
        if ($kind === 'desc' || $tag === 'p') return 'paragraph';
        return 'text';
    }

    /** Walk a template; $emit(kind, default, tag, class, sectionIndex, sectionLabel, exprs) returns replacement or null. */
    private static function walk(string $tpl, string $scope, callable $emit): string
    {
        $masks = [];
        $mask = function ($re, $s) use (&$masks) {
            return preg_replace_callback($re, function ($m) use (&$masks) { $masks[] = $m[0]; return "\x02" . (count($masks) - 1) . "\x03"; }, $s);
        };
        $tpl = $mask('/\{\{--.*?--\}\}/s', $tpl);
        $tpl = $mask('/@php\b.*?@endphp/s', $tpl);
        $tpl = $mask('/@php\s*\((?:[^()]|\((?:[^()]|\([^()]*\))*\))*\)/s', $tpl);
        $tpl = $mask('/<(script|style)\b[^>]*>.*?<\/\1>/is', $tpl);
        $tpl = $mask('/@(?!php\b)[a-zA-Z]+\s*\((?:[^()]|\((?:[^()]|\((?:[^()]|\([^()]*\))*\))*\))*\)/s', $tpl);
        $exprMasks = [];
        $tpl = preg_replace_callback('/\{!!(.*?)!!\}|\{\{(.*?)\}\}/s', function ($m) use (&$exprMasks) {
            $exprMasks[] = isset($m[2]) && $m[2] !== '' ? 'e(' . trim($m[2]) . ')' : trim($m[1]);
            return "\x04" . (count($exprMasks) - 1) . "\x05";
        }, $tpl);

        $parts = preg_split(self::TAG_RE, $tpl, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_OFFSET_CAPTURE | PREG_SPLIT_NO_EMPTY);
        $sections = [];
        $out = '';
        $lastTag = '';
        $lastCls = '';
        $skipDepth = 0;
        $skipTag = '';
        $sectionIdx = 0;
        $secLabels = [];
        preg_match_all('/<section\b[^>]*>(.*?)<\/section>/s', $tpl, $secs, PREG_OFFSET_CAPTURE);
        foreach ($secs[0] as $i => $s) {
            $label = '';
            if (preg_match('/<h[1-4]\b[^>]*>(.*?)<\/h[1-4]>/s', $s[0], $h)) $label = self::clean(preg_replace('/<[^>]+>|\x04\d+\x05/', ' ', $h[1]));
            $sections[] = ['start' => $s[1], 'end' => $s[1] + strlen($s[0]), 'idx' => $i + 1, 'label' => $label];
        }
        $sectionFor = function (int $pos) use ($sections) {
            foreach ($sections as $s) if ($pos >= $s['start'] && $pos < $s['end']) return [$s['idx'], $s['label']];
            return [0, ''];
        };

        foreach ($parts as [$seg, $pos]) {
            if ($seg[0] === '<') {
                preg_match('/^<(\/?)([a-zA-Z][\w:.-]*)/', $seg, $tm);
                $name = strtolower($tm[2] ?? '');
                $closing = ($tm[1] ?? '') === '/';
                if ($skipDepth) {
                    if ($name === $skipTag) $skipDepth += $closing ? -1 : 1;
                    $out .= $seg;
                    continue;
                }
                if (!$closing && in_array($name, self::SKIP_TAGS) && !str_ends_with($seg, '/>')) { $skipDepth = 1; $skipTag = $name; }
                if (!$closing) {
                    $lastTag = $name;
                    $lastCls = preg_match('/\bclass="([^"]*)"/', $seg, $cm) ? $cm[1] : '';
                    [$sIdx, $sLabel] = $sectionFor($pos);
                    $isComponent = str_starts_with($name, 'x-');
                    $seg = preg_replace_callback('/(?<=\s)(' . implode('|', self::ATTRS) . ')="([^"\x02\x04@]+)"/', function ($m) use ($emit, $name, $lastCls, $sIdx, $sLabel, $isComponent) {
                        $attr = $m[1];
                        if (!$isComponent && !in_array($attr, ['placeholder', 'alt'])) return $m[0];
                        $val = self::clean($m[2]);
                        if ($val === '' || str_contains($val, '"') || !preg_match('/[a-zA-Z]/', $val)) return $m[0];
                        $rep = $emit($attr, $val, $name, $lastCls, $sIdx, $sLabel, []);
                        if ($rep === null) return $m[0];
                        return $isComponent ? ':' . $attr . '="' . $rep . '"' : $attr . '="{{ ' . $rep . ' }}"';
                    }, $seg);
                }
                $out .= $seg;
                continue;
            }
            if ($skipDepth || str_contains($seg, '@') || str_contains($seg, "\x02") || str_contains($seg, '$')) { $out .= $seg; continue; }
            $lead = strspn($seg, " \t\r\n");
            $trail = strlen($seg) - strlen(rtrim($seg));
            $core = trim($seg);
            $exprs = [];
            $text = preg_replace_callback('/\x04(\d+)\x05/', function ($m) use (&$exprs, $exprMasks) { $exprs[] = $exprMasks[(int) $m[1]]; return '{' . count($exprs) . '}'; }, $core);
            $plain = preg_replace('/\{\d+\}/', '', $text);
            if (!preg_match('/[a-zA-Z]/', $plain)) { $out .= $seg; continue; }
            $default = self::clean($text);
            if ($exprs && count(array_filter(preg_split('/\{\d+\}/', $default), fn ($p) => preg_match('/[a-zA-Z]/', $p))) < 1) { $out .= $seg; continue; }
            [$sIdx, $sLabel] = $sectionFor($pos);
            $rep = $emit('text', $default, $lastTag, $lastCls, $sIdx, $sLabel, $exprs);
            $out .= $rep === null ? $seg : substr($seg, 0, $lead) . $rep . ($trail ? substr($seg, -$trail) : '');
        }

        $out = preg_replace_callback('/\x04(\d+)\x05/', fn ($m) => str_starts_with($exprMasks[(int) $m[1]], 'e(') ? '{{ ' . substr($exprMasks[(int) $m[1]], 2, -1) . ' }}' : '{!! ' . $exprMasks[(int) $m[1]] . ' !!}', $out);
        return preg_replace_callback('/\x02(\d+)\x03/', fn ($m) => $masks[(int) $m[1]], $out);
    }

    public static function instrument(string $tpl, string $scope): string
    {
        return self::walk($tpl, $scope, function ($kind, $default, $tag, $cls, $sIdx, $sLabel, $exprs) use ($scope) {
            $key = self::key($scope, $kind, $default);
            $k = var_export($key, true);
            $d = var_export($default, true);
            if ($kind !== 'text') return "cms_text($k, $d)";
            if ($exprs) return '{!! cms_tpl(' . $k . ', ' . $d . ', [' . implode(', ', $exprs) . ']) !!}';
            return "{{ cms_text($k, $d) }}";
        });
    }

    public static function catalog(): array
    {
        $entries = [];
        foreach (self::SCOPES as $rel => $scope) {
            $file = resource_path("views/$rel.blade.php");
            if (!is_file($file)) continue;
            self::walk(file_get_contents($file), $scope, function ($kind, $default, $tag, $cls, $sIdx, $sLabel) use ($scope, &$entries) {
                $key = self::key($scope, $kind, $default);
                $entries[$key] = ['key' => $key, 'scope' => $scope, 'kind' => $kind, 'default' => $default, 'tag' => $tag, 'role' => self::role($kind, $tag, $cls), 'section' => $sIdx, 'sectionLabel' => $sLabel];
                return null;
            });
        }
        return array_values($entries);
    }
}
