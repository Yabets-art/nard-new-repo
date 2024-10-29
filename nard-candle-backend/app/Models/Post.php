<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    // Define the table if it's not the plural of the model name (optional)
    protected $table = 'posts';

    // Define the fillable fields to allow mass assignment
    protected $fillable = [
        'title',
        'short_description',
        'link',
    ];
}
