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

        // $this->call([
        //     CountriesTableSeeder::class,
        // ]);

        factory(App\User::class, 100)->create();
        factory(App\Type::class, 10)->create();
        factory(App\Item::class, 100)->create();
        factory(App\Order::class, 1000)->create();

        $items = App\Item::all();

        App\Order::all()->each(function($order) use ($items) {

            $n = rand(1, 10);

            $order->items()->attach(
                $items->random($n)->pluck('id')->toArray()
            , ['quantity' => $n]);

            $order->total = $order->items->sum(function($item) {
                return $item->price * $item->pivot->quantity;
            });

            $order->save();

        });

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
