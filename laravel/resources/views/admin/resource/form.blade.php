@extends('layouts.admin')
@section('title', ($item->exists ? 'Edit ' : 'Tambah ') . $cfg['singular'])
@section('content')
<form method="post" action="{{ $item->exists ? route('admin.resource.update', [$cfg['key'], $item->id]) : route('admin.resource.store', $cfg['key']) }}" x-data="uploader()" data-testid="resource-form">@csrf @if($item->exists)@method('PUT')@endif
<div class="flex items-center justify-between gap-3 flex-wrap sticky top-0 bg-cream-100/90 backdrop-blur py-3 z-10 -mt-3"><div><a href="{{ route('admin.resource.index', $cfg['key']) }}" class="text-xs text-sand hover:text-ink">← {{ $cfg['title'] }}</a><h1 class="text-2xl font-bold">{{ $item->exists ? 'Edit' : 'Tambah' }} {{ $cfg['singular'] }}</h1></div><button class="btn-primary" data-testid="resource-save">{!! icon('Save') !!} Simpan</button></div>
<div class="space-y-5 mt-4">
@foreach($cfg['sections'] as $title => $fields)
<div class="card"><h2 class="font-bold mb-4">{{ $title }}</h2><div class="grid md:grid-cols-2 gap-4">
  @foreach($fields as $f)
    @php($key = $f['key'])
    @php($val = old($key, $item->{$key}))
    <div class="{{ ($f['span'] ?? 1) === 2 ? 'md:col-span-2' : '' }}"><label class="label">{{ $f['label'] }}@if($f['required'] ?? false) <span class="text-brand">*</span>@endif</label>
    @switch($f['type'])
      @case('textarea')<textarea name="{{ $key }}" rows="3" class="input" data-testid="field-{{ $key }}">{{ $val }}</textarea>@break
      @case('richtext')<textarea name="{{ $key }}" rows="6" class="input font-mono text-xs" data-testid="field-{{ $key }}">{{ $val }}</textarea><p class="text-[11px] text-sand mt-1">Boleh HTML sederhana (&lt;p&gt;, &lt;strong&gt;, &lt;ul&gt;, &lt;a&gt;) atau teks biasa dipisah baris kosong.</p>@break
      @case('lines')<textarea name="{{ $key }}" rows="4" class="input" data-testid="field-{{ $key }}">{{ is_array($val) ? implode("\n", $val) : $val }}</textarea>@break
      @case('json')<textarea name="{{ $key }}" rows="6" class="input font-mono text-xs" spellcheck="false" data-testid="field-{{ $key }}">{{ is_array($val) ? json_encode($val, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : $val }}</textarea>@break
      @case('select')<select name="{{ $key }}" class="input" data-testid="field-{{ $key }}">@foreach($f['options'] as $o)<option @selected((string) $val === (string) $o)>{{ $o }}</option>@endforeach @if($val && !in_array($val, $f['options']))<option selected>{{ $val }}</option>@endif</select>@break
      @case('bool')<label class="flex items-center gap-2 mt-2 text-sm"><input type="hidden" name="{{ $key }}" value="0"><input type="checkbox" name="{{ $key }}" value="1" @checked($val) class="w-4 h-4 accent-brand" data-testid="field-{{ $key }}"> Aktif</label>@break
      @case('image')<div class="flex gap-2 items-start"><div class="flex-1"><input name="{{ $key }}" id="f_{{ $key }}" value="{{ $val }}" class="input" placeholder="https://... atau upload" data-testid="field-{{ $key }}" x-ref="{{ $key }}"></div><label class="btn-secondary cursor-pointer !px-3" title="Upload gambar">{!! icon('Upload') !!}<input type="file" accept="image/jpeg,image/png,image/webp" class="hidden" @change="upload($event, 'f_{{ $key }}')" data-testid="upload-{{ $key }}"></label>@if($val)<img src="{{ $val }}" class="w-16 h-11 rounded-lg object-cover bg-cream-200" alt="">@endif</div>@break
      @case('number')<input type="number" step="{{ $f['step'] ?? 'any' }}" name="{{ $key }}" value="{{ $val }}" class="input" data-testid="field-{{ $key }}">@break
      @case('date')<input type="date" name="{{ $key }}" value="{{ $val instanceof \Carbon\Carbon ? $val->format('Y-m-d') : $val }}" class="input" data-testid="field-{{ $key }}">@break
      @default<input name="{{ $key }}" value="{{ $val }}" class="input" data-testid="field-{{ $key }}">
    @endswitch
    </div>
  @endforeach
</div></div>
@endforeach
</div>
</form>
@include('admin.partials.uploader')
@endsection
