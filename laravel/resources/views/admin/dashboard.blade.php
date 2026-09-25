@extends('layouts.admin')
@section('title', 'Dashboard')
@section('content')
<<<<<<< HEAD
@php($tones = ['brand' => 'bg-brand-50 text-brand', 'forest' => 'bg-sage text-sage-700', 'gold' => 'bg-gold-100 text-gold', 'sand' => 'bg-cream-200 text-sand'])
@php($typeTone = ['Tour Package' => 'bg-brand', 'Car Rental' => 'bg-forest', 'Activity' => 'bg-gold'])
@php($totalType = max(1, array_sum($byType->all())))
@php($max = max(1, max($daily ?: [0])))
<div data-testid="admin-dashboard">
  <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
    <div><h1 class="font-display font-bold text-ink text-2xl md:text-3xl tracking-tight" data-testid="admin-page-title">Overview</h1><p class="text-sand text-sm mt-1">A snapshot of your catalogue and incoming booking requests.</p></div>
    <div class="flex items-center gap-3"><a href="{{ route('admin.resource.create', 'tours') }}" class="btn-brand !py-2.5 !px-5 !text-xs" data-testid="dashboard-add-tour">{!! icon('Plus') !!} New Tour Package</a></div>
  </div>
  <div class="grid sm:grid-cols-2 xl:grid-cols-5 gap-4">
    @foreach([['Tour Packages', $counts['tours'], 'Map', 'brand', null, 'stat-tours'], ['Vehicles', $counts['cars'], 'Car', 'forest', null, 'stat-cars'], ['Activities', $counts['activities'], 'Compass', 'gold', null, 'stat-activities'], ['Articles', $counts['articles'], 'Newspaper', 'sand', null, 'stat-articles'], ['Booking Requests', $total, 'Inbox', 'brand', ($byStatus['new'] ?? 0) . ' new to follow up', 'stat-bookings']] as [$label, $value, $ic, $tone, $sub, $tid])
    <div class="bg-white rounded-2xl p-5 shadow-soft flex items-start justify-between lift" data-testid="{{ $tid }}">
      <div><div class="text-[11px] uppercase tracking-[0.14em] font-bold text-sand">{{ $label }}</div><div class="font-display font-bold text-ink text-3xl mt-2 leading-none">{{ $value }}</div>@if($sub)<div class="text-xs text-sand mt-2">{{ $sub }}</div>@endif</div>
      <span class="w-11 h-11 rounded-2xl flex items-center justify-center shrink-0 {{ $tones[$tone] }}">{!! icon($ic, 'w-5 h-5') !!}</span>
    </div>
    @endforeach
  </div>
  <div class="grid lg:grid-cols-3 gap-6 mt-6">
    <div class="lg:col-span-2 bg-white rounded-2xl p-6 shadow-soft" data-testid="bookings-chart">
      <div class="flex items-center justify-between"><div><div class="font-display font-bold text-ink text-lg">Booking Requests</div><div class="text-xs text-sand">Last 14 days</div></div></div>
      <div class="h-64 mt-4 relative">
        <div class="absolute inset-0 flex flex-col justify-between pb-6 pt-2 pointer-events-none">@for($i = 0; $i < 5; $i++)<div class="border-t border-[#EFE6D8]"></div>@endfor</div>
        <div class="absolute inset-x-0 top-2 bottom-6 flex items-end gap-2 px-1">
          @foreach($daily as $d => $n)
          <div class="flex-1 flex flex-col items-center justify-end h-full group relative" title="{{ $d }}: {{ $n }}">
            <div class="absolute -top-7 left-1/2 -translate-x-1/2 rounded-xl border border-[#EFE6D8] bg-white px-2 py-1 text-[11px] shadow-soft opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">count : {{ $n }}</div>
            <div class="w-full rounded-t-md bg-brand hover:bg-brand-600 transition-colors" style="height:{{ $n ? max(4, round($n / $max * 100)) : 0 }}%"></div>
          </div>
          @endforeach
        </div>
        <div class="absolute inset-x-0 bottom-0 flex gap-2 px-1">@foreach($daily as $d => $n)<div class="flex-1 text-center text-[11px] text-[#8A7A66]">{{ substr($d, 5) }}</div>@endforeach</div>
      </div>
    </div>
    <div class="bg-white rounded-2xl p-6 shadow-soft" data-testid="bookings-by-type">
      <div class="font-display font-bold text-ink text-lg">By Service</div>
      <div class="text-xs text-sand">Distribution of all requests</div>
      <div class="mt-6 space-y-5">
        @foreach(['Tour Package', 'Car Rental', 'Activity'] as $t)
        @php($n = $byType[$t] ?? 0)
        <div>
          <div class="flex items-center justify-between text-sm"><span class="font-medium text-ink">{{ $t }}</span><span class="text-sand">{{ $n }}</span></div>
          <div class="h-2 rounded-full bg-cream-100 mt-2 overflow-hidden"><div class="h-full rounded-full {{ $typeTone[$t] }} transition-[width] duration-700" style="width:{{ round($n / $totalType * 100) }}%"></div></div>
        </div>
        @endforeach
      </div>
      <div class="mt-6 pt-5 border-t border-ink/[0.08] grid grid-cols-2 gap-3 text-xs">
        @foreach(['new', 'contacted', 'confirmed', 'cancelled'] as $s)<div class="flex items-center justify-between rounded-xl bg-cream px-3 py-2"><span class="status-badge status-{{ $s }}" data-testid="status-{{ $s }}">{{ $s }}</span><span class="font-bold text-ink">{{ $byStatus[$s] ?? 0 }}</span></div>@endforeach
      </div>
    </div>
  </div>
  <div class="bg-white rounded-2xl shadow-soft mt-6 overflow-hidden" data-testid="recent-bookings">
    <div class="flex items-center justify-between px-6 py-5 border-b border-ink/[0.08]"><div class="font-display font-bold text-ink text-lg">Recent Requests</div><a href="{{ route('admin.bookings') }}" class="text-sm font-semibold text-brand inline-flex items-center gap-1 hover:gap-2 transition-all">View all {!! icon('ArrowRight') !!}</a></div>
    @if($recent->isEmpty())
      <div class="px-6 py-10 text-center text-sm text-sand">No booking requests yet. They will appear here once guests submit the booking form.</div>
    @else
      <div class="overflow-x-auto"><table class="w-full text-sm">
        <thead class="text-[11px] uppercase tracking-wider text-sand"><tr class="text-left"><th class="px-6 py-3 font-bold">Guest</th><th class="px-6 py-3 font-bold">Item</th><th class="px-6 py-3 font-bold">Date</th><th class="px-6 py-3 font-bold">Total</th><th class="px-6 py-3 font-bold">Status</th></tr></thead>
        <tbody class="divide-y divide-ink/5">@foreach($recent as $b)<tr class="hover:bg-cream/60"><td class="px-6 py-3"><div class="font-semibold text-ink">{{ $b->name }}</div><div class="text-xs text-sand">{{ $b->phone }}</div></td><td class="px-6 py-3"><div class="text-ink">{{ $b->item_name }}</div><div class="text-xs text-sand">{{ $b->type }}</div></td><td class="px-6 py-3 text-ink/80">{{ $b->date }}</td><td class="px-6 py-3 font-semibold text-ink">{{ $b->total ? fmt_idr($b->total) : '-' }}</td><td class="px-6 py-3"><span class="status-badge status-{{ $b->status }}" data-testid="status-{{ $b->status }}">{{ $b->status }}</span></td></tr>@endforeach</tbody>
      </table></div>
    @endif
  </div>
