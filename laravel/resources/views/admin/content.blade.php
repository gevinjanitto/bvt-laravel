@extends('layouts.admin')
@section('title', 'Konten Halaman')
@section('content')
@php($current = request('block', 'navigation'))
@php($labels = ['navigation' => 'Menu navigasi', 'homeStats' => 'Beranda: statistik', 'homeCategories' => 'Beranda: kategori', 'destinations' => 'Destinasi populer', 'homeFeatures' => 'Beranda: keunggulan', 'testimonials' => 'Testimoni', 'homeMarquee' => 'Beranda: teks berjalan', 'about' => 'Halaman About', 'airportRates' => 'Tarif airport transfer', 'carInclusions' => 'Car rental: inklusi', 'activityWhy' => 'Activities: kenapa kami', 'tourPerks' => 'Tour: fasilitas', 'faqFacts' => 'FAQ artikel', 'trendingTags' => 'Tag trending', 'footer' => 'Footer', 'policies' => 'Kebijakan (privacy/terms)', 'images' => 'Gambar statis'])
<div class="grid lg:grid-cols-4 gap-5" data-testid="content-editor">
  <div class="card !p-3 space-y-0.5 self-start">@foreach($labels as $k => $l)<a href="{{ route('admin.content', ['block' => $k]) }}" class="block rounded-lg px-3 py-2 text-sm {{ $current === $k ? 'bg-brand text-white font-semibold' : 'hover:bg-cream-100' }}" data-testid="content-block-{{ $k }}">{{ $l }}</a>@endforeach</div>
  <div class="lg:col-span-3 space-y-4">
    <form method="post" action="{{ route('admin.content.update') }}" class="card" data-testid="content-editor-form">@csrf @method('PUT')<input type="hidden" name="block" value="{{ $current }}">
      <div class="flex items-center justify-between gap-3 flex-wrap"><div><h1 class="text-xl font-bold">{{ $labels[$current] ?? $current }}</h1><p class="text-xs text-sand mt-1">Edit sebagai JSON. Kunci <code>{{ $current }}</code>. Gambar dapat berupa URL atau path hasil upload (<code>/storage/uploads/...</code>).</p></div><button class="btn-primary" data-testid="content-save">Simpan</button></div>
      <textarea name="value" rows="26" class="input font-mono text-xs mt-4" spellcheck="false" data-testid="content-json">{{ old('value', json_encode($blocks[$current] ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) }}</textarea>
    </form>
    <form method="post" action="{{ route('admin.content.reset') }}" onsubmit="return confirm('Kembalikan bagian ini ke isi default?')">@csrf<input type="hidden" name="block" value="{{ $current }}"><button class="btn-secondary" data-testid="content-reset">Kembalikan ke default</button></form>
  </div>
</div>
@endsection
