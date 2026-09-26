<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username', 40)->unique()->after('name');
            $table->unsignedSmallInteger('idle_timeout_minutes')->default(15)->after('password');
            $table->string('email')->nullable()->change();
        });

        Schema::create('tours', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->string('category')->default('Private Tour');
            $table->string('badge')->nullable();
            $table->string('region')->nullable();
            $table->string('duration')->nullable();
            $table->unsignedSmallInteger('days')->default(1);
            $table->decimal('rating', 2, 1)->default(5.0);
            $table->unsignedInteger('reviews')->default(0);
            $table->unsignedBigInteger('price')->default(0);
            $table->string('price_unit', 20)->default('Person');
            $table->unsignedBigInteger('original_price')->nullable();
            $table->string('image', 1000)->nullable();
            $table->boolean('bestseller')->default(true);
            $table->boolean('featured')->default(true);
            $table->text('description')->nullable();
            $table->longText('long_description')->nullable();
            foreach (['highlights', 'gallery', 'features', 'itinerary', 'inclusions', 'exclusions', 'addons', 'tips', 'reviews_list'] as $col) {
                $table->json($col)->nullable();
            }
            $table->integer('order')->default(0)->index();
            $table->timestamps();
        });

        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('category')->nullable();
            $table->string('filter')->default('Family MPV');
            $table->string('badge')->nullable();
            $table->string('headline', 500)->nullable();
            $table->text('description')->nullable();
            $table->longText('long_desc')->nullable();
            $table->unsignedBigInteger('price')->default(0);
            $table->unsignedBigInteger('price12h')->nullable();
            $table->string('image', 1000)->nullable();
            foreach (['capacity', 'capacity_sub', 'luggage', 'luggage_sub', 'drivetrain', 'drivetrain_sub', 'seating', 'seating_sub'] as $col) {
                $table->string($col)->nullable();
            }
            foreach (['gallery', 'tags', 'features', 'specs', 'amenities', 'pickup_areas', 'addons', 'routes', 'reviews_list'] as $col) {
                $table->json($col)->nullable();
            }
            $table->integer('order')->default(0)->index();
            $table->timestamps();
        });

        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('headline', 500)->nullable();
            $table->string('type')->default('Adventure & Trekking');
            $table->string('category')->nullable();
            $table->string('badge')->nullable();
            $table->string('badge_tone', 20)->default('brand');
            $table->string('duration')->nullable();
            $table->decimal('rating', 2, 1)->default(5.0);
            $table->unsignedInteger('reviews')->default(0);
            $table->unsignedBigInteger('price')->default(0);
            $table->string('image', 1000)->nullable();
            $table->text('description')->nullable();
            $table->longText('long_description')->nullable();
            foreach (['includes', 'tags', 'inclusions', 'exclusions', 'gallery', 'facts', 'highlights', 'timeline', 'slots', 'addons', 'packing', 'reviews_list'] as $col) {
                $table->json($col)->nullable();
            }
            $table->integer('order')->default(0)->index();
            $table->timestamps();
        });

        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title', 500);
            $table->string('subtitle', 1000)->nullable();
            $table->string('category')->default('Island Itineraries');
            $table->date('date')->nullable();
            $table->string('read_time', 50)->nullable();
            $table->string('location')->nullable();
            $table->boolean('featured')->default(false);
            $table->text('excerpt')->nullable();
            $table->string('image', 1000)->nullable();
            $table->string('image_caption', 1000)->nullable();
            foreach (['author', 'tags', 'bullets', 'highlights', 'facts', 'content'] as $col) {
                $table->json($col)->nullable();
            }
            $table->integer('order')->default(0)->index();
            $table->timestamps();
        });

        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('type', 50);
            $table->string('item_id')->nullable();
            $table->string('item_name', 500);
            $table->string('name');
            $table->string('phone', 50);
            $table->string('date', 50)->nullable();
            $table->unsignedSmallInteger('pax')->nullable();
            $table->text('option')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('total')->nullable();
            $table->string('status', 20)->default('new')->index();
            $table->timestamps();
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->string('key', 50)->primary();
            $table->json('value');
            $table->timestamps();
        });

        Schema::create('newsletter_subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        foreach (['newsletter_subscribers', 'settings', 'bookings', 'articles', 'activities', 'cars', 'tours'] as $t) {
            Schema::dropIfExists($t);
        }
    }
};
