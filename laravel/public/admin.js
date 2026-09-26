/* Bali Vision Tour admin — vanilla editors mirroring the React admin components. */
(function () {
  const h = (tag, attrs = {}, ...children) => {
    const el = document.createElement(tag);
    for (const [k, v] of Object.entries(attrs)) {
      if (v === null || v === undefined || v === false) continue;
      if (k === 'class') el.className = v;
      else if (k.startsWith('on')) el.addEventListener(k.slice(2), v);
      else if (k === 'html') el.innerHTML = v;
      else el.setAttribute(k, v === true ? '' : v);
    }
    for (const c of children.flat()) if (c !== null && c !== undefined && c !== false) el.append(c instanceof Node ? c : document.createTextNode(String(c)));
    return el;
  };
  const kebab = (n) => n.replace(/([a-z])([A-Z])/g, '$1-$2').replace(/([a-zA-Z])(\d)/g, '$1-$2').toLowerCase();
  const icon = (name, cls = 'w-4 h-4') => h('i', { 'data-lucide': kebab(name), class: cls });
  const refreshIcons = () => window.lucide && window.lucide.createIcons();
  const escapeHtml = (s) => String(s ?? '').replace(/[&<>"']/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));

  /* Toast (sonner-like, top center) */
  window.toast = (msg, type = 'success') => {
    const host = document.getElementById('toaster');
    if (!host) return alert(msg);
    const t = h('div', { class: `toast-item pointer-events-auto rounded-xl px-4 py-3 text-sm font-medium shadow-card border flex items-center gap-2 ${type === 'error' ? 'bg-red-50 text-red-700 border-red-200' : 'bg-[#ecfdf3] text-[#027a48] border-[#abefc6]'}`, 'data-testid': `toast-${type}` }, icon(type === 'error' ? 'CircleAlert' : 'CircleCheck'), msg);
    host.append(t); refreshIcons();
    setTimeout(() => { t.style.transition = 'opacity .3s'; t.style.opacity = '0'; setTimeout(() => t.remove(), 300); }, 3500);
  };
  if (window.FLASH) setTimeout(() => window.toast(window.FLASH), 50);
  if (window.FLASH_ERROR) setTimeout(() => window.toast(window.FLASH_ERROR, 'error'), 50);

  /* Upload */
  const uploadImage = async (file) => {
    const fd = new FormData(); fd.append('file', file);
    const r = await fetch(window.UPLOAD_URL, { method: 'POST', headers: { 'X-CSRF-TOKEN': window.CSRF, Accept: 'application/json' }, body: fd });
    const j = await r.json().catch(() => ({}));
    if (!r.ok) throw new Error(j.message || 'Upload failed');
    return j.url;
  };
  const errMsg = (e, fb) => (e && e.message) || fb;

  /* ImageUpload */
  const ImageUpload = ({ value = '', onChange, compact = false, testId = 'image-upload', recommendation = '1200 × 800 px · rasio 3:2', contain = false }) => {
    let val = value || '';
    const root = h('div', { class: 'space-y-2', 'data-testid': testId });
    const file = h('input', { type: 'file', accept: 'image/jpeg,image/png,image/webp', class: 'hidden', 'data-testid': `${testId}-input` });
    const render = () => {
      root.innerHTML = '';
      const preview = h('div', { class: `relative shrink-0 rounded-xl overflow-hidden bg-cream-100 border border-ink/10 flex items-center justify-center ${compact ? 'w-16 h-16' : 'w-32 h-24'}` },
        val ? h('img', { src: val, alt: 'Pratinjau gambar', 'data-testid': `${testId}-preview`, class: `w-full h-full ${contain ? 'object-contain' : 'object-cover'}` }) : icon('Image', 'w-5 h-5 text-ink/30'),
        val && h('button', { type: 'button', class: 'absolute top-1 right-1 w-5 h-5 rounded-full bg-ink/70 text-white flex items-center justify-center hover:bg-brand', 'data-testid': `${testId}-clear`, onclick: () => set('') }, icon('X', 'w-3 h-3')));
      const btn = h('button', { type: 'button', class: 'dashed-btn', 'data-testid': `${testId}-button`, onclick: () => file.click() }, icon('Upload', 'w-3.5 h-3.5'), ' Upload image');
      const url = h('input', { value: val, placeholder: 'or paste an image URL', class: 'ui-input h-8 text-xs', 'data-testid': `${testId}-url`, oninput: (e) => { val = e.target.value; onChange(val); } });
      root.append(h('div', { class: 'flex gap-3 items-start' }, preview, h('div', { class: 'flex-1 space-y-2 min-w-0' }, btn, url)),
        h('p', { class: 'text-[11px] leading-relaxed text-sand', 'data-testid': `${testId}-guidance` }, `Rekomendasi: ${recommendation}. JPG, PNG, WEBP · maksimal 8 MB per foto · maksimal 40 megapiksel.`), file);
      refreshIcons();
    };
    const set = (v) => { val = v; onChange(val); render(); };
    file.addEventListener('change', async (e) => {
      const f = e.target.files && e.target.files[0]; e.target.value = ''; if (!f) return;
      const btn = root.querySelector(`[data-testid="${testId}-button"]`); btn.disabled = true; btn.innerHTML = ''; btn.append(icon('Loader2', 'w-3.5 h-3.5 animate-spin'), ' Uploading...'); refreshIcons();
      try { set(await uploadImage(f)); window.toast('Image uploaded'); } catch (err) { window.toast(errMsg(err, 'Upload failed'), 'error'); render(); }
    });
    render();
    return root;
  };

  /* GalleryEditor */
  const GalleryEditor = ({ value = [], onChange }) => {
    let items = Array.isArray(value) ? value : [];
    const root = h('div', { class: 'space-y-3', 'data-testid': 'gallery-editor' });
    const file = h('input', { type: 'file', accept: 'image/jpeg,image/png,image/webp', multiple: true, class: 'hidden', 'data-testid': 'gallery-file-input' });
    const emit = () => onChange(items);
    const render = () => {
      root.innerHTML = '';
      root.append(h('div', { class: 'grid sm:grid-cols-2 gap-3' }, items.map((g, i) => h('div', { class: 'flex gap-3 items-center rounded-xl border border-ink/10 bg-white p-2' },
        h('img', { src: g.src || '', alt: '', class: 'w-14 h-14 rounded-lg object-cover bg-cream-100 shrink-0' }),
        h('div', { class: 'flex-1 min-w-0 space-y-2' },
          h('input', { 'data-testid': `gallery-caption-${i}`, value: g.label || '', placeholder: 'Caption', class: 'ui-input h-8 text-xs', oninput: (e) => { g.label = e.target.value; emit(); } }),
          h('input', { 'data-testid': `gallery-url-${i}`, value: g.src || '', placeholder: 'URL gambar', class: 'ui-input h-8 text-xs', oninput: (e) => { g.src = e.target.value; emit(); } })),
        h('button', { type: 'button', 'data-testid': `gallery-remove-${i}`, 'aria-label': `Hapus foto ${i + 1}`, class: 'w-7 h-7 rounded-full hover:bg-red-50 text-red-500 flex items-center justify-center shrink-0', onclick: () => { items.splice(i, 1); emit(); render(); } }, icon('X'))))),
        h('button', { type: 'button', class: 'dashed-btn', 'data-testid': 'gallery-add-button', onclick: () => file.click() }, icon('Upload', 'w-3.5 h-3.5'), ' Add gallery images'),
        h('p', { class: 'text-[11px] text-sand', 'data-testid': 'gallery-upload-guidance' }, '1200 × 800 px (3:2). JPG, PNG, WEBP · maksimal 8 MB per foto · maksimal 20 foto per galeri.'), file);
      refreshIcons();
    };
    file.addEventListener('change', async (e) => {
      const files = Array.from(e.target.files || []); e.target.value = ''; if (!files.length) return;
      if (items.length + files.length > 20) return window.toast('Maksimal 20 foto per galeri', 'error');
      if (files.some((f) => f.size > 8 * 1024 * 1024 || !['image/jpeg', 'image/png', 'image/webp'].includes(f.type))) return window.toast('Gunakan JPG, PNG, WEBP maksimal 8 MB per foto', 'error');
      const btn = root.querySelector('[data-testid="gallery-add-button"]'); btn.disabled = true; btn.innerHTML = ''; btn.append(icon('Loader2', 'w-3.5 h-3.5 animate-spin'), ' Uploading...'); refreshIcons();
      try {
        const urls = await Promise.all(files.map(uploadImage));
        items.push(...urls.map((src, i) => ({ src, label: (files[i].name || 'Photo').replace(/\.[^.]+$/, '').replace(/[-_]/g, ' ') })));
        emit(); window.toast(`${urls.length} image${urls.length > 1 ? 's' : ''} added to gallery`);
      } catch (err) { window.toast(errMsg(err, 'Upload failed'), 'error'); }
      render();
    });
    render();
    return root;
  };

  /* Select helper */
  const Select = ({ value, options, onChange, placeholder = 'Pilih...', testId, cls = '' }) => {
    const sel = h('select', { class: `ui-select ${cls}`, 'data-testid': testId, onchange: (e) => onChange(e.target.value) }, h('option', { value: '' }, placeholder));
    for (const o of options) sel.append(h('option', { value: o, selected: o === value }, o));
    if (value && !options.includes(value)) sel.append(h('option', { value, selected: true }, value));
    return sel;
  };
  const ICON_OPTIONS = ['Star', 'Users', 'UserRound', 'Car', 'Luggage', 'UtensilsCrossed', 'Footprints', 'Waves', 'Ticket', 'Sun', 'Sunrise', 'Moon', 'Landmark', 'Droplets', 'BedDouble', 'Banknote', 'BadgeDollarSign', 'Ship', 'ShieldCheck', 'Shield', 'Leaf', 'TreePine', 'Mountain', 'Map', 'Route', 'Headphones', 'Zap', 'MoveVertical', 'Bike', 'Armchair', 'Activity', 'Wind', 'Thermometer', 'SprayCan', 'Snowflake', 'Smile', 'Shirt', 'Plug', 'Package', 'LifeBuoy', 'Camera', 'Clock', 'Heart', 'Sparkles', 'Coffee', 'Wifi', 'Umbrella', 'Compass', 'Flag', 'Gift'];

  /* ListEditor */
  const ListEditor = ({ field, value = [], onChange }) => {
    let items = Array.isArray(value) ? value : [];
    const base = `list-${field.key}`;
    const root = h('div', { class: 'space-y-3', 'data-testid': base });
    const emit = () => onChange(items);
    const sub = (sf, it, i) => {
      const tid = `${base}-${i}-${sf.key}`;
      const v = it[sf.key];
      switch (sf.type) {
        case 'image': return ImageUpload({ value: v, onChange: (x) => { it[sf.key] = x; emit(); }, testId: tid, compact: true, recommendation: '400 × 400 px · foto profil' });
        case 'textarea': return h('textarea', { class: 'ui-textarea min-h-[64px] text-sm', placeholder: sf.placeholder, 'data-testid': tid, oninput: (e) => { it[sf.key] = e.target.value; emit(); } }, v ?? '');
        case 'lines': return h('textarea', { class: 'ui-textarea min-h-[72px] text-sm', placeholder: sf.placeholder || 'Satu item per baris', 'data-testid': tid, oninput: (e) => { it[sf.key] = e.target.value.split('\n'); emit(); } }, (v || []).join('\n'));
        case 'number': return h('input', { type: 'number', value: v ?? '', class: 'ui-input', placeholder: sf.placeholder, 'data-testid': tid, oninput: (e) => { it[sf.key] = e.target.value === '' ? '' : Number(e.target.value); emit(); } });
        case 'icon': return Select({ value: v || '', options: ICON_OPTIONS, placeholder: 'Pilih ikon', testId: tid, onChange: (x) => { it[sf.key] = x; emit(); } });
        case 'select': return Select({ value: v || '', options: sf.options || [], testId: tid, onChange: (x) => { it[sf.key] = x; emit(); } });
        default: return h('input', { value: v ?? '', class: 'ui-input', placeholder: sf.placeholder, 'data-testid': tid, oninput: (e) => { it[sf.key] = e.target.value; emit(); } });
      }
    };
    const render = () => {
      root.innerHTML = '';
      if (!items.length) root.append(h('div', { class: 'rounded-xl border border-dashed border-ink/15 bg-white/60 px-4 py-5 text-center text-xs text-sand' }, `Belum ada ${field.itemLabel.toLowerCase()}. Klik tombol di bawah untuk menambahkan.`));
      items.forEach((it, i) => root.append(h('div', { class: 'rounded-2xl border border-ink/10 bg-white p-4 shadow-sm', 'data-testid': `${base}-item-${i}` },
        h('div', { class: 'flex items-center justify-between mb-3' },
          h('div', { class: 'text-[11px] font-bold uppercase tracking-wider text-brand' }, `${field.itemLabel} ${i + 1}`),
          h('div', { class: 'flex items-center gap-1' },
            h('button', { type: 'button', disabled: i === 0, class: 'w-7 h-7 rounded-lg hover:bg-cream-100 text-ink/50 disabled:opacity-30 flex items-center justify-center', 'aria-label': 'Naik', onclick: () => { [items[i - 1], items[i]] = [items[i], items[i - 1]]; emit(); render(); } }, icon('ChevronUp')),
            h('button', { type: 'button', disabled: i === items.length - 1, class: 'w-7 h-7 rounded-lg hover:bg-cream-100 text-ink/50 disabled:opacity-30 flex items-center justify-center', 'aria-label': 'Turun', onclick: () => { [items[i + 1], items[i]] = [items[i], items[i + 1]]; emit(); render(); } }, icon('ChevronDown')),
            h('button', { type: 'button', class: 'w-7 h-7 rounded-lg hover:bg-red-50 text-ink/50 hover:text-red-600 flex items-center justify-center', 'aria-label': 'Hapus', 'data-testid': `${base}-remove-${i}`, onclick: () => { items.splice(i, 1); emit(); render(); } }, icon('Trash2')))),
        h('div', { class: 'grid sm:grid-cols-2 gap-3' }, field.schema.map((sf) => h('div', { class: `space-y-1 ${sf.span === 2 || sf.type === 'textarea' || sf.type === 'lines' ? 'sm:col-span-2' : ''}` }, h('label', { class: 'text-[10px] uppercase tracking-wider font-bold text-ink/60' }, sf.label), sub(sf, it, i)))))));
      root.append(h('button', { type: 'button', class: 'dashed-btn', 'data-testid': `${base}-add`, onclick: () => { items.push(Object.fromEntries(field.schema.map((sf) => [sf.key, sf.type === 'lines' ? [] : '']))); emit(); render(); } }, icon('Plus', 'w-3.5 h-3.5'), ` Tambah ${field.itemLabel}`));
      refreshIcons();
    };
    render();
    return root;
  };

  /* RichTextEditor (contenteditable) */
  const RichTextEditor = ({ value = '', onChange, testId = 'rich', placeholder = 'Tulis di sini…', minHeight = 180 }) => {
    const editor = h('div', { class: 'rich-editor', contenteditable: 'true', 'data-testid': testId, 'data-placeholder': placeholder, html: value || '' });
    editor.style.setProperty('--rich-min', `${minHeight}px`);
    const cmd = (c, arg = null) => { editor.focus(); document.execCommand(c, false, arg); update(); };
    const setLink = () => { const url = window.prompt('Masukkan URL link', ''); if (url === null) return; if (!url.trim()) return cmd('unlink'); cmd('createLink', url.trim()); editor.querySelectorAll('a').forEach((a) => { a.target = '_blank'; a.rel = 'noopener noreferrer'; }); update(); };
    const tools = [['bold', 'Bold', 'Tebal', () => cmd('bold')], ['italic', 'Italic', 'Miring', () => cmd('italic')], ['underline', 'Underline', 'Garis bawah', () => cmd('underline')], null,
      ['h2', 'Heading2', 'Subjudul', () => cmd('formatBlock', document.queryCommandValue('formatBlock') === 'h2' ? 'p' : 'h2')], ['h3', 'Heading3', 'Sub-subjudul', () => cmd('formatBlock', document.queryCommandValue('formatBlock') === 'h3' ? 'p' : 'h3')], null,
      ['bullet', 'List', 'Daftar poin', () => cmd('insertUnorderedList')], ['ordered', 'ListOrdered', 'Daftar bernomor', () => cmd('insertOrderedList')], ['quote', 'Quote', 'Kutipan', () => cmd('formatBlock', document.queryCommandValue('formatBlock') === 'blockquote' ? 'p' : 'blockquote')], ['hr', 'Minus', 'Garis pemisah', () => cmd('insertHorizontalRule')], null,
      ['link', 'Link2', 'Tambah link', setLink], ['unlink', 'Unlink', 'Hapus link', () => cmd('unlink')], null,
      ['undo', 'Undo2', 'Undo', () => cmd('undo')], ['redo', 'Redo2', 'Redo', () => cmd('redo')]];
    const toolbar = h('div', { class: 'flex flex-wrap items-center gap-0.5 px-2 py-1.5 border-b border-ink/[0.08] bg-cream/60', 'data-testid': `${testId}-toolbar` }, tools.map((t, i) => t === null ? h('span', { class: 'w-px h-5 bg-ink/10 mx-1' }) : h('button', { type: 'button', title: t[2], 'aria-label': t[2], 'data-testid': `${testId}-${t[0]}`, class: 'w-8 h-8 rounded-lg flex items-center justify-center transition-colors text-ink/70 hover:bg-cream-100', onmousedown: (e) => e.preventDefault(), onclick: t[3] }, icon(t[1]))));
    const isEmpty = (html) => !html || html === '<p></p>' || html === '<br>' || html === '<div><br></div>';
    const update = () => { const html = editor.innerHTML; onChange(isEmpty(html) ? '' : html); };
    editor.addEventListener('input', update);
    editor.addEventListener('focus', () => { if (!editor.innerHTML.trim()) document.execCommand('formatBlock', false, 'p'); });
    return h('div', { class: 'rounded-xl border border-[hsl(36_20%_86%)] bg-white overflow-hidden focus-within:ring-2 focus-within:ring-brand/40', 'data-testid': `${testId}-wrapper` }, toolbar, editor);
  };

  /* ContentTree */
  const labels = { title: 'Judul', name: 'Nama', desc: 'Deskripsi', description: 'Deskripsi', body: 'Isi halaman', text: 'Teks', label: 'Label', image: 'Foto', avatar: 'Foto profil', src: 'Foto', url: 'Link tujuan', to: 'Link tujuan', value: 'Nilai', sub: 'Keterangan', role: 'Jabatan', location: 'Lokasi', meta: 'Keterangan', tag: 'Label kecil', cta: 'Teks tombol', index: 'Nomor urut', icon: 'Ikon', tone: 'Warna', tagStyle: 'Gaya label', route: 'Rute', price: 'Harga (IDR)', initials: 'Inisial', suffix: 'Akhiran', link: 'Teks keterangan', stats: 'Statistik', pillars: 'Prinsip perusahaan', team: 'Tim', sustainability: 'Keberlanjutan', voices: 'Testimoni', services: 'Tautan layanan & kebijakan', payments: 'Metode pembayaran', descriptionTitle: 'Judul deskripsi', menuTitle: 'Judul menu', contactTitle: 'Judul kontak', servicesTitle: 'Judul layanan', paymentTitle: 'Judul pembayaran', privacy: 'Kebijakan privasi', terms: 'Syarat layanan' };
  const imageKeys = /(^|\.)(image|avatar|src|logo|favicon)$/i;
  const emptyLike = (v) => Array.isArray(v) ? [] : v && typeof v === 'object' ? Object.fromEntries(Object.entries(v).map(([k, x]) => [k, ['tone', 'icon', 'tagStyle'].includes(k) ? x : emptyLike(x)])) : typeof v === 'number' ? 0 : typeof v === 'boolean' ? false : '';
  const idFor = (p) => `content-${p.replace(/[^a-zA-Z0-9-]/g, '-')}`;
  const ContentTree = ({ value, onChange, path, template, image = false, recommendation }) => {
    const id = idFor(path);
    if (Array.isArray(value)) {
      const root = h('div', { class: 'space-y-4', 'data-testid': id });
      const render = () => {
        root.innerHTML = '';
        value.forEach((entry, i) => root.append(h('div', { class: 'border-l-2 border-brand/20 pl-4 py-2', 'data-testid': `${id}-item-${i}` },
          h('div', { class: 'flex justify-between items-center mb-4 gap-3' },
            h('h4', { class: 'font-semibold text-sm truncate', 'data-testid': `${id}-item-title-${i}` }, `${i + 1}. ${(entry && (entry.title || entry.name || entry.label)) || 'Item'}`),
            h('div', { class: 'flex gap-1 shrink-0' },
              h('button', { type: 'button', disabled: i === 0, title: 'Naik', 'aria-label': 'Naik', 'data-testid': `${id}-${i}-up`, class: 'p-2 rounded-lg hover:bg-white disabled:opacity-30', onclick: () => { [value[i - 1], value[i]] = [value[i], value[i - 1]]; onChange(value); render(); } }, icon('ArrowUp', 'w-[15px] h-[15px]')),
              h('button', { type: 'button', disabled: i === value.length - 1, title: 'Turun', 'aria-label': 'Turun', 'data-testid': `${id}-${i}-down`, class: 'p-2 rounded-lg hover:bg-white disabled:opacity-30', onclick: () => { [value[i + 1], value[i]] = [value[i], value[i + 1]]; onChange(value); render(); } }, icon('ArrowDown', 'w-[15px] h-[15px]')),
              h('button', { type: 'button', title: 'Hapus item', 'aria-label': 'Hapus item', 'data-testid': `${id}-${i}-remove`, class: 'p-2 rounded-lg text-red-500 hover:bg-red-50', onclick: () => { value.splice(i, 1); onChange(value); render(); } }, icon('Trash2', 'w-[15px] h-[15px]')))),
          ContentTree({ path: `${path}.${i}`, value: entry, template: template && template[0], onChange: (next) => { value[i] = next; onChange(value); } }))));
        root.append(h('button', { type: 'button', class: 'inline-flex gap-2 items-center border border-dashed border-brand/40 text-brand text-sm px-4 py-2 rounded-lg hover:bg-brand-50', 'data-testid': `${id}-add`, onclick: () => { value.push(emptyLike(value[0] ?? (template && template[0]) ?? '')); onChange(value); render(); } }, icon('Plus'), ' Tambah item'));
        refreshIcons();
      };
      render();
      return root;
    }
    if (value && typeof value === 'object') {
      return h('div', { class: 'grid sm:grid-cols-2 gap-4' }, Object.entries(value).map(([key, child]) => h('div', { class: `space-y-2 min-w-0 ${Array.isArray(child) || (child && typeof child === 'object') || ['text', 'desc', 'description', 'body'].includes(key) ? 'sm:col-span-2' : ''}` },
        h('label', { for: idFor(`${path}.${key}`), class: 'text-xs font-semibold text-ink/70', 'data-testid': `${id}-label-${key}` }, labels[key] || key),
        ContentTree({ path: `${path}.${key}`, value: child, template: template && template[key], onChange: (next) => { value[key] = next; onChange(value); } }))));
    }
    if (image || imageKeys.test(path)) return ImageUpload({ value, onChange, testId: id, recommendation: recommendation || (path.includes('avatar') ? '400 × 400 px · foto profil' : '1200 × 800 px · rasio 3:2') });
    if (typeof value === 'boolean') { const b = h('button', { type: 'button', role: 'switch', class: 'switch', 'aria-checked': String(value), 'data-testid': id, onclick: () => { const nv = b.getAttribute('aria-checked') !== 'true'; b.setAttribute('aria-checked', String(nv)); onChange(nv); } }, h('span')); return b; }
    if (typeof value === 'number') return h('input', { id, 'data-testid': id, type: 'number', class: 'ui-input', value, oninput: (e) => onChange(Number(e.target.value)) });
    if (/^article-p-block-\d+\.text$/.test(path)) return RichTextEditor({ value: value || '', onChange, testId: id, minHeight: 140 });
    if (String(value || '').length > 90 || /\.(desc|description|text|body)$/.test(path)) return h('textarea', { id, 'data-testid': id, class: 'ui-textarea min-h-[100px]', oninput: (e) => onChange(e.target.value) }, value || '');
    return h('input', { id, 'data-testid': id, class: 'ui-input', value: value ?? '', oninput: (e) => onChange(e.target.value) });
  };

  /* ArticleBlocksEditor */
  const templates = { p: { label: 'Paragraf', data: { text: '' } }, h2: { label: 'Subjudul', data: { text: '' } }, quote: { label: 'Kutipan', data: { text: '', cite: '' } }, list: { label: 'Daftar poin', data: { items: [''] } }, steps: { label: 'Langkah / itinerary', data: { items: [{ title: '', time: '', tag: '', desc: '' }] } }, gallery: { label: 'Galeri foto', data: { items: [{ src: '', label: '' }] } }, cards: { label: 'Kartu informasi', data: { items: [{ icon: 'Info', title: '', desc: '' }] } } };
  const ArticleBlocksEditor = ({ value = [], onChange }) => {
    const blocks = Array.isArray(value) ? value : [];
    let type = 'p';
    const root = h('div', { class: 'space-y-6', 'data-testid': 'article-block-editor' });
    const render = () => {
      root.innerHTML = '';
      blocks.forEach((block, i) => {
        const data = Object.fromEntries(Object.entries(block).filter(([k]) => k !== 'type'));
        root.append(h('div', { class: 'border-l-2 border-brand/30 pl-4' },
          h('div', { class: 'flex items-center justify-between gap-3 mb-3' },
            h('h4', { class: 'text-xs font-bold text-brand', 'data-testid': `article-block-title-${i}` }, `${i + 1}. ${(templates[block.type] || {}).label || block.type}`),
            h('div', { class: 'flex gap-1' },
              h('button', { type: 'button', disabled: i === 0, 'aria-label': 'Naik', 'data-testid': `article-block-up-${i}`, class: 'p-2 disabled:opacity-30 hover:bg-white', onclick: () => { [blocks[i - 1], blocks[i]] = [blocks[i], blocks[i - 1]]; onChange(blocks); render(); } }, icon('ArrowUp', 'w-[15px] h-[15px]')),
              h('button', { type: 'button', disabled: i === blocks.length - 1, 'aria-label': 'Turun', 'data-testid': `article-block-down-${i}`, class: 'p-2 disabled:opacity-30 hover:bg-white', onclick: () => { [blocks[i + 1], blocks[i]] = [blocks[i], blocks[i + 1]]; onChange(blocks); render(); } }, icon('ArrowDown', 'w-[15px] h-[15px]')),
              h('button', { type: 'button', 'aria-label': 'Hapus bagian', 'data-testid': `article-block-remove-${i}`, class: 'p-2 text-red-500 hover:bg-red-50', onclick: () => { blocks.splice(i, 1); onChange(blocks); render(); } }, icon('Trash2', 'w-[15px] h-[15px]')))),
          ContentTree({ value: data, template: (templates[block.type] || {}).data, path: block.type === 'p' ? `article-p-block-${i}` : `article-block-${i}`, onChange: (v) => { blocks[i] = { type: block.type, ...v }; onChange(blocks); } })));
      });
      const sel = h('select', { class: 'ui-select w-auto', 'data-testid': 'article-new-block-type', onchange: (e) => { type = e.target.value; } }, Object.entries(templates).map(([k, v]) => h('option', { value: k, selected: k === type }, v.label)));
      root.append(h('div', { class: 'flex flex-wrap items-center gap-3' }, sel, h('button', { type: 'button', class: 'inline-flex gap-2 items-center text-brand text-sm font-semibold', 'data-testid': 'article-add-block', onclick: () => { blocks.push({ type, ...JSON.parse(JSON.stringify(templates[type].data)) }); onChange(blocks); render(); } }, icon('Plus'), ' Tambah bagian')));
      refreshIcons();
    };
    render();
    return root;
  };

  /* TextFields (CMS copy catalogue) */
  const roles = { heading: ['Judul', 'bg-ink text-white'], eyebrow: ['Label kecil', 'bg-brand-50 text-brand'], paragraph: ['Paragraf', 'bg-cream-200 text-sand'], button: ['Tombol / link', 'bg-forest/10 text-forest'], bold: ['Teks tebal', 'bg-ink/10 text-ink'], italic: ['Teks miring (font serif)', 'bg-gold/20 text-brand-800'], accent: ['Teks aksen (warna brand)', 'bg-brand text-white'], placeholder: ['Placeholder kolom', 'bg-cream-200 text-sand'], alt: ['Teks alternatif foto', 'bg-cream-200 text-sand'], label: ['Label', 'bg-cream-200 text-sand'], text: ['Teks', 'bg-cream-200 text-sand'] };
  const TextField = ({ field: f, texts, index, rerender }) => {
    const [label, tone] = roles[f.role] || roles.text;
    const modified = Object.prototype.hasOwnProperty.call(texts, f.key);
    const current = modified ? texts[f.key] : f.default;
    const long = f.default.length > 90 || f.role === 'paragraph';
    const input = h(long ? 'textarea' : 'input', { id: `cms-${f.key}`, 'data-testid': `cms-input-${f.key}`, class: `${long ? 'ui-textarea min-h-[96px]' : 'ui-input'} bg-cream/60 ${f.role === 'heading' ? 'font-display font-bold text-base' : ''} ${f.role === 'italic' ? 'italic font-serif' : ''}`, oninput: (e) => { texts[f.key] = e.target.value; badge.style.display = ''; reset.disabled = false; } });
    if (long) input.textContent = current; else input.value = current;
    const badge = h('span', { class: 'text-[10px] font-semibold text-brand', 'data-testid': `cms-modified-${f.key}`, style: modified ? '' : 'display:none' }, '• diubah');
    const reset = h('button', { type: 'button', title: 'Kembalikan teks awal', 'aria-label': 'Kembalikan teks awal', disabled: !modified, class: 'p-1.5 rounded-lg hover:bg-cream text-sand disabled:opacity-30 shrink-0', 'data-testid': `cms-reset-${f.key}`, onclick: () => { delete texts[f.key]; rerender(); } }, icon('RotateCcw', 'w-3.5 h-3.5'));
    return h('div', { class: 'bg-white rounded-2xl border border-ink/[0.08] p-4 shadow-soft', 'data-testid': `cms-field-${f.key}` },
      h('div', { class: 'flex items-center justify-between gap-3 mb-3' }, h('div', { class: 'flex items-center gap-2 min-w-0' }, h('span', { class: 'text-xs text-sand font-semibold shrink-0' }, `${index}.`), h('span', { class: `text-[10px] uppercase tracking-[0.12em] font-bold px-2 py-1 rounded-full ${tone}`, 'data-testid': `cms-role-${f.key}` }, label), badge), reset),
      input,
      /\{\d+\}/.test(f.default) && h('p', { class: 'text-[11px] text-sand mt-2 leading-relaxed', 'data-testid': `cms-hint-${f.key}`, html: 'Kode <code class="bg-cream px-1 rounded">{1}</code>, <code class="bg-cream px-1 rounded">{2}</code>… adalah bagian otomatis (angka, ikon, atau teks berformat khusus yang bisa diubah di kolom berikutnya). Biarkan kode itu tetap ada, cukup ubah kalimat di sekitarnya.' }));
  };

  /* Hydration */
  const hydrateEditors = (scope = document) => {
    scope.querySelectorAll('[data-editor]').forEach((node) => {
      if (node.dataset.hydrated) return; node.dataset.hydrated = '1';
      const name = node.dataset.name; const type = node.dataset.editor; const tid = node.dataset.testidBase;
      const hidden = h('input', { type: 'hidden', name, value: node.dataset.value || '' });
      const parse = () => { try { return JSON.parse(node.dataset.value || '[]'); } catch (e) { return []; } };
      const setJson = (v) => { hidden.value = JSON.stringify(v); };
      let el;
      if (type === 'image') el = ImageUpload({ value: node.dataset.value, onChange: (v) => { hidden.value = v; }, testId: tid || 'image-upload', recommendation: node.dataset.recommendation, contain: !!node.dataset.contain, compact: !!node.dataset.compact });
      else if (type === 'rich') el = RichTextEditor({ value: node.dataset.value, onChange: (v) => { hidden.value = v; }, testId: tid || 'rich' });
      else if (type === 'gallery') { const v = parse(); setJson(v); el = GalleryEditor({ value: v, onChange: setJson }); }
      else if (type === 'list') { const v = parse(); setJson(v); el = ListEditor({ field: { key: name, itemLabel: node.dataset.itemLabel || 'Item', schema: JSON.parse(node.dataset.schema || '[]') }, value: v, onChange: setJson }); }
      else if (type === 'blocks') { const v = parse(); setJson(v); el = ArticleBlocksEditor({ value: v, onChange: setJson }); }
      if (el) node.replaceWith(h('div', {}, hidden, el));
    });
    scope.querySelectorAll('[data-switch]').forEach((b) => {
      if (b.dataset.hydrated) return; b.dataset.hydrated = '1';
      b.addEventListener('click', () => { const nv = b.getAttribute('aria-checked') !== 'true'; b.setAttribute('aria-checked', String(nv)); const hid = b.previousElementSibling; if (hid) hid.value = nv ? '1' : '0'; });
    });
    scope.querySelectorAll('[data-booking-status]').forEach((sel) => {
      if (sel.dataset.hydrated) return; sel.dataset.hydrated = '1';
      sel.addEventListener('change', async () => {
        const r = await fetch(sel.dataset.bookingStatus, { method: 'PATCH', headers: { 'Content-Type': 'application/json', Accept: 'application/json', 'X-CSRF-TOKEN': window.CSRF }, body: JSON.stringify({ status: sel.value }) });
        window.toast(r.ok ? 'Status updated' : 'Failed to update status', r.ok ? 'success' : 'error');
      });
    });
    refreshIcons();
  };

  /* Resource form: required-field check + busy state */
  document.querySelectorAll('[data-resource-form]').forEach((form) => form.addEventListener('submit', (e) => {
    for (const lbl of form.querySelectorAll('label.field-label')) {
      if (!lbl.querySelector('.text-brand')) continue;
      const wrap = lbl.parentElement; const inp = wrap.querySelector('input:not([type=hidden]), textarea, select');
      if (inp && !String(inp.value || '').trim()) { e.preventDefault(); const tab = wrap.closest('[role=tabpanel]'); if (tab && tab.style.display === 'none') { const idx = Array.from(form.querySelectorAll('[role=tabpanel]')).indexOf(tab); form.querySelectorAll('[role=tab]')[idx].click(); } window.toast(`${lbl.textContent.replace('*', '').trim()} is required`, 'error'); inp.focus(); return; }
    }
    const btn = form.querySelector('[data-testid="form-submit"]'); if (btn) { btn.disabled = true; btn.innerHTML = ''; btn.append(icon('Loader2', 'w-4 h-4 animate-spin'), ' Saving…'); refreshIcons(); }
  }));

  /* Content page */
  const initContent = () => {
    const C = window.CMS; if (!C) return;
    const texts = C.texts; const blocks = C.blocks;
    const textsInput = document.getElementById('texts-input'); const blocksInput = document.getElementById('blocks-input');
    const form = document.getElementById('content-form');
    form.addEventListener('submit', () => { textsInput.value = JSON.stringify(texts); const { company, ...rest } = blocks; blocksInput.value = JSON.stringify(rest); const b = form.querySelector('[data-testid="content-save"]'); b.disabled = true; });
    const pageSel = document.getElementById('content-page-select'); const search = document.getElementById('content-search');
    const sections = document.getElementById('cms-sections'); const count = document.getElementById('content-field-count');
    const renderTexts = () => {
      const scope = pageSel.value; const q = search.value.toLowerCase();
      const visible = C.catalog.filter((f) => f.scope === scope && `${f.default} ${texts[f.key] || ''}`.toLowerCase().includes(q));
      count.textContent = `${visible.length} teks di ${C.scopes[scope] || scope}, dikelompokkan per bagian halaman dari atas ke bawah.`;
      sections.innerHTML = '';
      const groups = new Map();
      visible.forEach((f) => { if (!groups.has(f.section)) groups.set(f.section, { label: f.sectionLabel, items: [] }); groups.get(f.section).items.push(f); });
      let n = 0;
      for (const [section, g] of groups) {
        const sec = h('section', { class: 'space-y-3', 'data-testid': `cms-section-${section}` },
          h('div', { class: 'flex items-center gap-3 pt-2' }, h('span', { class: 'eyebrow' }, section ? `Bagian ${section}` : 'Umum'), h('span', { class: 'h-px flex-1 bg-ink/10' }), h('span', { class: 'text-xs text-sand' }, `${g.items.length} teks`)),
          g.label && h('h3', { class: 'font-display font-bold text-ink text-lg leading-tight', 'data-testid': `cms-section-title-${section}` }, g.label));
        g.items.forEach((f) => sec.append(TextField({ field: f, texts, index: ++n, rerender: renderTexts })));
        sections.append(sec);
      }
      refreshIcons();
    };
    pageSel.addEventListener('change', () => { search.value = ''; renderTexts(); });
    search.addEventListener('input', renderTexts);
    renderTexts();
    const blockSel = document.getElementById('content-block-select'); const tree = document.getElementById('block-tree');
    const renderBlock = () => { const k = blockSel.value; tree.innerHTML = ''; tree.append(ContentTree({ path: k, value: blocks[k], template: C.defaults[k], onChange: (v) => { blocks[k] = v; } })); refreshIcons(); };
    blockSel.addEventListener('change', renderBlock); renderBlock();
    const grid = document.getElementById('image-grid');
    Object.entries(blocks.images || {}).forEach(([key, value]) => grid.append(h('div', { class: 'space-y-3 border-b border-ink/10 pb-6 min-w-0' },
      h('h3', { class: 'text-sm font-semibold', 'data-testid': `content-image-title-${key}` }, C.imageLabels[key] || `Foto · ${key}`),
      ContentTree({ image: true, path: `images.${key}`, value, recommendation: key.startsWith('av') ? '400 × 400 px' : key.startsWith('staff') ? '800 × 1000 px' : ['hero', 'lempuyang2', 'batur', 'bedugul', 'agung'].includes(key) ? '1920 × 1080 px · rasio 16:9' : '1200 × 800 px · rasio 3:2', onChange: (v) => { blocks.images[key] = v; } }))));
    refreshIcons();
  };

  /* Idle logout */
  const initIdle = () => {
    const KEY = 'bvt_admin_last_active'; const WARN = 60 * 1000;
    const dialog = document.getElementById('idle-dialog'); if (!dialog) return;
    const logout = () => { localStorage.removeItem(KEY); document.getElementById('logout-form').submit(); };
    const limit = () => (Number(window.IDLE_MINUTES) || 0) * 60 * 1000;
    if (limit()) { const stored = Number(localStorage.getItem(KEY)); if (stored && Date.now() - stored >= limit()) return logout(); }
    localStorage.setItem(KEY, String(Date.now()));
    let lastWrite = 0;
    const touch = () => { const now = Date.now(); if (now - lastWrite < 1000) return; lastWrite = now; localStorage.setItem(KEY, String(now)); dialog.classList.add('hidden'); dialog.classList.remove('flex'); };
    ['mousemove', 'mousedown', 'keydown', 'scroll', 'touchstart', 'click'].forEach((ev) => window.addEventListener(ev, touch, { passive: true }));
    setInterval(() => {
      const l = limit(); if (!l) return;
      const remaining = l - (Date.now() - Number(localStorage.getItem(KEY) || Date.now()));
      if (remaining <= 0) return logout();
      if (remaining <= WARN) { document.getElementById('idle-minutes').textContent = Math.max(window.IDLE_MINUTES - 1, 0); document.getElementById('idle-countdown').textContent = Math.ceil(remaining / 1000); dialog.classList.remove('hidden'); dialog.classList.add('flex'); }
    }, 1000);
    document.getElementById('idle-stay').addEventListener('click', (e) => { e.stopPropagation(); lastWrite = 0; touch(); });
    document.getElementById('idle-logout-now').addEventListener('click', logout);
  };

  document.addEventListener('DOMContentLoaded', () => { hydrateEditors(); initContent(); initIdle(); refreshIcons(); });
  window.BVT = { ImageUpload, GalleryEditor, ListEditor, ContentTree, RichTextEditor, hydrateEditors };
})();
