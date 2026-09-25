@extends('layouts.admin')
<<<<<<< HEAD
@section('title', 'Kontak & Identitas')
@section('content')
@php($c = $site['contact'])
@php($so = $site['social'])
@php($b = $site['brand'])
@php($field = fn ($group, $key, $label, $attrs = '', $icon = null) => '<div class="space-y-2 min-w-0"><label for="setting-' . $key . '" class="ui-label inline-flex items-center gap-2">' . ($icon ? fa_icon($icon, 'w-3.5 h-3.5 text-brand') : '') . e($label) . '</label><input id="setting-' . $key . '" name="' . $group . '[' . $key . ']" value="' . e(old("$group.$key", $site[$group][$key] ?? '')) . '" class="ui-input" ' . $attrs . ' data-testid="settings-' . ($key === 'title' ? 'brand-title' : $key) . '"></div>')
<form method="post" action="{{ route('admin.settings.update') }}" class="max-w-5xl" data-testid="settings-form">@csrf @method('PUT')
  <div class="flex flex-wrap justify-between items-start gap-4 mb-9"><div><p class="eyebrow" data-testid="settings-eyebrow">PENGATURAN WEBSITE</p><h1 class="font-display text-3xl font-bold text-ink mt-2" data-testid="settings-title">Kontak & Identitas</h1><p class="text-sm text-sand mt-2" data-testid="settings-intro">Semua kontak, sosial media, dan logo dalam satu tempat.</p></div><a href="{{ route('home') }}" target="_blank" rel="noopener noreferrer" class="btn-outline !py-2.5" data-testid="settings-view-site">Lihat website {!! icon('ExternalLink') !!}</a></div>

  <section class="border-b border-ink/10 pb-10 mb-10" data-testid="settings-section-contact">
    <h2 class="font-display text-xl font-bold text-ink" data-testid="settings-heading-contact">Kontak utama</h2>
    <p class="text-sm text-sand mt-2 mb-6" data-testid="settings-desc-contact">Nomor WhatsApp ini digunakan oleh seluruh tombol chat dan booking di website. Gunakan kode negara, misalnya 62812…; nomor 0812… akan diubah otomatis.</p>
    <div class="grid sm:grid-cols-2 gap-5 mt-5">
      {!! $field('contact', 'whatsapp', 'Nomor WhatsApp', 'required inputmode="tel"', 'whatsapp') !!}
      {!! $field('contact', 'email', 'Alamat email', 'type="email" required') !!}
      {!! $field('contact', 'emailLink', 'Link email (kosong = mailto otomatis)', 'placeholder="mailto:hello@balivisiontour.com"') !!}
      {!! $field('contact', 'address', 'Alamat', 'required') !!}
      {!! $field('contact', 'addressLink', 'Link alamat / Google Maps (kosong = pencarian otomatis)', 'type="url" placeholder="https://maps.google.com/…"') !!}
      <div class="space-y-2"><label for="setting-whatsappMessage" class="ui-label">Pesan awal WhatsApp</label><textarea id="setting-whatsappMessage" name="contact[whatsappMessage]" class="ui-textarea" data-testid="settings-whatsappMessage">{{ old('contact.whatsappMessage', $c['whatsappMessage'] ?? '') }}</textarea></div>
      <input type="hidden" name="contact[phone]" value="{{ $c['phone'] ?? '' }}">
    </div>
  </section>

  <section class="border-b border-ink/10 pb-10 mb-10" data-testid="settings-section-social">
    <h2 class="font-display text-xl font-bold text-ink" data-testid="settings-heading-social">Sosial media</h2>
    <p class="text-sm text-sand mt-2 mb-6" data-testid="settings-desc-social">Masukkan URL profil lengkap. Ikon tanpa link tetap terlihat, tetapi tidak mengarah ke halaman yang salah.</p>
    <div class="grid sm:grid-cols-2 gap-5 mt-5">
      @foreach([['instagram', 'Instagram'], ['facebook', 'Facebook'], ['youtube', 'YouTube'], ['tiktok', 'TikTok']] as [$k, $l]){!! $field('social', $k, $l, 'type="url" placeholder="https://' . $k . '.com/…"', $k) !!}@endforeach
    </div>
  </section>

  <section class="border-b border-ink/10 pb-10 mb-10" data-testid="settings-section-brand">
    <h2 class="font-display text-xl font-bold text-ink" data-testid="settings-heading-brand">Identitas & logo</h2>
    <p class="text-sm text-sand mt-2 mb-6" data-testid="settings-desc-brand">Gunakan PNG transparan atau WEBP. Logo ikon: 512 × 512 px. Logo lengkap: 800 × 240 px. Maksimal 8 MB per gambar.</p>
    <div class="grid sm:grid-cols-2 gap-5 mt-5">
      {!! $field('brand', 'name', 'Nama website', 'required') !!}
      {!! $field('brand', 'title', 'Nama di samping logo') !!}
      {!! $field('brand', 'tagline', 'Tagline logo') !!}
      {!! $field('brand', 'legal', 'Nama perusahaan di footer') !!}
      <div class="space-y-2"><label for="logo-mode" class="ui-label">Tampilan logo</label><select id="logo-mode" name="brand[logoMode]" class="ui-select" data-testid="settings-logo-mode">@foreach(['icon' => 'Ikon + nama & tagline', 'full' => 'Logo lengkap (tanpa teks tambahan)'] as $v => $l)<option value="{{ $v }}" @selected(old('brand.logoMode', $b['logoMode']) === $v)>{{ $l }}</option>@endforeach</select></div>
      <div class="space-y-2"><label class="ui-label">Logo utama</label><div data-editor="image" data-name="brand[logo]" data-value="{{ old('brand.logo', $b['logo']) }}" data-testid-base="settings-logo" data-contain="1" data-recommendation="{{ $b['logoMode'] === 'full' ? '800 × 240 px · logo lengkap' : '512 × 512 px · logo ikon' }}"></div></div>
      <div class="space-y-2"><label class="ui-label">Logo pada latar gelap (opsional)</label><div data-editor="image" data-name="brand[logoLight]" data-value="{{ old('brand.logoLight', $b['logoLight']) }}" data-testid-base="settings-logo-light" data-contain="1" data-recommendation="512 × 512 px atau 800 × 240 px"></div></div>
      <div class="space-y-2"><label class="ui-label">Favicon / ikon tab browser</label><div data-editor="image" data-name="brand[favicon]" data-value="{{ old('brand.favicon', $b['favicon']) }}" data-testid-base="settings-favicon" data-contain="1" data-recommendation="64 × 64 px atau 512 × 512 px"></div></div>
    </div>
  </section>

  <div class="sticky bottom-0 bg-cream/95 backdrop-blur py-4 border-t border-ink/10 flex justify-end"><button type="submit" class="btn-brand disabled:opacity-60" data-testid="settings-save">{!! icon('Save') !!} Simpan pengaturan</button></div>
