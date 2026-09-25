@extends('layouts.site')
@section('title', 'Articles — ' . site('brand.name'))
@section('content')
<div data-testid="articles-page">
  <x-page-hero :image="img('bedugul')" eyebrow="Curated Local Insights" title="The Bali Travel Journal: Insider Guides & Curated Stories" desc="Handcrafted itineraries, cultural etiquette, hidden culinary spots, and expert advice from native Balinese locals to inspire mindful island exploration.">
    <form method="get" action="{{ route('articles') }}" class="mt-10 bg-white rounded-3xl p-5 md:p-6 shadow-card reveal">
      <input type="hidden" name="cat" value="{{ $cat }}">
      <div class="flex items-center gap-3 rounded-2xl bg-cream border border-ink/10 px-4 py-2">{!! icon('Search', 'w-4 h-4 text-ink/50') !!}<input name="q" value="{{ $q }}" placeholder="Search articles, destinations, or tips (e.g., Mount Batur sunrise, Jimbaran seafood, Nyepi rules)..." class="flex-1 min-w-0 bg-transparent py-2 text-sm outline-none" data-testid="article-search"><button type="submit" class="btn-forest !py-2 !px-4 !text-xs">Find Guides</button></div>
      <div class="flex flex-wrap items-center gap-2 mt-4"><span class="text-[11px] uppercase tracking-[0.14em] font-bold text-ink/60 mr-1">Filter by:</span>@foreach($cats as $c)<a href="{{ route('articles', array_filter(['cat' => $c === 'All' ? null : $c, 'q' => $q ?: null])) }}" class="rounded-full px-4 py-2 text-xs font-semibold transition-colors {{ $cat === $c ? 'bg-forest text-white' : 'bg-cream-100 text-ink/80 hover:bg-cream-200' }}" data-testid="article-cat-{{ Str::slug($c) }}">{{ $c === 'All' ? "All Articles ({$all->count()})" : $c }}</a>@endforeach</div>
    </form>
  </x-page-hero>

  @if($showFeatured)
  <section class="mx-auto max-w-7xl px-4 md:px-6 lg:px-10 py-12">
    <div class="flex items-end justify-between"><x-section-heading eyebrow="Cover Editorial" title="Featured Guide of the Month" /><span class="text-xs text-sand hidden md:block">Updated for {{ date('Y') }} Explorers</span></div>
    <div class="mt-8 bg-white rounded-3xl overflow-hidden shadow-card grid lg:grid-cols-12" data-testid="featured-article">
      <a href="{{ route('articles.show', $featured->slug) }}" class="lg:col-span-7 relative h-80 lg:h-auto min-h-[420px] img-zoom overflow-hidden block"><img src="{{ $featured->image }}" alt="{{ $featured->title }}" class="absolute inset-0 w-full h-full object-cover"><div class="absolute inset-0 bg-gradient-to-t from-ink/60 via-transparent to-transparent"></div><div class="absolute top-5 left-5 flex gap-2"><x-pill tone="brand" :uppercase="true">Featured Expedition</x-pill><x-pill tone="forest">{{ $featured->category }}</x-pill></div><div class="absolute bottom-5 left-5 flex gap-4 text-white text-xs"><span class="flex items-center gap-1">{!! icon('MapPin', 'w-3.5 h-3.5') !!} {{ $featured->location }}</span><span class="flex items-center gap-1">{!! icon('Camera', 'w-3.5 h-3.5') !!} Original Photo Journal</span></div></a>
      <div class="lg:col-span-5 p-8 md:p-10 flex flex-col">
        <div class="flex flex-wrap items-center gap-3 text-xs text-sand"><span class="text-sage-700 font-semibold">{{ $featured->category }}</span><span class="flex items-center gap-1">{!! icon('Timer', 'w-3.5 h-3.5') !!} {{ $featured->read_time }}</span><span>{{ fmt_date($featured->date) }}</span></div>
        <h3 class="font-display font-bold text-ink text-2xl md:text-3xl leading-tight mt-3">{{ $featured->title }}</h3>
        <p class="text-sand text-sm leading-relaxed mt-4 flex-1">{{ $featured->excerpt }}</p>
        <ul class="mt-5 space-y-2 border-t border-ink/8 pt-5">@foreach($featured->bullets ?? [] as $b)<li class="flex items-start gap-2 text-sm text-ink/80">{!! icon('CircleCheck', 'w-4 h-4 text-sage-700 shrink-0 mt-0.5') !!} {{ $b }}</li>@endforeach</ul>
        <div class="flex items-center justify-between mt-6 pt-5 border-t border-ink/8"><div class="flex items-center gap-3"><img src="{{ $featured->author['avatar'] ?? '' }}" alt="" class="w-10 h-10 rounded-full object-cover bg-cream-200"><div><div class="font-semibold text-sm text-ink">{{ $featured->author['name'] ?? '' }}</div><div class="text-xs text-sand">{{ $featured->author['role'] ?? '' }}</div></div></div><a href="{{ route('articles.show', $featured->slug) }}" class="text-sm font-bold text-brand inline-flex items-center gap-1.5 hover:gap-2.5 transition-all" data-testid="read-featured">Read Full Story {!! icon('ArrowRight') !!}</a></div>
      </div>
    </div>
  </section>
  @endif

  <section class="mx-auto max-w-7xl px-4 md:px-6 lg:px-10 pb-16 grid lg:grid-cols-3 gap-8 items-start">
    <div class="lg:col-span-2">
      <div class="flex items-end justify-between"><x-section-heading eyebrow="Field Notes & Dispatch" title="Latest Journal Dispatches" title-class="!text-3xl" /><span class="text-xs text-sand">Showing {{ $items->count() }} Handcrafted Guides</span></div>
      @if($items->isEmpty())<div class="mt-8 bg-white rounded-3xl p-12 text-center shadow-soft" data-testid="article-empty-state"><div class="font-display text-xl font-bold">No articles found</div><p class="text-sand text-sm mt-2">Try a different keyword or category.</p><a href="{{ route('articles') }}" class="btn-outline mt-5" data-testid="article-reset-filters">Reset Filters</a></div>
      @else<div class="grid sm:grid-cols-2 gap-6 mt-8">@foreach($items as $a)<x-article-card :article="$a" />@endforeach</div>@endif
      @if($pages > 1)<div class="flex items-center justify-center gap-2 mt-10" data-testid="pagination">@for($i = 1; $i <= $pages; $i++)<a href="{{ route('articles', array_filter(['cat' => $cat === 'All' ? null : $cat, 'q' => $q ?: null, 'page' => $i])) }}" class="w-9 h-9 rounded-full text-sm font-semibold flex items-center justify-center {{ $page === $i ? 'bg-brand-800 text-white' : 'bg-cream-100 hover:bg-cream-200' }}">{{ $i }}</a>@endfor</div>@endif
    </div>
    <div class="space-y-5 lg:sticky lg:top-24">
      <div class="rounded-3xl bg-gradient-to-br from-forest to-forest-700 text-white p-7 shadow-card grain relative overflow-hidden"><div class="relative z-10"><x-pill tone="gold" :uppercase="true">Bali Vision Tour Advantage</x-pill><h3 class="font-display font-bold text-2xl mt-4">Turn Any Article Into Your Private Itinerary</h3><p class="text-white/80 text-sm mt-3">Found a hidden waterfall or temple in our guides? Our licensed English-speaking chauffeurs will craft a bespoke daily route with VIP transport.</p><ul class="mt-5 space-y-2 text-sm text-white/90">@foreach(['10-Hour Private Chauffeur & Fuel Included', 'Flexible Stops & Custom Start Times', 'Complimentary Cold Spring Water & Towels'] as $t)<li class="flex items-center gap-2">{!! icon('CircleCheck', 'w-4 h-4 text-gold') !!} {{ $t }}</li>@endforeach</ul><a href="{{ wa_url('Halo, saya ingin membuat itinerary privat berdasarkan artikel di website Anda.') }}" target="_blank" rel="noopener" class="btn-brand w-full mt-6 !rounded-xl" data-testid="article-wa-cta">{!! icon('MessageCircle') !!} Chat on WhatsApp</a></div></div>
      <div class="bg-white rounded-3xl p-6 shadow-soft"><div class="eyebrow flex items-center gap-2">{!! icon('Info', 'w-3.5 h-3.5') !!} Essential Bali Intelligence</div><h3 class="font-display font-bold text-ink text-xl mt-1">Traveler Fast Facts &amp; FAQ</h3><div class="space-y-3 mt-5">@foreach(blocks('faqFacts') as $f)<div class="rounded-2xl bg-cream p-4"><div class="flex items-start justify-between gap-3"><div class="font-semibold text-ink text-sm">{{ $f['title'] }}</div>{!! icon($f['icon'], 'w-4 h-4 text-brand shrink-0') !!}</div><p class="text-xs text-sand mt-2 leading-relaxed">{{ $f['desc'] }}</p></div>@endforeach</div></div>
      <div class="bg-white rounded-3xl p-6 shadow-soft"><h3 class="font-display font-bold text-ink text-lg">Trending Journal Topics</h3><div class="flex flex-wrap gap-2 mt-4">@foreach(blocks('trendingTags') as $t)<a href="{{ route('articles', ['q' => explode(' ', trim(preg_replace('/([A-Z])/', ' $1', ltrim($t, '#'))))[0]]) }}" class="rounded-lg bg-cream-100 hover:bg-brand-50 hover:text-brand px-3 py-1.5 text-xs font-medium transition-colors">{{ $t }}</a>@endforeach</div></div>
    </div>
  </section>
  <x-newsletter variant="card" eyebrow="Private Concierge Bulletin" title="Make Moments That Last Across Bali" desc="Receive secret luxury villa recommendations, off-the-beaten-path cultural itineraries, and private chauffeur seasonal privileges delivered once a fortnight. No spam—only pure island magic." cta="Subscribe Free" />
</div>
@endsection
