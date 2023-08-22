<?php

namespace App\Http\Controllers;

use App\Models\History;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function getHistory()
    {
        $currencies = History::select('id', 'name', 'quoting')->get();

        return response()->json($currencies);
    }
}
