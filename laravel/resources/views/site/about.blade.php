@extends('layouts.site')
@section('title', 'About Us — ' . site('brand.name'))
@section('content')
@php($A = blocks('about'))
<div data-testid="about-page">
  <section class="mx-auto max-w-7xl px-4 md:px-6 lg:px-10 pt-10 md:pt-16 grid lg:grid-cols-2 gap-12 items-center">
    <div class="reveal">
      <x-pill tone="brand-soft" :uppercase="true" class="!bg-white border border-brand-100">{!! icon('Sparkles', 'w-3 h-3') !!} The Soul of Balinese Travel</x-pill>
      <h1 class="font-display font-bold text-ink text-4xl md:text-6xl leading-[1.02] tracking-tight mt-6">Crafting Unforgettable Balinese Journeys with <span class="font-serif italic font-medium text-brand">Heart &amp; Heritage</span></h1>
      <p class="text-sand mt-6 text-[17px] leading-relaxed max-w-xl">Founded on authentic hospitality and deep reverence for the Island of the Gods, Bali Vision Tour blends curated luxury with grassroots Balinese warmth under the care of licensed, insured local professionals.</p>
      <div class="flex flex-wrap gap-3 mt-8"><a href="#story" class="btn-brand !bg-brand-800 hover:!bg-brand-700">Read Our Story {!! icon('ArrowDown') !!}</a><a href="{{ wa_url('Halo Bali Vision Tour! Saya ingin berbicara dengan concierge.') }}" target="_blank" rel="noopener" class="btn-outline">{!! icon('MessageCircle') !!} Chat with Concierge</a></div>
    </div>
    <div class="grid grid-cols-2 gap-4">
      @foreach([[img('lempuyang2'), 'Sacred Heritage', 'h-60 md:h-72'], [img('suv2'), 'Chauffeured Comfort', 'h-44 md:h-52 mt-6'], [img('staff2'), 'Native Hospitality', 'h-44 md:h-52'], [img('ubud'), 'Untouched Vistas', 'h-60 md:h-72 -mt-10']] as [$src, $l, $h])<div class="relative rounded-2xl overflow-hidden img-zoom shadow-card {{ $h }}"><img src="{{ $src }}" alt="{{ $l }}" class="w-full h-full object-cover"><div class="absolute inset-0 bg-gradient-to-t from-forest/70 to-transparent"></div><div class="absolute bottom-3 left-3 text-white text-[10px] uppercase tracking-[0.16em] font-bold">{{ $l }}</div></div>@endforeach
    </div>
  </section>

  <section class="mx-auto max-w-7xl px-4 md:px-6 lg:px-10 py-16"><div class="grid grid-cols-2 lg:grid-cols-4 gap-8 border-y border-ink/8 py-10">
    @foreach($A['stats'] as $s)<div><div class="font-display font-bold text-4xl md:text-5xl {{ ($s['tone'] ?? '') === 'forest' ? 'text-forest' : 'text-brand-700' }}">{{ $s['value'] }}@if(!empty($s['suffix']))<span class="text-lg text-sand">{{ $s['suffix'] }}</span> {!! icon('Star', 'inline w-5 h-5 ml-1 fill-gold text-gold') !!}@endif</div><div class="font-semibold text-ink mt-2">{{ $s['label'] }}</div><div class="text-xs text-sand">{{ $s['sub'] }}</div></div>@endforeach
  </div></section>

  <section id="story" class="mx-auto max-w-7xl px-4 md:px-6 lg:px-10 pb-20 grid lg:grid-cols-12 gap-12 items-center">
    <div class="lg:col-span-5 relative"><div class="rounded-3xl overflow-hidden h-[440px] img-zoom shadow-card"><img src="{{ img('group') }}" alt="Guide with travelers" class="w-full h-full object-cover"></div><div class="absolute -bottom-6 left-6 right-6 bg-white/95 backdrop-blur rounded-2xl p-5 shadow-card" data-testid="about-story-card"><div class="eyebrow">Since 2014</div><div class="font-display font-bold text-ink text-lg mt-1">Bali Vision Tour</div><div class="text-xs text-sand mt-0.5">Licensed &amp; insured tour operator based in Denpasar, Bali</div></div></div>
    <div class="lg:col-span-7 lg:pl-6">
      <div class="eyebrow flex items-center gap-3"><span class="w-8 h-px bg-brand"></span> Our Humble Roots</div>
      <h2 class="font-display font-bold text-ink text-4xl md:text-5xl leading-[1.05] tracking-tight mt-4">Born in Denpasar, Rooted Across the Archipelago</h2>
      <div class="space-y-4 mt-6 text-ink/75 leading-relaxed text-[15px]">
        <p>Bali Vision Tour emerged from a profound conviction: travel across Bali should never feel transactional. In 2014, our founder, Wayan Sudiarta, began escorting small groups of curious visitors across Mount Batur and Bedugul with a single well-maintained MPV and a genuine desire to unveil the island's mystical sanctity beyond tourist corridors.</p>
        <p>As <b class="text-ink">Bali Vision Tour</b>, we grew into a licensed premier destination management company. What remains unaltered is our philosophy of <i class="font-serif text-brand">Tri Hita Karana</i>—the sacred Balinese principle harmonizing human connection, pristine nature, and spiritual heritage.</p>
        <p>Today, with our own fleet of pristine luxury MPVs, private speedboats, and an elite network of certified native Balinese storytellers, we deliver bespoke, private day tours, airport VIP transfers, and curated retreats with uncompromised integrity.</p>
      </div>
      <div class="mt-8 flex items-center gap-4 bg-white border border-sage rounded-2xl p-5 shadow-soft"><span class="w-11 h-11 rounded-full bg-sage text-sage-700 flex items-center justify-center shrink-0">{!! icon('BadgeCheck', 'w-5 h-5') !!}</span><div><div class="font-semibold text-ink">Registered Tourism Operator in Bali</div><div class="text-xs text-sand">Fully insured vehicles, licensed local guides, transparent billing with zero tourist surcharges.</div></div></div>
    </div>
  </section>

  <section class="bg-cream-100 py-20"><div class="mx-auto max-w-7xl px-4 md:px-6 lg:px-10">
    <x-section-heading align="center" eyebrow="Our Operating Creed" title="The Four Pillars of Bali Vision Tour" desc="Every itinerary, chauffeur assignment, and bespoke itinerary is guided by our four non-negotiable promises." />
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5 mt-12">@foreach($A['pillars'] as $p)<div class="bg-white rounded-2xl p-6 shadow-soft lift h-full flex flex-col"><span class="w-12 h-12 rounded-xl flex items-center justify-center {{ tone_class($p['tone'] ?? 'brand') }}">{!! icon($p['icon'], 'w-5 h-5') !!}</span><h3 class="font-display font-bold text-ink text-xl mt-5">{{ $p['title'] }}</h3><p class="text-sand text-sm mt-3 leading-relaxed flex-1">{{ $p['desc'] }}</p><div class="mt-5 pt-4 border-t border-ink/8 text-xs font-bold text-brand inline-flex items-center gap-1">{{ $p['link'] }} {!! icon('ArrowRight', 'w-3.5 h-3.5') !!}</div></div>@endforeach</div>
  </div></section>

  <section class="mx-auto max-w-7xl px-4 md:px-6 lg:px-10 py-20">
    <div class="grid md:grid-cols-2 gap-6 items-end"><x-section-heading eyebrow="The Stewards of Your Journey" title="Meet Our Leadership & Concierge Team" /><p class="text-sand md:text-right max-w-md md:justify-self-end">Over 40 certified Balinese professionals, mechanics, dispatchers, and guides united by warm family values.</p></div>
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5 mt-12">@foreach($A['team'] as $m)<div class="bg-white rounded-2xl overflow-hidden shadow-soft lift" data-testid="team-card"><div class="relative h-64 img-zoom overflow-hidden"><img src="{{ $m['image'] }}" alt="{{ $m['name'] }}" class="w-full h-full object-cover" loading="lazy"><div class="absolute bottom-3 left-3"><x-pill tone="dark" :uppercase="true">{{ $m['tag'] }}</x-pill></div></div><div class="p-5"><div class="font-display font-bold text-ink text-lg">{{ $m['name'] }}</div><div class="text-xs font-semibold text-brand">{{ $m['role'] }}</div><p class="text-xs text-sand mt-3 leading-relaxed">{{ $m['desc'] }}</p></div></div>@endforeach</div>
  </section>

  <section class="sunset-band grain"><div class="relative z-10 mx-auto max-w-7xl px-4 md:px-6 lg:px-10 py-20 grid lg:grid-cols-2 gap-12 items-center text-white">
    <div><div class="text-[10px] uppercase tracking-[0.16em] font-bold text-gold-100">Sustainable &amp; Ethical Stewardship</div><h2 class="font-display font-bold text-4xl md:text-5xl leading-[1.05] mt-3">Protecting the Sacred Island We Call Home</h2><p class="text-white/85 mt-5 leading-relaxed">As native custodians of Bali, Bali Vision Tour commits 5% of annual proceeds to direct community eco-funds, artisan guilds, and temple preservation trusts across Bali's less traveled rural regencies.</p>
      <div class="space-y-3 mt-8">@foreach($A['sustainability'] as $s)<div class="flex gap-4 rounded-2xl border border-white/25 bg-white/10 p-4">{!! icon($s['icon'], 'w-5 h-5 text-gold shrink-0 mt-0.5') !!}<div><div class="font-semibold">{{ $s['title'] }}</div><div class="text-xs text-white/80 mt-1">{{ $s['desc'] }}</div></div></div>@endforeach</div></div>
    <div class="relative rounded-3xl overflow-hidden h-[420px] shadow-card img-zoom"><img src="{{ img('riceMist') }}" alt="Rice terraces" class="w-full h-full object-cover"><div class="absolute inset-0 bg-gradient-to-t from-forest/80 to-transparent"></div><div class="absolute bottom-6 left-6 right-6"><div class="text-[10px] uppercase tracking-[0.16em] font-bold text-gold">The Subak Tradition</div><div class="font-display font-bold text-2xl mt-1">UNESCO World Heritage Cultural Landscape</div><p class="text-xs text-white/80 mt-1">We educate every guest on Bali's ancient cooperative water management systems dating back to the 9th century.</p></div></div>
  </div></section>

  <section class="mx-auto max-w-7xl px-4 md:px-6 lg:px-10 py-20">
    <x-section-heading align="center" eyebrow="Guest Reflections" title="Voices of Our Travelers" desc="Stories shared by couples, families, and solo adventurers who explored Bali with us." />
    <div class="grid md:grid-cols-3 gap-5 mt-12">@foreach($A['voices'] as $v)<div class="bg-white rounded-3xl p-7 shadow-soft lift h-full flex flex-col"><x-stars :value="5" class="w-4 h-4" /><p class="font-serif italic text-ink/85 leading-relaxed mt-4 flex-1 text-[15px]">"{{ $v['text'] }}"</p><div class="flex items-center gap-3 mt-6 pt-5 border-t border-ink/8"><span class="w-10 h-10 rounded-full bg-brand-50 text-brand font-bold text-xs flex items-center justify-center">{{ $v['initials'] }}</span><div><div class="font-semibold text-ink text-sm">{{ $v['name'] }}</div><div class="text-xs text-sand">{{ $v['meta'] }}</div></div></div></div>@endforeach</div>
  </section>
  <x-newsletter variant="card" eyebrow="Curated Invitations" title="Make Moments That Last Across Bali" desc="Receive secret luxury villa recommendations, off-the-beaten-path cultural itineraries, and private chauffeur seasonal privileges." cta="Subscribe Free" />
</div>
@endsection
