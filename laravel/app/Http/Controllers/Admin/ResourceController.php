<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
<<<<<<< HEAD
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
=======
use App\Models\ContentModel;
use Illuminate\Http\Request;
>>>>>>> 3d75822977b8fa74ecaa4dc0a07e5dc1508a4a17
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

<<<<<<< HEAD
    private function page(array $cfg, $item = null)
    {
        $items = $cfg['model']::ordered()->get();
        return view('admin.resource.index', compact('cfg', 'items', 'item'));
    }

    public function index(string $resource)
    {
        return $this->page($this->cfg($resource));
=======
    public function index(string $resource)
    {
        $cfg = $this->cfg($resource);
        $items = $cfg['model']::ordered()->get();
        return view('admin.resource.index', compact('cfg', 'items'));
>>>>>>> 3d75822977b8fa74ecaa4dc0a07e5dc1508a4a17
    }

    public function create(string $resource)
    {
        $cfg = $this->cfg($resource);
<<<<<<< HEAD
        $item = new $cfg['model']($cfg['defaults'] ?? []);
        return $this->page($cfg, $item);
=======
        return view('admin.resource.form', ['cfg' => $cfg, 'item' => new $cfg['model']()]);
>>>>>>> 3d75822977b8fa74ecaa4dc0a07e5dc1508a4a17
    }

    public function edit(string $resource, int $id)
    {
        $cfg = $this->cfg($resource);
<<<<<<< HEAD
        return $this->page($cfg, $cfg['model']::findOrFail($id));
=======
        return view('admin.resource.form', ['cfg' => $cfg, 'item' => $cfg['model']::findOrFail($id)]);
>>>>>>> 3d75822977b8fa74ecaa4dc0a07e5dc1508a4a17
    }

    public function store(Request $r, string $resource)
    {
        $cfg = $this->cfg($resource);
        $model = $cfg['model'];
        $data = $this->payload($r, $cfg);
        $data['slug'] = $model::uniqueSlug($data['slug'] ?: ($data[$cfg['title_key']] ?? ''));
        $data['order'] = ((int) $model::min('order')) - 1;
<<<<<<< HEAD
        $model::create($data);
        return redirect()->route('admin.resource.index', $resource)->with('status', $cfg['singular'] . ' created');
=======
        $item = $model::create($data);
        return redirect()->route('admin.resource.index', $resource)->with('status', $cfg['singular'] . ' created.');
>>>>>>> 3d75822977b8fa74ecaa4dc0a07e5dc1508a4a17
    }

    public function update(Request $r, string $resource, int $id)
    {
        $cfg = $this->cfg($resource);
        $item = $cfg['model']::findOrFail($id);
        $data = $this->payload($r, $cfg);
        $data['slug'] = $cfg['model']::uniqueSlug($data['slug'] ?: ($data[$cfg['title_key']] ?? ''), $item->id);
        $item->update($data);
<<<<<<< HEAD
        return redirect()->route('admin.resource.index', $resource)->with('status', $cfg['singular'] . ' updated');
=======
        return redirect()->route('admin.resource.index', $resource)->with('status', $cfg['singular'] . ' updated.');
>>>>>>> 3d75822977b8fa74ecaa4dc0a07e5dc1508a4a17
    }

    public function destroy(string $resource, int $id)
    {
        $cfg = $this->cfg($resource);
        $cfg['model']::findOrFail($id)->delete();
<<<<<<< HEAD
        return redirect()->route('admin.resource.index', $resource)->with('status', $cfg['singular'] . ' deleted');
=======
        return redirect()->route('admin.resource.index', $resource)->with('status', $cfg['singular'] . ' deleted.');
>>>>>>> 3d75822977b8fa74ecaa4dc0a07e5dc1508a4a17
    }

    private function payload(Request $r, array $cfg): array
    {
        $data = [];
        $errors = [];
        foreach ($this->fields($cfg) as $f) {
            $key = $f['key'];
<<<<<<< HEAD
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
=======
            $raw = $r->input($key);
            switch ($f['type']) {
                case 'bool':
                    $data[$key] = $r->boolean($key);
                    break;
                case 'number':
                    $data[$key] = ($raw === null || $raw === '') ? null : (float) $raw;
                    if (($f['required'] ?? false) && $data[$key] === null) $errors[$key] = $f['label'] . ' is required';
                    $defaults = ['days' => 1, 'rating' => 5, 'reviews' => 0, 'price' => 0];
                    if ($data[$key] === null && isset($defaults[$key])) $data[$key] = $defaults[$key];
                    break;
                case 'lines':
                    $data[$key] = array_values(array_filter(array_map('trim', preg_split('/\r?\n/', (string) $raw)), fn ($v) => $v !== ''));
                    break;
                case 'json':
                    $raw = trim((string) $raw);
                    if ($raw === '') {
                        $data[$key] = $key === 'author' ? [] : [];
                        break;
                    }
                    $decoded = json_decode($raw, true);
                    if (json_last_error() !== JSON_ERROR_NONE) $errors[$key] = $f['label'] . ': invalid JSON (' . json_last_error_msg() . ')';
                    elseif ($key === 'gallery' && count($decoded) > 20) $errors[$key] = 'Maksimal 20 foto per galeri';
                    else $data[$key] = $decoded;
                    break;
                default:
                    $val = is_string($raw) ? trim($raw) : $raw;
                    $data[$key] = ($val === '' ? null : $val);
                    if (($f['required'] ?? false) && $data[$key] === null) $errors[$key] = $f['label'] . ' is required';
            }
>>>>>>> 3d75822977b8fa74ecaa4dc0a07e5dc1508a4a17
        }
        if ($errors) {
            throw ValidationException::withMessages($errors);
        }
        return $data;
    }
}
