<?php

namespace Database\Seeders;

use Illuminate\Support\Arr;
use App\Models\Transaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransactionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Transaction::factory()->count(20)->create();

        // Delete admin transaction
        $admins = DB::table('users')->where('status', 'admin')->get('id')->toArray();
        $admin_ids = Arr::pluck($admins, 'id');

        DB::table('transactions')
            ->whereIn('user_id', $admin_ids)
            ->delete();

        // Fixed amount depending on the quantity of the purchase price
        DB::update("UPDATE `transactions` SET `amount`=`quantity`*`purchase_price`");

    }
}
