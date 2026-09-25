<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'Dashboard') — Bali Vision Tour</title>
<link rel="icon" href="{{ site('brand.favicon') ?: '/logo-icon.png' }}">
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Playfair+Display:ital,wght@0,500;1,400;1,500&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<script>
tailwind.config = { theme: { extend: {
  fontFamily: { display: ['Outfit','sans-serif'], body: ['"Plus Jakarta Sans"','sans-serif'], serif: ['"Playfair Display"','serif'] },
  boxShadow: { soft: '0 4px 24px -6px rgba(30,45,39,0.08)', card: '0 10px 40px -12px rgba(30,45,39,0.14)', glow: '0 12px 32px -8px rgba(232,98,44,0.45)' },
  colors: { cream: { DEFAULT: '#FAF7F2', 100: '#F5EFE6', 200: '#EFE6D8' }, ink: '#1E2D27', forest: { DEFAULT: '#1F3B2E', 600: '#2A4A3B', 700: '#183026' },
    brand: { DEFAULT: '#E8622C', 600: '#D4521F', 700: '#B8431A', 800: '#9A3412', 50: '#FDF0EA', 100: '#FBE3D8' }, gold: { DEFAULT: '#D9A441', 100: '#FBF1DC' },
    sage: { DEFAULT: '#DDEDE1', 700: '#2E7D5B' }, sand: '#8A7A66' }
} } };
</script>
<style type="text/tailwindcss">
body { margin:0; background:#FAF7F2; color:#1E2D27; font-family:'Plus Jakarta Sans',sans-serif; -webkit-font-smoothing:antialiased; }
h1,h2,h3,h4,.font-display { font-family:'Outfit',sans-serif; }
::selection { background:#E8622C; color:#fff; }
::-webkit-scrollbar { width:8px; height:8px; } ::-webkit-scrollbar-thumb { background:#D9CFC2; border-radius:8px; }
.admin-scroll::-webkit-scrollbar { width:6px; } .no-scrollbar::-webkit-scrollbar { display:none; } .no-scrollbar { scrollbar-width:none; }
[x-cloak] { display:none !important; }
.eyebrow { font-family:'Plus Jakarta Sans',sans-serif; font-size:11px; letter-spacing:.16em; text-transform:uppercase; font-weight:700; color:#E8622C; }
.grain::after { content:''; position:absolute; inset:0; pointer-events:none; opacity:.06; mix-blend-mode:overlay; background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='160' height='160' viewBox='0 0 160 160'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E"); }
.btn-brand { @apply inline-flex items-center justify-center gap-2 rounded-full bg-brand text-white font-semibold px-6 py-3 text-sm shadow-glow transition-colors duration-200 hover:bg-brand-600 active:scale-[0.98]; }
.btn-forest { @apply inline-flex items-center justify-center gap-2 rounded-full bg-forest text-white font-semibold px-6 py-3 text-sm transition-colors duration-200 hover:bg-forest-700 active:scale-[0.98]; }
.btn-outline { @apply inline-flex items-center justify-center gap-2 rounded-full border border-ink/15 bg-white/70 backdrop-blur text-ink font-semibold px-6 py-3 text-sm transition-colors duration-200 hover:bg-white hover:border-ink/30; }
.lift { transition:transform .35s cubic-bezier(.2,.8,.2,1), box-shadow .35s cubic-bezier(.2,.8,.2,1); } .lift:hover { transform:translateY(-6px); box-shadow:0 24px 48px -16px rgba(30,45,39,.22); }
@keyframes pulse-dot { 0%,100% { transform:scale(1); opacity:1;} 50% { transform:scale(1.6); opacity:.5;} } .pulse-dot { animation:pulse-dot 1.8s ease-in-out infinite; }
@keyframes rise { from { opacity:0; transform:translateY(24px);} to { opacity:1; transform:translateY(0);} } .reveal { animation:rise .6s cubic-bezier(.22,1,.36,1) both; }
.clamp-2 { display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; }
.ui-input { @apply flex h-10 w-full rounded-md border border-[hsl(36_20%_86%)] bg-white px-3 py-2 text-sm text-ink placeholder:text-ink/40 outline-none focus-visible:ring-2 focus-visible:ring-brand/40 disabled:opacity-50; }
.ui-textarea { @apply flex w-full rounded-md border border-[hsl(36_20%_86%)] bg-white px-3 py-2 text-sm text-ink placeholder:text-ink/40 outline-none focus-visible:ring-2 focus-visible:ring-brand/40 min-h-[80px]; }
.ui-select { @apply flex h-10 w-full items-center rounded-md border border-[hsl(36_20%_86%)] bg-white px-3 py-2 text-sm text-ink outline-none focus-visible:ring-2 focus-visible:ring-brand/40 appearance-none bg-no-repeat; background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%231E2D27' stroke-opacity='.5' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E"); background-position:right .6rem center; padding-right:2rem; }
.ui-label { @apply text-sm font-medium leading-none text-ink; }
.field-label { @apply text-[11px] uppercase tracking-wider font-bold text-ink/70; }
.switch { @apply relative inline-flex h-6 w-11 shrink-0 cursor-pointer items-center rounded-full border-2 border-transparent transition-colors bg-[hsl(36_20%_86%)]; } .switch[aria-checked="true"] { @apply bg-brand; }
.switch > span { @apply pointer-events-none block h-5 w-5 rounded-full bg-white shadow-lg ring-0 transition-transform translate-x-0; } .switch[aria-checked="true"] > span { @apply translate-x-5; }
.tab-trigger { @apply inline-flex items-center justify-center whitespace-nowrap rounded-lg px-3 py-1.5 text-xs font-medium text-ink/70 transition-all; } .tab-trigger[aria-selected="true"] { @apply bg-forest text-white shadow-sm; }
.tab-trigger-light { @apply inline-flex items-center justify-center whitespace-nowrap rounded-md px-3 py-1.5 text-sm font-medium text-ink/70 transition-all; } .tab-trigger-light[aria-selected="true"] { @apply bg-cream-100 text-ink shadow-sm; }
.dashed-btn { @apply inline-flex items-center gap-2 rounded-lg border border-dashed border-brand/50 bg-brand-50/50 text-brand-700 text-xs font-semibold px-3 py-2 hover:bg-brand-50 transition-colors disabled:opacity-60; }
.icon-btn { @apply w-8 h-8 rounded-lg flex items-center justify-center transition-colors; }
.status-badge { @apply inline-flex items-center rounded-full border px-2.5 py-0.5 text-[11px] font-bold uppercase tracking-wider; }
.status-new { @apply bg-brand-50 text-brand-700 border-brand-100; } .status-contacted { @apply bg-gold-100 text-gold border-gold/30; } .status-confirmed { @apply bg-sage text-sage-700 border-sage-700/20; } .status-cancelled { @apply bg-cream-200 text-sand border-ink/10; }
.rich-editor { min-height:var(--rich-min,180px); padding:.85rem 1rem; font-size:.9rem; line-height:1.7; outline:none; color:#1E2D27; }
.rich-editor:empty::before { content:attr(data-placeholder); color:rgb(0 0 0/.35); }
.rich-editor > * + * { margin-top:.6em; } .rich-editor h2 { font-family:Outfit,sans-serif; font-weight:700; font-size:1.35em; line-height:1.25; } .rich-editor h3 { font-family:Outfit,sans-serif; font-weight:700; font-size:1.15em; line-height:1.3; }
.rich-editor ul { list-style:disc; padding-left:1.4em; } .rich-editor ol { list-style:decimal; padding-left:1.4em; } .rich-editor li { margin-top:.25em; }
.rich-editor blockquote { border-left:3px solid #E8622C; padding-left:1em; font-style:italic; color:rgb(0 0 0/.65); } .rich-editor hr { border:0; border-top:1px solid rgb(0 0 0/.1); margin:1em 0; } .rich-editor a { color:#E8622C; text-decoration:underline; text-underline-offset:3px; } .rich-editor u { text-decoration:underline; }
@keyframes sheet-in { from { transform:translateX(100%);} to { transform:translateX(0);} } .sheet-panel { animation:sheet-in .4s cubic-bezier(.22,1,.36,1) both; }
@keyframes fade-in { from { opacity:0;} to { opacity:1;} } .fade-in { animation:fade-in .2s ease-out both; }
@keyframes toast-in { from { opacity:0; transform:translateY(-12px) scale(.97);} to { opacity:1; transform:none;} } .toast-item { animation:toast-in .25s ease-out both; }
</style>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="min-h-screen" x-data="{ menu: false }">
@php($user = auth()->user())
@php($links = [['admin.dashboard', 'Dashboard', 'LayoutDashboard', 'admin', []], ['admin.resource.index', 'Tour Packages', 'Map', 'admin/tours', 'tours'], ['admin.resource.index', 'Car Rental', 'Car', 'admin/cars', 'cars'], ['admin.resource.index', 'Activities', 'Compass', 'admin/activities', 'activities'], ['admin.resource.index', 'Articles', 'Newspaper', 'admin/articles', 'articles'], ['admin.bookings', 'Booking Requests', 'Inbox', 'admin/bookings', []], ['admin.settings', 'Kontak & Identitas', 'Settings', 'admin/settings', []], ['admin.content', 'Isi Halaman', 'FilePenLine', 'admin/content', []], ['admin.account', 'Akun Admin', 'ShieldCheck', 'admin/account', []]])
@php($current = collect($links)->first(fn ($l) => $l[3] === 'admin' ? request()->is('admin') : request()->is($l[3] . '*'))[1] ?? 'Dashboard')
<div class="min-h-screen bg-cream flex" data-testid="admin-layout">
  <aside class="hidden lg:block w-64 shrink-0 sticky top-0 h-screen">@include('admin.partials.sidebar')</aside>
  <div x-show="menu" x-cloak class="lg:hidden fixed inset-0 z-50 flex">
    <div class="w-72 h-full shadow-2xl">@include('admin.partials.sidebar')</div>
    <button class="flex-1 bg-ink/40 backdrop-blur-sm" @click="menu = false" aria-label="Close menu"></button>
  </div>
  <div class="flex-1 min-w-0 flex flex-col">
    <header class="sticky top-0 z-40 h-[72px] bg-cream/85 backdrop-blur-md border-b border-ink/5 flex items-center justify-between px-5 md:px-8">
      <div class="flex items-center gap-3">
        <button @click="menu = !menu" class="lg:hidden w-10 h-10 rounded-xl bg-white shadow-soft flex items-center justify-center" aria-label="Menu" data-testid="admin-menu-toggle">{!! icon('Menu', 'w-5 h-5') !!}</button>
        <div><div class="text-[10px] uppercase tracking-[0.16em] font-bold text-brand">Bali Vision Tour</div><div class="font-display font-bold text-ink">{{ $current }}</div></div>
      </div>
      <div class="hidden sm:flex items-center gap-2 text-xs text-sand"><span class="w-2 h-2 rounded-full bg-sage-700 pulse-dot"></span> Live &bull; {{ now()->format('D, d M Y') }}</div>
    </header>
    <main class="flex-1 p-5 md:p-8">
      @if($errors->any())<div class="mb-5 rounded-xl bg-red-50 border border-red-100 text-red-600 text-sm px-4 py-3" data-testid="admin-errors"><ul class="space-y-0.5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
      @yield('content')
    </main>
  </div>
</div>

<div id="toaster" class="fixed top-5 inset-x-0 z-[110] flex flex-col items-center gap-2 px-4 pointer-events-none" data-testid="toaster"></div>

<div id="idle-dialog" class="hidden fixed inset-0 z-[120] bg-ink/60 backdrop-blur-sm items-center justify-center p-4" data-testid="idle-warning-dialog">
  <div class="bg-white rounded-3xl p-6 w-full max-w-lg shadow-2xl fade-in">
    <div class="w-12 h-12 rounded-2xl bg-brand-50 text-brand flex items-center justify-center mb-2">{!! icon('TimerOff', 'w-6 h-6') !!}</div>
    <h2 class="font-display font-bold text-lg text-ink">Sesi akan berakhir</h2>
    <p class="text-sm text-sand mt-2">Tidak ada aktivitas selama <span id="idle-minutes"></span> menit. Anda akan otomatis logout dalam <span class="font-bold text-brand tabular-nums" id="idle-countdown" data-testid="idle-countdown">60</span> detik. Gerakkan mouse atau klik untuk tetap masuk.</p>
    <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-2 mt-6">
      <button type="button" id="idle-stay" class="btn-brand !py-2.5 !px-5" data-testid="idle-stay-button">Tetap masuk</button>
      <button type="button" id="idle-logout-now" class="btn-outline !py-2.5 !px-5" data-testid="idle-logout-now-button">Logout sekarang</button>
    </div>
  </div>
</div>
<form id="logout-form" method="post" action="{{ route('admin.logout') }}" class="hidden">@csrf</form>

<script>
window.CSRF = document.querySelector('meta[name=csrf-token]').content;
window.UPLOAD_URL = @json(route('admin.upload'));
window.IDLE_MINUTES = {{ (int) ($user->idle_timeout_minutes ?? 15) }};
window.FLASH = @json(session('status'));
window.FLASH_ERROR = @json($errors->any() ? $errors->first() : null);
</script>
<script src="/admin.js?v={{ filemtime(public_path('admin.js')) }}"></script>
@stack('scripts')
</body>
</html>
