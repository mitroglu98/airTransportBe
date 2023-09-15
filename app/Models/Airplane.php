<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 
class Airplane extends Model
{
    use SoftDeletes;
    use HasFactory;
    protected $fillable = [
        'name_en', 'name_cg', 'type', 'translations'
    ];

    public function airplane()
{
    return $this->belongsTo(Airplane::class);
}
}
