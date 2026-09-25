@props(['light' => false, 'size' => 'md', 'testId' => null])
@php($brand = site('brand'))
@php($dims = $size === 'lg' ? 'w-14 h-14' : ($size === 'sm' ? 'w-9 h-9' : 'w-11 h-11'))
@php($title = $size === 'lg' ? 'text-2xl' : ($size === 'sm' ? 'text-base' : 'text-lg'))
@php($tid = $testId ?? 'logo-' . ($light ? 'light' : 'dark') . '-' . $size)
<a href="/" class="flex items-center gap-2.5 group min-w-0 shrink-0" data-testid="{{ $tid }}">
  <img src="{{ ($light && $brand['logoLight']) ? $brand['logoLight'] : ($brand['logo'] ?: '/logo-icon.png') }}" alt="{{ $brand['name'] }}" data-testid="{{ $tid }}-image" class="{{ $brand['logoMode'] === 'full' ? 'w-40 h-12' : $dims }} object-contain transition-transform duration-300 group-hover:scale-105">
  @if($brand['logoMode'] !== 'full')
  <div class="leading-none">
    <div class="font-display font-bold {{ $title }} {{ $light ? 'text-white' : 'text-ink' }}">{{ $brand['title'] }}</div>
    <div class="font-display font-bold tracking-[0.12em] {{ $size === 'lg' ? 'text-xs' : 'text-[9px]' }} {{ $light ? 'text-white/85' : 'text-brand' }}">{{ $brand['tagline'] }}</div>
  </div>
  @endif
</a>
