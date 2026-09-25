<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Article;
use App\Models\Booking;
use App\Models\Car;
use App\Models\Setting;
use App\Models\Tour;
<<<<<<< HEAD
use App\Support\CmsText;
=======
>>>>>>> 3d75822977b8fa74ecaa4dc0a07e5dc1508a4a17
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AdminController extends Controller
{
    public function dashboard()
    {
        $bookings = Booking::latest()->get();
        $daily = [];
        for ($i = 13; $i >= 0; $i--) {
            $daily[now()->subDays($i)->toDateString()] = 0;
        }
        foreach ($bookings as $b) {
            $d = $b->created_at->toDateString();
            if (isset($daily[$d])) $daily[$d]++;
        }
        return view('admin.dashboard', [
            'counts' => ['tours' => Tour::count(), 'cars' => Car::count(), 'activities' => Activity::count(), 'articles' => Article::count()],
            'total' => $bookings->count(),
            'byStatus' => $bookings->countBy('status'),
            'byType' => $bookings->countBy('type'),
            'recent' => $bookings->take(6),
            'daily' => $daily,
        ]);
    }

    public function bookings()
    {
        return view('admin.bookings', ['bookings' => Booking::latest()->get()]);
    }

    public function updateBooking(Request $r, Booking $booking)
    {
        $data = $r->validate(['status' => 'required|in:' . implode(',', Booking::STATUSES)]);
        $booking->update($data);
<<<<<<< HEAD
        if ($r->wantsJson()) return response()->json(['ok' => true, 'status' => $booking->status]);
        return back()->with('status', 'Status updated');
=======
        return back()->with('status', 'Booking status updated.');
>>>>>>> 3d75822977b8fa74ecaa4dc0a07e5dc1508a4a17
    }

    public function destroyBooking(Booking $booking)
    {
        $booking->delete();
<<<<<<< HEAD
        return back()->with('status', 'Booking deleted');
=======
        return back()->with('status', 'Booking deleted.');
>>>>>>> 3d75822977b8fa74ecaa4dc0a07e5dc1508a4a17
    }

    public function settings()
    {
        return view('admin.settings', ['site' => site()]);
    }

    public function updateSettings(Request $r)
    {
        $data = $r->validate([
            'contact.whatsapp' => ['required', 'regex:/^[+\d\s()-]+$/'], 'contact.phone' => 'nullable|string|max:50', 'contact.email' => 'required|email',
            'contact.emailLink' => 'nullable|string|max:500', 'contact.address' => 'nullable|string|max:500', 'contact.addressLink' => 'nullable|url|max:500', 'contact.whatsappMessage' => 'nullable|string|max:1000',
            'social.instagram' => 'nullable|url', 'social.facebook' => 'nullable|url', 'social.youtube' => 'nullable|url', 'social.tiktok' => 'nullable|url',
            'brand.name' => 'required|string|max:100', 'brand.title' => 'required|string|max:100', 'brand.tagline' => 'nullable|string|max:100', 'brand.legal' => 'nullable|string|max:150',
            'brand.logo' => 'nullable|string|max:1000', 'brand.logoLight' => 'nullable|string|max:1000', 'brand.logoMode' => 'required|in:icon,full', 'brand.favicon' => 'nullable|string|max:1000',
        ]);
        $wa = preg_replace('/\D/', '', $data['contact']['whatsapp']);
        if (str_starts_with($wa, '0')) $wa = '62' . substr($wa, 1);
        if (!preg_match('/^[1-9]\d{7,14}$/', $wa)) throw ValidationException::withMessages(['contact.whatsapp' => 'Nomor WhatsApp harus 8–15 digit termasuk kode negara']);
        $data['contact']['whatsapp'] = $wa;
        foreach (['contact', 'social', 'brand'] as $k) {
<<<<<<< HEAD
            Setting::updateOrCreate(['key' => $k], ['value' => array_map(fn ($v) => $v ?? '', $data[$k] ?? [])]);
        }
        return back()->with('status', 'Kontak dan identitas website berhasil disimpan');
=======
            Setting::updateOrCreate(['key' => $k], ['value' => array_map(fn ($v) => $v ?? '', $data[$k])]);
        }
        return back()->with('status', 'Pengaturan website disimpan.');
>>>>>>> 3d75822977b8fa74ecaa4dc0a07e5dc1508a4a17
    }

    public function content()
    {
<<<<<<< HEAD
        return view('admin.content', [
            'blocks' => site('blocks'),
            'defaults' => config('site.blocks'),
            'texts' => site('texts', []),
            'catalog' => CmsText::catalog(),
        ]);
=======
        $blocks = site('blocks');
        return view('admin.content', ['blocks' => $blocks]);
>>>>>>> 3d75822977b8fa74ecaa4dc0a07e5dc1508a4a17
    }

    public function updateContent(Request $r)
    {
<<<<<<< HEAD
        $texts = json_decode((string) $r->input('texts', '{}'), true);
        $blocks = json_decode((string) $r->input('blocks', '{}'), true);
        if (!is_array($texts) || !is_array($blocks)) {
            throw ValidationException::withMessages(['blocks' => 'Data konten tidak valid']);
        }
        $defaults = config('site.blocks');
        $blocks = array_intersect_key($blocks, $defaults);
        Setting::updateOrCreate(['key' => 'texts'], ['value' => array_filter($texts, fn ($v) => is_string($v))]);
        Setting::updateOrCreate(['key' => 'blocks'], ['value' => $blocks]);
        return back()->with('status', 'Konten website berhasil disimpan');
=======
        $key = $r->input('block');
        $defaults = config('site.blocks');
        abort_unless(isset($defaults[$key]), 404);
        $raw = trim((string) $r->input('value'));
        $decoded = json_decode($raw, true);
        if (json_last_error() !== JSON_ERROR_NONE || (!is_array($decoded))) {
            throw ValidationException::withMessages(['value' => 'JSON tidak valid: ' . json_last_error_msg()]);
        }
        $row = Setting::firstOrNew(['key' => 'blocks']);
        $blocks = $row->value ?? [];
        $blocks[$key] = $decoded;
        $row->value = $blocks;
        $row->save();
        return redirect()->route('admin.content', ['block' => $key])->with('status', "Bagian \"$key\" disimpan.");
    }

    public function resetContent(Request $r)
    {
        $key = $r->input('block');
        $row = Setting::find('blocks');
        if ($row) {
            $blocks = $row->value ?? [];
            unset($blocks[$key]);
            $row->value = $blocks;
            $row->save();
        }
        return redirect()->route('admin.content', ['block' => $key])->with('status', "Bagian \"$key\" dikembalikan ke default.");
>>>>>>> 3d75822977b8fa74ecaa4dc0a07e5dc1508a4a17
    }

    public function upload(Request $r)
    {
        $r->validate(['file' => 'required|image|mimes:jpeg,png,webp|max:8192']);
        $file = $r->file('file');
        $name = Str::random(20) . '.' . $file->extension();
        $file->storeAs('uploads', $name, 'public');
        return response()->json(['url' => '/storage/uploads/' . $name]);
    }
}
