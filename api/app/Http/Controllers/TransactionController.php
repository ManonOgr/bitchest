<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Currency;
use App\Models\Transaction;
use App\Models\API;

class TransactionController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */

     public function getUserTransactions($userId)
     {
         $userTransactions = Transaction::where('user_id', $userId)
             ->join('currencies', 'transactions.currency_id', '=', 'currencies.id')
             ->select('transactions.id', 'transactions.currency_id', 'currencies.name as currency_name', 'transactions.quantity', 'transactions.purchase_date', 'transactions.purchase_price','transactions.selling_price' ) // Ajoutez le champ purchase_price ici
             ->get();

         return response()->json(['transactions' => $userTransactions]);
     }

    public function getTransaction()
    {
        $currencies = Transaction::select('id', 'currency_id', 'user_id', 'amount', 'quantity', 'purchase_price', 'purchase_date', 'sold', 'selling_amount', 'selling_price', 'selling_date')->get();
        return response()->json($currencies);
    }

    public function sellCrypto($id)
    {
        try {
            // Récupérez la transaction par son ID
            $transaction = Transaction::find($id);

            if (!$transaction) {
                return response()->json(['message' => 'Transaction non trouvée'], 404);
            }

            // Vérifiez si le prix de vente est disponible
            if (is_null($transaction->selling_price)) {
                return response()->json(['message' => 'Prix de vente non disponible pour cette transaction'], 404);
            }

            // Votre logique de vente ici, vous pouvez utiliser directement $transaction->selling_price comme prix de vente

            // Enregistrez la transaction mise à jour (si vous mettez également à jour d'autres propriétés)
             $transaction->save();

            // Supprimez la transaction si nécessaire
            $transaction->delete();

            return response()->json(['message' => 'Crypto vendue avec succès'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erreur lors de la vente de la crypto'], 500);
        }
    }






    public function __construct(Request $request)
    {
        if (Str::contains($request->path(), 'create')) {
            view()->composer('layouts.layout', function ($view) {
                $view->with('section', 'currencies');
            });
        } else {
            view()->composer('layouts.layout', function ($view) {
                $view->with('section', 'wallet');
            });
        }

        date_default_timezone_set('Europe/Paris');
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
    {   // Get form inputs data
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
        // Create transaction in Database
        $transaction = Transaction::create($attributes);

        return redirect()
            ->route('wallet')
            ->with('message', 'The transaction was successful. Your bought : ' . round($transaction->quantity, 6) . ' ' . $transaction->currency->name . ' for an amount of ' . $transaction->amount . ' ' . config('currency')['symbol'] . '.');
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
                ->with('message', 'The transaction was successful. Your sold : ' . floatval($transaction->quantity) . ' ' . $currency->name . ' for an amount of ' . round($request->selling_amount, 2) . ' ' . config('currency')['symbol'] . '.');
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
                ->with('message', 'The transaction was successful. Your sold : ' . floatval($quantity_total) . ' ' . $currency->name . ' for an amount of ' . round($selling_amount_total, 2) . ' ' . config('currency')['symbol'] . '.');
        }
    }


    // ...

    public function buyCrypto(Request $request)
    {
        try {
            // Validez les données de la requête (par exemple, la crypto à acheter et la quantité)
            $request->validate([
                'crypto_id' => 'required|integer', // ID de la crypto à acheter
                'quantity' => 'required|numeric|min:0', // Quantité à acheter (doit être positive)
            ]);

            // Obtenez l'ID de l'utilisateur connecté
            $userId = auth()->user()->id;

            // Logique d'achat ici...
            // Par exemple, créez une nouvelle transaction d'achat

            $transaction = new Transaction([
                'user_id' => $userId,
                'currency_id' => $request->crypto_id,
                'quantity' => $request->quantity,
                'transaction_type' => 'achat',
                // Autres données de transaction si nécessaire
            ]);

            // Enregistrez la transaction dans la base de données
            $transaction->save();

            // Vous pouvez également effectuer d'autres opérations ici, par exemple, mettre à jour le portefeuille de l'utilisateur

            return response()->json(['message' => 'Transaction d\'achat réussie']);
        } catch (\Exception $e) {
            // En cas d'erreur, renvoyez une réponse d'erreur
            return response()->json(['error' => $e->getMessage()], 500);
        }




        // Vous pouvez également effectuer d'autres opérations ici, par exemple, mettre à jour le portefeuille de l'utilisateur

        return response()->json(['message' => 'Transaction d\'achat réussie']);
    }

    public function status()
    {
        // Renvoie une réponse JSON avec le statut "ok"
        return response()->json(['status' => 'ok']);
    }
}
