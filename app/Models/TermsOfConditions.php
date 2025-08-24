<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TermsOfConditions extends Model
{
   protected $fillable = [
        'terms_of_conditions_ar',
        'terms_of_conditions_en',
        'terms_of_use_ar',
        'terms_of_use_en',
    ];


}
