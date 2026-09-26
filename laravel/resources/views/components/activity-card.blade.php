@props(['activity'])
<div class="group bg-white rounded-2xl overflow-hidden shadow-soft lift flex flex-col" data-testid="activity-card-{{ $activity->slug }}">
  <a href="{{ route('activities.show', $activity->slug) }}" class="relative img-zoom overflow-hidden h-52 block">
    <img src="{{ $activity->image }}" alt="{{ $activity->title }}" class="w-full h-full object-cover" loading="lazy">
    <div class="absolute inset-0 bg-gradient-to-t from-ink/50 via-transparent to-transparent"></div>
    <div class="absolute top-3 left-3"><x-pill :tone="in_array($activity->badge_tone, ['brand','forest','sand','gold']) ? $activity->badge_tone : 'brand'" :uppercase="true">{{ $activity->badge }}</x-pill></div>
    <div class="absolute bottom-3 left-3"><x-pill tone="dark">{!! icon('Clock', 'w-3 h-3') !!} {{ $activity->duration }}</x-pill></div>
  </a>
  <div class="p-5 flex flex-col flex-1">
    <div class="flex items-center justify-between text-[11px]">
      <span class="uppercase tracking-[0.14em] font-bold text-sage-700">{{ $activity->category }}</span>
      <span class="flex items-center gap-1 text-ink font-semibold">{!! icon('Star', 'w-3.5 h-3.5 fill-gold text-gold') !!} {{ number_format($activity->rating, 1) }} <span class="text-sand font-normal">({{ $activity->reviews }})</span></span>
    </div>
    <a href="{{ route('activities.show', $activity->slug) }}"><h3 class="font-display font-bold text-ink text-lg leading-snug mt-2 group-hover:text-brand transition-colors">{{ $activity->title }}</h3></a>
    <div class="text-[10px] uppercase tracking-[0.14em] font-bold text-ink/60 mt-3">Includes:</div>
    <ul class="mt-1.5 space-y-1.5 flex-1">@foreach(array_slice($activity->includes ?? [], 0, 3) as $h)<li class="flex items-start gap-2 text-[12.5px] text-ink/75">{!! icon('CircleCheck', 'w-3.5 h-3.5 text-sage-700 shrink-0 mt-[2px]') !!} {{ $h }}</li>@endforeach</ul>
    <div class="flex items-center justify-between mt-4 pt-4 border-t border-ink/8">
      <div><div class="text-[10px] text-sand">From</div><div class="font-display font-bold text-brand text-lg leading-tight">{{ fmt_idr($activity->price) }} <span class="text-xs text-sand font-body font-normal">/ Person</span></div></div>
      <button type="button" @click="openBooking({ type: 'Activity', item_id: '{{ $activity->id }}', item_name: @js($activity->title), unit_price: {{ (int) $activity->price }}, unit_label: 'Person', pax: 2 })" class="btn-forest !py-2 !px-4 !text-xs" data-testid="activity-book-{{ $activity->slug }}">Book {!! icon('ChevronRight', 'w-3.5 h-3.5') !!}</button>
    </div>
  </div>
</div>
