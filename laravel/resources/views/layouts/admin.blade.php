<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'Admin') — Bali Vision Tour</title>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<script>tailwind.config = { theme: { extend: { fontFamily: { display: ['Outfit','sans-serif'], body: ['"Plus Jakarta Sans"','sans-serif'] }, colors: { cream: { DEFAULT: '#FAF7F2', 100: '#F5EFE6', 200: '#EFE6D8' }, ink: '#1E2D27', forest: { DEFAULT: '#1F3B2E', 700: '#183026' }, brand: { DEFAULT: '#E8622C', 600: '#D4521F', 700: '#B8431A', 50: '#FDF0EA', 100: '#FBE3D8' }, sage: { DEFAULT: '#DDEDE1', 700: '#2E7D5B' }, sand: '#8A7A66' } } } };</script>
<style type="text/tailwindcss">
body { font-family:'Plus Jakarta Sans',sans-serif; background:#F5EFE6; color:#1E2D27; } h1,h2,h3,.font-display { font-family:'Outfit',sans-serif; } [x-cloak]{display:none!important}
.input { @apply w-full rounded-xl border border-ink/10 bg-white px-3.5 py-2.5 text-sm outline-none focus:border-brand/60 focus:ring-2 focus:ring-brand/10; }
.label { @apply block text-[11px] uppercase tracking-wider font-bold text-ink/60 mb-1.5; }
.btn { @apply inline-flex items-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold transition-colors; }
.btn-primary { @apply btn bg-brand text-white hover:bg-brand-600; } .btn-secondary { @apply btn bg-white border border-ink/10 text-ink hover:bg-cream; } .btn-danger { @apply btn bg-red-50 text-red-700 hover:bg-red-100; }
.card { @apply bg-white rounded-2xl shadow-[0_4px_24px_-6px_rgba(30,45,39,0.08)] p-6; }
.nav-link { @apply flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-medium text-white/75 hover:bg-white/10 hover:text-white transition-colors; } .nav-link.active { @apply bg-brand text-white; }
</style>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="min-h-screen" x-data="{ menu: false }">
@php($links = [['admin.dashboard', 'Dashboard', 'LayoutDashboard', 'admin'], ['admin.resource.index', 'Tour Packages', 'Map', 'admin/tours', 'tours'], ['admin.resource.index', 'Car Rental', 'Car', 'admin/cars', 'cars'], ['admin.resource.index', 'Activities', 'Compass', 'admin/activities', 'activities'], ['admin.resource.index', 'Articles', 'Newspaper', 'admin/articles', 'articles'], ['admin.bookings', 'Bookings', 'CalendarCheck', 'admin/bookings'], ['admin.settings', 'Pengaturan Website', 'Settings', 'admin/settings'], ['admin.content', 'Konten Halaman', 'LayoutTemplate', 'admin/content'], ['admin.account', 'Akun Admin', 'UserRound', 'admin/account']])
<div class="flex min-h-screen">
  <aside class="fixed inset-y-0 left-0 z-40 w-64 bg-forest text-white p-5 flex flex-col transform transition-transform md:translate-x-0 md:static" :class="menu ? 'translate-x-0' : '-translate-x-full'" data-testid="admin-sidebar">
    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-2"><img src="/logo-icon.png" class="w-10 h-10 object-contain" alt=""><div><div class="font-display font-bold">Bali Vision</div><div class="text-[10px] tracking-[0.14em] text-white/60 font-bold">ADMIN PANEL</div></div></a>
    <nav class="mt-8 space-y-1 flex-1">
      @foreach($links as $l)
        @php($active = $l[3] === 'admin' ? request()->is('admin') : request()->is($l[3] . '*'))
        <a href="{{ route($l[0], $l[4] ?? []) }}" class="nav-link {{ $active ? 'active' : '' }}" data-testid="admin-nav-{{ Str::slug($l[1]) }}">{!! icon($l[2], 'w-4 h-4') !!} {{ $l[1] }}</a>
      @endforeach
    </nav>
    <div class="border-t border-white/10 pt-4 mt-4 flex items-center justify-between"><div class="text-sm"><div class="font-semibold">{{ auth()->user()->name }}</div><div class="text-xs text-white/60">@ {{ auth()->user()->username }}</div></div>
      <form method="post" action="{{ route('admin.logout') }}">@csrf<button class="w-9 h-9 rounded-lg bg-white/10 hover:bg-brand flex items-center justify-center" title="Logout" data-testid="admin-logout">{!! icon('LogOut') !!}</button></form></div>
    <a href="{{ route('home') }}" target="_blank" class="mt-3 text-xs text-white/60 hover:text-white inline-flex items-center gap-1.5">{!! icon('ExternalLink', 'w-3.5 h-3.5') !!} Lihat website</a>
  </aside>
  <div x-show="menu" x-cloak @click="menu = false" class="fixed inset-0 bg-ink/50 z-30 md:hidden"></div>
  <main class="flex-1 min-w-0">
    <div class="md:hidden flex items-center justify-between px-4 py-3 bg-white border-b border-ink/8"><button @click="menu = true" class="p-2" data-testid="admin-menu-toggle">{!! icon('Menu', 'w-5 h-5') !!}</button><span class="font-display font-bold">@yield('title', 'Admin')</span><span class="w-9"></span></div>
    <div class="p-5 md:p-8 max-w-6xl">
      @if(session('status'))<div class="mb-5 rounded-xl bg-sage text-sage-700 px-4 py-3 text-sm font-medium" data-testid="admin-status">{{ session('status') }}</div>@endif
      @if($errors->any())<div class="mb-5 rounded-xl bg-red-50 text-red-700 px-4 py-3 text-sm" data-testid="admin-errors"><ul class="list-disc pl-4 space-y-0.5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
      @yield('content')
    </div>
  </main>
</div>
<script>window.CSRF = document.querySelector('meta[name=csrf-token]').content; document.addEventListener('DOMContentLoaded', () => lucide.createIcons()); document.addEventListener('alpine:initialized', () => setTimeout(() => lucide.createIcons(), 50));</script>
@stack('scripts')
</body>
</html>
