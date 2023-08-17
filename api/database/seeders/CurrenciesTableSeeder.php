<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Currency;

class CurrenciesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Currency::create([ "name" => "Bitcoin"]);
        Currency::create([ "name" => "Ethereum"]);
        Currency::create([ "name" => "Ripple"]);
        Currency::create([ "name" => "Bitcoin Cash"]);
        Currency::create([ "name" => "Cardano"]);
        Currency::create([ "name" => "Litecoin"]);
        Currency::create([ "name" => "NEM"]);
        Currency::create([ "name" => "Stellar"]);
        Currency::create([ "name" => "IOTA"]);
        Currency::create([ "name" => "Dash"]);

    }
}
