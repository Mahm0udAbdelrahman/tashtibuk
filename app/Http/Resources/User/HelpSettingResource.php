<?php

namespace App\Http\Resources\User;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HelpSettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"=> $this->id,
            'title' =>  $this->{'title_' . app()->getLocale()},
            'description' =>  $this->{'description_' . app()->getLocale()},
        ];
    }
}
