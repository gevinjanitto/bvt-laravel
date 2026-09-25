<!DOCTYPE html>
<html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Admin Login — Bali Vision Tour</title>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@600;700&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet"><script src="https://cdn.tailwindcss.com"></script>
<script>tailwind.config = { theme: { extend: { colors: { cream: '#FAF7F2', ink: '#1E2D27', forest: '#1F3B2E', brand: { DEFAULT: '#E8622C', 600: '#D4521F' }, sand: '#8A7A66' } } } };</script>
<style>body{font-family:'Plus Jakarta Sans',sans-serif} h1{font-family:Outfit,sans-serif}</style></head>
<body class="min-h-screen bg-forest flex items-center justify-center p-4" style="background-image:linear-gradient(rgba(31,59,46,.88),rgba(31,59,46,.95)),url('https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&w=1600&q=60');background-size:cover">
<form method="post" action="{{ route('admin.login.post') }}" class="w-full max-w-sm bg-white rounded-3xl p-8 shadow-2xl" data-testid="admin-login-form">@csrf
  <div class="flex items-center gap-3"><img src="/logo-icon.png" class="w-12 h-12 object-contain" alt=""><div><h1 class="text-xl font-bold text-ink">Bali Vision Tour</h1><div class="text-[10px] tracking-[0.16em] font-bold text-brand">ADMIN PANEL</div></div></div>
  @if(session('status'))<div class="mt-5 rounded-xl bg-green-50 text-green-700 px-4 py-2.5 text-sm">{{ session('status') }}</div>@endif
  @error('username')<div class="mt-5 rounded-xl bg-red-50 text-red-700 px-4 py-2.5 text-sm" data-testid="login-error">{{ $message }}</div>@enderror
  <label class="block text-[11px] uppercase tracking-wider font-bold text-ink/60 mt-6 mb-1.5">Username</label><input name="username" value="{{ old('username') }}" autocomplete="username" required class="w-full rounded-xl border border-ink/10 bg-cream px-3.5 py-3 text-sm outline-none focus:border-brand" data-testid="login-username">
  <label class="block text-[11px] uppercase tracking-wider font-bold text-ink/60 mt-4 mb-1.5">Password</label><input type="password" name="password" autocomplete="current-password" required class="w-full rounded-xl border border-ink/10 bg-cream px-3.5 py-3 text-sm outline-none focus:border-brand" data-testid="login-password">
  <button class="mt-6 w-full rounded-xl bg-brand hover:bg-brand-600 text-white font-semibold py-3 text-sm transition-colors" data-testid="login-submit">Masuk</button>
  <a href="/" class="block text-center text-xs text-sand mt-5 hover:text-ink">← Kembali ke website</a>
</form></body></html>
