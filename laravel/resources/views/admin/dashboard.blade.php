@extends('layouts.admin')
@section('title', 'Dashboard')
@section('content')
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
</div>
@endsection
