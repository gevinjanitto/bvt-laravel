<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class ResourceController extends Controller
{
    private function cfg(string $resource): array
    {
        $cfg = config("resources.$resource");
        abort_unless($cfg, 404);
        return $cfg + ['key' => $resource];
    }

    private function fields(array $cfg): array
    {
        return array_merge(...array_values($cfg['sections']));
    }

    private function page(array $cfg, $item = null)
    {
        $items = $cfg['model']::ordered()->get();
        return view('admin.resource.index', compact('cfg', 'items', 'item'));
    }

    public function index(string $resource)
    {
        return $this->page($this->cfg($resource));
    }

    public function create(string $resource)
    {
        $cfg = $this->cfg($resource);
        $item = new $cfg['model']($cfg['defaults'] ?? []);
        return $this->page($cfg, $item);
    }

    public function edit(string $resource, int $id)
    {
        $cfg = $this->cfg($resource);
        return $this->page($cfg, $cfg['model']::findOrFail($id));
    }

    public function store(Request $r, string $resource)
    {
        $cfg = $this->cfg($resource);
        $model = $cfg['model'];
        $data = $this->payload($r, $cfg);
        $data['slug'] = $model::uniqueSlug($data['slug'] ?: ($data[$cfg['title_key']] ?? ''));
        $data['order'] = ((int) $model::min('order')) - 1;
        $model::create($data);
        return redirect()->route('admin.resource.index', $resource)->with('status', $cfg['singular'] . ' created');
    }

    public function update(Request $r, string $resource, int $id)
    {
        $cfg = $this->cfg($resource);
        $item = $cfg['model']::findOrFail($id);
        $data = $this->payload($r, $cfg);
        $data['slug'] = $cfg['model']::uniqueSlug($data['slug'] ?: ($data[$cfg['title_key']] ?? ''), $item->id);
        $item->update($data);
        return redirect()->route('admin.resource.index', $resource)->with('status', $cfg['singular'] . ' updated');
    }

    public function destroy(string $resource, int $id)
    {
        $cfg = $this->cfg($resource);
        $cfg['model']::findOrFail($id)->delete();
        return redirect()->route('admin.resource.index', $resource)->with('status', $cfg['singular'] . ' deleted');
    }

    private function payload(Request $r, array $cfg): array
    {
        $data = [];
        $errors = [];
        foreach ($this->fields($cfg) as $f) {
            $key = $f['key'];
            $raw = $r->input(str_replace('.', '__', $key));
            $val = null;
            switch ($f['type']) {
                case 'switch':
                    $val = $r->boolean(str_replace('.', '__', $key));
                    break;
                case 'number':
                    $val = ($raw === null || $raw === '') ? null : (float) $raw;
                    if (($f['required'] ?? false) && $val === null) $errors[$key] = $f['label'] . ' is required';
                    $defaults = ['days' => 1, 'rating' => 5, 'reviews' => 0, 'price' => 0];
                    if ($val === null && isset($defaults[$key])) $val = $defaults[$key];
                    break;
                case 'lines':
                    $val = array_values(array_filter(array_map('trim', preg_split('/\r?\n/', (string) $raw)), fn ($v) => $v !== ''));
                    break;
                case 'gallery':
                case 'list':
                case 'richblocks':
                    $raw = trim((string) $raw);
                    $decoded = $raw === '' ? [] : json_decode($raw, true);
                    if (!is_array($decoded)) { $errors[$key] = 'Invalid data in "' . $f['label'] . '"'; break; }
                    if ($key === 'gallery' && count($decoded) > 20) { $errors[$key] = 'Maksimal 20 foto per galeri'; break; }
                    if ($f['type'] === 'list') {
                        $decoded = array_map(fn ($it) => array_map(fn ($v) => is_array($v) ? array_values(array_filter(array_map(fn ($s) => trim((string) $s), $v), fn ($s) => $s !== '')) : $v, (array) $it), $decoded);
                    }
                    $val = array_values($decoded);
                    break;
                default:
                    $val = is_string($raw) ? trim($raw) : $raw;
                    $val = ($val === '' ? null : $val);
                    if (($f['required'] ?? false) && $val === null) $errors[$key] = $f['label'] . ' is required';
            }
            Arr::set($data, $key, $val);
        }
        foreach ($cfg['defaults'] ?? [] as $k => $v) {
            if (($data[$k] ?? null) === null || $data[$k] === []) $data[$k] = $v;
        }
        if (isset($data['author']) && is_array($data['author'])) {
            $data['author'] = array_map(fn ($v) => $v ?? '', $data['author']);
        }
        if (array_key_exists('gallery', $data) && empty($data['gallery']) && !empty($data['image'])) {
            $data['gallery'] = [['src' => $data['image'], 'label' => 'Signature Experience']];
        }
        if ($errors) {
            throw ValidationException::withMessages($errors);
        }
        return $data;
    }
}