=======
<div data-testid="admin-dashboard">
<h1 class="text-2xl font-bold">Dashboard</h1><p class="text-sm text-sand mt-1">Ringkasan konten dan permintaan booking.</p>
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mt-6">
  @foreach([['tours', 'Tour Packages', 'Map'], ['cars', 'Vehicles', 'Car'], ['activities', 'Activities', 'Compass'], ['articles', 'Articles', 'Newspaper']] as [$k, $l, $ic])
  <a href="{{ route('admin.resource.index', $k) }}" class="card flex items-center gap-4 hover:-translate-y-0.5 transition-transform" data-testid="stat-{{ $k }}"><span class="w-11 h-11 rounded-full bg-brand-50 text-brand flex items-center justify-center">{!! icon($ic, 'w-5 h-5') !!}</span><div><div class="text-2xl font-display font-bold">{{ $counts[$k] }}</div><div class="text-xs text-sand">{{ $l }}</div></div></a>
  @endforeach
</div>
<div class="grid lg:grid-cols-3 gap-4 mt-4">
  <div class="card lg:col-span-2"><div class="flex items-center justify-between"><h2 class="font-bold">Booking 14 hari terakhir</h2><span class="text-xs text-sand">Total {{ $total }} booking</span></div>
    @php($max = max(1, max($daily ?: [0])))
    <div class="flex items-end gap-1.5 h-40 mt-5">@foreach($daily as $d => $n)<div class="flex-1 flex flex-col items-center gap-1 group" title="{{ $d }}: {{ $n }}"><span class="text-[10px] text-sand opacity-0 group-hover:opacity-100">{{ $n }}</span><div class="w-full rounded-t-md bg-brand/80" style="height:{{ max(4, $n / $max * 120) }}px"></div><span class="text-[9px] text-sand">{{ substr($d, 8) }}</span></div>@endforeach</div>
  </div>
  <div class="card"><h2 class="font-bold">Status booking</h2><div class="space-y-2.5 mt-4">@foreach(['new' => 'Baru', 'contacted' => 'Dihubungi', 'confirmed' => 'Terkonfirmasi', 'cancelled' => 'Dibatalkan'] as $s => $l)<div class="flex items-center justify-between text-sm"><span class="text-ink/70">{{ $l }}</span><span class="font-bold">{{ $byStatus[$s] ?? 0 }}</span></div>@endforeach</div>
    <h2 class="font-bold mt-6">Per jenis</h2><div class="space-y-2.5 mt-3">@forelse($byType as $t => $n)<div class="flex items-center justify-between text-sm"><span class="text-ink/70">{{ $t }}</span><span class="font-bold">{{ $n }}</span></div>@empty<div class="text-sm text-sand">Belum ada booking.</div>@endforelse</div></div>
</div>
<div class="card mt-4"><div class="flex items-center justify-between"><h2 class="font-bold">Booking terbaru</h2><a href="{{ route('admin.bookings') }}" class="text-sm text-brand font-semibold">Lihat semua →</a></div>
  <div class="overflow-x-auto mt-4"><table class="w-full text-sm"><thead><tr class="text-left text-[11px] uppercase tracking-wider text-sand"><th class="py-2">Nama</th><th>Item</th><th>Tanggal</th><th>Status</th></tr></thead><tbody>
  @forelse($recent as $b)<tr class="border-t border-ink/5"><td class="py-2.5 font-medium">{{ $b->name }}<div class="text-xs text-sand">{{ $b->phone }}</div></td><td>{{ $b->item_name }}<div class="text-xs text-sand">{{ $b->type }}</div></td><td>{{ $b->date }}</td><td><span class="rounded-full bg-cream-100 px-2.5 py-1 text-xs font-semibold">{{ $b->status }}</span></td></tr>@empty<tr><td colspan="4" class="py-6 text-center text-sand">Belum ada booking.</td></tr>@endforelse
  </tbody></table></div></div>
>>>>>>> 3d75822977b8fa74ecaa4dc0a07e5dc1508a4a17
</div>
@endsection
