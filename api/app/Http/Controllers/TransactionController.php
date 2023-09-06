<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Currency;
use App\Models\Transaction;
use App\Models\API;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */

    public function getUserTransactions($userId)
    {
        // Retrieve user transactions
        $userTransactions = Transaction::where('user_id', $userId)
            ->join('currencies', 'transactions.currency_id', '=', 'currencies.id')
            ->select('transactions.id', 'transactions.currency_id', 'currencies.name as currency_name', 'transactions.quantity', 'transactions.purchase_date', 'transactions.purchase_price', 'transactions.selling_price') // Add the purchase_price field here
            ->get();

        return response()->json(['transactions' => $userTransactions]);
    }

    public function getTransaction()
    {
        // Get all transactions
        $currencies = Transaction::select('id', 'currency_id', 'user_id', 'amount', 'quantity', 'purchase_price', 'purchase_date', 'selling_amount', 'selling_price', 'selling_date')->get();
        return response()->json($currencies);
    }

    public function sellCrypto($id)
    {
        try {
            // Retrieve the transaction by its ID
            $transaction = Transaction::find($id);

            if (!$transaction) {
                return response()->json(['message' => 'Transaction not found'], 404);
            }

            // Check if selling price is available
            if (is_null($transaction->selling_price)) {
                return response()->json(['message' => 'Selling price not available for this transaction'], 404);
            }

            // Your selling logic here; you can use $transaction->selling_price directly as the selling price

            // Save the updated transaction (if you are also updating other properties)
            $transaction->save();

            // Delete the transaction if necessary
            $transaction->delete();

            return response()->json(['message' => 'Crypto sold successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error while selling crypto'], 500);
        }
    }

    public function purchaseCrypto(Request $request)
    {
        // Check if the user is authenticated
        if (Auth::check()) {
            // User is authenticated, proceed with purchase logic
            $user = Auth::user();

            // Validate the data received from the request (e.g., currency_id, quantity, price, etc.)
            $validator = Validator::make($request->all(), [
                'currency_id' => 'required|integer', // Currency ID
                'quantity' => 'required|numeric',  // Quantity purchased
                'purchase_price' => 'required|numeric',  // Purchase price
                'selling_price' => 'required|numeric',  // Selling price
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            // Create a new purchase transaction
            $transaction = new Transaction([
                'currency_id' => $request->currency_id,
                'quantity' => $request->quantity,
                'purchase_price' => rand(100000, 1000000) / 100,
                'selling_price' => rand(100000, 1000000) / 100,
                'selling_date' => now()->subDays(rand(1, 30))->toDateTimeString(),
                'purchase_date' => now(), // Current purchase date
                'user_id' => $user->id, // Make sure to associate the transaction with the currently authenticated user
            ]);

            // Save the transaction to the database
            $transaction->save();

            // You can also perform other operations here, such as updating the user's balance, etc.

            // Respond with a success message or transaction details
            return response()->json(['message' => 'Purchase transaction successful', 'transaction' => $transaction], 200);
        } else {
            // User is not authenticated, return an error response
            return response()->json(['message' => 'User not authenticated'], 401);
        }
    }

    /**
     * Show the form for buying a currency.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Currency $currency)
    {
        $title = 'Buy ' . $currency->name;

        return view('transactions.create', ['title' => $title, 'currency' => $currency]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, API $api)
    {
        // Get form inputs data
        $attributes = $request->all();
        // Get user's id to insert it
        $attributes['user_id'] = Auth::id();
        // Get current datetime
        $attributes['purchase_date'] = Carbon::now()->toDateTimeString();
        // Get current rate
        $attributes['purchase_price'] = $api->getPrice($attributes['currency_api_id']);

        // Calculate quantity or amount depending on which buying option the user chose
        if ($attributes['buying_option'] == 'amountBuyingInput') {
            $attributes['quantity'] = $attributes['amount'] / $attributes['purchase_price'];
        } elseif ($attributes['buying_option'] == 'quantityBuyingInput') {
            $attributes['amount'] = $attributes['quantity'] * $attributes['purchase_price'];
        }
        // Create a transaction in the database
        $transaction = Transaction::create($attributes);

        return redirect()
            ->route('wallet')
            ->with('message', 'The transaction was successful. You bought: ' . round($transaction->quantity, 6) . ' ' . $transaction->currency->name . ' for an amount of ' . $transaction->amount . ' ' . config('currency')['symbol'] . '.');
    }

    /**
     * Sell one specified transaction or all those of a currency.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Currency $currency, Transaction $transaction = null, Request $request, API $api)
    {
        if ($transaction) {
            $transaction->sold = 1;
            $transaction->selling_amount = round($request->selling_amount, 2);
            $transaction->selling_price = $request->selling_price;
            $transaction->selling_date = Carbon::now();

            $transaction->save();

            return redirect()
                ->route('wallet')
                ->with('message', 'The transaction was successful. You sold: ' . floatval($transaction->quantity) . ' ' . $currency->name . ' for an amount of ' . round($request->selling_amount, 2) . ' ' . config('currency')['symbol'] . '.');
        } else {
            $quantity_total = 0;
            $selling_amount_total = 0;

            $transactions = Transaction::where([
                'currency_id' => $currency->id,
                'user_id' => Auth::id()
            ]);

            $transactions->update([
                'sold' => 1,
                'selling_price' => $api->getPrice($currency->api_id),
                'selling_date' => Carbon::now()
            ]);

            foreach ($transactions->get() as $transaction) {
                $transaction->selling_amount = $transaction->selling_price * $transaction->quantity;
                $transaction->save();

                $quantity_total += $transaction->quantity;
                $selling_amount_total += $transaction->selling_amount;
            }

            return redirect()
                ->route('wallet')
                ->with('message', 'The transaction was successful. You sold: ' . floatval($quantity_total) . ' ' . $currency->name . ' for an amount of ' . round($selling_amount_total, 2) . ' ' . config('currency')['symbol'] . '.');
        }
    }

    public function status()
    {
        // Return a JSON response with the "ok" status
        return response()->json(['status' => 'ok']);
    }

    public function destroy($id)
    {
        try {
            // Find the transaction by its ID
            $transaction = Transaction::find($id);

            if (!$transaction) {
                return response()->json(['message' => 'Transaction not found'], 404);
            }

            // Delete the transaction from the database
            $transaction->delete();

            return response()->json(['message' => 'Transaction deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error while deleting the transaction'], 500);
        }
    }
}
