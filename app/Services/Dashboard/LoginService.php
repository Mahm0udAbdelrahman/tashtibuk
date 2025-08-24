<?php
namespace App\Services\Dashboard;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\InsuranceNotFoundException;

class LoginService
{
    public function __construct(public User $model)
    {}

    public function login($data)
    {
        $user = $this->model->where('email', $data['email'])->first();
        if (! $user) {
            throw new InsuranceNotFoundException(__('These credentials do not match our records.', [], Request()->header('Accept-language')));
        }

        if ($user && Hash::check($data['password'], $user->password)) {

            $token = $user->createToken("API TOKEN")->plainTextToken;
            return [$user, $token];

        }

        throw new InsuranceNotFoundException(__('These credentials do not match our records.', [], Request()->header('Accept-language')));
    }
    public function logout(): void
    {
        Auth::logout();
    }
}
