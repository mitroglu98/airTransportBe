<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cost extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'fuel_cost',
        'crew_cost',
        'service_cost',
    ];
    
    public function flight()
    {
        return $this->belongsTo(Flight::class);
    }
}
