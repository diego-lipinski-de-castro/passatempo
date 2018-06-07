<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Eloquent::unguard();

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $this->call([
            CountriesTableSeeder::class,
        ]);

        factory(App\User::class, 200)->create();
        factory(App\Type::class, 50)->create();
        factory(App\Item::class, 300)->create();
        factory(App\Order::class, 2000)->create();

        $items = App\Item::all();

        App\Order::all()->each(function($order) use ($items) {
            $order->items()->attach(
                $items->random(rand(1, 10))->pluck('id')->toArray()
            );
        });

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
