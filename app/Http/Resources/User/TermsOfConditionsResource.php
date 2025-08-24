<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TermsOfConditionsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=> $this->id,
            'terms_of_conditions' => $this->{'terms_of_conditions_' . app()->getLocale()},
            'terms_of_use' => $this->{'terms_of_use_' . app()->getLocale()},
         ];
    }
}
