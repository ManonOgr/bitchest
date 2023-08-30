<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;  // Import the HasFactory trait
use Illuminate\Database\Eloquent\Model;  // Import the base Model class

class Currency extends Model
{
    use HasFactory;  // Use the HasFactory trait in this model

    protected $table = 'currencies';  // Define the table name for this model as 'currencies'
}
