<?php
namespace App\Services\User;

use App\Models\User;

use App\Traits\HasImage;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\InsuranceNotFoundException;


class ProfileService
{
    use HasImage;
    public function profile()
    {
        $user = auth('sanctum')->user();
        return $user;
    }

    public function updateProfile($data)
    {
        $user = auth('sanctum')->user();
        if (isset($data['image'])) {
            $data['image'] = $this->saveImage($data['image'], 'user');
        }
        $user->update($data);
        return $user;
    }

}
