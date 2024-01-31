<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;

class Product extends Model
{
    use HasFactory;
    protected $primaryKey = 'products_id';

    public function category(){
        return $this->belongts(Category::class,'category_id');


    }
}
