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
        $userTransactions = Transaction::where('user_id', $userId)
            ->join('currencies', 'transactions.currency_id', '=', 'currencies.id')
            ->select('transactions.id', 'transactions.currency_id', 'currencies.name as currency_name', 'transactions.quantity', 'transactions.purchase_date', 'transactions.purchase_price', 'transactions.selling_price') // Ajoutez le champ purchase_price ici
            ->get();

        return response()->json(['transactions' => $userTransactions]);
    }

    public function getTransaction()
    {
        $currencies = Transaction::select('id', 'currency_id', 'user_id', 'amount', 'quantity', 'purchase_price', 'purchase_date', 'selling_amount', 'selling_price', 'selling_date')->get();
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


    public function purchaseCrypto(Request $request)
    {
        // Vérifiez si l'utilisateur est connecté
        if (Auth::check()) {
            // L'utilisateur est connecté, continuez avec la logique d'achat
            $user = Auth::user();

            // Validez les données reçues depuis la requête (par exemple, la quantité, le prix, etc.)
            $validator = Validator::make($request->all(), [
                'currency_id' => 'required|integer', // ID de la crypto-monnaie
                'quantity' => 'required|numeric',  // Quantité achetée
                'purchase_price' => 'required|numeric',  // Prix d'achat
                'selling_price' => 'required|numeric',  // Prix de vente
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            // Créez une nouvelle transaction d'achat
            $transaction = new Transaction([
                'currency_id' => $request->currency_id,
                'quantity' => $request->quantity,
                'purchase_price' => rand(100000, 1000000) / 100,
                'selling_price' => rand(100000, 1000000) / 100,
                'selling_date' => now()->subDays(rand(1, 30))->toDateTimeString(),
                'purchase_date' => now(), // Date d'achat actuelle
                'user_id' => $user->id, // Assurez-vous d'associer la transaction à l'utilisateur connecté
            ]);

            // Enregistrez la transaction dans la base de données
            $transaction->save();

            // Vous pouvez également effectuer d'autres opérations ici, telles que la mise à jour du solde de l'utilisateur, etc.

            // Répondez avec un message de réussite ou les détails de la transaction
            return response()->json(['message' => 'Transaction d\'achat réussie', 'transaction' => $transaction], 200);
        } else {
            // L'utilisateur n'est pas connecté, renvoyez une réponse d'erreur
            return response()->json(['message' => 'Utilisateur non connecté'], 401);
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


    public function status()
    {
        // Renvoie une réponse JSON avec le statut "ok"
        return response()->json(['status' => 'ok']);
    }

    public function destroy($id)
{
    try {
        // Recherchez la transaction par son ID
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        // Vous pouvez ajouter ici des vérifications supplémentaires, par exemple, si la transaction appartient à l'utilisateur actuellement authentifié.

        // Supprimez la transaction de la base de données
        $transaction->delete();

        return response()->json(['message' => 'Transaction deleted successfully'], 200);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Error while deleting the transaction'], 500);
    }
}

}

