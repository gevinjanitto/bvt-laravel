<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContentModel;
use Illuminate\Http\Request;
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

    public function index(string $resource)
    {
        $cfg = $this->cfg($resource);
        $items = $cfg['model']::ordered()->get();
        return view('admin.resource.index', compact('cfg', 'items'));
    }

    public function create(string $resource)
    {
        $cfg = $this->cfg($resource);
        return view('admin.resource.form', ['cfg' => $cfg, 'item' => new $cfg['model']()]);
    }

    public function edit(string $resource, int $id)
    {
        $cfg = $this->cfg($resource);
        return view('admin.resource.form', ['cfg' => $cfg, 'item' => $cfg['model']::findOrFail($id)]);
    }

    public function store(Request $r, string $resource)
    {
        $cfg = $this->cfg($resource);
        $model = $cfg['model'];
        $data = $this->payload($r, $cfg);
        $data['slug'] = $model::uniqueSlug($data['slug'] ?: ($data[$cfg['title_key']] ?? ''));
        $data['order'] = ((int) $model::min('order')) - 1;
        $item = $model::create($data);
        return redirect()->route('admin.resource.index', $resource)->with('status', $cfg['singular'] . ' created.');
    }

    public function update(Request $r, string $resource, int $id)
    {
        $cfg = $this->cfg($resource);
        $item = $cfg['model']::findOrFail($id);
        $data = $this->payload($r, $cfg);
        $data['slug'] = $cfg['model']::uniqueSlug($data['slug'] ?: ($data[$cfg['title_key']] ?? ''), $item->id);
        $item->update($data);
        return redirect()->route('admin.resource.index', $resource)->with('status', $cfg['singular'] . ' updated.');
    }

    public function destroy(string $resource, int $id)
    {
        $cfg = $this->cfg($resource);
        $cfg['model']::findOrFail($id)->delete();
        return redirect()->route('admin.resource.index', $resource)->with('status', $cfg['singular'] . ' deleted.');
    }

    private function payload(Request $r, array $cfg): array
    {
        $data = [];
        $errors = [];
        foreach ($this->fields($cfg) as $f) {
            $key = $f['key'];
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
        }
        if ($errors) {
            throw ValidationException::withMessages($errors);
        }
        return $data;
    }
}
