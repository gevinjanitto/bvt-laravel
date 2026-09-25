@extends('layouts.admin')
@section('title', $cfg['title'])
@section('content')
@php($n = $items->count())
<div data-testid="admin-{{ $cfg['key'] }}-page" x-data="{ q: '', confirm: null }">
  <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
    <div><h1 class="font-display font-bold text-ink text-2xl md:text-3xl tracking-tight" data-testid="admin-page-title">{{ $cfg['title'] }}</h1><p class="text-sand text-sm mt-1">{{ $n }} {{ $n === 1 ? 'item' : 'items' }} published on the website.</p></div>
    <div class="flex items-center gap-3">
      <div class="relative">{!! icon('Search', 'w-4 h-4 text-ink/40 absolute left-3 top-1/2 -translate-y-1/2') !!}<input x-model="q" placeholder="Search {{ strtolower($cfg['title']) }}..." class="ui-input !pl-9 h-10 w-56 rounded-xl" data-testid="resource-search"></div>
      <a href="{{ route('admin.resource.create', $cfg['key']) }}" class="btn-brand !py-2.5 !px-5 !text-xs" data-testid="resource-add-button">{!! icon('Plus') !!} Add {{ $cfg['singular'] }}</a>
    </div>
  </div>

  @if($items->isEmpty())
    <div class="text-center py-16 px-6 bg-white rounded-2xl border border-dashed border-ink/15" data-testid="empty-state">
      <div class="font-display font-bold text-ink text-lg">No {{ strtolower($cfg['title']) }} yet</div>
      <p class="text-sand text-sm mt-1">Create your first {{ strtolower($cfg['singular']) }} to publish it on the website.</p>
      <div class="mt-5"><a href="{{ route('admin.resource.create', $cfg['key']) }}" class="btn-forest !py-2.5 !px-5 !text-xs">{!! icon('Plus') !!} Add {{ $cfg['singular'] }}</a></div>
    </div>
  @else
    <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-sm" data-testid="resource-table">
          <thead class="text-[11px] uppercase tracking-wider text-sand bg-cream/60">
            <tr class="text-left"><th class="px-5 py-3 font-bold">{{ $cfg['singular'] }}</th>@foreach($cfg['columns'] as $c)<th class="px-5 py-3 font-bold whitespace-nowrap">{{ $c['label'] }}</th>@endforeach<th class="px-5 py-3 font-bold text-right">Actions</th></tr>
          </thead>
          <tbody class="divide-y divide-ink/5">
            @foreach($items as $it)
            @php($title = $it->{$cfg['title_key']})
            @php($hay = strtolower(implode(' ', array_filter([$title, $it->category ?? '', $it->type ?? '', $it->region ?? '', $it->badge ?? '']))))
            <tr class="hover:bg-cream/50 transition-colors" data-testid="resource-row-{{ $it->slug }}" x-show="!q.trim() || @js($hay).includes(q.trim().toLowerCase())">
              <td class="px-5 py-3">
                <div class="flex items-center gap-3 min-w-[260px]">
                  <img src="{{ $it->image ?: '/logo-icon.png' }}" alt="" class="w-14 h-11 rounded-lg object-cover bg-cream-100 shrink-0">
                  <div class="min-w-0"><div class="font-semibold text-ink truncate max-w-[320px]">{{ $title }}</div><div class="text-[11px] text-sand truncate">/{{ $it->slug }}</div></div>
                </div>
              </td>
              @foreach($cfg['columns'] as $c)
                @php($v = data_get($it, $c['key']))
                <td class="px-5 py-3 whitespace-nowrap">
                  @switch($c['type'] ?? 'text')
                    @case('price')<span class="font-semibold text-ink">{{ $v ? fmt_idr($v) : '-' }}</span>@break
                    @case('rating')<span class="inline-flex items-center gap-1 text-ink">{!! icon('Star', 'w-3.5 h-3.5 fill-gold text-gold') !!} {{ number_format((float) $v, 1) }}</span>@break
                    @case('bool')@if($v)<span class="text-sage-700 font-semibold text-xs">Yes</span>@else<span class="text-sand text-xs">No</span>@endif @break
                    @case('date')<span class="text-ink/80">{{ fmt_date($v) }}</span>@break
                    @default<span class="text-ink/80">{{ $v ?: '-' }}</span>
                  @endswitch
                </td>
              @endforeach
              <td class="px-5 py-3">
                <div class="flex items-center justify-end gap-1">
                  <a href="{{ $cfg['public'] }}/{{ $it->slug }}" target="_blank" class="icon-btn hover:bg-cream-100 text-ink/50 hover:text-ink" aria-label="View" data-testid="view-{{ $it->slug }}">{!! icon('ExternalLink') !!}</a>
                  <a href="{{ route('admin.resource.edit', [$cfg['key'], $it->id]) }}" class="icon-btn hover:bg-brand-50 text-ink/60 hover:text-brand" aria-label="Edit" data-testid="edit-{{ $it->slug }}">{!! icon('Pencil') !!}</a>
                  <button type="button" @click="confirm = { id: {{ $it->id }}, title: @js($title) }" class="icon-btn hover:bg-red-50 text-ink/60 hover:text-red-600" aria-label="Delete" data-testid="delete-{{ $it->slug }}">{!! icon('Trash2') !!}</button>
                </div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  @endif

  <div x-show="confirm" x-cloak class="fixed inset-0 z-[90] bg-ink/60 backdrop-blur-sm flex items-center justify-center p-4" data-testid="delete-dialog" @keydown.escape.window="confirm = null">
    <form method="post" :action="confirm ? '{{ url('admin/' . $cfg['key']) }}/' + confirm.id : '#'" class="bg-white rounded-3xl p-6 w-full max-w-lg shadow-2xl fade-in" @click.outside="confirm = null">@csrf @method('DELETE')
      <h2 class="font-display font-bold text-lg text-ink">Delete this {{ strtolower($cfg['singular']) }}?</h2>
      <p class="text-sm text-sand mt-2">"<span x-text="confirm?.title"></span>" will be removed from the website permanently.</p>
      <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-2 mt-6">
        <button type="button" @click="confirm = null" class="btn-outline !py-2.5 !px-5" data-testid="delete-cancel">Cancel</button>
        <button type="submit" class="inline-flex items-center justify-center rounded-full bg-red-600 hover:bg-red-700 text-white font-semibold px-5 py-2.5 text-sm transition-colors" data-testid="delete-confirm">Delete</button>
      </div>
    </form>
  </div>
</div>

@if($item)
  @include('admin.resource.form')
@endif
@endsection
