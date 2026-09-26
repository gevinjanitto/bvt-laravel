@props(['value' => 5, 'class' => 'w-3.5 h-3.5'])
<div class="flex items-center gap-0.5">@for($i = 0; $i < 5; $i++)<i data-lucide="star" class="{{ $class }} {{ $i < round($value) ? 'fill-gold text-gold' : 'text-ink/15' }}"></i>@endfor</div>
