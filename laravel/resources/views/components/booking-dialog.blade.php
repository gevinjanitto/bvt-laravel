<div x-data="bookingDialog" x-cloak x-show="open" class="fixed inset-0 z-[90] flex items-end sm:items-center justify-center p-0 sm:p-4" data-testid="booking-dialog" @keydown.escape.window="open = false">
  <div class="absolute inset-0 bg-ink/60 backdrop-blur-sm" @click="open = false"></div>
  <div class="relative w-full sm:max-w-[520px] bg-white rounded-t-3xl sm:rounded-3xl overflow-hidden shadow-card max-h-[92vh] overflow-y-auto" x-show="open" x-transition.scale.origin.bottom>
    <div class="h-1.5 w-full bg-gradient-to-r from-brand-700 via-brand to-gold"></div>
    <div class="p-6 md:p-8">
      <div class="eyebrow">Quick Booking &bull; <span x-text="d.type"></span></div>
      <h3 class="font-display text-2xl text-ink leading-tight mt-1 font-bold" x-text="d.item_name"></h3>
      <p class="text-sand text-sm mt-1">Isi data singkat di bawah, lalu kami arahkan ke WhatsApp dengan detail yang sudah terisi.</p>
      <form @submit.prevent="submit" class="mt-6 space-y-4">
        <div class="grid sm:grid-cols-2 gap-4">
          <div><label class="field-label">Nama Lengkap</label><input x-model="name" placeholder="e.g. Wayan Putra" class="w-full rounded-xl h-11 bg-cream border border-ink/10 px-3 text-sm outline-none focus:border-brand/50" data-testid="booking-name"></div>
          <div><label class="field-label">No. WhatsApp</label><input x-model="phone" placeholder="08xx xxxx xxxx" class="w-full rounded-xl h-11 bg-cream border border-ink/10 px-3 text-sm outline-none focus:border-brand/50" data-testid="booking-phone"></div>
        </div>
        <div class="grid sm:grid-cols-2 gap-4">
          <div><label class="field-label">Tanggal</label><input type="date" x-model="date" :min="today" class="w-full rounded-xl h-11 bg-cream border border-ink/10 px-3 text-sm outline-none focus:border-brand/50" data-testid="booking-date"></div>
          <div><label class="field-label" x-text="d.unit_label === 'Vehicle' ? 'Jumlah Penumpang' : 'Jumlah Peserta'"></label>
            <div class="h-11 rounded-xl bg-cream border border-ink/10 flex items-center justify-between px-2">
              <button type="button" @click="pax = Math.max(1, pax - 1)" class="w-8 h-8 rounded-lg bg-white hover:bg-brand-50 text-brand flex items-center justify-center" data-testid="booking-pax-minus">−</button>
              <span class="font-semibold text-ink" x-text="pax" data-testid="booking-pax"></span>
              <button type="button" @click="pax = Math.min(30, pax + 1)" class="w-8 h-8 rounded-lg bg-white hover:bg-brand-50 text-brand flex items-center justify-center" data-testid="booking-pax-plus">+</button>
            </div>
          </div>
        </div>
        <template x-if="d.option"><div class="rounded-xl bg-brand-50 border border-brand-100 px-4 py-2.5 text-sm text-brand-700 font-medium">Opsi: <span x-text="d.option"></span></div></template>
        <div><label class="field-label">Catatan (opsional)</label><textarea x-model="notes" placeholder="Hotel pickup, permintaan khusus, dll." class="w-full rounded-xl bg-cream border border-ink/10 px-3 py-2 text-sm min-h-[80px] outline-none focus:border-brand/50" data-testid="booking-notes"></textarea></div>
        <template x-if="total() !== null"><div class="flex items-center justify-between rounded-xl bg-cream-100 px-4 py-3"><span class="text-sm text-sand">Estimasi Total</span><span class="font-display font-bold text-brand-700 text-lg" x-text="fmtIDR(total())" data-testid="booking-total"></span></div></template>
        <button type="submit" :disabled="loading" class="btn-brand w-full !py-3.5 !rounded-2xl disabled:opacity-70" data-testid="booking-submit"><i data-lucide="message-circle" class="w-4 h-4"></i> <span x-text="loading ? 'Menyiapkan WhatsApp...' : 'Lanjutkan ke WhatsApp'"></span></button>
        <div class="flex items-center justify-center gap-1.5 text-[11px] text-sand"><i data-lucide="shield-check" class="w-3.5 h-3.5 text-sage-700"></i> Tanpa biaya booking. Konfirmasi instan via WhatsApp concierge.</div>
      </form>
    </div>
  </div>
</div>
<script>
document.addEventListener('alpine:init', () => {
  Alpine.data('bookingDialog', () => ({
    open: false, d: {}, name: '', phone: '', date: '', pax: 2, notes: '', loading: false, today: new Date().toISOString().slice(0, 10),
    init() { window.addEventListener('open-booking', (e) => { this.d = e.detail || {}; this.pax = this.d.pax || 2; this.date = this.d.date || ''; this.open = true; setTimeout(() => lucide.createIcons(), 30); }); },
    total() { if (!this.d.unit_price) return null; return ['Family', 'Vehicle'].includes(this.d.unit_label) ? this.d.unit_price : this.d.unit_price * this.pax; },
    async submit() {
      if (!this.name.trim()) return toast('Mohon isi nama Anda', 'error');
      if (this.phone.replace(/\D/g, '').length < 8) return toast('Mohon isi nomor WhatsApp yang valid', 'error');
      if (!this.date) return toast('Mohon pilih tanggal', 'error');
      this.loading = true;
      const total = this.total();
      const payload = { type: this.d.type, item_id: String(this.d.item_id || ''), item_name: this.d.item_name, name: this.name, phone: this.phone, date: this.date, pax: this.pax, option: this.d.option || null, notes: this.notes, total };
      const lines = [`Halo Bali Vision Tour! Saya ingin booking *${payload.type}*.`, '', `*Item:* ${payload.item_name}`, `*Nama:* ${payload.name}`, `*No. HP/WA:* ${payload.phone}`, `*Tanggal:* ${payload.date}`, `*Jumlah Peserta:* ${payload.pax}`, payload.option ? `*Opsi:* ${payload.option}` : null, total ? `*Estimasi Total:* ${fmtIDR(total)}` : null, payload.notes ? `*Catatan:* ${payload.notes}` : null, '', 'Mohon info ketersediaan & konfirmasi. Terima kasih!'].filter((l) => l !== null);
      try {
        const r = await fetch('/booking', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }, body: JSON.stringify(payload) });
        if (!r.ok) throw new Error();
        toast('Permintaan booking tersimpan. Melanjutkan ke WhatsApp...');
      } catch (e) { toast('Gagal menyimpan booking, tetap melanjutkan ke WhatsApp.', 'error'); }
      window.open(`https://wa.me/${WA_NUMBER}?text=${encodeURIComponent(lines.join('\n'))}`, '_blank', 'noopener');
      this.loading = false; this.open = false; this.name = ''; this.phone = ''; this.notes = '';
    },
  }));
});
</script>
