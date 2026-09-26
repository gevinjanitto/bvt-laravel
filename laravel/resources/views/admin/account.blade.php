@extends('layouts.admin')
@section('title', 'Akun Admin')
@section('content')
@php($opts = [5 => '5 menit', 10 => '10 menit', 15 => '15 menit (bawaan)', 30 => '30 menit', 60 => '1 jam', 120 => '2 jam', 0 => 'Nonaktif (tidak pernah logout otomatis)'])
@php($saved = (int) $user->idle_timeout_minutes)
<div class="max-w-2xl space-y-10" data-testid="account-page">
  <div>
    <p class="eyebrow" data-testid="account-eyebrow">KEAMANAN AKUN</p>
    <h1 class="font-display text-3xl font-bold mt-2 text-ink" data-testid="account-title">Akun Admin</h1>
    <p class="text-sm text-sand mt-3" data-testid="account-description">Ganti username dan password. Setelah disimpan, seluruh sesi lama akan keluar.</p>
    <form method="post" action="{{ route('admin.account.update') }}" class="space-y-6 mt-9" data-testid="account-form" x-data="{ show: false, pw: '' }" @submit="if (pw !== $refs.confirm.value) { $event.preventDefault(); window.toast('Konfirmasi password tidak sama', 'error'); }">@csrf @method('PUT')
      <div class="space-y-2"><label for="account-username" class="ui-label">Username</label><input id="account-username" name="username" data-testid="account-username" autocomplete="username" value="{{ old('username', $user->username) }}" required minlength="3" maxlength="40" pattern="[a-zA-Z0-9_.\-]+" class="ui-input"></div>
      <div class="space-y-2"><label for="account-current-password" class="ui-label">Password saat ini</label><input id="account-current-password" name="current_password" data-testid="account-current-password" :type="show ? 'text' : 'password'" required autocomplete="current-password" class="ui-input"></div>
      <div class="space-y-2"><label for="account-new-password" class="ui-label">Password baru (kosongkan jika tidak diganti)</label><input id="account-new-password" name="new_password" x-model="pw" data-testid="account-new-password" :type="show ? 'text' : 'password'" minlength="8" autocomplete="new-password" class="ui-input"></div>
      <div class="space-y-2"><label for="account-confirm-password" class="ui-label">Konfirmasi password baru</label><input id="account-confirm-password" name="new_password_confirmation" x-ref="confirm" data-testid="account-confirm-password" :type="show ? 'text' : 'password'" :required="!!pw" autocomplete="new-password" class="ui-input"></div>
      <div class="flex items-center justify-between gap-4"><span class="text-xs text-sand" data-testid="account-password-hint">Password baru minimal 8 karakter.</span><button type="button" @click="show = !show" class="inline-flex items-center gap-2 text-xs text-sand hover:text-brand" data-testid="account-show-password"><span x-show="!show" class="inline-flex items-center gap-2">{!! icon('Eye') !!} Tampilkan</span><span x-show="show" x-cloak class="inline-flex items-center gap-2">{!! icon('EyeOff') !!} Sembunyikan</span></button></div>
      @if($errors->any())<p role="alert" class="text-sm text-red-600 bg-red-50 p-4 rounded-lg" data-testid="account-error">{{ $errors->first() }}</p>@endif
      <div class="border-t border-ink/10 pt-6 flex flex-wrap justify-between gap-4 items-center"><span class="text-xs text-sand inline-flex items-center gap-2" data-testid="account-security-note">{!! icon('ShieldCheck', 'w-4 h-4 text-sage-700') !!} Password saat ini wajib dikonfirmasi</span><button type="submit" class="btn-brand disabled:opacity-60" data-testid="account-save">{!! icon('Save') !!} Simpan akun</button></div>
    </form>
  </div>

  <form class="bg-white rounded-2xl border border-ink/[0.08] p-6 shadow-soft space-y-5" data-testid="idle-settings-card" x-data="{ value: '{{ $saved }}', saved: {{ $saved }}, busy: false, labels: @js($opts) }" @submit.prevent="busy = true; fetch(@js(route('admin.preferences.update')), { method: 'PUT', headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': window.CSRF }, body: JSON.stringify({ idle_timeout_minutes: Number(value) }) }).then(r => r.ok ? r.json() : Promise.reject()).then(d => { saved = d.idle_timeout_minutes; window.IDLE_MINUTES = saved; window.toast(saved === 0 ? 'Logout otomatis dinonaktifkan.' : 'Logout otomatis diatur ke ' + labels[saved] + '.'); }).catch(() => window.toast('Gagal menyimpan pengaturan', 'error')).finally(() => busy = false)">
    <div class="flex items-start gap-4">
      <span class="w-11 h-11 rounded-2xl bg-brand-50 text-brand flex items-center justify-center shrink-0">{!! icon('TimerOff', 'w-5 h-5') !!}</span>
      <div><h2 class="font-display font-bold text-ink text-lg" data-testid="idle-settings-title">Logout otomatis</h2><p class="text-sm text-sand mt-1" data-testid="idle-settings-description">Admin akan keluar otomatis jika tidak ada interaksi (atau halaman ditutup) selama durasi ini. Peringatan muncul 1 menit sebelum logout.</p></div>
    </div>
    <div class="space-y-2 max-w-sm"><label for="idle-timeout" class="ui-label">Durasi tanpa aktivitas</label><select id="idle-timeout" x-model="value" class="ui-select bg-cream/60" data-testid="idle-timeout-select">@foreach($opts as $v => $l)<option value="{{ $v }}" data-testid="idle-timeout-option-{{ $v }}">{{ $l }}</option>@endforeach</select></div>
    <div class="flex items-center justify-between gap-4 pt-2 border-t border-ink/[0.08]"><span class="text-xs text-sand" data-testid="idle-settings-current">Saat ini: <strong class="text-ink" x-text="labels[saved] || (saved + ' menit')"></strong></span><button type="submit" :disabled="busy || Number(value) === saved" class="btn-brand !py-2.5 !px-5 disabled:opacity-60" data-testid="idle-settings-save">{!! icon('Save') !!} <span x-text="busy ? 'Menyimpan…' : 'Simpan durasi'"></span></button></div>
  </form>
</div>
@endsection
