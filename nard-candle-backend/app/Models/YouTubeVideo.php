<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YouTubeVideo extends Model
{
    use HasFactory;

    protected $fillable = ['link', 'description'];

    // Update table name to match the renamed table in the migration
    protected $table = 'you_tube_videos';
}
