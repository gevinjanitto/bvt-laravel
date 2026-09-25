@extends('layouts.site')
@section('title', site('brand.name') . ' — Authentic & Bespoke Bali Escapes')
@section('content')
<div data-testid="home-page">
  <section class="mx-auto max-w-7xl px-4 md:px-6 lg:px-10 pt-4 md:pt-6" data-testid="hero">
    <div class="relative rounded-[28px] md:rounded-[36px] overflow-hidden bg-forest text-white shadow-card grain min-h-[560px] md:min-h-[600px] reveal">
      <img src="{{ img('hero') }}" alt="Bali temple gate and volcano" class="absolute inset-0 w-full h-full object-cover object-center">
      <div class="absolute inset-0 bg-gradient-to-r from-forest via-forest/85 to-forest/20 md:to-transparent"></div>
      <div class="absolute inset-0 bg-gradient-to-t from-forest via-transparent to-transparent"></div>
      <div class="relative z-10 p-6 md:p-12 lg:p-14 flex flex-col justify-between min-h-[560px] md:min-h-[600px]">
        <div class="max-w-2xl">
          <div class="inline-flex items-center gap-2 rounded-full border border-white/25 bg-white/10 backdrop-blur px-3.5 py-1.5 text-[10px] font-bold tracking-[0.18em] uppercase"><span class="w-1.5 h-1.5 rounded-full bg-brand pulse-dot"></span> Authentic &amp; Bespoke Bali Escapes</div>
          <h1 class="font-display font-bold text-4xl md:text-6xl lg:text-[4.2rem] leading-[1.02] tracking-tight mt-6" data-testid="hero-title">Discover Bali's Soul,<br><span class="font-serif italic font-medium text-brand">Craft Your Timeless</span><br><span class="font-serif italic font-medium text-brand">Journey.</span></h1>
          <p class="mt-6 text-white/80 text-base md:text-lg max-w-lg leading-relaxed">Handpicked private tours, thrilling island adventures, and premier transportation crafted for an unforgettable luxury holiday in the Island of the Gods.</p>
          <div class="flex flex-wrap items-center gap-3 mt-8">
            <a href="{{ route('tours') }}" class="btn-brand" data-testid="hero-explore">Explore Tour Packages {!! icon('ArrowRight') !!}</a>
            <a href="{{ wa_url('Halo Bali Vision Tour! Saya ingin konsultasi rencana liburan di Bali.') }}" target="_blank" rel="noopener" class="btn-ghost-light" data-testid="hero-chat">{!! icon('MessageCircle') !!} Chat Concierge</a>
          </div>
        </div>
        <form action="{{ route('tours') }}" method="get" class="mt-10 bg-white rounded-3xl p-3 shadow-card flex flex-col lg:flex-row gap-3" data-testid="hero-search">
          @foreach([['Tag','Holiday Type','category', array_merge(['All Categories'], config('site.tour_categories'))], ['Compass','Destination','destination', array_merge(['All Bali Destinations'], array_column(blocks('destinations'), 'name'))], ['Clock','Duration','duration', ['Any Length','1 Day','2-3 Days','4+ Days']]] as [$ic,$label,$name,$opts])
          <div class="flex items-center gap-3 bg-white rounded-2xl border border-ink/8 px-4 py-2.5 flex-1 min-w-0">
            <span class="w-9 h-9 rounded-full bg-brand-50 text-brand flex items-center justify-center shrink-0">{!! icon($ic) !!}</span>
            <div class="flex-1 min-w-0"><div class="text-[9px] uppercase tracking-[0.16em] font-bold text-sand">{{ $label }}</div>
              <select name="{{ $name }}" class="w-full bg-transparent text-sm font-semibold text-ink outline-none" data-testid="hero-select-{{ $name }}">@foreach($opts as $o)<option>{{ $o }}</option>@endforeach</select></div>
          </div>
          @endforeach
          <button type="submit" class="btn-forest lg:w-52 !rounded-2xl !py-4" data-testid="hero-search-btn">{!! icon('Search') !!} Find Experience</button>
        </form>
      </div>
    </div>
  </section>

  <section class="mx-auto max-w-7xl px-4 md:px-6 lg:px-10 mt-6"><div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
    @foreach(blocks('homeStats') as $s)<div class="bg-white rounded-2xl p-5 flex items-center gap-4 shadow-soft lift" data-testid="stat-card"><span class="w-11 h-11 rounded-full flex items-center justify-center shrink-0 {{ ($s['tone'] ?? '') === 'sage' ? 'bg-sage text-sage-700' : 'bg-brand-50 text-brand' }}">{!! icon($s['icon'] ?? 'Star', 'w-5 h-5') !!}</span><div><div class="font-display font-bold text-ink text-lg leading-tight">{{ $s['value'] }}</div><div class="text-xs text-sand">{{ $s['label'] }}</div></div></div>@endforeach
  </div></section>

  @php($mq = blocks('homeMarquee'))
  <section class="mt-16 border-y border-ink/8 py-5 overflow-hidden" aria-hidden="true" data-testid="marquee"><div class="flex w-max marquee-slow">
    @foreach(array_merge($mq, $mq) as $i => $t)<span class="flex items-center gap-6 pr-6 font-display font-semibold text-ink/70 text-lg md:text-2xl whitespace-nowrap"><span class="{{ $i % 2 ? 'font-serif italic font-medium text-brand' : '' }}">{{ $t }}</span><span class="w-1.5 h-1.5 rounded-full bg-gold"></span></span>@endforeach
  </div></section>

  <section class="mx-auto max-w-7xl px-4 md:px-6 lg:px-10 py-20">
    <div class="grid md:grid-cols-2 gap-6 items-end"><x-section-heading eyebrow="Exclusive Signature Categories" title="Curate Your Island Journey" /><p class="text-sand text-[15px] md:text-right md:justify-self-end max-w-md leading-relaxed">Discover our exclusive destinations and bespoke services tailored for ultimate comfort, flexibility, and unforgettable moments.</p></div>
    <div class="grid md:grid-cols-3 gap-6 mt-10">
      @foreach(blocks('homeCategories') as $c)
      <a href="{{ safe_link($c['to']) }}" class="group relative block h-[420px] rounded-3xl overflow-hidden shadow-card img-zoom" data-testid="category-card-{{ $c['index'] }}">
        <img src="{{ $c['image'] }}" alt="{{ $c['title'] }}" class="absolute inset-0 w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-t from-forest via-forest/50 to-transparent"></div>
        <div class="absolute top-5 left-5 right-5 flex items-center justify-between"><x-pill tone="white" :uppercase="true">{{ $c['index'] }} &bull; {{ $c['tag'] }}</x-pill><span class="w-9 h-9 rounded-full bg-white/20 backdrop-blur border border-white/30 text-white flex items-center justify-center group-hover:bg-brand group-hover:border-brand transition-colors duration-300">{!! icon('ArrowUpRight') !!}</span></div>
        <div class="absolute bottom-0 left-0 right-0 p-6 text-white"><h3 class="font-display font-bold text-2xl">{{ $c['title'] }}</h3><p class="text-white/80 text-sm mt-2 leading-relaxed">{{ $c['desc'] }}</p><div class="mt-4 inline-flex items-center gap-1.5 text-[11px] font-bold uppercase tracking-[0.14em] text-gold group-hover:gap-3 transition-all duration-300">{{ $c['cta'] }} {!! icon('ArrowRight', 'w-3.5 h-3.5') !!}</div></div>
      </a>
      @endforeach
    </div>
  </section>

  <section class="bg-cream-100 py-20"><div class="mx-auto max-w-7xl px-4 md:px-6 lg:px-10">
    <x-section-heading align="center" eyebrow="Iconic Spots of Bali" title="Popular Destinations in Bali" desc="Unveil the breathtaking natural splendor and rich cultural sanctuary across Bali's most iconic corners." />
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-12">
      @foreach(blocks('destinations') as $d)
      <a href="{{ route('tours', ['destination' => $d['name']]) }}" class="group relative block h-64 rounded-3xl overflow-hidden shadow-soft img-zoom" data-testid="destination-{{ Str::slug($d['name']) }}">
        <img src="{{ $d['image'] }}" alt="{{ $d['name'] }}" class="absolute inset-0 w-full h-full object-cover" loading="lazy">
        <div class="absolute inset-0 bg-gradient-to-t from-forest/90 via-forest/20 to-transparent"></div>
        <div class="absolute top-4 left-4 right-4 flex items-center justify-between"><x-pill :tone="($d['tagStyle'] ?? '') === 'brand' ? 'brand' : 'glass'" :uppercase="($d['tagStyle'] ?? '') === 'brand'">{{ $d['tag'] }}</x-pill><span class="w-8 h-8 rounded-full bg-white/20 backdrop-blur border border-white/30 text-white flex items-center justify-center group-hover:bg-brand transition-colors duration-300">{!! icon('ArrowUpRight', 'w-3.5 h-3.5') !!}</span></div>
        <div class="absolute bottom-0 left-0 right-0 p-5 text-white"><h3 class="font-display font-bold text-2xl">{{ $d['name'] }}</h3><p class="text-white/75 text-xs mt-1 leading-relaxed">{{ $d['desc'] }}</p></div>
      </a>
      @endforeach
    </div>
  </div></section>

  <section class="mx-auto max-w-7xl px-4 md:px-6 lg:px-10 py-20">
    <x-pill tone="brand-soft" class="!bg-white border border-brand-100">{!! icon('Heart', 'w-3 h-3') !!} Guest Favorite Collection</x-pill>
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mt-4"><x-section-heading title="Bestselling Tour Packages" desc="Curated signature tour packages with meticulously crafted itineraries for your dream escape." /><a href="{{ route('tours') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-brand hover:gap-2.5 transition-all border-b-2 border-brand pb-0.5">View All Packages {!! icon('ArrowRight') !!}</a></div>
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5 mt-10">@foreach($tours as $t)<x-tour-card :tour="$t" :compact="true" />@endforeach</div>
  </section>

  <section class="bg-cream-100 py-20"><div class="mx-auto max-w-7xl px-4 md:px-6 lg:px-10 grid lg:grid-cols-12 gap-10 items-start">
    <div class="lg:col-span-5">
      <x-pill tone="sage" class="!bg-white border border-sage">{!! icon('ShieldCheck', 'w-3 h-3') !!} New Standard of Bali Travel</x-pill>
      <h2 class="font-display font-bold text-forest text-4xl md:text-5xl leading-[1.05] tracking-tight mt-5">Uncompromised Comfort &amp; Peace of Mind</h2>
      <p class="text-sand mt-5 leading-relaxed">Bali Vision Tour is your trusted island partner. From scenic hidden gems to private chauffeurs, every itinerary is designed for a seamless, memorable experience.</p>
      <div class="mt-8 bg-forest text-white rounded-3xl p-6 flex items-start gap-4 shadow-card grain relative overflow-hidden"><span class="w-11 h-11 rounded-full bg-white/10 flex items-center justify-center shrink-0">{!! icon('ShieldCheck', 'w-5 h-5 text-gold') !!}</span><div><div class="font-display font-bold text-lg">Privacy &amp; Absolute Flexibility</div><p class="text-white/75 text-sm mt-1">Immaculate fleet, certified local drivers, and guaranteed itineraries without stress or rushing.</p></div></div>
    </div>
    <div class="lg:col-span-7 grid sm:grid-cols-2 gap-5">
      @foreach(blocks('homeFeatures') as $f)<div class="bg-white rounded-2xl p-6 shadow-soft lift h-full" data-testid="feature-{{ $f['index'] }}"><div class="flex items-center justify-between"><x-pill tone="brand-soft" :uppercase="true">Feature {{ $f['index'] }}</x-pill>{!! icon($f['icon'], 'w-5 h-5 text-brand') !!}</div><h3 class="font-display font-bold text-ink text-lg mt-5">{{ $f['title'] }}</h3><p class="text-sand text-sm mt-2 leading-relaxed">{{ $f['desc'] }}</p></div>@endforeach
    </div>
  </div></section>

  <section class="mx-auto max-w-7xl px-4 md:px-6 lg:px-10 py-20">
    <x-section-heading align="center" eyebrow="Voices of Travelers" title="Why Our Guests Keep Returning" desc="Hear directly from travelers who trusted Bali Vision Tour for their island adventures." title-class="text-forest" />
    <div class="grid md:grid-cols-2 gap-6 mt-12">
      @foreach(blocks('testimonials') as $t)<div class="bg-white rounded-3xl p-7 shadow-soft lift h-full flex flex-col" data-testid="testimonial-card"><x-stars :value="5" class="w-4 h-4" /><p class="font-serif italic text-ink/85 text-[17px] leading-relaxed mt-4 flex-1">"{{ $t['text'] }}"</p><div class="flex items-center gap-3 mt-6 pt-5 border-t border-ink/8"><img src="{{ $t['avatar'] }}" alt="{{ $t['name'] }}" class="w-11 h-11 rounded-full object-cover"><div><div class="font-semibold text-ink text-sm">{{ $t['name'] }}</div><div class="text-xs text-sand">{{ $t['location'] }}</div></div></div></div>@endforeach
    </div>
  </section>

  <x-newsletter variant="card" />
</div>
@endsection
