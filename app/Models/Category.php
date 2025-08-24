<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'image',
        'name_ar',
        'name_en',
        'status',
    ];
}
