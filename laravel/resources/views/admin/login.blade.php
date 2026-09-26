<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Admin Login — Bali Vision Tour</title>
<link rel="icon" href="{{ site('brand.favicon') ?: '/logo-icon.png' }}">
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<script>tailwind.config = { theme: { extend: { fontFamily: { display: ['Outfit','sans-serif'] }, boxShadow: { glow: '0 12px 32px -8px rgba(232,98,44,0.45)' }, colors: { cream: { DEFAULT: '#FAF7F2', 100: '#F5EFE6' }, ink: '#1E2D27', forest: { DEFAULT: '#1F3B2E', 700: '#183026' }, brand: { DEFAULT: '#E8622C', 600: '#D4521F' }, gold: { DEFAULT: '#D9A441' }, sand: '#8A7A66' } } } };</script>
<style type="text/tailwindcss">
body{font-family:'Plus Jakarta Sans',sans-serif;-webkit-font-smoothing:antialiased} h1,h2,.font-display{font-family:Outfit,sans-serif}
.grain::after { content:''; position:absolute; inset:0; pointer-events:none; opacity:.06; mix-blend-mode:overlay; background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='160' height='160' viewBox='0 0 160 160'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E"); }
.btn-brand { @apply inline-flex items-center justify-center gap-2 rounded-full bg-brand text-white font-semibold px-6 py-3 text-sm shadow-glow transition-colors duration-200 hover:bg-brand-600 active:scale-[0.98]; }
.ui-input { @apply flex h-12 w-full rounded-xl border border-[hsl(36_20%_86%)] bg-white pl-10 pr-3 py-2 text-sm text-ink placeholder:text-ink/40 outline-none focus-visible:ring-2 focus-visible:ring-brand/40; }
@keyframes rise { from { opacity:0; transform:translateY(24px);} to { opacity:1; transform:translateY(0);} } .reveal { animation:rise .6s cubic-bezier(.22,1,.36,1) both; }
@keyframes spin { to { transform:rotate(360deg);} } .animate-spin { animation:spin 1s linear infinite; }
</style>
<script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-cream">
<div class="min-h-screen grid lg:grid-cols-2 bg-cream" data-testid="admin-login-page">
  <div class="relative hidden lg:block overflow-hidden">
    <img src="{{ img('hero') }}" alt="Bali" class="absolute inset-0 w-full h-full object-cover">
    <div class="absolute inset-0 bg-gradient-to-t from-forest via-forest/70 to-forest/30"></div>
    <div class="absolute inset-0 grain"></div>
    <div class="relative z-10 h-full flex flex-col justify-between p-12 text-white">
      <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-sm text-white/80 hover:text-white transition-colors w-fit" data-testid="login-back-home"><i data-lucide="arrow-left" class="w-4 h-4"></i> Back to website</a>
      <div>
        <div class="text-[11px] uppercase tracking-[0.2em] font-bold text-gold">Bali Vision Tour &bull; Control Room</div>
        <h2 class="font-display font-bold text-4xl xl:text-5xl leading-[1.05] mt-4 max-w-md">Curate every journey from one place.</h2>
        <p class="text-white/75 mt-4 max-w-sm text-sm leading-relaxed">Manage tour packages, fleet, activities, journal articles and incoming WhatsApp booking requests.</p>
      </div>
    </div>
  </div>
  <div class="flex items-center justify-center p-6 md:p-12">
    <div class="w-full max-w-md reveal">
      <div class="flex items-center gap-3"><x-logo test-id="admin-login-logo" /></div>
      <h1 class="font-display font-bold text-ink text-3xl mt-10">Welcome back</h1>
      <p class="text-sand text-sm mt-2">Sign in with your administrator credentials.</p>
      <form method="post" action="{{ route('admin.login.post') }}" class="mt-8 space-y-5" data-testid="admin-login-form" onsubmit="this.querySelector('button[type=submit]').disabled=true;this.querySelector('[data-spin]').classList.remove('hidden');this.querySelector('[data-idle]').classList.add('hidden')">@csrf
        @if(session('status'))<div class="rounded-xl bg-green-50 border border-green-100 text-green-700 text-sm px-4 py-3" data-testid="login-status">{{ session('status') }}</div>@endif
        <div class="space-y-1.5">
          <label for="username" class="text-[11px] uppercase tracking-wider font-bold text-ink/70">Username</label>
          <div class="relative"><i data-lucide="user" class="w-4 h-4 text-ink/40 absolute left-3.5 top-1/2 -translate-y-1/2"></i><input id="username" name="username" value="{{ old('username') }}" autocomplete="username" class="ui-input" placeholder="admin" data-testid="login-username-input"></div>
        </div>
        <div class="space-y-1.5">
          <label for="password" class="text-[11px] uppercase tracking-wider font-bold text-ink/70">Password</label>
          <div class="relative"><i data-lucide="lock" class="w-4 h-4 text-ink/40 absolute left-3.5 top-1/2 -translate-y-1/2"></i><input id="password" type="password" name="password" autocomplete="current-password" class="ui-input" placeholder="••••••" data-testid="login-password-input"></div>
        </div>
        @if($errors->any())<div class="rounded-xl bg-red-50 border border-red-100 text-red-600 text-sm px-4 py-3" data-testid="login-error">{{ $errors->first() }}</div>@endif
        <button type="submit" class="btn-brand w-full !py-3.5 !rounded-xl disabled:opacity-60" data-testid="login-submit-button"><span data-idle class="inline-flex items-center gap-2">Sign in <i data-lucide="arrow-right" class="w-4 h-4"></i></span><i data-spin data-lucide="loader-2" class="w-4 h-4 animate-spin hidden"></i></button>
      </form>
    </div>
  </div>
</div>
<script>lucide.createIcons();</script>
</body>
</html>
