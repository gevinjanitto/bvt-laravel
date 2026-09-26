@props(['eyebrow' => null, 'title', 'desc' => null, 'align' => 'left', 'titleClass' => ''])
<div {{ $attributes->merge(['class' => $align === 'center' ? 'text-center mx-auto max-w-2xl' : '']) }}>
  @if($eyebrow)<div class="eyebrow mb-3">{{ $eyebrow }}</div>@endif
  <h2 class="font-display font-bold text-ink text-3xl md:text-4xl lg:text-[2.6rem] leading-[1.1] tracking-tight {{ $titleClass }}">{{ $title }}</h2>
  @if($desc)<p class="mt-4 text-sand text-[15px] leading-relaxed {{ $align === 'center' ? 'mx-auto max-w-xl' : 'max-w-xl' }}">{{ $desc }}</p>@endif
</div>
