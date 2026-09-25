@extends('layouts.site')
@section('title', $act->title . ' — ' . site('brand.name'))
@section('content')
@php($gallery = $act->gallery ?? [])
@php($slots = $act->slots ?: [['label' => 'Standard Session', 'sub' => 'Hotel pickup included']])
<div data-testid="activity-detail-page" x-data="{ date: '', slot: 0, pax: 2, addons: [], price: {{ (int) $act->price }}, addonList: @js($act->addons ?? []), slots: @js($slots),
  total() { return this.price * this.pax + this.addonList.filter(a => this.addons.includes(a.title)).reduce((s, a) => s + Number(a.price || 0) * this.pax, 0); },
  book() { openBooking({ type: 'Activity', item_id: '{{ $act->id }}', item_name: @js($act->title), option: (this.slots[this.slot]?.label || 'Standard') + (this.addons.length ? ' • Add-ons: ' + this.addons.join(', ') : ''), unit_price: this.total() / this.pax, unit_label: 'Person', pax: this.pax, date: this.date }); } }">
  <div class="mx-auto max-w-7xl px-4 md:px-6 lg:px-10 pt-8">
    <div class="flex flex-wrap items-center justify-between gap-3">
      <div class="flex flex-wrap items-center gap-2">@foreach($act->tags ?: [$act->badge, $act->category, "Duration: {$act->duration}"] as $i => $t)<x-pill :tone="$i === 0 ? 'brand-soft' : ($i === 1 ? 'sand' : 'sage')" :uppercase="true">{{ $t }}</x-pill>@endforeach</div>
      <div class="inline-flex items-center gap-1.5 rounded-full bg-white px-3 py-1.5 text-sm shadow-soft">{!! icon('Star', 'w-4 h-4 fill-gold text-gold') !!} <b>{{ number_format($act->rating, 1) }}</b> <span class="text-sand">({{ $act->reviews }}+ reviews)</span></div>
    </div>
    <h1 class="font-display font-bold text-ink text-3xl md:text-5xl leading-[1.1] tracking-tight mt-5 max-w-4xl">{{ $act->headline ?: $act->title }}</h1>
    <p class="text-sand text-base md:text-lg mt-4 max-w-3xl leading-relaxed">{{ $act->description }}</p>
    <div class="grid lg:grid-cols-12 gap-4 mt-8">
      <div class="lg:col-span-7 relative rounded-3xl overflow-hidden h-72 lg:h-[420px] img-zoom shadow-soft"><img src="{{ $gallery[0]['src'] ?? $act->image }}" alt="{{ $act->title }}" class="w-full h-full object-cover"><div class="absolute inset-0 bg-gradient-to-t from-ink/70 via-transparent to-transparent"></div><div class="absolute bottom-5 left-5 text-white"><div class="text-[10px] uppercase tracking-[0.16em] font-bold text-gold">{{ $act->category }}</div><div class="font-display font-bold text-xl mt-1">{{ $gallery[0]['label'] ?? '' }}</div></div></div>
      <div class="lg:col-span-5 grid grid-cols-2 gap-4">@foreach(array_slice($gallery, 1, 4) as $g)<div class="relative rounded-2xl overflow-hidden h-36 lg:h-[202px] img-zoom shadow-soft"><img src="{{ $g['src'] }}" alt="{{ $g['label'] ?? '' }}" class="w-full h-full object-cover" loading="lazy"><div class="absolute bottom-3 left-3"><x-pill tone="dark">{{ $g['label'] ?? '' }}</x-pill></div></div>@endforeach</div>
    </div>
  </div>

  <div class="mx-auto max-w-7xl px-4 md:px-6 lg:px-10 py-12 grid lg:grid-cols-3 gap-8 items-start">
    <div class="lg:col-span-2 space-y-8">
      @if($act->facts)<div class="bg-white rounded-3xl p-5 shadow-soft"><div class="grid grid-cols-2 md:grid-cols-4 divide-x divide-ink/8">@foreach($act->facts as $f)<div class="text-center px-3 py-2"><span class="w-10 h-10 rounded-full bg-brand-50 text-brand inline-flex items-center justify-center">{!! icon($f['icon'] ?? 'Info') !!}</span><div class="text-[11px] text-sand mt-2">{{ $f['label'] ?? '' }}</div><div class="font-display font-bold text-ink">{{ $f['value'] ?? '' }}</div><div class="text-[10px] text-sand">{{ $f['sub'] ?? '' }}</div></div>@endforeach</div></div>@endif

      <div class="bg-white rounded-3xl p-6 md:p-8 shadow-soft">
        <h2 class="font-display font-bold text-2xl text-ink flex items-center"><span class="inline-block w-2.5 h-7 rounded-full mr-3 bg-brand-800"></span> Experience Overview &amp; Highlights</h2>
        <div class="rich-content text-ink/75 leading-relaxed mt-5 text-[15px]" data-testid="activity-long-description">{!! rich_html($act->long_description ?: $act->description) !!}</div>
        <div class="grid sm:grid-cols-2 gap-4 mt-6">@foreach($act->highlights ?? [] as $h)<div class="flex gap-3 bg-cream rounded-2xl p-4"><span class="w-10 h-10 rounded-xl bg-brand-50 text-brand flex items-center justify-center shrink-0">{!! icon($h['icon'] ?? 'Check', 'w-5 h-5') !!}</span><div><div class="font-semibold text-ink text-sm">{{ $h['title'] ?? '' }}</div><div class="text-xs text-sand mt-0.5 leading-relaxed">{{ $h['desc'] ?? '' }}</div></div></div>@endforeach</div>
      </div>

      @if($act->timeline)<div class="bg-white rounded-3xl p-6 md:p-8 shadow-soft">
        <div class="flex items-center justify-between"><div><h2 class="font-display font-bold text-2xl text-ink flex items-center"><span class="inline-block w-2.5 h-7 rounded-full mr-3 bg-forest"></span> Detailed Journey Timeline</h2><p class="text-sand text-sm mt-1 ml-[22px]">Carefully calibrated for zero-stress pacing and pristine arrival.</p></div><x-pill tone="sand" :uppercase="true">{!! icon('Clock', 'w-3 h-3') !!} {{ $act->duration }} total</x-pill></div>
        <div class="mt-8 relative pl-6 border-l-2 border-dashed border-gold/40 space-y-7" data-testid="activity-timeline">@foreach($act->timeline as $t)<div class="relative"><span class="absolute -left-[31px] top-1 w-3 h-3 rounded-full ring-4 ring-white {{ tone_class($t['tone'] ?? 'brand', 'solid') }}"></span><div class="flex flex-wrap items-baseline gap-x-3"><span class="text-brand-700 font-bold text-sm">{{ $t['time'] ?? '' }}</span><span class="font-display font-bold text-ink">{{ $t['title'] ?? '' }}</span></div><p class="text-sm text-sand mt-1 leading-relaxed">{{ $t['desc'] ?? '' }}</p></div>@endforeach</div>
      </div>@endif

      <div class="bg-white rounded-3xl p-6 md:p-8 shadow-soft">
        <h2 class="font-display font-bold text-2xl text-ink flex items-center"><span class="inline-block w-2.5 h-7 rounded-full mr-3 bg-gold"></span> Inclusions &amp; Exclusions</h2>
        <div class="grid sm:grid-cols-2 gap-8 mt-6">
          <div><div class="flex items-center gap-2 font-display font-bold text-sage-700">{!! icon('Check', 'w-5 h-5') !!} Included in Package</div><ul class="mt-4 space-y-2.5">@foreach($act->inclusions ?? [] as $i)<li class="flex items-start gap-2 text-sm text-ink/75">{!! icon('Check', 'w-4 h-4 text-sage-700 shrink-0 mt-0.5') !!} {{ $i }}</li>@endforeach</ul></div>
          <div><div class="flex items-center gap-2 font-display font-bold text-ink/60">{!! icon('X', 'w-5 h-5') !!} Not Included</div><ul class="mt-4 space-y-2.5">@foreach($act->exclusions ?? [] as $i)<li class="flex items-start gap-2 text-sm text-ink/75">{!! icon('X', 'w-4 h-4 text-ink/40 shrink-0 mt-0.5') !!} {{ $i }}</li>@endforeach</ul></div>
        </div>
      </div>

      @if($act->packing)<div class="bg-cream-100 rounded-3xl p-6 md:p-8"><h3 class="font-display font-bold text-2xl text-ink flex items-center gap-2">{!! icon('Backpack', 'w-6 h-6 text-brand') !!} Essential Preparation &amp; Packing Guide</h3><div class="grid sm:grid-cols-3 gap-4 mt-6">@foreach($act->packing as $p)<div class="bg-white rounded-2xl p-5"><div class="flex items-center gap-2 font-semibold text-ink text-sm">{!! icon($p['icon'] ?? 'Check', 'w-4 h-4 text-brand') !!} {{ $p['title'] ?? '' }}</div><p class="text-xs text-sand mt-2 leading-relaxed">{{ $p['desc'] ?? '' }}</p></div>@endforeach</div></div>@endif

      @if($act->reviews_list)<div class="bg-white rounded-3xl p-6 md:p-8 shadow-soft">
        <div class="flex items-center justify-between"><h2 class="font-display font-bold text-2xl text-ink flex items-center"><span class="inline-block w-2.5 h-7 rounded-full mr-3 bg-brand-800"></span> Traveler Experiences</h2><span class="text-xs font-semibold text-brand">{{ $act->reviews }} Verified Reviews</span></div>
        <div class="grid sm:grid-cols-2 gap-4 mt-6">@foreach($act->reviews_list as $r)<div class="rounded-2xl border border-ink/10 p-5"><div class="flex items-center justify-between"><x-stars :value="5" /><span class="text-xs text-sand">{{ $r['date'] ?? '' }}</span></div><p class="font-serif italic text-ink/80 text-sm leading-relaxed mt-3">"{{ $r['text'] ?? '' }}"</p><div class="flex items-center gap-3 mt-4 pt-4 border-t border-ink/8"><span class="w-9 h-9 rounded-full flex items-center justify-center text-white text-xs font-bold {{ ($r['tone'] ?? '') === 'forest' ? 'bg-forest' : 'bg-brand-700' }}">{{ $r['initials'] ?? '' }}</span><div><div class="font-semibold text-sm text-ink">{{ $r['name'] ?? '' }}</div><div class="text-xs text-sand">{{ $r['location'] ?? '' }}</div></div></div></div>@endforeach</div>
      </div>@endif
    </div>

    <div class="lg:sticky lg:top-24 space-y-5">
      <div class="bg-white rounded-3xl shadow-card overflow-hidden" data-testid="activity-booking-sidebar"><div class="h-1.5 bg-gradient-to-r from-brand-700 via-brand to-gold"></div>
        <div class="p-6">
          <div class="field-label !mb-0">Starting From</div>
          <div class="font-display font-bold text-4xl text-brand-700 mt-1">{{ fmt_idr($act->price) }} <span class="text-xs text-sand font-body font-normal">/ person</span></div>
          <div class="mt-4 text-xs text-sage-700 flex items-center gap-1.5 font-medium">{!! icon('CircleCheck', 'w-3.5 h-3.5') !!} All Gear, Meals &amp; Hotel Transfers Included</div>
          <div class="mt-5"><label class="field-label">Select Date</label><input type="date" x-model="date" min="{{ date('Y-m-d') }}" class="select-input" data-testid="activity-date"></div>
          <div class="mt-4"><label class="field-label">Time Slot &amp; Pickup</label><div class="space-y-2">@foreach($slots as $i => $s)<button type="button" @click="slot = {{ $i }}" :class="slot === {{ $i }} ? 'border-brand bg-brand-50/50' : 'border-ink/10 hover:border-brand/40'" class="w-full flex items-center justify-between rounded-xl border px-4 py-3 text-left transition-colors"><div><div class="font-semibold text-ink text-sm">{{ $s['label'] ?? '' }}</div><div class="text-[11px] text-sand">{{ $s['sub'] ?? '' }}</div></div><span x-show="slot === {{ $i }}">{!! icon('CircleCheck', 'w-5 h-5 text-brand-700') !!}</span></button>@endforeach</div></div>
          <div class="mt-4"><label class="field-label">Number of Participants</label><div class="h-12 rounded-xl bg-cream border border-ink/10 flex items-center justify-between px-3"><span class="text-sm text-ink/70">Participants</span><div class="flex items-center gap-3"><button type="button" @click="pax = Math.max(1, pax - 1)" class="w-8 h-8 rounded-lg bg-white text-brand flex items-center justify-center hover:bg-brand-50">−</button><span class="font-semibold w-4 text-center" x-text="pax"></span><button type="button" @click="pax = Math.min(30, pax + 1)" class="w-8 h-8 rounded-lg bg-white text-brand flex items-center justify-center hover:bg-brand-50">+</button></div></div></div>
          @if($act->addons)<div class="mt-4"><label class="field-label">Optional Upgrades</label><div class="space-y-2">@foreach($act->addons as $a)<label class="flex items-center gap-3 rounded-xl border border-ink/10 bg-cream px-3 py-2.5 cursor-pointer hover:border-brand/40"><input type="checkbox" value="{{ $a['title'] }}" x-model="addons" class="accent-brand w-4 h-4"><div class="flex-1"><div class="text-sm font-medium text-ink">{{ $a['title'] }}</div><div class="text-[11px] text-sand">{{ $a['desc'] ?? '' }}</div></div><div class="text-xs font-bold text-brand">+{{ fmt_idr($a['price'] ?? 0) }}/pax</div></label>@endforeach</div></div>@endif
          <div class="mt-5 pt-4 border-t border-ink/8 text-sm space-y-1.5"><div class="flex justify-between text-ink/70"><span>Standard (<span x-text="pax"></span> Persons)</span><span x-text="fmtIDR(price * pax)"></span></div><div class="flex justify-between text-ink/70 text-xs"><span>Transfers &amp; Equipment</span><span class="text-sage-700">Included</span></div><div class="flex justify-between font-bold text-ink pt-1"><span>Total Investment</span><span class="text-brand-700 font-display text-lg" x-text="fmtIDR(total())" data-testid="activity-total"></span></div></div>
          <button type="button" @click="book()" class="btn-brand w-full !py-4 !rounded-2xl mt-5 !bg-brand-800 hover:!bg-brand-700" data-testid="book-activity-btn">Book Activity Now {!! icon('ArrowRight') !!}</button>
          <a href="{{ wa_url("Halo, saya ingin bertanya tentang aktivitas \"{$act->title}\".") }}" target="_blank" rel="noopener" class="w-full mt-3 text-sm font-semibold text-forest inline-flex items-center justify-center gap-2 hover:text-brand transition-colors">{!! icon('MessageCircle') !!} Quick Inquiry via WhatsApp</a>
          <ul class="mt-5 space-y-2 text-xs text-ink/70">@foreach([['Bus', 'Free hotel pickup in South Bali & Ubud'], ['ShieldCheck', 'Full medical and passenger insurance included'], ['CloudRain', '100% Weather Refund / Reschedule Guarantee']] as [$ic, $t])<li class="flex items-center gap-2">{!! icon($ic, 'w-3.5 h-3.5 text-sage-700') !!} {{ $t }}</li>@endforeach</ul>
        </div>
      </div>
    </div>
  </div>
  <x-newsletter eyebrow="Curated Balinese Journeys" desc="Subscribe to receive secret dawn trekking trails, private villa retreats, and seasonal cultural festival access directly to your inbox." />
</div>
@endsection
