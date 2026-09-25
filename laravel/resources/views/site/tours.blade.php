@extends('layouts.site')
@section('title', 'Tour Packages — ' . site('brand.name'))
@section('content')
<div data-testid="tour-packages-page">
  <x-page-hero :image="img('bedugul')" eyebrow="Sun-Drenched Tropical Luxe" eyebrow-icon="Sun" title="Curated Tour Packages" title-accent="in Bali" desc="Immerse yourself in authentic Balinese culture, sacred temples, stunning waterfalls, and island escapades with dedicated private guides and comfortable chauffeur-driven fleets.">
    <form method="get" action="{{ route('tours') }}" id="filters" class="mt-10 bg-white rounded-3xl p-6 md:p-7 shadow-card reveal" x-data="{ category: @js($category) }">
      <input type="hidden" name="category" :value="category">
      <div class="flex flex-wrap items-center gap-2" data-testid="tour-filter-chips">
        <span class="text-[11px] uppercase tracking-[0.14em] font-bold text-ink/60 mr-2">Filter by:</span>
        @foreach(array_merge(['All'], config('site.tour_categories')) as $c)
          <button type="button" @click="category = @js($c); $el.form.requestSubmit()" :class="category === @js($c) ? 'bg-forest text-white' : 'bg-cream-100 text-ink/80 hover:bg-cream-200'" class="rounded-full px-4 py-2 text-xs font-semibold transition-colors duration-200" data-testid="chip-{{ Str::slug($c) }}">{{ $c === 'All' ? "All Packages ({$all->count()})" : $c }}</button>
        @endforeach
      </div>
      <div class="grid md:grid-cols-4 gap-4 mt-6 items-end">
        @foreach([['MapPin','Destination Region','destination', array_merge(['All Bali Destinations'], array_column(blocks('destinations'), 'name')), $destination], ['CalendarDays','Duration','duration', ['Any Length','1 Day','2-3 Days','4+ Days'], $duration], ['Wallet','Budget Tier (IDR)','budget', ['All Price Ranges','Under Rp 1,000,000','Rp 1,000,000 - 3,000,000','Above Rp 3,000,000'], $budget]] as [$ic,$label,$name,$opts,$val])
        <div><label class="field-label">{{ $label }}</label><div class="relative">{!! icon($ic, 'w-4 h-4 text-brand absolute left-4 top-4 pointer-events-none') !!}<select name="{{ $name }}" class="select-input !pl-10" data-testid="tour-select-{{ $name }}">@foreach($opts as $o)<option @selected($o === $val)>{{ $o }}</option>@endforeach</select></div></div>
        @endforeach
        <button type="submit" class="btn-brand h-12 !rounded-xl" data-testid="apply-filters">{!! icon('SlidersHorizontal') !!} Apply Filters</button>
      </div>
    </form>
  </x-page-hero>

  <section class="bg-cream-100 border-y border-ink/5"><div class="mx-auto max-w-7xl px-6 lg:px-10 py-8 grid grid-cols-2 md:grid-cols-5 gap-6 md:divide-x divide-ink/8">
    @foreach(blocks('tourPerks') as $p)<div class="text-center px-2"><span class="w-11 h-11 rounded-full bg-sage text-sage-700 inline-flex items-center justify-center">{!! icon($p['icon'], 'w-5 h-5') !!}</span><div class="font-semibold text-ink text-sm mt-3">{{ $p['title'] }}</div><div class="text-xs text-sand">{{ $p['sub'] }}</div></div>@endforeach
  </div></section>

  <section id="packages" class="mx-auto max-w-7xl px-4 md:px-6 lg:px-10 py-16">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-3"><x-section-heading eyebrow="Handpicked Itineraries" title="Featured Travel Packages" /><div class="text-sm text-sand" data-testid="tour-count">Showing {{ $filtered->count() }} of {{ $filtered->count() }} exclusive island experiences</div></div>
    @if($filtered->isEmpty())
      <div data-testid="tour-empty-state" class="mt-12 text-center bg-white rounded-3xl p-14 shadow-soft"><div class="font-display text-2xl text-ink font-bold">No packages match your filters</div><p class="text-sand mt-2">Try broadening your destination or duration.</p><a href="{{ route('tours') }}" class="btn-outline mt-6" data-testid="tour-reset-filters">Reset Filters</a></div>
    @else
      <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-10">@foreach($filtered as $t)<x-tour-card :tour="$t" />@endforeach</div>
    @endif
  </section>
  <x-newsletter eyebrow="Insider Bali Dispatch" desc="Join 12,000+ discerning travelers receiving curated hidden temple routes, seasonal secret waterfalls, and VIP rate privileges." />
</div>
@endsection
