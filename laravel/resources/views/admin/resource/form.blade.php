@php($editing = $item->exists)
@php($sections = array_keys($cfg['sections']))
<div class="fixed inset-0 z-[80] flex justify-end" data-testid="resource-sheet-overlay">
  <a href="{{ route('admin.resource.index', $cfg['key']) }}" class="absolute inset-0 bg-ink/60 backdrop-blur-sm fade-in" aria-label="Close"></a>
  <div class="sheet-panel relative w-full sm:max-w-3xl h-full bg-cream flex flex-col p-6 md:p-8 overflow-hidden shadow-2xl" data-testid="resource-sheet">
    <a href="{{ route('admin.resource.index', $cfg['key']) }}" class="absolute right-4 top-4 rounded-sm opacity-70 hover:opacity-100 transition-opacity" aria-label="Close" data-testid="sheet-close">{!! icon('X') !!}</a>
    <div class="text-left space-y-1">
      <div class="eyebrow">{{ $cfg['title'] }}</div>
      <h2 class="font-display text-2xl font-semibold text-ink">{{ $editing ? 'Edit ' : 'New ' }}{{ $cfg['singular'] }}</h2>
      <p class="text-sm text-sand">Changes are published to the website instantly after saving.</p>
    </div>
    <div class="flex-1 min-h-0 mt-4">
      <form method="post" action="{{ $editing ? route('admin.resource.update', [$cfg['key'], $item->id]) : route('admin.resource.store', $cfg['key']) }}" class="flex flex-col h-full" data-testid="resource-form" x-data="{ tab: @js($sections[0]) }" data-resource-form>@csrf @if($editing)@method('PUT')@endif
        <div class="flex-1 flex flex-col min-h-0">
          <div class="w-full flex justify-start overflow-x-auto no-scrollbar bg-cream-100 rounded-xl h-auto p-1 gap-0.5" role="tablist">
            @foreach($sections as $t)<button type="button" role="tab" class="tab-trigger" :aria-selected="tab === @js($t) ? 'true' : 'false'" @click="tab = @js($t)" data-testid="tab-{{ Str::slug($t) }}">{{ $t }}</button>@endforeach
          </div>
          @foreach($cfg['sections'] as $t => $fields)
          <div class="flex-1 overflow-y-auto admin-scroll pr-1 mt-4" x-show="tab === @js($t)" role="tabpanel">
            <div class="grid sm:grid-cols-2 gap-4">
              @foreach($fields as $f)
                @php($key = $f['key'])
                @php($name = str_replace('.', '__', $key))
                @php($tid = 'field-' . str_replace('.', '-', $key))
                @php($val = old($name, data_get($item, $key)))
                <div class="space-y-1.5 {{ ($f['span'] ?? 1) === 2 ? 'sm:col-span-2' : '' }}">
                  <label for="f-{{ $name }}" class="field-label">{{ $f['label'] }}@if($f['required'] ?? false) <span class="text-brand">*</span>@endif</label>
                  @switch($f['type'])
                    @case('textarea')<textarea id="f-{{ $name }}" name="{{ $name }}" class="ui-textarea min-h-[90px]" data-testid="{{ $tid }}">{{ $val }}</textarea>@break
                    @case('lines')<textarea id="f-{{ $name }}" name="{{ $name }}" class="ui-textarea min-h-[110px] font-mono text-xs" data-testid="{{ $tid }}">{{ is_array($val) ? implode("\n", $val) : $val }}</textarea>@break
                    @case('richtext')<div data-editor="rich" data-name="{{ $name }}" data-value="{{ rich_html($val) }}" data-testid-base="{{ $tid }}"></div>@break
                    @case('richblocks')<div data-editor="blocks" data-name="{{ $name }}" data-value="{{ json_encode(is_array($val) ? $val : (json_decode((string) $val, true) ?: [])) }}"></div>@break
                    @case('number')<input id="f-{{ $name }}" type="number" step="{{ $f['step'] ?? 1 }}" name="{{ $name }}" value="{{ $val }}" class="ui-input" data-testid="{{ $tid }}">@break
                    @case('date')<input id="f-{{ $name }}" type="date" name="{{ $name }}" value="{{ $val instanceof \Carbon\Carbon ? $val->format('Y-m-d') : ($val ?: ($editing ? '' : now()->format('Y-m-d'))) }}" class="ui-input" data-testid="{{ $tid }}">@break
                    @case('switch')<div class="h-10 flex items-center"><input type="hidden" name="{{ $name }}" value="{{ $val ? 1 : 0 }}"><button type="button" role="switch" class="switch" aria-checked="{{ $val ? 'true' : 'false' }}" data-switch data-testid="{{ $tid }}"><span></span></button></div>@break
                    @case('select')<select id="f-{{ $name }}" name="{{ $name }}" class="ui-select" data-testid="{{ $tid }}"><option value="">Select...</option>@foreach($f['options'] as $o)<option @selected((string) $val === (string) $o)>{{ $o }}</option>@endforeach @if($val && !in_array($val, $f['options']))<option selected>{{ $val }}</option>@endif</select>@break
                    @case('image')<div data-editor="image" data-name="{{ $name }}" data-value="{{ $val }}" data-testid-base="{{ $tid }}" data-recommendation="{{ str_contains($key, 'avatar') ? '400 × 400 px · foto profil' : '1200 × 800 px · rasio 3:2' }}"></div>@break
                    @case('gallery')<div data-editor="gallery" data-name="{{ $name }}" data-value="{{ json_encode(is_array($val) ? $val : (json_decode((string) $val, true) ?: [])) }}"></div>@break
                    @case('list')<div data-editor="list" data-name="{{ $name }}" data-value="{{ json_encode(is_array($val) ? $val : (json_decode((string) $val, true) ?: [])) }}" data-schema="{{ json_encode($f['schema']) }}" data-item-label="{{ $f['itemLabel'] }}"></div>@break
                    @default<input id="f-{{ $name }}" name="{{ $name }}" value="{{ $val }}" class="ui-input" data-testid="{{ $tid }}">
                  @endswitch
                  @if($f['hint'] ?? false)<p class="text-[11px] text-sand">{{ $f['hint'] }}</p>@endif
                </div>
              @endforeach
            </div>
          </div>
          @endforeach
        </div>
        <div class="flex items-center justify-end gap-3 pt-4 mt-4 border-t border-ink/[0.08]">
          <a href="{{ route('admin.resource.index', $cfg['key']) }}" class="btn-outline !py-2.5 !px-5" data-testid="form-cancel">Cancel</a>
          <button type="submit" class="btn-brand !py-2.5 !px-6 disabled:opacity-60" data-testid="form-submit">{!! icon('Save') !!} {{ $editing ? 'Save changes' : 'Create ' . $cfg['singular'] }}</button>
        </div>
      </form>
    </div>
  </div>
</div>
