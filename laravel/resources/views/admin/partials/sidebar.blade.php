<div class="h-full flex flex-col bg-forest text-white relative overflow-y-auto">
  <div class="absolute inset-0 grain pointer-events-none"></div>
  <div class="relative z-10 flex items-center gap-3 px-6 h-[72px] border-b border-white/10"><x-logo :light="true" size="sm" test-id="admin-logo" /></div>
  <nav class="relative z-10 flex-1 px-4 py-6 space-y-1" data-testid="admin-sidebar">
    @foreach($links as $l)
      @php($active = $l[3] === 'admin' ? request()->is('admin') : request()->is($l[3] . '*'))
      <a href="{{ route($l[0], $l[4]) }}" data-testid="admin-nav-{{ Str::slug($l[1]) }}" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium transition-colors duration-200 {{ $active ? 'bg-brand text-white shadow-glow' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">{!! icon($l[2], 'w-[18px] h-[18px]') !!} {{ $l[1] }}</a>
    @endforeach
  </nav>
  <div class="relative z-10 px-4 pb-6 space-y-2">
    <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-3 rounded-xl px-4 py-2.5 text-xs text-white/60 hover:text-white hover:bg-white/10 transition-colors" data-testid="admin-view-site">{!! icon('ExternalLink') !!} View website</a>
    <div class="rounded-2xl bg-white/10 p-4 flex items-center gap-3">
      <span class="w-9 h-9 rounded-full bg-gold text-forest font-bold flex items-center justify-center text-sm">{{ strtoupper(substr($user->name ?: 'A', 0, 1)) }}</span>
      <div class="flex-1 min-w-0"><div class="text-sm font-semibold truncate">{{ $user->name ?: 'Administrator' }}</div><div class="text-[11px] text-white/60">{{ '@' . $user->username }}</div></div>
      <form method="post" action="{{ route('admin.logout') }}">@csrf<button class="w-8 h-8 rounded-lg hover:bg-white/15 flex items-center justify-center text-white/70 hover:text-white transition-colors" aria-label="Logout" data-testid="logout-button">{!! icon('LogOut') !!}</button></form>
    </div>
  </div>
</div>
