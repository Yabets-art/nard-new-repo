<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Alert extends Model
{
    protected $fillable = ['type', 'icon', 'title', 'message'];
}

