<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
class Cart extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    use HasFactory;
    protected $fillable = [
        'id',
        'name',
        'price',
        'user_id',
        'image',
        'offer',
        'quantity',
        
    ];
}
