<?php
namespace App\Services\Vendor;


use App\Traits\HasImage;



class ProfileService
{
    use HasImage;
   public function profile()
{
    $user = auth('vendor')->user();
    return $user;
}

    public function updateProfile($data)
    {
        $user = auth('vendor')->user();
        if (isset($data['image'])) {
            $data['image'] = $this->saveImage($data['image'], 'user');
        }
        if (isset($data['logo'])) {
            $data['logo'] = $this->saveImage($data['logo'], 'Vendor');
        }
        if (isset($data['background'])) {
            $data['background'] = $this->saveImage($data['background'], 'Vendor');
        }
        $user->update($data);
        return $user;
    }

}
