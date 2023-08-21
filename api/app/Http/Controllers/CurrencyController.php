<?php

namespace App\Http\Controllers;
use App\Models\Currency;
use App\Models\API;

class CurrencyController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        view()->composer('layouts.layout', function ($view) {
            $view->with('section', 'currencies');
        });
    }

    /**
     * Display the currencies list
     */

     public function index()
     {
         $names = Currency::pluck('name');
         return response()->json($names);
     }

    /**
     * Display a currency's.
     */
    public function show(Currency $currency, API $api)
    {
        $days = $api->getHistory($currency->api_id);
        return view('currencies.show', [
            'currency' => $currency,
            'days' => $days
        ]);
    }
}
