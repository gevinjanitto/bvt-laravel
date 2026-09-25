<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Article;
use App\Models\Booking;
use App\Models\Car;
use App\Models\NewsletterSubscriber;
use App\Models\Tour;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function home()
    {
        return view('site.home', ['tours' => Tour::ordered()->where('bestseller', true)->take(8)->get()]);
    }

    public function tours(Request $r)
    {
        $category = $r->query('category', 'All');
        $destination = $r->query('destination', 'All Bali Destinations');
        $duration = $r->query('duration', 'Any Length');
        $budget = $r->query('budget', 'All Price Ranges');
        $all = Tour::ordered()->get();
        $filtered = $all->filter(function (Tour $t) use ($category, $destination, $duration, $budget) {
            if ($category !== 'All' && $category !== 'All Categories' && $t->category !== $category) return false;
            if ($destination && $destination !== 'All Bali Destinations') {
                $hay = strtolower("{$t->title} {$t->region} {$t->description} " . implode(' ', $t->highlights ?? []));
                if (!str_contains($hay, strtolower($destination))) return false;
            }
            if ($duration === '1 Day' && $t->days != 1) return false;
            if ($duration === '2-3 Days' && !($t->days >= 2 && $t->days <= 3)) return false;
            if ($duration === '4+ Days' && $t->days < 4) return false;
            if ($budget === 'Under Rp 1,000,000' && $t->price >= 1000000) return false;
            if ($budget === 'Rp 1,000,000 - 3,000,000' && ($t->price < 1000000 || $t->price > 3000000)) return false;
            if ($budget === 'Above Rp 3,000,000' && $t->price <= 3000000) return false;
            return true;
        })->values();
        return view('site.tours', compact('all', 'filtered', 'category', 'destination', 'duration', 'budget'));
    }

    public function tourShow(string $slug)
    {
        $tour = Tour::findBySlug($slug);
        abort_unless($tour, 404);
        return view('site.tour-detail', compact('tour'));
    }

    public function cars(Request $r)
    {
        $filter = $r->query('filter', 'All Vehicles');
        $all = Car::ordered()->get();
        $list = $filter === 'All Vehicles' ? $all : $all->where('filter', $filter)->values();
        return view('site.cars', compact('all', 'list', 'filter'));
    }

    public function carShow(string $slug)
    {
        $car = Car::findBySlug($slug);
        abort_unless($car, 404);
        return view('site.car-detail', compact('car'));
    }

    public function activities(Request $r)
    {
        $type = $r->query('type', 'All Activities');
        $all = Activity::ordered()->get();
        $list = $type === 'All Activities' ? $all : $all->where('type', $type)->values();
        return view('site.activities', compact('list', 'type'));
    }

    public function activityShow(string $slug)
    {
        $act = Activity::findBySlug($slug);
        abort_unless($act, 404);
        return view('site.activity-detail', compact('act'));
    }

    public function about()
    {
        return view('site.about');
    }

    public function articles(Request $r)
    {
        $q = trim((string) $r->query('q', ''));
        $cat = $r->query('cat', 'All');
        $all = Article::ordered()->get();
        $cats = array_merge(['All'], $all->pluck('category')->unique()->values()->all());
        $featured = $all->firstWhere('featured', true) ?? $all->first();
        $showFeatured = $featured && $cat === 'All' && $q === '';
        $listed = $all->filter(fn ($a) => ($cat === 'All' || $a->category === $cat) && ($q === '' || str_contains(strtolower("{$a->title} {$a->excerpt} {$a->category}"), strtolower($q))))
            ->reject(fn ($a) => $showFeatured && $a->id === $featured->id)->values();
        $page = max(1, (int) $r->query('page', 1));
        $per = 6;
        $pages = max(1, (int) ceil($listed->count() / $per));
        $page = min($page, $pages);
        $items = $listed->slice(($page - 1) * $per, $per)->values();
        return view('site.articles', compact('all', 'cats', 'featured', 'showFeatured', 'items', 'page', 'pages', 'q', 'cat'));
    }

    public function articleShow(string $slug)
    {
        $article = Article::findBySlug($slug);
        abort_unless($article, 404);
        $related = Article::ordered()->where('id', '!=', $article->id)->take(3)->get();
        return view('site.article-detail', compact('article', 'related'));
    }

    public function policy(string $type)
    {
        $policies = blocks('policies');
        $policy = $policies[$type] ?? $policies['privacy'];
        return view('site.policy', compact('policy'));
    }

    public function booking(Request $r)
    {
        $data = $r->validate([
            'type' => 'required|string|max:50', 'item_id' => 'nullable|string|max:50', 'item_name' => 'required|string|max:500',
            'name' => 'required|string|max:120', 'phone' => 'required|string|max:50', 'date' => 'nullable|string|max:50',
            'pax' => 'nullable|integer|min:1|max:200', 'option' => 'nullable|string|max:1000', 'notes' => 'nullable|string|max:2000', 'total' => 'nullable|numeric|min:0',
        ]);
        $booking = Booking::create($data + ['status' => 'new']);
        return response()->json(['ok' => true, 'id' => $booking->id], 201);
    }

    public function newsletter(Request $r)
    {
        $data = $r->validate(['email' => 'required|email|max:190']);
        NewsletterSubscriber::firstOrCreate(['email' => strtolower($data['email'])]);
        return response()->json(['ok' => true]);
    }
}
