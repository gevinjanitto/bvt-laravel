@props(['tour', 'compact' => false])
<a href="{{ route('tours.show', $tour->slug) }}" class="group block bg-white rounded-2xl overflow-hidden shadow-soft lift" data-testid="tour-card-{{ $tour->slug }}">
  <div class="relative img-zoom overflow-hidden {{ $compact ? 'h-40' : 'h-56' }}">
    <img src="{{ $tour->image }}" alt="{{ $tour->title }}" class="w-full h-full object-cover" loading="lazy">
    <div class="absolute inset-0 bg-gradient-to-t from-ink/40 via-transparent to-transparent"></div>
    <div class="absolute top-3 left-3 flex items-center gap-1.5">
      @if($tour->badge)<x-pill tone="brand" :uppercase="true">{{ $tour->badge }}</x-pill>@endif
      <x-pill tone="white">{{ $tour->category }}</x-pill>
    </div>
    @unless($compact)<div class="absolute bottom-3 right-3"><x-pill tone="dark">{!! icon('Clock', 'w-3 h-3') !!} {{ $tour->duration }}</x-pill></div>@endunless
  </div>
  <div class="{{ $compact ? 'p-4' : 'p-5' }}">
    <div class="flex items-center justify-between text-[11px]">
      @if($compact)<span class="flex items-center gap-1 text-sand">{!! icon('Clock', 'w-3 h-3') !!} {{ $tour->duration }}</span>
      @else<span class="uppercase tracking-[0.14em] font-bold text-sage-700">{{ $tour->region }}</span>@endif
      <span class="flex items-center gap-1 text-ink font-semibold">{!! icon('Star', 'w-3.5 h-3.5 fill-gold text-gold') !!} {{ number_format($tour->rating, 1) }} <span class="text-sand font-normal">({{ $tour->reviews }})</span></span>
    </div>
    <h3 class="font-display font-bold text-ink mt-2 leading-snug group-hover:text-brand transition-colors duration-200 {{ $compact ? 'text-sm uppercase tracking-wide truncate' : 'text-lg' }}">{{ $tour->title }}</h3>
    @unless($compact)<ul class="mt-3 space-y-1.5">@foreach(array_slice($tour->highlights ?? [], 0, 3) as $h)<li class="flex items-start gap-2 text-[13px] text-ink/75">{!! icon('CircleCheck', 'w-4 h-4 text-brand shrink-0 mt-[1px]') !!} {{ $h }}</li>@endforeach</ul>@endunless
    <div class="flex items-center justify-between border-t border-ink/8 {{ $compact ? 'mt-3 pt-3' : 'mt-4 pt-4' }}">
      <div>
        <div class="text-[10px] uppercase tracking-wider text-sand font-semibold">{{ $tour->price_unit === 'Family' ? 'Package Rate' : 'Starting from' }}</div>
        <div class="font-display font-bold text-brand text-lg leading-tight">{{ fmt_idr($tour->price) }} <span class="text-xs text-sand font-body font-normal">/ {{ $tour->price_unit }}</span></div>
      </div>
      <span class="w-9 h-9 rounded-full bg-cream-100 group-hover:bg-brand group-hover:text-white text-brand flex items-center justify-center transition-colors duration-200">{!! icon('ArrowRight') !!}</span>
    </div>
  </div>
</a>
