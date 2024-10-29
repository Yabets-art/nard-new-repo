<?php  
// app/Models/CustomOrder.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'contact_method',
        'preferences',
        'image',
        'status', // Possible values: 'pending', 'accepted', 'completed'
    ];
}
