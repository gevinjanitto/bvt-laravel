@props(['variant' => 'band', 'eyebrow' => 'Stay Connected', 'title' => 'Make Moments That Last', 'desc' => 'Receive holiday inspiration, secret island spots, and exclusive private tour offers directly to your inbox.', 'cta' => 'Subscribe'])
<section class="{{ $variant === 'card' ? 'mx-auto max-w-7xl px-6 lg:px-10 py-10' : '' }}" data-testid="newsletter" x-data="{ email: '', saving: false, async submit() { if (!/^\S+@\S+\.\S+$/.test(this.email)) return toast('Please enter a valid email address', 'error'); this.saving = true; try { const r = await fetch('/newsletter', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }, body: JSON.stringify({ email: this.email }) }); if (!r.ok) throw new Error(); toast('Thank you! Your subscription has been saved.'); this.email = ''; } catch (e) { toast('Subscription could not be saved. Please try again.', 'error'); } this.saving = false; } }">
  <div class="sunset-band grain {{ $variant === 'card' ? 'rounded-[28px]' : '' }}">
    <div class="relative z-10 {{ $variant === 'card' ? 'px-8 md:px-12 py-14' : 'mx-auto max-w-7xl px-6 lg:px-10 py-14' }} grid md:grid-cols-2 gap-10 items-center">
      <div>
        <div class="text-[11px] uppercase tracking-[0.16em] font-bold text-gold-100">{{ $eyebrow }}</div>
        <h3 class="font-display font-bold text-white text-3xl md:text-4xl mt-2 leading-tight">{{ $title }}</h3>
        <p class="text-white/85 mt-3 text-[15px] max-w-lg">{{ $desc }}</p>
      </div>
      <form @submit.prevent="submit" class="flex flex-wrap sm:flex-nowrap items-center bg-white rounded-3xl sm:rounded-full p-1.5 shadow-card max-w-xl md:ml-auto w-full gap-1">
        <input x-model="email" type="email" placeholder="Enter your email address" class="flex-1 min-w-0 basis-full sm:basis-auto bg-transparent px-5 py-3 text-sm outline-none text-ink placeholder:text-ink/40" data-testid="newsletter-email">
        <button type="submit" :disabled="saving" class="btn-brand !py-3 !px-6 disabled:opacity-60 w-full sm:w-auto" data-testid="newsletter-submit" x-text="saving ? '…' : '{{ $cta }}'"></button>
      </form>
    </div>
  </div>
</section>
