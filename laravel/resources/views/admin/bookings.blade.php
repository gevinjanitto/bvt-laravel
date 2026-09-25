@extends('layouts.admin')
@section('title', 'Booking Requests')
@section('content')
@php($statuses = \App\Models\Booking::STATUSES)
<div data-testid="admin-bookings-page" x-data="{ q: '', status: 'all' }">
  <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
    <div><h1 class="font-display font-bold text-ink text-2xl md:text-3xl tracking-tight" data-testid="admin-page-title">Booking Requests</h1><p class="text-sand text-sm mt-1">Requests submitted through the website before guests are redirected to WhatsApp.</p></div>
    <div class="flex items-center gap-3">
      <div class="relative">{!! icon('Search', 'w-4 h-4 text-ink/40 absolute left-3 top-1/2 -translate-y-1/2') !!}<input x-model="q" placeholder="Search guest, phone, item..." class="ui-input !pl-9 h-10 w-56 rounded-xl" data-testid="bookings-search"></div>
      <select x-model="status" class="ui-select h-10 w-40 rounded-xl" data-testid="bookings-status-filter"><option value="all">All statuses</option>@foreach($statuses as $s)<option value="{{ $s }}">{{ $s }}</option>@endforeach</select>
      <a href="{{ route('admin.bookings') }}" class="w-10 h-10 rounded-xl bg-white shadow-soft flex items-center justify-center text-ink/60 hover:text-brand" aria-label="Refresh" data-testid="bookings-refresh">{!! icon('RefreshCw') !!}</a>
    </div>
  </div>
  @if($bookings->isEmpty())
    <div class="text-center py-16 px-6 bg-white rounded-2xl border border-dashed border-ink/15" data-testid="empty-state"><div class="font-display font-bold text-ink text-lg">No booking requests</div><p class="text-sand text-sm mt-1">When a guest fills the quick booking form on the website, it appears here.</p></div>
  @else
    <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-sm" data-testid="bookings-table">
          <thead class="text-[11px] uppercase tracking-wider text-sand bg-cream/60"><tr class="text-left"><th class="px-5 py-3 font-bold">Guest</th><th class="px-5 py-3 font-bold">Request</th><th class="px-5 py-3 font-bold">Date &amp; Pax</th><th class="px-5 py-3 font-bold">Total</th><th class="px-5 py-3 font-bold">Status</th><th class="px-5 py-3 font-bold text-right">Actions</th></tr></thead>
          <tbody class="divide-y divide-ink/5">
            @foreach($bookings as $b)
            @php($phone = preg_replace('/^0/', '62', preg_replace('/\D/', '', $b->phone)))
            @php($hay = strtolower("{$b->name} {$b->phone} {$b->item_name}"))
            <tr class="hover:bg-cream/50 align-top" data-testid="booking-row-{{ $b->id }}" x-data="{ s: @js($b->status) }" x-show="(status === 'all' || s === status) && (!q || @js($hay).includes(q.toLowerCase()))">
              <td class="px-5 py-3"><div class="font-semibold text-ink">{{ $b->name }}</div><div class="text-xs text-sand">{{ $b->phone }}</div><div class="text-[10px] text-sand mt-1">{{ $b->created_at->format('d/m/Y, H:i:s') }}</div></td>
              <td class="px-5 py-3 max-w-xs"><div class="text-ink font-medium">{{ $b->item_name }}</div><div class="text-xs text-brand font-semibold">{{ $b->type }}</div>@if($b->option)<div class="text-xs text-sand mt-1 clamp-2">{{ $b->option }}</div>@endif @if($b->notes)<div class="text-xs text-ink/60 italic mt-1 clamp-2">"{{ $b->notes }}"</div>@endif</td>
              <td class="px-5 py-3 whitespace-nowrap text-ink/80">{{ $b->date ?: '-' }}<div class="text-xs text-sand">{{ $b->pax ? $b->pax . ' pax' : '' }}</div></td>
              <td class="px-5 py-3 whitespace-nowrap font-semibold text-ink">{{ $b->total ? fmt_idr($b->total) : '-' }}</td>
              <td class="px-5 py-3">
                <div class="relative inline-flex items-center h-8 w-36 rounded-lg bg-cream px-2">
                  <span class="status-badge" :class="'status-' + s" x-text="s" data-testid="booking-status-badge-{{ $b->id }}"></span>
                  <span class="ml-auto opacity-50">{!! icon('ChevronDown') !!}</span>
                  <select class="absolute inset-0 opacity-0 cursor-pointer text-xs" data-booking-status="{{ route('admin.bookings.update', $b) }}" @change="s = $event.target.value" data-testid="booking-status-{{ $b->id }}">@foreach($statuses as $st)<option value="{{ $st }}" @selected($b->status === $st)>{{ $st }}</option>@endforeach</select>
                </div>
              </td>
              <td class="px-5 py-3"><div class="flex items-center justify-end gap-1">
                <a href="https://wa.me/{{ $phone }}?text={{ rawurlencode("Halo {$b->name}, terima kasih sudah menghubungi Bali Vision Tour mengenai {$b->item_name}" . ($b->date ? " ({$b->date})" : '') . '. ') }}" target="_blank" rel="noreferrer" class="icon-btn hover:bg-sage text-ink/60 hover:text-sage-700" aria-label="WhatsApp" data-testid="booking-wa-{{ $b->id }}">{!! icon('MessageCircle') !!}</a>
                <form method="post" action="{{ route('admin.bookings.destroy', $b) }}" onsubmit="return confirm('Delete this booking request?')">@csrf @method('DELETE')<button class="icon-btn hover:bg-red-50 text-ink/60 hover:text-red-600" aria-label="Delete" data-testid="booking-delete-{{ $b->id }}">{!! icon('Trash2') !!}</button></form>
              </div></td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  @endif
</div>
@endsection
