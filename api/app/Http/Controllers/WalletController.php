<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function getWallet()
    {
        $currencies = Wallet::select('id', 'user_id', 'quantity')->get();

        return response()->json($currencies);
    }
}
