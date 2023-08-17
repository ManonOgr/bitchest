<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\Transaction;
use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //User::factory(3)->create();
        //Currency::factory(5)->create();
        //Transaction::factory(5)->create();

        $this->call(UserTableSeeder::class);
        $this->call(CurrenciesTableSeeder::class);
        $this->call(TransactionsTableSeeder::class);
    }
}