</form>
=======
@section('title', 'Pengaturan Website')
@section('content')
<form method="post" action="{{ route('admin.settings.update') }}" data-testid="settings-form" x-data="uploader()">@csrf @method('PUT')
<div class="flex items-center justify-between"><div><h1 class="text-2xl font-bold">Pengaturan Website</h1><p class="text-sm text-sand mt-1">Kontak, sosial media, dan identitas brand yang tampil di seluruh website.</p></div><button class="btn-primary" data-testid="settings-save">Simpan</button></div>
<div class="grid lg:grid-cols-2 gap-5 mt-6">
  <div class="card space-y-4"><h2 class="font-bold">Kontak</h2>
    @foreach([['whatsapp', 'Nomor WhatsApp (contoh 6282247479695)'], ['phone', 'Nomor telepon tampil'], ['email', 'Email'], ['emailLink', 'Link email (opsional, mailto:/https)'], ['address', 'Alamat'], ['addressLink', 'Link Google Maps (opsional)']] as [$k, $l])<div><label class="label">{{ $l }}</label><input name="contact[{{ $k }}]" value="{{ old("contact.$k", $site['contact'][$k] ?? '') }}" class="input" data-testid="settings-contact-{{ $k }}"></div>@endforeach
    <div><label class="label">Pesan WhatsApp default</label><textarea name="contact[whatsappMessage]" class="input" rows="2">{{ old('contact.whatsappMessage', $site['contact']['whatsappMessage'] ?? '') }}</textarea></div>
  </div>
  <div class="space-y-5">
    <div class="card space-y-4"><h2 class="font-bold">Sosial Media</h2>@foreach(['instagram', 'facebook', 'youtube', 'tiktok'] as $k)<div><label class="label">{{ ucfirst($k) }} URL</label><input name="social[{{ $k }}]" value="{{ old("social.$k", $site['social'][$k] ?? '') }}" class="input" placeholder="https://" data-testid="settings-social-{{ $k }}"></div>@endforeach</div>
    <div class="card space-y-4"><h2 class="font-bold">Brand</h2>
      @foreach([['name', 'Nama brand'], ['title', 'Judul logo'], ['tagline', 'Tagline'], ['legal', 'Nama legal (footer)']] as [$k, $l])<div><label class="label">{{ $l }}</label><input name="brand[{{ $k }}]" value="{{ old("brand.$k", $site['brand'][$k] ?? '') }}" class="input" data-testid="settings-brand-{{ $k }}"></div>@endforeach
      @foreach([['logo', 'Logo utama'], ['logoLight', 'Logo versi terang (footer)'], ['favicon', 'Favicon']] as [$k, $l])<div><label class="label">{{ $l }}</label><div class="flex gap-2"><input name="brand[{{ $k }}]" value="{{ old("brand.$k", $site['brand'][$k] ?? '') }}" class="input" id="brand_{{ $k }}"><label class="btn-secondary cursor-pointer !px-3">{!! icon('Upload') !!}<input type="file" accept="image/*" class="hidden" @change="upload($event, 'brand_{{ $k }}')"></label></div></div>@endforeach
      <div><label class="label">Mode logo</label><select name="brand[logoMode]" class="input">@foreach(['icon' => 'Ikon + teks', 'full' => 'Gambar logo penuh'] as $v => $l)<option value="{{ $v }}" @selected(old('brand.logoMode', $site['brand']['logoMode']) === $v)>{{ $l }}</option>@endforeach</select></div>
    </div>
  </div>
</div>
</form>
@include('admin.partials.uploader')
>>>>>>> 3d75822977b8fa74ecaa4dc0a07e5dc1508a4a17
@endsection
