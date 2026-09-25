@extends('layouts.admin')
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
@endsection
