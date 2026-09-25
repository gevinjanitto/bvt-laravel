@extends('layouts.admin')
@section('title', $cfg['title'])
@section('content')
<div data-testid="resource-{{ $cfg['key'] }}">
<div class="flex items-center justify-between gap-3 flex-wrap"><div><h1 class="text-2xl font-bold">{{ $cfg['title'] }}</h1><p class="text-sm text-sand mt-1">{{ $items->count() }} item.</p></div><a href="{{ route('admin.resource.create', $cfg['key']) }}" class="btn-primary" data-testid="resource-create">{!! icon('Plus') !!} Tambah {{ $cfg['singular'] }}</a></div>
<div class="card mt-6 overflow-x-auto"><table class="w-full text-sm"><thead><tr class="text-left text-[11px] uppercase tracking-wider text-sand"><th class="py-2">{{ $cfg['singular'] }}</th>@foreach($cfg['columns'] as $c => $l)<th>{{ $l }}</th>@endforeach<th></th></tr></thead><tbody>
@forelse($items as $it)
<tr class="border-t border-ink/5" data-testid="resource-row-{{ $it->id }}">
  <td class="py-2.5"><div class="flex items-center gap-3"><img src="{{ $it->image ?: '/logo-icon.png' }}" class="w-12 h-9 rounded-lg object-cover bg-cream-200" alt=""><div><div class="font-medium">{{ $it->{$cfg['title_key']} }}</div><div class="text-xs text-sand">/{{ $it->slug }}</div></div></div></td>
  @foreach($cfg['columns'] as $c => $l)@php($v = $it->{$c})<td class="py-2.5 text-ink/80">@if(is_bool($v)){{ $v ? 'Ya' : 'Tidak' }}@elseif(in_array($c, ['price', 'price12h'])){{ fmt_idr($v) }}@elseif($v instanceof \Carbon\Carbon){{ $v->format('Y-m-d') }}@else{{ $v }}@endif</td>@endforeach
  <td class="py-2.5"><div class="flex items-center gap-2 justify-end"><a href="{{ route('admin.resource.edit', [$cfg['key'], $it->id]) }}" class="btn-secondary !px-3 !py-1.5" data-testid="resource-edit-{{ $it->id }}">{!! icon('Pencil') !!}</a><form method="post" action="{{ route('admin.resource.destroy', [$cfg['key'], $it->id]) }}" onsubmit="return confirm('Hapus item ini?')">@csrf @method('DELETE')<button class="btn-danger !px-3 !py-1.5" data-testid="resource-delete-{{ $it->id }}">{!! icon('Trash2') !!}</button></form></div></td>
</tr>
@empty<tr><td colspan="{{ count($cfg['columns']) + 2 }}" class="py-10 text-center text-sand">Belum ada data.</td></tr>@endforelse
</tbody></table></div>
</div>
@endsection
