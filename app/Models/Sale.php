<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    public function books()
    {
        return $this->hasMany(OrderDetail::class, 'sale_id', 'id');
    }
}
