<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trainee extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'address',
        'phone_number',
        'email',
        'educational_status',
        'training_type',
        'training_day',
        'status',
        'is_paid'
    ];
    public function trainingDay()
    {
        return $this->belongsTo(TrainingDay::class);
    }
}

