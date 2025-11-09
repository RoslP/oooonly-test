<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Position;
use App\Models\ComfortCategory;
use App\Models\CarModel;
use App\Models\Driver;
use App\Models\Car;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $categories = collect([
            'Первая',
            'Вторая',
            'Третья',
            'Четвёртая'
        ])->map(fn($name) => ComfortCategory::create(['name' => $name]));

        $positions = collect([
            'Директор',
            'Программист',
            'Менеджер',
            'Бухгалтер',
        ])->map(fn($name) => Position::create(['name' => $name]));

        $positions->each(function ($pos) use ($categories) {
            switch ($pos->name) {
                case 'Директор':
                    $allowed = [4];
                    break;

                case 'Программист':
                    $allowed = [3];
                    break;

                case 'Менеджер':
                    $allowed = [2];
                    break;

                case 'Бухгалтер':
                    $allowed = [1];
                    break;
            }

            $pos->comfortCategories()->sync($allowed);
        });

        Driver::factory(10)->create();

        CarModel::factory(10)->create();

        Car::factory(20)->create();

        User::factory(50)->create();
    }
}
