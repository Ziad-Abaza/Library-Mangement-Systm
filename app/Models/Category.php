<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name',  'description', 'category_group_id'];

    public function categoryGroup()
    {
        return $this->belongsTo(CategoryGroup::class, 'category_group_id');
    }
}
