@props(['image', 'eyebrow' => null, 'eyebrowIcon' => null, 'title', 'titleAccent' => null, 'desc' => null])
<section class="page-hero" style="--hero-img:url('{{ $image }}')" data-testid="page-hero">
  <div class="relative z-10 mx-auto max-w-7xl px-6 lg:px-10 pt-10 md:pt-14 pb-10">
    @if($eyebrow)<div class="inline-flex items-center gap-2 rounded-full bg-white/90 backdrop-blur px-3.5 py-1.5 text-[10px] font-bold tracking-[0.16em] uppercase text-brand shadow-soft">@if($eyebrowIcon){!! icon($eyebrowIcon, 'w-3.5 h-3.5') !!}@endif{{ $eyebrow }}</div>@endif
    <h1 class="font-display font-bold text-ink text-4xl md:text-5xl lg:text-[3.6rem] leading-[1.08] tracking-tight mt-5 max-w-3xl">{{ $title }} @if($titleAccent)<span class="font-serif italic font-medium text-brand">{{ $titleAccent }}</span>@endif</h1>
    @if($desc)<p class="mt-5 text-sand text-base md:text-[17px] leading-relaxed max-w-2xl">{{ $desc }}</p>@endif
    {{ $slot }}
  </div>
</section>
