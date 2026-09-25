@extends('layouts.site')
@section('title', 'Activities — ' . site('brand.name'))
@section('content')
@php($typeIcon = ['All Activities' => 'LayoutGrid', 'Water Sports & Marine' => 'Waves', 'Adventure & Trekking' => 'Mountain', 'Culture & Workshops' => 'Palette', 'Wellness & Spa' => 'Flower2', 'Wildlife & Nature' => 'PawPrint'])
<div data-testid="activities-page">
  <x-page-hero :image="img('bedugul')" eyebrow="Authentic Island Adventures" eyebrow-icon="Flame" title="Thrilling Activities & Cultural Experiences" desc="Dive into the vibrant heart of Bali: adrenaline-fueled river rafting, sunrise volcano treks, sacred wellness rituals, and pristine marine encounters.">
    <div class="flex flex-wrap gap-3 mt-8">@foreach([['Waves', '40+ Curated', 'Activities & Tours', 'sage'], ['ShieldCheck', '100% Insured', 'Certified Instructors', 'brand'], ['MessageCircle', '24/7 Concierge', 'Instant WhatsApp Support', 'brand']] as [$ic, $t, $s, $tone])<div class="flex items-center gap-3 bg-white/90 backdrop-blur rounded-2xl px-4 py-3 shadow-soft"><span class="w-9 h-9 rounded-full flex items-center justify-center {{ tone_class($tone) }}">{!! icon($ic) !!}</span><div><div class="font-semibold text-ink text-sm leading-tight">{{ $t }}</div><div class="text-[11px] text-sand">{{ $s }}</div></div></div>@endforeach</div>
    <div class="mt-8">
      <div class="flex items-center justify-between text-[11px] uppercase tracking-[0.14em] font-bold text-ink/60 mb-3"><span>Filter by Experience Type:</span><span class="normal-case tracking-normal font-normal text-sand">Showing {{ $list->count() }} Top Rated Experiences</span></div>
      <div class="bg-white rounded-full p-2 shadow-card flex gap-1 overflow-x-auto no-scrollbar" data-testid="activity-filters">
        @foreach(array_merge(['All Activities'], config('site.activity_types')) as $t)<a href="{{ route('activities', $t === 'All Activities' ? [] : ['type' => $t]) }}" class="whitespace-nowrap flex items-center gap-1.5 rounded-full px-4 py-2.5 text-xs font-semibold transition-colors {{ $type === $t ? 'bg-forest text-white' : 'text-ink/80 hover:bg-cream-100' }}" data-testid="activity-filter-{{ Str::slug($t) }}">{!! icon($typeIcon[$t] ?? 'Circle', 'w-3.5 h-3.5') !!} {{ $t }}</a>@endforeach
      </div>
    </div>
  </x-page-hero>

  <section class="mx-auto max-w-7xl px-4 md:px-6 lg:px-10 py-16">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-3"><x-section-heading eyebrow="Handpicked Adventures" title="Featured Bali Activities" /><div class="text-xs text-sand flex items-center gap-2">{!! icon('SlidersHorizontal', 'w-4 h-4 text-sage-700') !!} All prices include standard equipment, round-trip transport availability &amp; insurance</div></div>
    @if($list->isEmpty())<div class="mt-10 bg-white rounded-3xl p-14 text-center shadow-soft" data-testid="activity-empty-state"><div class="font-display text-2xl font-bold">No activities in this category yet</div><a href="{{ route('activities') }}" class="btn-outline mt-5" data-testid="activity-reset-filters">Reset Filters</a></div>
    @else<div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-10">@foreach($list as $a)<x-activity-card :activity="$a" />@endforeach</div>@endif
  </section>

  <section class="bg-cream-100 py-20"><div class="mx-auto max-w-7xl px-4 md:px-6 lg:px-10">
    <x-section-heading align="center" eyebrow="The Bali Vision Standard" title="Why Book Activities with Us" desc="We vet every single instructor, river run, and mountain guide in person so you experience Bali at its most magical and safest." />
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5 mt-12">@foreach(blocks('activityWhy') as $w)<div class="bg-white rounded-2xl p-6 text-center shadow-soft lift h-full"><span class="w-12 h-12 rounded-full inline-flex items-center justify-center {{ tone_class($w['tone'] ?? 'brand') }}">{!! icon($w['icon'], 'w-5 h-5') !!}</span><h3 class="font-display font-bold text-ink text-lg mt-4">{{ $w['title'] }}</h3><p class="text-sand text-xs mt-2 leading-relaxed">{{ $w['desc'] }}</p></div>@endforeach</div>
  </div></section>

  <section class="mx-auto max-w-7xl px-4 md:px-6 lg:px-10 py-16"><div class="sunset-band grain rounded-[28px] text-white"><div class="relative z-10 p-8 md:p-14 grid md:grid-cols-3 gap-8 items-center">
    <div class="md:col-span-2"><x-pill tone="glass" :uppercase="true">{!! icon('Users', 'w-3 h-3') !!} Group Gatherings &amp; Corporate Retreats</x-pill><h2 class="font-display font-bold text-3xl md:text-5xl leading-[1.05] mt-5">Planning a company outing, wedding group, or private retreat in Bali?</h2><p class="text-white/85 mt-5 max-w-xl leading-relaxed">We organize seamless group activities and team-building adventures tailored to your schedule. Custom private buses, luxury beachside catering, and dedicated tour coordinators included.</p></div>
    <div class="flex flex-col gap-3 md:items-end"><a href="{{ wa_url('Halo, saya ingin inquiry paket group / corporate retreat di Bali.') }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 rounded-full bg-white text-brand-800 font-bold px-6 py-3.5 text-sm shadow-card hover:bg-cream transition-colors" data-testid="inquire-group">{!! icon('Mail') !!} Inquire Group Package</a><a href="https://wa.me/{{ site('contact.whatsapp') }}" target="_blank" rel="noopener noreferrer" data-testid="activity-contact-whatsapp" class="btn-ghost-light">{!! icon('Phone') !!} WhatsApp +{{ site('contact.whatsapp') }}</a></div>
  </div></div></section>
  <x-newsletter eyebrow="Stay Inspired" desc="Subscribe for secret waterfalls, exclusive cultural event dates, and seasonal Bali adventure privileges." />
</div>
@endsection
