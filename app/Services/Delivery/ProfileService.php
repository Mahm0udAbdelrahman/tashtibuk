<?php
namespace App\Services\Delivery;


use App\Traits\HasImage;



class ProfileService
{
    use HasImage;
   public function profile()
{
    $delivery = auth('delivery')->user();
    return $delivery;
}

    public function updateProfile($data)
    {
        $delivery = auth()->guard('delivery')->user();
        if (isset($data['image'])) {
            $data['image'] = $this->saveImage($data['image'], 'delivery');
        }
        if (isset($data['id_card'])) {
            $data['id_card'] = $this->saveImage($data['id_card'], 'Delivery');
        }
        if (isset($data['driving_license'])) {
            $data['driving_license'] = $this->saveImage($data['driving_license'], 'Delivery');
        }

         if (isset($data['vehicle_license'])) {
            $data['vehicle_license'] = $this->saveImage($data['vehicle_license'], 'Delivery');
        }

        $delivery->update($data);
        return $delivery;
    }
    
   public function location($data)
    {
        $delivery = auth('delivery')->user();
        $delivery->update($data);
        return $delivery;
    }

}
