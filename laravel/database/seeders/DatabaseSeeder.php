<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Article;
use App\Models\Car;
use App\Models\Tour;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $username = strtolower(env('ADMIN_USERNAME', 'admin'));
        $password = env('ADMIN_PASSWORD', 'admin');
        $admin = User::where('username', $username)->first();
        if (!$admin) {
            User::create(['name' => 'Administrator', 'username' => $username, 'email' => null, 'password' => Hash::make($password)]);
        } elseif (!Hash::check($password, $admin->password)) {
            $admin->update(['password' => Hash::make($password)]);
        }

        $data = json_decode(file_get_contents(__DIR__ . '/seed_data.json'), true);
        $map = ['tours' => Tour::class, 'cars' => Car::class, 'activities' => Activity::class, 'articles' => Article::class];
        foreach ($map as $key => $model) {
            if ($model::count() > 0 || empty($data[$key])) {
                continue;
            }
            foreach ($data[$key] as $i => $item) {
                unset($item['id']);
                $row = [];
                foreach ($item as $k => $v) {
                    $row[Str::snake($k)] = $v;
                }
                $row['order'] = $i;
                if (isset($row['long_description']) && is_array($row['long_description'])) {
                    $row['long_description'] = rich_html($row['long_description']);
                }
                $model::create($row);
            }
            $this->command?->info("Seeded " . count($data[$key]) . " $key");
        }
    }
}
