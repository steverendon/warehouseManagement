<?php

namespace App\Models;

use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Section extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function categories()
    {
        return $this->hasMany(Category::class);
    }
}
