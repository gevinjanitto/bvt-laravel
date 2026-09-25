@extends('layouts.site')
@section('title', 'Car Rental — ' . site('brand.name'))
@section('content')
@php($default = $all->values()->get(1) ?? $all->first())
<div data-testid="car-rental-page" x-data="{ vehicle: '{{ $default?->id }}', pkg: 'Car + Driver + Petrol (10 Hours)', date: '', cars: @js($all->map(fn ($c) => ['id' => (string) $c->id, 'name' => $c->name, 'price' => (int) $c->price, 'price12h' => (int) ($c->price12h ?? $c->price)])->values()),
  check() { const c = this.cars.find(x => x.id === this.vehicle) || this.cars[0]; if (!c) return; openBooking({ type: 'Car Rental', item_id: c.id, item_name: c.name, option: this.pkg, unit_price: this.pkg.includes('12 Hours') ? c.price12h : c.price, unit_label: 'Vehicle', pax: 2, date: this.date }); } }">
  <x-page-hero :image="img('bedugul')" eyebrow="Official VIP Chauffeur Services • Bali Island" eyebrow-icon="ShieldCheck" title="Private Car Rental & Chauffeur Services in Bali" desc="Travel with ultimate peace of mind. Immaculate, air-conditioned vehicles driven by professional, English-speaking local drivers. Fuel, insurance, and unlimited smiles included.">
    <div class="mt-10 bg-white rounded-3xl p-6 md:p-7 shadow-card reveal" data-testid="fleet-calculator">
      <div class="flex flex-wrap items-center justify-between gap-3"><div class="flex items-center gap-2 font-display font-bold text-ink">{!! icon('Car', 'w-5 h-5 text-brand') !!} Fleet Availability &amp; Instant Rate Calculator</div><x-pill tone="sage">All-Inclusive Flat Pricing Guarantee</x-pill></div>
      <div class="grid md:grid-cols-4 gap-4 mt-6 items-end">
        <div><label class="field-label">Select Vehicle Category</label><select x-model="vehicle" class="select-input" data-testid="fleet-vehicle">@foreach($all as $c)<option value="{{ $c->id }}">{{ $c->category }} ({{ implode(' ', array_slice(explode(' ', $c->name), 1, 2)) }})</option>@endforeach</select></div>
        <div><label class="field-label">Service Package</label><select x-model="pkg" class="select-input" data-testid="fleet-package">@foreach(['Car + Driver + Petrol (10 Hours)', 'Car + Driver + Petrol (12 Hours)', 'Airport Transfer (One Way)', 'Multi-day Charter'] as $o)<option>{{ $o }}</option>@endforeach</select></div>
        <div><label class="field-label">Pickup Date</label><input type="date" x-model="date" min="{{ date('Y-m-d') }}" class="select-input" data-testid="fleet-date"></div>
        <button type="button" @click="check()" class="btn-brand h-12 !rounded-xl !bg-brand-800 hover:!bg-brand-700" data-testid="check-fleet">{!! icon('Search') !!} Check Fleet Availability</button>
      </div>
      <div class="flex flex-wrap gap-x-8 gap-y-2 mt-5 text-xs text-ink/70">@foreach(['Zero hidden taxes or credit card fees', 'English-speaking chauffeur confirmed with license', 'Free cancellation up to 24h before pick-up', 'Complimentary bottled mineral water & cold towels'] as $t)<span class="flex items-center gap-1.5">{!! icon('CircleCheck', 'w-3.5 h-3.5 text-sage-700') !!} {{ $t }}</span>@endforeach</div>
    </div>
  </x-page-hero>

  <section class="mx-auto max-w-7xl px-4 md:px-6 lg:px-10 py-16">
    <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6">
      <div><div class="eyebrow flex items-center gap-2">{!! icon('Car', 'w-3.5 h-3.5') !!} Curated Luxury Fleet</div><h2 class="font-display font-bold text-ink text-4xl md:text-5xl leading-[1.05] tracking-tight mt-3">Explore Private Vehicles &amp; Rates</h2><p class="text-sand mt-4 max-w-xl text-[15px]">All rates include brand-new sanitized vehicles, experienced professional drivers, standard gasoline/fuel, and hotel pickup across South &amp; Central Bali.</p></div>
      <div class="flex flex-wrap gap-2" data-testid="car-filters">@foreach(array_merge(['All Vehicles'], config('site.car_filters')) as $f)<a href="{{ route('cars', $f === 'All Vehicles' ? [] : ['filter' => $f]) }}#fleet" class="rounded-full px-4 py-2 text-xs font-semibold transition-colors {{ $filter === $f ? 'bg-forest text-white' : 'bg-cream-100 text-ink/80 hover:bg-cream-200' }}" data-testid="car-filter-{{ Str::slug($f) }}">{{ $f }}</a>@endforeach</div>
    </div>
    <div id="fleet"></div>
    @if($list->isEmpty())<div class="mt-10 bg-white rounded-3xl p-12 text-center shadow-soft" data-testid="car-empty-state"><h3 class="font-display text-xl font-bold">No vehicles in this category yet</h3><a href="{{ route('cars') }}" class="btn-outline mt-5" data-testid="car-reset-filters">Reset Filters</a></div>@endif
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-10">@foreach($list as $c)<x-car-card :car="$c" />@endforeach</div>
  </section>

  <section class="bg-cream-100 py-20"><div class="mx-auto max-w-7xl px-4 md:px-6 lg:px-10">
    <x-section-heading align="center" eyebrow="The Bali Vision Distinction" title="What Is Always Included In Your Charter" desc="Experience stress-free travel across the Island of the Gods with complete transparent rates and unmatched hospitality." />
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5 mt-12">@foreach(blocks('carInclusions') as $c)<div class="bg-white rounded-2xl p-6 shadow-soft lift h-full"><span class="w-12 h-12 rounded-full flex items-center justify-center {{ tone_class($c['tone'] ?? 'brand') }}">{!! icon($c['icon'], 'w-5 h-5') !!}</span><h3 class="font-display font-bold text-ink text-lg mt-5">{{ $c['title'] }}</h3><p class="text-sand text-sm mt-2 leading-relaxed">{{ $c['desc'] }}</p></div>@endforeach</div>
  </div></section>

  <section class="mx-auto max-w-7xl px-4 md:px-6 lg:px-10 py-20 grid lg:grid-cols-12 gap-10 items-center">
    <div class="lg:col-span-5">
      <div class="eyebrow flex items-center gap-2">{!! icon('Plane', 'w-3.5 h-3.5') !!} Airport Direct Service</div>
      <h2 class="font-display font-bold text-ink text-4xl md:text-5xl leading-[1.05] tracking-tight mt-3">Ngurah Rai Airport (DPS) Meet &amp; Greet Transfers</h2>
      <p class="text-sand mt-5 leading-relaxed">Skip lengthy taxi lines and airport negotiation stress. Your chauffeur awaits at the arrival terminal holding a personalized sign with your name, ready to assist with baggage and escort you to your air-conditioned vehicle.</p>
      <ul class="mt-6 space-y-2.5 text-sm text-ink/80">@foreach([['Radar', 'Flight tracker monitoring for delayed flights at no extra charge'], ['Clock', 'Up to 90 minutes complimentary airport waiting time after touchdown'], ['Ticket', 'Includes all airport parking and highway toll road tickets']] as [$ic, $t])<li class="flex items-center gap-2.5">{!! icon($ic, 'w-4 h-4 text-sage-700') !!} {{ $t }}</li>@endforeach</ul>
      <button type="button" @click="pkg = 'Airport Transfer (One Way)'; check()" class="btn-forest mt-8" data-testid="prebook-airport">{!! icon('Send') !!} Pre-Book Airport Chauffeur</button>
    </div>
    <div class="lg:col-span-7"><div class="bg-white rounded-3xl shadow-card overflow-hidden" data-testid="airport-rates">
      <div class="flex items-center justify-between px-6 py-4 bg-cream-100"><div class="font-display font-bold text-ink">Fixed Destination Rates (Per Vehicle)</div><span class="text-xs font-semibold text-brand">Standard MPV (Up to 4 Pax)</span></div>
      <div class="divide-y divide-ink/8">@foreach(blocks('airportRates') as $r)<div class="flex items-center gap-4 px-6 py-4 hover:bg-cream/60 transition-colors"><span class="w-9 h-9 rounded-full bg-brand-50 text-brand flex items-center justify-center shrink-0">{!! icon('MapPin') !!}</span><div class="flex-1"><div class="font-semibold text-ink text-sm">{{ str_replace('->', '→', $r['route']) }}</div><div class="text-xs text-sand">{{ $r['meta'] }}</div></div><div class="text-right"><div class="font-display font-bold text-brand-700">{{ fmt_idr($r['price']) }}</div><div class="text-[9px] uppercase tracking-wider text-sand font-bold">Net Price</div></div></div>@endforeach</div>
      <div class="flex items-center justify-between px-6 py-4 bg-cream text-xs"><span class="text-sand">Need an Innova or HiAce upgrade for airport transfer?</span><a href="{{ wa_url('Halo, saya ingin upgrade kendaraan untuk airport transfer.') }}" target="_blank" rel="noopener" class="text-brand font-semibold inline-flex items-center gap-1 hover:gap-2 transition-all">Contact Fleet Desk {!! icon('ArrowRight', 'w-3.5 h-3.5') !!}</a></div>
    </div></div>
  </section>

  <section class="sunset-band grain"><div class="relative z-10 mx-auto max-w-7xl px-6 lg:px-10 py-16 grid md:grid-cols-3 gap-8 items-center text-white">
    <div class="md:col-span-2"><x-pill tone="glass" :uppercase="true">24/7 Bali Concierge Desk</x-pill><h2 class="font-display font-bold text-3xl md:text-5xl leading-[1.05] mt-4">Need an immediate driver or custom multi-day charter?</h2><p class="text-white/85 mt-4 max-w-xl">Chat directly with our bilingual transport dispatch. Receive instant confirmations, custom quotes for Nusa Penida tours, or tailored multi-vehicle wedding fleets.</p></div>
    <div class="md:justify-self-end"><a href="{{ wa_url('Halo, saya butuh driver / charter multi-day di Bali. Bisa bantu?') }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 rounded-full bg-white text-brand-800 font-bold px-6 py-3.5 text-sm shadow-card hover:bg-cream transition-colors" data-testid="wa-quick-booking">{!! icon('MessageCircle', 'w-4 h-4 text-sage-700') !!} WhatsApp Quick Booking</a></div>
  </div></section>
  <x-newsletter desc="Receive exclusive Bali travel guides, secret beach recommendations, and VIP chauffeur privileges directly to your inbox." />
</div>
@endsection
