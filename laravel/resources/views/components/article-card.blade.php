@props(['article'])
<a href="{{ route('articles.show', $article->slug) }}" class="group block bg-white rounded-2xl overflow-hidden shadow-soft lift" data-testid="article-card-{{ $article->slug }}">
  <div class="relative img-zoom overflow-hidden h-52">
    <img src="{{ $article->image }}" alt="{{ $article->title }}" class="w-full h-full object-cover" loading="lazy">
    <div class="absolute top-3 left-3"><x-pill :tone="$article->featured ? 'brand' : 'forest'" :uppercase="true">{{ $article->category }}</x-pill></div>
    <div class="absolute bottom-3 right-3"><x-pill tone="white">{!! icon('Timer', 'w-3 h-3 text-brand') !!} {{ $article->read_time }}</x-pill></div>
  </div>
  <div class="p-5">
    <h3 class="font-display font-bold text-ink text-[17px] leading-snug group-hover:text-brand transition-colors">{{ $article->title }}</h3>
    <p class="text-[13px] text-sand mt-2 leading-relaxed clamp-2">{{ $article->excerpt }}</p>
    <div class="flex items-center justify-between mt-4 pt-4 border-t border-ink/8 text-xs">
      <div class="flex items-center gap-2"><img src="{{ $article->author['avatar'] ?? '' }}" alt="" class="w-7 h-7 rounded-full object-cover"><span class="text-ink/80 font-medium">{{ $article->author['name'] ?? '' }}</span></div>
      <span class="text-sand">{{ fmt_date($article->date) }}</span>
    </div>
  </div>
</a>
