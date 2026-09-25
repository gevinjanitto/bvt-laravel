@extends('layouts.admin')
@section('title', 'Akun Admin')
@section('content')
<div data-testid="admin-account" class="grid lg:grid-cols-2 gap-5">
<form method="post" action="{{ route('admin.account.update') }}" class="card space-y-4">@csrf @method('PUT')
  <h1 class="text-xl font-bold">Akun Admin</h1><p class="text-sm text-sand">Ubah username atau password. Anda akan diminta login ulang setelah menyimpan.</p>
  <div><label class="label">Username</label><input name="username" value="{{ old('username', $user->username) }}" class="input" data-testid="account-username"></div>
  <div><label class="label">Password saat ini</label><input type="password" name="current_password" class="input" required data-testid="account-current-password"></div>
  <div><label class="label">Password baru (opsional, min. 8)</label><input type="password" name="new_password" class="input" data-testid="account-new-password"></div>
  <div><label class="label">Konfirmasi password baru</label><input type="password" name="new_password_confirmation" class="input" data-testid="account-new-password-confirm"></div>
  <button class="btn-primary" data-testid="account-save">Simpan akun</button>
</form>
<form method="post" action="{{ route('admin.preferences.update') }}" class="card space-y-4 self-start">@csrf @method('PUT')
  <h2 class="text-xl font-bold">Logout Otomatis</h2><p class="text-sm text-sand">Durasi tanpa aktivitas sebelum sesi admin keluar otomatis.</p>
  <select name="idle_timeout_minutes" class="input" data-testid="idle-timeout">@foreach([0 => 'Nonaktif', 5 => '5 menit', 10 => '10 menit', 15 => '15 menit', 30 => '30 menit', 60 => '1 jam', 120 => '2 jam'] as $v => $l)<option value="{{ $v }}" @selected($user->idle_timeout_minutes == $v)>{{ $l }}</option>@endforeach</select>
  <button class="btn-secondary" data-testid="idle-save">Simpan durasi</button>
</form>
</div>
@endsection
