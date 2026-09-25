@extends('layouts.site')
@section('title', $tour->title . ' — ' . site('brand.name'))
@section('content')
@php($gallery = $tour->gallery ?? [])
@php($discount = $tour->original_price ? round((1 - $tour->price / $tour->original_price) * 100) : 0)
<div data-testid="tour-detail-page" x-data="{ adults: 2, children: 0, addons: [], date: '', family: @js($tour->price_unit === 'Family'), price: {{ (int) $tour->price }}, addonList: @js($tour->addons ?? []),
  base() { return this.family ? this.price : this.price * this.adults + this.price * 0.5 * this.children; },
  extra() { return this.addonList.filter(a => this.addons.includes(a.title)).reduce((s, a) => s + Number(a.price || 0), 0); },
  total() { return this.base() + this.extra(); },
  book() { openBooking({ type: 'Tour Package', item_id: '{{ $tour->id }}', item_name: @js($tour->title), option: this.addons.length ? 'Add-ons: ' + this.addons.join(', ') : null, unit_price: this.family ? this.total() : this.price, unit_label: @js($tour->price_unit), pax: this.adults + this.children, date: this.date }); } }">
  <div class="mx-auto max-w-7xl px-4 md:px-6 lg:px-10 pt-8">
    <div class="flex flex-wrap items-center justify-between gap-3">
      <div class="flex flex-wrap items-center gap-2">
        @if($tour->badge)<x-pill tone="brand" :uppercase="true">{!! icon('Flame', 'w-3 h-3') !!} {{ $tour->badge }}</x-pill>@endif
        <x-pill tone="forest" :uppercase="true">{!! icon('MapPin', 'w-3 h-3') !!} {{ $tour->category }}</x-pill>
        <x-pill tone="sand" :uppercase="true">{!! icon('Clock', 'w-3 h-3') !!} {{ $tour->duration }}</x-pill>
      </div>
      <div class="inline-flex items-center gap-1.5 rounded-full bg-white px-3 py-1.5 text-sm shadow-soft">{!! icon('Star', 'w-4 h-4 fill-gold text-gold') !!} <b>{{ number_format($tour->rating, 1) }}</b> <span class="text-sand">({{ $tour->reviews }}+ reviews)</span></div>
    </div>
    <h1 class="font-display font-bold text-ink text-3xl md:text-5xl leading-[1.1] tracking-tight mt-5 max-w-4xl">{{ $tour->title }}{{ $tour->subtitle ? ': ' . $tour->subtitle : '' }}</h1>
    <p class="text-sand text-base md:text-lg mt-4 max-w-3xl leading-relaxed">{{ $tour->description }}</p>
    <div class="grid lg:grid-cols-2 gap-4 mt-8" data-testid="tour-gallery">
      <div class="relative rounded-3xl overflow-hidden h-72 lg:h-[420px] img-zoom shadow-soft"><img src="{{ $gallery[0]['src'] ?? $tour->image }}" alt="{{ $tour->title }}" class="w-full h-full object-cover"><div class="absolute inset-0 bg-gradient-to-t from-ink/60 via-transparent to-transparent"></div><div class="absolute bottom-5 left-5 text-white"><x-pill tone="glass">Signature Highlight</x-pill><div class="font-display font-bold text-xl mt-2">{{ $gallery[0]['label'] ?? '' }}</div></div></div>
      <div class="grid grid-cols-2 gap-4">@foreach(array_slice($gallery, 1, 4) as $g)<div class="relative rounded-2xl overflow-hidden h-36 lg:h-[202px] img-zoom shadow-soft"><img src="{{ $g['src'] }}" alt="{{ $g['label'] ?? '' }}" class="w-full h-full object-cover" loading="lazy"><div class="absolute bottom-3 left-3"><x-pill tone="dark">{{ $g['label'] ?? '' }}</x-pill></div></div>@endforeach</div>
    </div>
  </div>

  <div class="mx-auto max-w-7xl px-4 md:px-6 lg:px-10 py-12 grid lg:grid-cols-3 gap-8 items-start">
    <div class="lg:col-span-2 space-y-8">
      <div class="bg-white rounded-3xl p-6 md:p-8 shadow-soft">
        <h2 class="font-display font-bold text-2xl text-ink flex items-center gap-2">{!! icon('Sparkles', 'w-5 h-5 text-brand') !!} Curated Journey Highlights</h2>
        <div class="grid sm:grid-cols-2 gap-4 mt-6">@foreach($tour->features ?? [] as $f)<div class="flex gap-3 bg-cream rounded-2xl p-4"><span class="w-10 h-10 rounded-xl bg-brand-50 text-brand flex items-center justify-center shrink-0">{!! icon($f['icon'] ?? 'Check', 'w-5 h-5') !!}</span><div><div class="font-semibold text-ink text-sm">{{ $f['title'] ?? '' }}</div><div class="text-xs text-sand mt-0.5 leading-relaxed">{{ $f['desc'] ?? '' }}</div></div></div>@endforeach</div>
      </div>
      <div><div class="eyebrow flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-brand"></span> Experience the Extraordinary</div><h2 class="font-display font-bold text-3xl text-ink mt-3">Unveiling {{ $tour->region }}</h2><div class="rich-content text-ink/75 leading-relaxed mt-4 text-[15px]" data-testid="tour-long-description">{!! rich_html($tour->long_description) !!}</div></div>

      <div x-data="{ open: 1 }">
        <div class="flex items-center justify-between"><div><div class="eyebrow">Comprehensive Schedule</div><h2 class="font-display font-bold text-3xl text-ink mt-1">Day-by-Day Curated Itinerary</h2></div><x-pill tone="sand">{{ $tour->duration }}</x-pill></div>
        <div class="mt-6 space-y-4" data-testid="itinerary">
          @foreach($tour->itinerary ?? [] as $idx => $d)
          <div class="bg-white rounded-2xl shadow-soft px-6" :class="open === {{ $idx + 1 }} && 'ring-1 ring-brand/20'">
            <button type="button" @click="open = open === {{ $idx + 1 }} ? 0 : {{ $idx + 1 }}" class="w-full flex items-center justify-between py-5 text-left">
              <div class="flex items-center gap-4"><span class="w-10 h-10 rounded-full flex items-center justify-center font-display font-bold text-sm {{ $idx === 0 ? 'bg-brand text-white' : 'bg-cream-100 text-ink' }}">{{ str_pad(preg_replace('/\D/', '', (string) ($d['day'] ?? $idx + 1)) ?: $idx + 1, 2, '0', STR_PAD_LEFT) }}</span><div><div class="text-[10px] uppercase tracking-[0.16em] font-bold text-brand">Day {{ preg_replace('/\D/', '', (string) ($d['day'] ?? $idx + 1)) ?: $idx + 1 }}</div><div class="font-display font-bold text-ink text-lg leading-snug">{{ $d['title'] ?? '' }}</div></div></div>
              {!! icon('ChevronDown', 'w-4 h-4 text-ink/50 transition-transform') !!}
            </button>
            <div x-show="open === {{ $idx + 1 }}" x-collapse class="pb-6">
              <p class="text-ink/75 text-sm leading-relaxed">{{ $d['desc'] ?? '' }}</p>
              <div class="grid sm:grid-cols-2 gap-2 mt-4">@foreach($d['points'] ?? [] as $p)<div class="flex items-start gap-2 text-sm text-ink font-medium">{!! icon('Check', 'w-4 h-4 text-brand shrink-0 mt-0.5') !!} {{ $p }}</div>@endforeach</div>
              @if(!empty($d['meals']))<div class="mt-4 rounded-xl bg-cream px-4 py-3 text-xs text-ink/80 flex items-center gap-2">{!! icon('UtensilsCrossed', 'w-4 h-4 text-brand') !!} {{ $d['meals'] }}</div>@endif
            </div>
          </div>
          @endforeach
        </div>
      </div>

      <div class="bg-white rounded-3xl p-6 md:p-8 shadow-soft">
        <h2 class="font-display font-bold text-2xl text-ink">Package Inclusions &amp; Exclusions</h2>
        <div class="grid sm:grid-cols-2 gap-8 mt-6">
          <div><div class="flex items-center gap-2 font-display font-bold text-ink text-lg pb-3 border-b border-ink/8">{!! icon('Check', 'w-5 h-5 text-sage-700') !!} What Is Included</div><ul class="mt-4 space-y-2.5">@foreach($tour->inclusions ?? [] as $i)<li class="flex items-start gap-2 text-sm text-ink/75">{!! icon('Check', 'w-4 h-4 text-sage-700 shrink-0 mt-0.5') !!} {{ $i }}</li>@endforeach</ul></div>
          <div><div class="flex items-center gap-2 font-display font-bold text-ink text-lg pb-3 border-b border-ink/8">{!! icon('X', 'w-5 h-5 text-brand') !!} What Is Excluded</div><ul class="mt-4 space-y-2.5">@foreach($tour->exclusions ?? [] as $i)<li class="flex items-start gap-2 text-sm text-ink/75">{!! icon('X', 'w-4 h-4 text-brand shrink-0 mt-0.5') !!} {{ $i }}</li>@endforeach</ul></div>
        </div>
      </div>

      @if($tour->tips)<div class="bg-cream-100 rounded-3xl p-6 md:p-8"><h3 class="font-display font-bold text-xl text-ink flex items-center gap-2">{!! icon('Lightbulb', 'w-5 h-5 text-brand') !!} Curator's Island Advice &amp; Tips</h3><div class="grid sm:grid-cols-3 gap-4 mt-5">@foreach($tour->tips as $t)<div class="bg-white rounded-2xl p-4">{!! icon($t['icon'] ?? 'Info', 'w-5 h-5 text-brand') !!}<div class="font-semibold text-ink text-sm mt-3">{{ $t['title'] ?? '' }}</div><div class="text-xs text-sand mt-1 leading-relaxed">{{ $t['desc'] ?? '' }}</div></div>@endforeach</div></div>@endif

      <div>
        <div class="eyebrow">Guest Experiences</div><h2 class="font-display font-bold text-3xl text-ink mt-1">Verified Traveler Reviews</h2>
        <div class="bg-white rounded-3xl p-6 md:p-8 shadow-soft mt-6 flex flex-col sm:flex-row gap-8 items-center">
          <div class="text-center sm:pr-8 sm:border-r border-ink/8"><div class="font-display font-bold text-5xl text-brand-700">{{ number_format($tour->rating, 1) }}</div><div class="flex justify-center"><x-stars :value="5" class="w-4 h-4" /></div><div class="text-xs text-sand mt-1">Based on {{ $tour->reviews }} verified reviews</div></div>
          <div class="flex-1 w-full space-y-3">@foreach([['Service', 5.0], ['Guide & 4x4', 4.9], ['Villa Stay', 4.8]] as [$l, $v])<div class="flex items-center gap-4 text-xs"><span class="w-24 text-ink/70">{{ $l }}</span><div class="flex-1 h-1.5 rounded-full bg-cream-200 overflow-hidden"><div class="h-full bg-brand-700 rounded-full" style="width:{{ $v / 5 * 100 }}%"></div></div><span class="font-semibold w-8 text-right">{{ number_format($v, 1) }}</span></div>@endforeach</div>
        </div>
        <div class="grid sm:grid-cols-2 gap-4 mt-4">@foreach($tour->reviews_list ?? [] as $r)<div class="bg-white rounded-3xl p-6 shadow-soft"><div class="flex items-center justify-between"><div class="flex items-center gap-3"><img src="{{ $r['avatar'] ?? '' }}" alt="" class="w-10 h-10 rounded-full object-cover bg-cream-200"><div><div class="font-semibold text-sm text-ink">{{ $r['name'] ?? '' }}</div><div class="text-xs text-sand">{{ $r['location'] ?? '' }}</div></div></div><x-stars :value="5" /></div><p class="font-serif italic text-ink/80 text-sm leading-relaxed mt-4">"{{ $r['text'] ?? '' }}"</p><div class="text-[11px] text-sage-700 mt-4 flex items-center gap-1">{!! icon('ShieldCheck', 'w-3.5 h-3.5') !!} {{ $r['date'] ?? '' }}</div></div>@endforeach</div>
      </div>
    </div>

    <div class="lg:sticky lg:top-24 space-y-5">
      <div class="bg-white rounded-3xl shadow-card overflow-hidden" data-testid="booking-sidebar">
        <div class="h-1.5 bg-gradient-to-r from-brand-700 via-brand to-gold"></div>
        <div class="p-6">
          <div class="flex items-start justify-between">
            <div><div class="field-label !mb-0">Starting From</div><div class="font-display font-bold text-3xl text-brand-700 mt-1">{{ fmt_idr($tour->price) }} <span class="text-xs text-sand font-body font-normal">/{{ strtolower($tour->price_unit) }}</span></div></div>
            @if($tour->original_price)<div class="text-right"><div class="text-xs text-sand line-through">{{ fmt_idr($tour->original_price) }}</div><x-pill tone="brand" class="mt-1">{{ $discount }}% OFF</x-pill></div>@endif
          </div>
          <div class="mt-6"><label class="field-label">Select Departure Date</label><input type="date" x-model="date" min="{{ date('Y-m-d') }}" class="select-input" data-testid="sidebar-date"></div>
          <div class="grid grid-cols-2 gap-3 mt-4">
            @foreach([['Adults (12+)', 'adults', 1], ['Children (3-11)', 'children', 0]] as [$l, $v, $min])
            <div><label class="field-label">{{ $l }}</label><div class="h-12 rounded-xl bg-cream border border-ink/10 flex items-center justify-between px-2"><button type="button" @click="{{ $v }} = Math.max({{ $min }}, {{ $v }} - 1)" class="w-8 h-8 rounded-lg bg-white text-brand flex items-center justify-center hover:bg-brand-50">−</button><span class="font-semibold" x-text="{{ $v }}"></span><button type="button" @click="{{ $v }} = Math.min(20, {{ $v }} + 1)" class="w-8 h-8 rounded-lg bg-white text-brand flex items-center justify-center hover:bg-brand-50">+</button></div></div>
            @endforeach
          </div>
          @if($tour->addons)<div class="mt-5"><label class="field-label">Optional Luxury Add-ons</label><div class="space-y-2">@foreach($tour->addons as $a)<label class="flex items-center gap-3 rounded-xl border border-ink/10 bg-cream px-3 py-2.5 cursor-pointer hover:border-brand/40 transition-colors"><input type="checkbox" value="{{ $a['title'] }}" x-model="addons" class="accent-brand w-4 h-4"><div class="flex-1"><div class="text-sm font-medium text-ink">{{ $a['title'] }}</div><div class="text-[11px] text-sand">{{ $a['desc'] ?? '' }}</div></div><div class="text-xs font-bold text-brand">+{{ fmt_idr($a['price'] ?? 0) }}</div></label>@endforeach</div></div>@endif
          <div class="mt-5 pt-4 border-t border-ink/8 space-y-1.5 text-sm">
            <div class="flex justify-between text-ink/70"><span>Tour Base (<span x-text="family ? '1 family' : (adults + children) + ' guests'"></span>)</span><span x-text="fmtIDR(base())"></span></div>
            @if($tour->original_price)<div class="flex justify-between text-sage-700 text-xs"><span>Seasonal Early Bird Discount</span><span>-{{ $discount }}% applied</span></div>@endif
            <div class="flex justify-between font-bold text-ink pt-2"><span>Total Estimated</span><span class="text-brand-700 font-display text-lg" x-text="fmtIDR(total())" data-testid="sidebar-total"></span></div>
          </div>
          <button type="button" @click="book()" class="btn-brand w-full !py-4 !rounded-2xl mt-5 !bg-brand-800 hover:!bg-brand-700" data-testid="book-tour-btn">{!! icon('Zap') !!} Book This Tour Now</button>
          <ul class="mt-5 space-y-2 text-xs text-ink/70">@foreach([['ShieldCheck', 'Instant confirmation via WhatsApp concierge'], ['Lock', 'Zero hidden booking charges or port taxes'], ['CalendarClock', 'Free date reschedule up to 48 hours prior']] as [$ic, $t])<li class="flex items-center gap-2">{!! icon($ic, 'w-3.5 h-3.5 text-sage-700') !!} {{ $t }}</li>@endforeach</ul>
        </div>
      </div>
      <div class="bg-cream-100 rounded-3xl p-6 text-center">{!! icon('ShieldCheck', 'w-7 h-7 text-brand-700 mx-auto') !!}<div class="font-display font-bold text-ink text-lg mt-2">100% Satisfaction Guarantee</div><p class="text-xs text-sand mt-1 leading-relaxed">If weather conditions make fast boat crossing unsafe, your trip is fully rescheduled or refunded without penalties.</p></div>
    </div>
  </div>
  <x-newsletter variant="card" eyebrow="Make Moments That Last" title="Receive Secret Bali Guides & VIP Perks" desc="Join over 15,000 mindful travelers receiving our curated monthly private villa discounts and secret destination updates." />
</div>
@endsection
