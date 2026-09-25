@extends('layouts.admin')
@section('title', 'Bookings')
@section('content')
<div data-testid="admin-bookings">
<h1 class="text-2xl font-bold">Bookings</h1><p class="text-sm text-sand mt-1">{{ $bookings->count() }} permintaan booking dari website.</p>
<div class="card mt-6 overflow-x-auto"><table class="w-full text-sm"><thead><tr class="text-left text-[11px] uppercase tracking-wider text-sand"><th class="py-2">Waktu</th><th>Pemesan</th><th>Item</th><th>Detail</th><th>Total</th><th>Status</th><th></th></tr></thead><tbody>
@forelse($bookings as $b)
<tr class="border-t border-ink/5 align-top" data-testid="booking-row-{{ $b->id }}">
  <td class="py-3 text-xs text-sand whitespace-nowrap">{{ $b->created_at->format('d M Y H:i') }}</td>
  <td class="py-3"><div class="font-medium">{{ $b->name }}</div><a href="https://wa.me/{{ preg_replace('/\D/', '', $b->phone) }}" target="_blank" class="text-xs text-brand">{{ $b->phone }}</a></td>
  <td class="py-3"><div class="font-medium">{{ $b->item_name }}</div><div class="text-xs text-sand">{{ $b->type }}</div></td>
  <td class="py-3 text-xs text-ink/70 max-w-xs"><div>{{ $b->date }} • {{ $b->pax }} pax</div>@if($b->option)<div class="mt-0.5">{{ $b->option }}</div>@endif @if($b->notes)<div class="mt-0.5 italic">"{{ $b->notes }}"</div>@endif</td>
  <td class="py-3 font-semibold whitespace-nowrap">{{ $b->total ? fmt_idr($b->total) : '-' }}</td>
  <td class="py-3"><form method="post" action="{{ route('admin.bookings.update', $b) }}">@csrf @method('PATCH')<select name="status" onchange="this.form.submit()" class="input !py-1.5 !w-auto text-xs" data-testid="booking-status-{{ $b->id }}">@foreach(\App\Models\Booking::STATUSES as $s)<option @selected($b->status === $s)>{{ $s }}</option>@endforeach</select></form></td>
  <td class="py-3"><form method="post" action="{{ route('admin.bookings.destroy', $b) }}" onsubmit="return confirm('Hapus booking ini?')">@csrf @method('DELETE')<button class="btn-danger !px-2.5 !py-1.5" data-testid="booking-delete-{{ $b->id }}">{!! icon('Trash2') !!}</button></form></td>
</tr>
@empty<tr><td colspan="7" class="py-10 text-center text-sand">Belum ada booking.</td></tr>@endforelse
</tbody></table></div>
</div>
@endsection
