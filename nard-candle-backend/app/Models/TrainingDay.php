<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingDay extends Model
{
    use HasFactory;

    protected $fillable = ['day', 'is_available'];

    public function trainees()
    {
        return $this->hasMany(Trainee::class);
    }
}
