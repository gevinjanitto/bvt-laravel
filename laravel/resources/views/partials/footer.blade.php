@php($F = blocks('footer'))
@php($contact = site('contact'))
@php($social = site('social'))
@php($brand = site('brand'))
<footer class="footer-band grain text-white" data-testid="footer">
  <div class="relative z-10 mx-auto max-w-7xl px-6 lg:px-10 pt-16 pb-8">
    <div class="grid md:grid-cols-2 lg:grid-cols-12 gap-10">
      <div class="lg:col-span-4"><x-logo :light="true" size="lg" test-id="footer-logo" />
        <p class="mt-6 text-white/75 text-sm leading-relaxed max-w-sm" data-testid="footer-description">{{ $F['description'] }}</p>
        <div class="flex items-center gap-3 mt-6">
          @foreach([['instagram','Instagram'],['facebook','Facebook'],['youtube','Youtube'],['tiktok','Music2']] as [$key,$ic])
            @if(!empty($social[$key]))<a href="{{ safe_link($social[$key]) }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $key }}" data-testid="footer-social-{{ $key }}" class="w-9 h-9 rounded-full bg-white/10 hover:bg-brand flex items-center justify-center transition-colors">{!! icon($ic) !!}</a>
            @else<span title="{{ $key }} belum diatur" data-testid="footer-social-{{ $key }}-unset" class="w-9 h-9 rounded-full bg-white/10 text-white/50 flex items-center justify-center">{!! icon($ic) !!}</span>@endif
          @endforeach
        </div>
      </div>
      <div class="lg:col-span-2"><h3 class="text-xs tracking-widest font-bold uppercase mb-5">{{ $F['menuTitle'] }}</h3><ul class="space-y-2.5 text-sm text-white/75">@foreach(blocks('navigation') as $i => $l)<li><a href="{{ safe_link($l['to']) }}" data-testid="footer-menu-{{ $i }}" class="hover:text-white transition-colors">{{ $l['label'] }}</a></li>@endforeach</ul></div>
      <div class="lg:col-span-3"><h3 class="text-xs tracking-widest font-bold uppercase mb-5">{{ $F['servicesTitle'] }}</h3><ul class="space-y-2.5 text-sm text-white/75">@foreach($F['services'] as $i => $l)<li><a href="{{ safe_link($l['url']) }}" data-testid="footer-service-{{ $i }}" class="hover:text-white transition-colors">{{ $l['label'] }}</a></li>@endforeach</ul></div>
      <div class="lg:col-span-3 min-w-0"><h3 class="text-xs tracking-widest font-bold uppercase mb-5">{{ $F['contactTitle'] }}</h3><p class="text-sm font-semibold" data-testid="footer-legal">{{ $brand['legal'] }}</p>
        <ul class="space-y-3 text-sm text-white/75 mt-4">
          <li><a href="{{ wa_url() }}" target="_blank" rel="noopener" data-testid="footer-contact-whatsapp" class="flex items-start gap-2 hover:text-white transition-colors">{!! icon('Phone', 'w-4 h-4 text-brand shrink-0 mt-0.5') !!}<span class="break-words min-w-0">+{{ $contact['whatsapp'] }}</span></a></li>
          <li><a href="{{ safe_link($contact['emailLink'] ?: 'mailto:' . $contact['email']) }}" data-testid="footer-contact-email" class="flex items-start gap-2 hover:text-white transition-colors">{!! icon('Mail', 'w-4 h-4 text-brand shrink-0 mt-0.5') !!}<span class="break-words min-w-0">{{ $contact['email'] }}</span></a></li>
          <li><a href="{{ safe_link($contact['addressLink'] ?: 'https://www.google.com/maps/search/?api=1&query=' . urlencode($contact['address'])) }}" target="_blank" rel="noopener" data-testid="footer-contact-address" class="flex items-start gap-2 hover:text-white transition-colors">{!! icon('MapPin', 'w-4 h-4 text-brand shrink-0 mt-0.5') !!}<span class="break-words min-w-0">{{ $contact['address'] }}</span></a></li>
        </ul>
      </div>
    </div>
    <div class="mt-14 pt-6 border-t border-white/15 flex flex-col md:flex-row md:items-center md:justify-between gap-4 text-xs text-white/60">
      <div class="flex flex-wrap items-center gap-x-3 gap-y-1">
        <p data-testid="footer-copyright">© {{ date('Y') }} {{ $brand['name'] }}. All rights reserved. | Design &amp; Develop <a href="https://www.maiharta.com" target="_blank" rel="noopener noreferrer" class="text-white/85 hover:text-white hover:underline">CV Maiharta</a></p>
        <span class="text-white/30">|</span>
        <a href="{{ route('admin.login') }}" class="hover:text-white transition-colors" data-testid="footer-admin-link">Admin</a>
      </div>
      <div class="flex flex-wrap items-center gap-2"><span>{{ $F['paymentTitle'] }}</span>@foreach($F['payments'] as $p)<span class="rounded-md bg-white/10 px-2.5 py-1 text-[10px] font-bold text-white/85">{{ $p }}</span>@endforeach</div>
    </div>
  </div>
</footer>
