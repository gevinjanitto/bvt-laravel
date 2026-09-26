@extends('layouts.admin')
@section('title', 'Isi Halaman')
@section('content')
@php($scopes = ['Home' => 'Beranda', 'TourPackages' => 'Tour Packages', 'TourDetail' => 'Detail Tour', 'CarRental' => 'Car Rental', 'CarDetail' => 'Detail Kendaraan', 'Activities' => 'Activities', 'ActivityDetail' => 'Detail Aktivitas', 'About' => 'About Us', 'Articles' => 'Articles', 'ArticleDetail' => 'Detail Artikel', 'PolicyPage' => 'Halaman kebijakan', 'Layout' => 'Newsletter & hero', 'Navbar' => 'Navigasi', 'Cards' => 'Kartu daftar', 'BookingDialog' => 'Form booking'])
@php($blockLabels = ['homeStats' => 'Beranda · Statistik', 'homeCategories' => 'Beranda · Kategori layanan', 'destinations' => 'Beranda · Destinasi', 'homeFeatures' => 'Beranda · Keunggulan', 'testimonials' => 'Beranda · Testimoni', 'homeMarquee' => 'Beranda · Teks berjalan', 'about' => 'About Us · Statistik, tim & testimoni', 'airportRates' => 'Car Rental · Tarif bandara', 'carInclusions' => 'Car Rental · Fasilitas', 'activityWhy' => 'Activities · Keunggulan', 'tourPerks' => 'Tour Packages · Fasilitas', 'faqFacts' => 'Articles · FAQ', 'trendingTags' => 'Articles · Topik populer', 'navigation' => 'Navigasi website', 'footer' => 'Footer · Deskripsi, menu & pembayaran', 'policies' => 'Halaman kebijakan & syarat'])
@php($imageLabels = ['hero' => 'Beranda · Foto hero utama', 'lempuyang2' => 'Tour Packages / About · Pura Lempuyang', 'batur' => 'Activities · Foto hero', 'bedugul' => 'Car Rental · Foto hero / destinasi Bedugul', 'agung' => 'Articles · Foto hero', 'group' => 'About · Foto sejarah perusahaan', 'riceMist' => 'About · Foto keberlanjutan', 'ubud' => 'About / Destinasi · Ubud', 'suv2' => 'About / Kategori layanan · Kendaraan'])
<form method="post" action="{{ route('admin.content.update') }}" class="max-w-5xl" data-testid="content-editor-form" x-data="{ tab: 'texts' }" id="content-form">@csrf @method('PUT')
  <input type="hidden" name="texts" id="texts-input" value="{{ json_encode((object) $texts) }}">
  <input type="hidden" name="blocks" id="blocks-input" value="{{ json_encode($blocks) }}">
  <p class="eyebrow" data-testid="content-eyebrow">KONTEN WEBSITE</p>
  <h1 class="font-display text-3xl font-bold text-ink mt-2" data-testid="content-title">Isi Halaman</h1>
  <p class="text-sm text-sand mt-3 mb-8" data-testid="content-description">Teks, foto, dan bagian pendukung website. Data paket, kendaraan, aktivitas, dan artikel tersedia di menu masing-masing.</p>
  <div class="bg-white border border-ink/10 rounded-md h-auto inline-flex flex-wrap justify-start p-1 gap-1" role="tablist">
    <button type="button" class="tab-trigger-light" :aria-selected="tab === 'texts' ? 'true' : 'false'" @click="tab = 'texts'" data-testid="content-tab-texts">{!! icon('Type', 'w-4 h-4 mr-2') !!} Teks halaman</button>
    <button type="button" class="tab-trigger-light" :aria-selected="tab === 'blocks' ? 'true' : 'false'" @click="tab = 'blocks'" data-testid="content-tab-blocks">{!! icon('Layers', 'w-4 h-4 mr-2') !!} Bagian & daftar</button>
    <button type="button" class="tab-trigger-light" :aria-selected="tab === 'images' ? 'true' : 'false'" @click="tab = 'images'" data-testid="content-tab-images">{!! icon('Image', 'w-4 h-4 mr-2') !!} Foto website</button>
  </div>

  <div x-show="tab === 'texts'" class="mt-7">
    <div class="grid sm:grid-cols-2 gap-4 mb-7">
      <div><label for="content-page-select" class="text-xs text-sand font-semibold">Pilih halaman</label><select id="content-page-select" data-testid="content-page-select" class="ui-select mt-2 h-11">@foreach($scopes as $k => $l)<option value="{{ $k }}">{{ $l }}</option>@endforeach</select></div>
      <div><label for="content-search" class="text-xs text-sand font-semibold">Cari teks</label><div class="relative mt-2">{!! icon('Search', 'w-4 h-4 absolute left-3 top-3.5 text-sand') !!}<input id="content-search" data-testid="content-search" placeholder="Cari judul, deskripsi, atau teks tombol…" class="ui-input h-11 !pl-10"></div></div>
    </div>
    <p class="text-xs text-sand mb-5" data-testid="content-field-count" id="content-field-count"></p>
    <div id="cms-sections" data-testid="cms-sections" class="space-y-8"></div>
  </div>

  <div x-show="tab === 'blocks'" class="mt-7">
    <label for="content-block-select" class="text-xs text-sand font-semibold">Pilih bagian</label>
    <select id="content-block-select" data-testid="content-block-select" class="ui-select mt-2 mb-7 h-11">@foreach($blockLabels as $k => $l)@if(isset($blocks[$k]))<option value="{{ $k }}">{{ $l }}</option>@endif @endforeach</select>
    <div id="block-tree"></div>
  </div>

  <div x-show="tab === 'images'" class="mt-7">
    <p class="text-sm text-sand mb-6" data-testid="content-image-guidelines">Hero: 1920 × 1080 px. Foto konten: 1200 × 800 px. Potret tim: 800 × 1000 px. Maksimal 8 MB per gambar (JPG, PNG, WEBP).</p>
    <div class="grid md:grid-cols-2 gap-x-8 gap-y-7" id="image-grid"></div>
  </div>

  <div class="sticky bottom-0 bg-cream/95 backdrop-blur py-4 border-t border-ink/10 flex justify-end mt-8"><button type="submit" class="btn-brand disabled:opacity-60" data-testid="content-save">{!! icon('Save') !!} Simpan konten</button></div>
</form>
<script>
window.CMS = { catalog: @json($catalog), texts: @json((object) $texts), blocks: @json($blocks), defaults: @json($defaults), scopes: @json($scopes), imageLabels: @json($imageLabels) };
</script>
@endsection
