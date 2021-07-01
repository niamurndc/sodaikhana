<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public function products(){
        return $this->belongsToMany(Product::class);
    }

    public function parentId(){
        return $this->belongsTo(Category::class, 'parent');
    }

    public function subcats(){
        return $this->hasMany(Category::class, 'parent');
    }
}
