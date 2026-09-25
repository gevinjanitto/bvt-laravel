<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', site('brand.name'))</title>
<link rel="icon" href="{{ site('brand.favicon') ?: '/logo-icon.png' }}">
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Playfair+Display:ital,wght@0,500;1,400;1,500&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<script>
tailwind.config = { blocklist: ['overline'], theme: { extend: {
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
.no-scrollbar::-webkit-scrollbar { display:none; } .no-scrollbar { scrollbar-width:none; }
[x-cloak] { display:none !important; }
img { max-width:100%; }
.eyebrow { font-family:'Plus Jakarta Sans',sans-serif; font-size:11px; letter-spacing:.16em; text-transform:uppercase; font-weight:700; color:#E8622C; }
.grain::after { content:''; position:absolute; inset:0; pointer-events:none; opacity:.06; mix-blend-mode:overlay; background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='160' height='160' viewBox='0 0 160 160'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E"); }
.sunset-band { background:linear-gradient(105deg,#B8431A 0%,#D4521F 45%,#E8763A 100%); position:relative; overflow:hidden; }
.sunset-band::before { content:''; position:absolute; inset:0; background-image:url('https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&w=1600&q=60'); background-size:cover; background-position:center; opacity:.18; mix-blend-mode:luminosity; }
.footer-band { background:linear-gradient(180deg,#5A2A1C 0%,#3E1D13 100%); position:relative; overflow:hidden; }
.footer-band::before { content:''; position:absolute; inset:0; background-image:url('https://images.unsplash.com/photo-1508591086314-d7deb00cede9?auto=format&fit=crop&w=1600&q=60'); background-size:cover; background-position:center; opacity:.22; mix-blend-mode:luminosity; }
.page-hero { position:relative; overflow:hidden; }
.page-hero::before { content:''; position:absolute; inset:0; background-image:var(--hero-img); background-size:cover; background-position:center top; opacity:.35; -webkit-mask-image:linear-gradient(180deg,rgba(0,0,0,.9) 0%,rgba(0,0,0,.7) 60%,transparent 100%); mask-image:linear-gradient(180deg,rgba(0,0,0,.9) 0%,rgba(0,0,0,.7) 60%,transparent 100%); }
.page-hero::after { content:''; position:absolute; inset:0; background:linear-gradient(180deg,rgba(250,247,242,.55) 0%,rgba(250,247,242,.35) 50%,#FAF7F2 100%); }
.btn-brand { @apply inline-flex items-center justify-center gap-2 rounded-full bg-brand text-white font-semibold px-6 py-3 text-sm shadow-glow transition-colors duration-200 hover:bg-brand-600 active:scale-[0.98]; }
.btn-forest { @apply inline-flex items-center justify-center gap-2 rounded-full bg-forest text-white font-semibold px-6 py-3 text-sm transition-colors duration-200 hover:bg-forest-700 active:scale-[0.98]; }
.btn-outline { @apply inline-flex items-center justify-center gap-2 rounded-full border border-ink/15 bg-white/70 backdrop-blur text-ink font-semibold px-6 py-3 text-sm transition-colors duration-200 hover:bg-white hover:border-ink/30; }
.btn-ghost-light { @apply inline-flex items-center justify-center gap-2 rounded-full border border-white/30 bg-white/10 backdrop-blur text-white font-semibold px-6 py-3 text-sm transition-colors duration-200 hover:bg-white/20; }
.lift { transition:transform .35s cubic-bezier(.2,.8,.2,1), box-shadow .35s cubic-bezier(.2,.8,.2,1); } .lift:hover { transform:translateY(-6px); box-shadow:0 24px 48px -16px rgba(30,45,39,.22); }
.img-zoom img { transition:transform .7s cubic-bezier(.2,.8,.2,1); } .img-zoom:hover img { transform:scale(1.06); }
@keyframes marquee { from { transform:translateX(0);} to { transform:translateX(-50%);} } .marquee-slow { animation:marquee 55s linear infinite; } .marquee-slow:hover { animation-play-state:paused; }
@keyframes pre-logo { from { transform:scale(.6) rotate(-20deg); opacity:0; } to { transform:scale(1) rotate(0); opacity:1; } }
@keyframes pre-up { from { transform:translateY(110%); } to { transform:translateY(0); } }
@keyframes pre-bar { from { transform:translateX(-100%); } to { transform:translateX(0); } }
#preloader { position:fixed; inset:0; z-index:100; background:#1F3B2E; color:#fff; display:flex; align-items:center; justify-content:center; transition:transform .8s cubic-bezier(.76,0,.24,1); }
#preloader.is-done { transform:translateY(-100%); }
#preloader .pre-logo { animation:pre-logo .7s cubic-bezier(.22,1,.36,1) both; }
#preloader .pre-brand { animation:pre-up .7s .2s cubic-bezier(.22,1,.36,1) both; }
#preloader .pre-bar { animation:pre-bar .9s ease-in-out both; }
@keyframes pulse-dot { 0%,100% { transform:scale(1); opacity:1;} 50% { transform:scale(1.6); opacity:.5;} } .pulse-dot { animation:pulse-dot 1.8s ease-in-out infinite; }
@keyframes rise { from { opacity:0; transform:translateY(24px);} to { opacity:1; transform:translateY(0);} } .reveal { animation:rise .8s cubic-bezier(.22,1,.36,1) both; }
.clamp-2 { display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; }
.rich-content > * + * { margin-top:.85em; } .rich-content strong { font-weight:700; }
.rich-content h2 { font-family:Outfit,sans-serif; font-weight:700; font-size:1.35em; line-height:1.25; } .rich-content h3 { font-family:Outfit,sans-serif; font-weight:700; font-size:1.15em; }
.rich-content ul { list-style:disc; padding-left:1.4em; } .rich-content ol { list-style:decimal; padding-left:1.4em; } .rich-content li { margin-top:.25em; }
.rich-content blockquote { border-left:3px solid #E8622C; padding-left:1em; font-style:italic; color:rgb(0 0 0/.65); } .rich-content a { color:#E8622C; text-decoration:underline; text-underline-offset:3px; }
.field-label { @apply text-[10px] uppercase tracking-[0.16em] font-bold text-ink/60 mb-2 block; }
.select-input { @apply h-12 w-full rounded-xl bg-cream border border-ink/10 text-sm font-medium px-4 outline-none focus:border-brand/50; }
</style>
<script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="min-h-screen flex flex-col pb-[66px] md:pb-0" x-data="{ scrolled: false }" @scroll.window="scrolled = window.scrollY > 12">
@php($nav = blocks('navigation'))
@php($brand = site('brand'))
<div id="preloader" class="grain" data-testid="preloader">
  <div class="relative z-10 flex flex-col items-center">
    <img src="{{ $brand['logoLight'] ?: ($brand['logo'] ?: '/logo-icon.png') }}" alt="{{ $brand['name'] }}" data-testid="preloader-logo" class="pre-logo w-32 h-16 object-contain">
    <div class="overflow-hidden mt-5"><div class="pre-brand font-display font-bold text-2xl tracking-tight" data-testid="preloader-brand">{{ $brand['name'] }}</div></div>
    <div class="h-px bg-white/30 mt-5 w-40 overflow-hidden"><div class="pre-bar h-full bg-brand"></div></div>
  </div>
</div>
<script>(function(){var p=document.getElementById('preloader'),t0=Date.now(),done=false;function fin(){if(done)return;done=true;var w=Math.max(0,900-(Date.now()-t0));setTimeout(function(){p.classList.add('is-done');setTimeout(function(){p.remove();},850);},w);}window.addEventListener('load',fin);setTimeout(fin,3500);})();</script>
<header class="sticky top-0 z-50 transition-colors duration-300" :class="scrolled ? 'bg-cream/85 backdrop-blur-md border-b border-ink/5 shadow-soft' : 'bg-cream/70 backdrop-blur'" data-testid="navbar">
  <div class="hidden md:flex mx-auto max-w-7xl items-center justify-between px-6 lg:px-10 h-[72px]">
    <x-logo />
    <nav class="flex items-center gap-7">
      @foreach($nav as $l)
        @php($active = $l['to'] === '/' ? request()->is('/') : request()->is(ltrim($l['to'], '/') . '*'))
        <a href="{{ safe_link($l['to']) }}" data-testid="nav-{{ Str::slug($l['label']) }}" class="relative text-[14px] font-medium transition-colors duration-200 pb-1 {{ $active ? 'text-brand after:absolute after:left-0 after:right-0 after:-bottom-0.5 after:h-[2px] after:bg-brand after:rounded-full' : 'text-ink/80 hover:text-brand' }}">{{ $l['label'] }}</a>
      @endforeach
    </nav>
    <a href="{{ wa_url('Halo Bali Vision Tour! Saya ingin bertanya tentang paket & layanan Anda.') }}" target="_blank" rel="noopener" class="btn-brand !py-2.5 !px-5" data-testid="nav-book-now">{!! icon('MessageSquareText') !!} Book Now</a>
  </div>
  <div class="md:hidden flex items-center justify-center h-16"><x-logo size="sm" /></div>
</header>

<main class="flex-1">@yield('content')</main>

@include('partials.footer')

<a href="{{ wa_url() }}" target="_blank" rel="noopener noreferrer" aria-label="Chat WhatsApp" data-testid="floating-whatsapp" class="fixed right-5 bottom-[84px] md:right-7 md:bottom-7 z-40 w-14 h-14 rounded-full bg-brand text-white shadow-card flex items-center justify-center hover:bg-brand-700 hover:-translate-y-1 transition-[background-color,transform] duration-200"><svg viewBox="0 0 448 512" class="w-8 h-8 fill-current" aria-hidden="true"><path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"/></svg></a>

<nav class="md:hidden fixed bottom-0 inset-x-0 z-50 bg-white/95 backdrop-blur border-t border-ink/8 shadow-[0_-8px_30px_-12px_rgba(30,45,39,0.18)]" data-testid="mobile-nav">
  <div class="grid grid-cols-5 h-[66px]">
    @foreach([['Home','/','Home'],['Tours','/tour-packages','Map'],['Cars','/car-rental','Car'],['Activities','/activities','Compass'],['Articles','/articles','Newspaper']] as [$label,$to,$ic])
      @php($active = $to === '/' ? request()->is('/') : request()->is(ltrim($to, '/') . '*'))
      <a href="{{ $to }}" class="flex flex-col items-center justify-center gap-1 text-[10px] font-semibold" data-testid="mobile-nav-{{ strtolower($label) }}">
        <span class="flex items-center justify-center w-10 h-7 rounded-full {{ $active ? 'bg-brand-50 text-brand' : 'text-ink/55' }}">{!! icon($ic, 'w-[18px] h-[18px]') !!}</span>
        <span class="{{ $active ? 'text-brand' : 'text-ink/60' }}">{{ $label }}</span>
      </a>
    @endforeach
  </div>
</nav>

<x-booking-dialog />
<div x-data="toast" x-cloak x-show="show" x-transition class="fixed top-5 inset-x-0 z-[100] flex justify-center px-4" data-testid="toast">
  <div class="rounded-2xl px-5 py-3 text-sm font-medium shadow-card" :class="type === 'error' ? 'bg-red-600 text-white' : 'bg-forest text-white'" x-text="msg"></div>
</div>
<script>
document.addEventListener('alpine:init', () => {
  Alpine.data('toast', () => ({ show: false, msg: '', type: 'success', init() { window.addEventListener('toast', (e) => { this.msg = e.detail.msg; this.type = e.detail.type || 'success'; this.show = true; clearTimeout(this.t); this.t = setTimeout(() => this.show = false, 3200); }); } }));
});
window.toast = (msg, type) => window.dispatchEvent(new CustomEvent('toast', { detail: { msg, type } }));
window.fmtIDR = (n) => 'Rp ' + Math.round(Number(n || 0)).toLocaleString('id-ID');
window.CSRF = document.querySelector('meta[name=csrf-token]').content;
window.WA_NUMBER = @json(site('contact.whatsapp'));
window.openBooking = (detail) => window.dispatchEvent(new CustomEvent('open-booking', { detail }));
document.addEventListener('DOMContentLoaded', () => lucide.createIcons());
document.addEventListener('alpine:initialized', () => setTimeout(() => lucide.createIcons(), 50));
</script>
@stack('scripts')
</body>
</html>
