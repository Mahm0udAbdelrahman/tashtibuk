<?php
namespace App\Services\Dashboard;

use App\Models\Setting;

class SettingService
{
    public function __construct(public Setting $model)
    {}

    public function index()
    {
        return $this->model->first();
    }

    public function update($data)
    {
        return $this->model->updateOrCreate([], $data);
    }

}
