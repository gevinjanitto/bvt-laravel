@extends('layouts.site')
@section('title', $car->name . ' — ' . site('brand.name'))
@section('content')
@php($gallery = $car->gallery ?? [])
@php($specs = [['Passenger Capacity', $car->capacity, $car->capacity_sub, 'Users'], ['Luggage Capacity', $car->luggage, $car->luggage_sub, 'Luggage'], ['Drivetrain & NVH', $car->drivetrain, $car->drivetrain_sub, 'Zap'], ['Interior Seating', $car->seating, $car->seating_sub, 'Armchair']])
<div data-testid="car-detail-page" x-data="{ duration: '10', date: '', time: '08:30 AM (Recommended)', area: @js(($car->pickup_areas ?? [])[0] ?? ''), addons: [], p10: {{ (int) $car->price }}, p12: {{ (int) ($car->price12h ?? $car->price) }}, addonList: @js($car->addons ?? []),
  base() { return this.duration === '12' ? this.p12 : this.p10; },
  total() { return this.base() + this.addonList.filter(a => this.addons.includes(a.title)).reduce((s, a) => s + Number(a.price || 0), 0); },
  book() { openBooking({ type: 'Car Rental', item_id: '{{ $car->id }}', item_name: @js($car->name), option: `${this.duration} Hours Charter • ${this.time} • Pickup: ${this.area}` + (this.addons.length ? ` • Add-ons: ${this.addons.join(', ')}` : ''), unit_price: this.total(), unit_label: 'Vehicle', pax: 2, date: this.date }); } }">
  <div class="mx-auto max-w-7xl px-4 md:px-6 lg:px-10 pt-8">
    <div class="flex flex-wrap items-center justify-between gap-3">
      <div class="flex flex-wrap items-center gap-2">@foreach($car->tags ?? [] as $i => $t)<x-pill :tone="$i === 0 ? 'forest' : ($i === 1 ? 'sage' : 'brand-soft')" :uppercase="true">{{ $t }}</x-pill>@endforeach</div>
      <div class="inline-flex items-center gap-1.5 rounded-full bg-white px-3 py-1.5 text-sm shadow-soft">{!! icon('Star', 'w-4 h-4 fill-gold text-gold') !!} <b>5.0</b> <span class="text-sand">(120+ reviews)</span></div>
    </div>
    <h1 class="font-display font-bold text-ink text-3xl md:text-5xl leading-[1.1] tracking-tight mt-5 max-w-4xl">{{ $car->headline ?: $car->name }}</h1>
    <div class="rich-content text-sand text-base md:text-lg mt-4 max-w-3xl leading-relaxed" data-testid="car-long-description">{!! rich_html($car->long_desc) !!}</div>
    <div class="grid lg:grid-cols-2 gap-4 mt-8">
      <div class="relative rounded-3xl overflow-hidden h-72 lg:h-[420px] img-zoom shadow-soft"><img src="{{ $gallery[0]['src'] ?? $car->image }}" alt="{{ $car->name }}" class="w-full h-full object-cover"><div class="absolute inset-0 bg-gradient-to-t from-ink/70 via-transparent to-transparent"></div><div class="absolute bottom-5 left-5 right-5 text-white flex items-end justify-between"><div><x-pill tone="brand" :uppercase="true">Flagship Fleet</x-pill><div class="font-display font-bold text-xl mt-2">{{ $car->name }}</div><div class="text-xs text-white/75">Smooth cruising across Bali's coastal and mountain roads</div></div><span class="w-9 h-9 rounded-full bg-white/20 backdrop-blur border border-white/30 flex items-center justify-center">{!! icon('Camera') !!}</span></div></div>
      <div class="grid grid-cols-2 gap-4">@foreach(array_slice($gallery, 1, 4) as $g)<div class="relative rounded-2xl overflow-hidden h-36 lg:h-[202px] img-zoom shadow-soft"><img src="{{ $g['src'] }}" alt="{{ $g['label'] ?? '' }}" class="w-full h-full object-cover" loading="lazy"><div class="absolute bottom-3 left-3"><x-pill tone="dark">{{ $g['label'] ?? '' }}</x-pill></div></div>@endforeach</div>
    </div>
  </div>

  <div class="mx-auto max-w-7xl px-4 md:px-6 lg:px-10 py-12 grid lg:grid-cols-3 gap-8 items-start">
    <div class="lg:col-span-2 space-y-8">
      <div class="bg-white rounded-3xl p-6 md:p-8 shadow-soft">
        <div class="flex items-start gap-3">{!! icon('Car', 'w-6 h-6 text-brand mt-1') !!}<div><h2 class="font-display font-bold text-2xl text-ink">Vehicle Specifications &amp; Luxury Amenities</h2><p class="text-sand text-sm mt-1">Engineered for silky silent travel through Bali's bustling towns and highland roads.</p></div></div>
        <div class="grid sm:grid-cols-2 gap-4 mt-6">@foreach($specs as [$l, $v, $s, $ic])<div class="flex gap-3 bg-cream rounded-2xl p-4"><span class="w-10 h-10 rounded-xl bg-sage text-sage-700 flex items-center justify-center shrink-0">{!! icon($ic, 'w-5 h-5') !!}</span><div><div class="text-[10px] uppercase tracking-[0.14em] font-bold text-ink/60">{{ $l }}</div><div class="font-semibold text-ink text-sm mt-0.5">{{ $v }}</div><div class="text-xs text-sand mt-0.5">{{ $s }}</div></div></div>@endforeach</div>
        <h3 class="font-display font-bold text-ink text-lg mt-8">Complimentary Onboard Luxury Touches</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mt-4">@foreach($car->amenities ?? [] as $a)<div class="bg-cream rounded-xl px-3 py-3 text-xs font-medium text-ink flex items-center gap-2">{!! icon('Sparkles', 'w-3.5 h-3.5 text-brand') !!} {{ $a }}</div>@endforeach</div>
      </div>

      <div class="rounded-3xl bg-gradient-to-br from-brand-800 to-brand-700 text-white p-8 grain relative overflow-hidden shadow-card"><div class="relative z-10">
        <div class="text-[10px] uppercase tracking-[0.16em] font-bold text-gold-100">The Bali Vision Distinction</div>
        <h2 class="font-display font-bold text-2xl md:text-3xl mt-2 flex items-center gap-3">{!! icon('ShieldCheck', 'w-7 h-7 text-gold') !!} White-Glove Private Chauffeur Guarantee</h2>
        <p class="text-white/85 mt-4 leading-relaxed">Unlike standard cab or ride-hailing services, our chauffeurs are handpicked Balinese hospitality professionals trained to anticipate your needs, navigate island traffic seamlessly, and safeguard your journey.</p>
        <div class="grid sm:grid-cols-3 gap-4 mt-6">@foreach([['Languages', 'Fluent English Speakers', 'Articulate local experts who share Balinese culture, etiquette, and authentic hidden gems along your route.'], ['SprayCan', 'Pristine Daily Sanitization', 'Vehicles undergo rigorous pre-departure deep cleaning, ozone deodorization, and strict non-smoking adherence.'], ['Compass', 'Informal Island Concierge', "From arranging temple sarongs to timing sunset dinners in Jimbaran without traffic stress, we've got you covered."]] as [$ic, $t, $d])<div class="rounded-2xl border border-white/20 bg-white/10 p-4">{!! icon($ic, 'w-5 h-5 text-gold') !!}<div class="font-semibold mt-3">{{ $t }}</div><div class="text-xs text-white/80 mt-1 leading-relaxed">{{ $d }}</div></div>@endforeach</div>
      </div></div>

      <div class="bg-white rounded-3xl p-6 md:p-8 shadow-soft">
        <h2 class="font-display font-bold text-2xl text-ink flex items-center gap-2">{!! icon('Clock', 'w-5 h-5 text-brand') !!} Charter Duration &amp; Geographic Coverage</h2>
        <div class="space-y-4 mt-6">
          @foreach([['01', '10', '10 Hours Full Day Charter', $car->price, 'Ideal for Southern and Central Bali explorations. Easily covers Seminyak, Canggu, Kuta, Sanur, Uluwatu cliff temples, Tanah Lot sunset, or central Ubud waterfalls and monkey forest.', ['10 Hours on-call chauffeur', 'Unlimited kms in designated zone'], false], ['02', '12', '12 Hours Extended Island Expedition', $car->price12h ?? $car->price, 'Recommended for long-distance highland adventures: Kintamani Mount Batur volcano view, Bedugul Lake Beratan water temple, Jatiluwih UNESCO Rice Terraces, or Eastern Bali Besakih Mother Temple.', ['12 Hours on-call chauffeur', 'Extended highland fuel included'], true]] as [$n, $dur, $t, $p, $d, $pts, $popular])
          <button type="button" @click="duration = '{{ $dur }}'" :class="duration === '{{ $dur }}' ? 'border-brand bg-brand-50/40' : 'border-ink/10 hover:border-brand/40'" class="w-full text-left rounded-2xl border p-5 transition-colors relative" data-testid="charter-option-{{ $n }}">
            @if($popular)<span class="absolute -top-2.5 right-4 bg-brand-800 text-white text-[9px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-md">Most Popular</span>@endif
            <div class="flex items-center justify-between"><div class="flex items-center gap-2"><x-pill :tone="$n === '01' ? 'sage' : 'brand'">Option {{ $n }}</x-pill><span class="font-display font-bold text-ink">{{ $t }}</span></div><span class="font-display font-bold text-brand-700">{{ fmt_idr($p) }}</span></div>
            <p class="text-sm text-sand mt-2">{{ $d }}</p>
            <div class="flex flex-wrap gap-4 mt-3 text-[11px] font-bold text-sage-700 uppercase tracking-wide">@foreach($pts as $x)<span class="flex items-center gap-1">{!! icon('CircleCheck', 'w-3.5 h-3.5') !!} {{ $x }}</span>@endforeach</div>
          </button>
          @endforeach
        </div>
        <div class="mt-5 rounded-2xl bg-cream px-5 py-4 text-sm flex gap-3">{!! icon('Plane', 'w-5 h-5 text-brand shrink-0') !!}<span><b>Need Ngurah Rai (DPS) Airport VIP Transfer?</b> <span class="text-sand">Chauffeur greets you at arrival with personalized nameboard, luggage portering, and chilled refreshments directly to your luxury villa.</span></span></div>
      </div>

      <div class="bg-white rounded-3xl p-6 md:p-8 shadow-soft">
        <h2 class="font-display font-bold text-2xl text-ink flex items-center gap-2">{!! icon('ShieldCheck', 'w-5 h-5 text-brand') !!} Transparent Inclusions &amp; Zero Hidden Costs</h2>
        <p class="text-sand text-sm mt-1">We believe luxury means no unexpected surprises or uncomfortable tipping moments.</p>
        <div class="grid sm:grid-cols-2 gap-4 mt-6">@foreach([['Chauffeur Fee & Meals', 'Full driver honorarium and daily meal allowance are completely covered.'], ['Vehicle Gasoline / Fuel', 'Standard fuel allowance for the entire 10 or 12 hour charter radius.'], ['Parking & Local Village Fees', 'Toll roads, temple village retribution passes, and parking permits paid for you.'], ['100% Flexible Custom Route', 'You design the stops, or pause whenever you wish for spontaneous photography.']] as [$t, $d])<div class="flex gap-3 bg-cream rounded-2xl p-4">{!! icon('CircleCheck', 'w-5 h-5 text-sage-700 shrink-0') !!}<div><div class="font-semibold text-ink text-sm">{{ $t }}</div><div class="text-xs text-sand mt-0.5">{{ $d }}</div></div></div>@endforeach</div>
      </div>

      @if($car->routes)<div class="bg-white rounded-3xl p-6 md:p-8 shadow-soft">
        <div class="flex items-center justify-between"><h2 class="font-display font-bold text-2xl text-ink flex items-center gap-2">{!! icon('Route', 'w-5 h-5 text-brand') !!} Suggested Curated Day Routes</h2><span class="text-xs text-sand">Customize anytime with your chauffeur</span></div>
        <div class="grid sm:grid-cols-2 gap-4 mt-6">@foreach($car->routes as $r)<div class="rounded-2xl border border-ink/10 p-5"><div class="flex items-center justify-between"><x-pill :tone="($r['tone'] ?? '') === 'sage' ? 'sage' : 'brand-soft'" :uppercase="true">{{ $r['tag'] ?? '' }}</x-pill><span class="text-xs text-sand">{{ $r['hours'] ?? '' }}</span></div><div class="font-display font-bold text-ink text-lg mt-3">{{ $r['title'] ?? '' }}</div><ul class="mt-3 space-y-1.5">@foreach($r['stops'] ?? [] as $s)<li class="flex items-start gap-2 text-xs text-ink/75">{!! icon('MapPin', 'w-3.5 h-3.5 text-brand shrink-0 mt-0.5') !!} {{ $s }}</li>@endforeach</ul><div class="text-[10px] font-bold uppercase tracking-[0.14em] text-brand mt-4">Included at standard rate →</div></div>@endforeach</div>
      </div>@endif

      @if($car->reviews_list)<div class="bg-cream-100 rounded-3xl p-6 md:p-8">
        <div class="flex items-center justify-between"><div><div class="eyebrow">Guest Experiences</div><h2 class="font-display font-bold text-2xl text-ink mt-1">What Discerning Travelers Say</h2></div><div class="text-right"><x-stars :value="5" class="w-4 h-4" /><div class="text-xs text-sand mt-1">5.0 / 5.0 Rating</div></div></div>
        <div class="grid sm:grid-cols-2 gap-4 mt-6">@foreach($car->reviews_list as $r)<div class="bg-white rounded-2xl p-5"><div class="flex items-center gap-3"><span class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold {{ ($r['tone'] ?? '') === 'forest' ? 'bg-forest' : 'bg-brand' }}">{{ $r['initials'] ?? '' }}</span><div><div class="font-semibold text-sm text-ink">{{ $r['name'] ?? '' }}</div><div class="text-xs text-sand">{{ $r['meta'] ?? '' }}</div></div></div><p class="font-serif italic text-ink/80 text-sm leading-relaxed mt-4">"{{ $r['text'] ?? '' }}"</p></div>@endforeach</div>
      </div>@endif
    </div>

    <div class="lg:sticky lg:top-24 space-y-5">
      <div class="bg-white rounded-3xl shadow-card overflow-hidden" data-testid="car-booking-sidebar">
        <div class="h-1.5 bg-gradient-to-r from-brand-700 via-brand to-gold"></div>
        <div class="p-6">
          <div class="flex items-start justify-between"><div><div class="field-label !mb-0">Starting From</div><div class="font-display font-bold text-3xl text-brand-700 mt-1">{{ fmt_idr($car->price) }}</div></div><div class="text-right"><div class="text-xs text-sand line-through">{{ fmt_idr(round($car->price * 1.18)) }}</div><x-pill tone="brand" class="mt-1">18% OFF</x-pill></div></div>
          <div class="mt-4 text-xs text-sage-700 flex items-center gap-1.5 font-medium">{!! icon('CircleCheck', 'w-3.5 h-3.5') !!} Guaranteed Model: {{ $car->name }}</div>
          <div class="mt-5"><label class="field-label">Select Charter Duration</label><div class="grid grid-cols-2 gap-3">
            @foreach([['10', 'South & Central', $car->price], ['12', 'Highlands Expedition', $car->price12h ?? $car->price]] as [$d, $l, $p])<button type="button" @click="duration = '{{ $d }}'" :class="duration === '{{ $d }}' ? 'border-brand bg-brand-50/50' : 'border-ink/10 hover:border-brand/40'" class="rounded-2xl border p-3 text-center transition-colors" data-testid="duration-{{ $d }}"><div class="font-display font-bold text-ink">{{ $d }} Hours</div><div class="text-[11px] text-sand">{{ $l }}</div><div class="text-xs font-bold text-brand-700 mt-1">{{ fmt_idr($p) }}</div></button>@endforeach
          </div></div>
          <div class="grid grid-cols-2 gap-3 mt-4">
            <div><label class="field-label">Rental Date</label><input type="date" x-model="date" min="{{ date('Y-m-d') }}" class="select-input !h-11"></div>
            <div><label class="field-label">Pickup Time</label><select x-model="time" class="select-input !h-11">@foreach(['06:00 AM', '07:00 AM', '08:30 AM (Recommended)', '10:00 AM', '12:00 PM'] as $t)<option>{{ $t }}</option>@endforeach</select></div>
          </div>
          <div class="mt-4"><label class="field-label">Pickup Location / Villa Area</label><select x-model="area" class="select-input !h-11">@foreach($car->pickup_areas ?? [] as $a)<option>{{ $a }}</option>@endforeach</select></div>
          @if($car->addons)<div class="mt-5"><label class="field-label">Bespoke Add-ons</label><div class="space-y-2">@foreach($car->addons as $a)<label class="flex items-center gap-3 rounded-xl border border-ink/10 bg-cream px-3 py-2.5 cursor-pointer hover:border-brand/40"><input type="checkbox" value="{{ $a['title'] }}" x-model="addons" class="accent-brand w-4 h-4"><span class="flex-1 text-sm text-ink">{{ $a['title'] }}</span><span class="text-xs font-bold text-brand">+{{ fmt_idr($a['price'] ?? 0) }}</span></label>@endforeach</div></div>@endif
          <div class="mt-5 pt-4 border-t border-ink/8 text-sm space-y-1.5"><div class="flex justify-between text-ink/70"><span>Base Charter Service</span><span x-text="fmtIDR(base())"></span></div><div class="flex justify-between font-bold text-ink"><span>Estimated Total</span><span class="text-brand-700 font-display text-lg" x-text="fmtIDR(total())" data-testid="car-total"></span></div><div class="text-[11px] text-sand text-right">No booking fees • Pay Chauffeur on Departure</div></div>
          <button type="button" @click="book()" class="btn-brand w-full !py-3.5 !rounded-2xl mt-5 !bg-brand-800 hover:!bg-brand-700" data-testid="book-car-btn">{!! icon('MessageCircle') !!} Book Vehicle via WhatsApp</button>
          <ul class="mt-5 space-y-2 text-xs text-ink/70">@foreach(['Free cancellation up to 24 hours prior', 'Zero credit card surcharge or prepayment hold', 'Instant confirmation & chauffeur contact via WhatsApp'] as $t)<li class="flex items-center gap-2">{!! icon('CircleCheck', 'w-3.5 h-3.5 text-sage-700') !!} {{ $t }}</li>@endforeach</ul>
        </div>
      </div>
      <div class="bg-cream-100 rounded-3xl p-6 text-center">{!! icon('ShieldCheck', 'w-7 h-7 text-brand-700 mx-auto') !!}<div class="font-display font-bold text-ink text-lg mt-2">100% Satisfaction Guarantee</div><p class="text-xs text-sand mt-1">If your chauffeur is late or the vehicle does not match the guaranteed model, your charter is free.</p></div>
    </div>
  </div>
  <x-newsletter eyebrow="Curated Island Inspirations" title="Make Moments That Last Across Bali" desc="Subscribe to receive secret luxury villa recommendations, off-the-beaten-path cultural itineraries, and private chauffeur seasonal privileges." cta="Join Privileges" />
</div>
@endsection
