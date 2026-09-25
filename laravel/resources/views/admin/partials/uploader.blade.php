@push('scripts')
<script>
function uploader() { return { async upload(e, targetId) { const f = e.target.files[0]; if (!f) return; const fd = new FormData(); fd.append('file', f); const r = await fetch('{{ route('admin.upload') }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }, body: fd }); const j = await r.json(); if (!r.ok) return alert(j.message || 'Upload gagal'); const el = document.getElementById(targetId); el.value = j.url; el.dispatchEvent(new Event('input')); } } }
</script>
@endpush
