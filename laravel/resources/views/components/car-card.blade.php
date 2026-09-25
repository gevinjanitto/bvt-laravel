@props(['car'])
<div class="group bg-white rounded-2xl overflow-hidden shadow-soft lift flex flex-col {{ $car->badge ? 'ring-2 ring-brand/70' : '' }}" data-testid="car-card-{{ $car->slug }}">
  <a href="{{ route('cars.show', $car->slug) }}" class="relative img-zoom overflow-hidden h-48 block">
    <img src="{{ $car->image }}" alt="{{ $car->name }}" class="w-full h-full object-cover" loading="lazy">
    <div class="absolute top-3 left-3"><x-pill tone="forest" :uppercase="true">{{ $car->category }}</x-pill></div>
    @if($car->badge)<div class="absolute top-0 right-0"><span class="inline-block bg-brand-800 text-white text-[10px] font-bold uppercase tracking-[0.12em] px-3.5 py-1.5 rounded-bl-xl">{{ $car->badge }}</span></div>@endif
  </a>
  <div class="p-5 flex flex-col flex-1">
    <a href="{{ route('cars.show', $car->slug) }}"><h3 class="font-display font-bold text-ink text-lg leading-snug group-hover:text-brand transition-colors">{{ $car->name }}</h3></a>
    <p class="text-[13px] text-sand mt-1.5 leading-relaxed">{{ $car->description }}</p>
    <div class="grid grid-cols-2 gap-x-3 gap-y-2 mt-4 rounded-xl bg-cream-100 p-3">
      @foreach(array_slice($car->specs ?? [], 0, 4) as $s)<div class="flex items-center gap-1.5 text-[12px] text-ink/80 font-medium">{!! icon($s['icon'] ?? 'Check', 'w-3.5 h-3.5 text-brand') !!} {{ $s['label'] ?? '' }}</div>@endforeach
    </div>
    <ul class="mt-3 space-y-1.5 flex-1">@foreach(array_slice($car->features ?? [], 0, 2) as $f)<li class="flex items-start gap-2 text-[12.5px] text-ink/70">{!! icon('CircleCheck', 'w-3.5 h-3.5 text-sage-700 shrink-0 mt-[2px]') !!} {{ $f }}</li>@endforeach</ul>
    <div class="flex items-center justify-between mt-5 pt-4 border-t border-ink/8">
      <div><div class="text-[10px] text-sand">From / 10 Hours</div><div class="font-display font-bold text-brand text-lg leading-tight">{{ fmt_idr($car->price) }}</div></div>
      <button type="button" @click="openBooking({ type: 'Car Rental', item_id: '{{ $car->id }}', item_name: @js($car->name), option: 'Car + Driver + Petrol (10 Hours)', unit_price: {{ (int) $car->price }}, unit_label: 'Vehicle', pax: 2 })" class="{{ $car->badge ? 'btn-brand !bg-brand-800 hover:!bg-brand-700' : 'btn-forest' }} !py-2 !px-4 !text-xs" data-testid="car-book-{{ $car->slug }}">Book Vehicle {!! icon('ChevronRight', 'w-3.5 h-3.5') !!}</button>
    </div>
  </div>
</div>
