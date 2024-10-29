<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YouTubeVideo extends Model
{
    use HasFactory;

    protected $fillable = ['link', 'description'];

    // Specify the table name if it doesn't follow Laravel's naming convention
    protected $table = 'youtube_videos';
}
