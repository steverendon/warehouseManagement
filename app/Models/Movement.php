<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Movement extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'amount', 'type', 'description',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
