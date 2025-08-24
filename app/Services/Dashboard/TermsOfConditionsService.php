<?php
namespace App\Services\Dashboard;

use App\Models\TermsOfConditions;

class TermsOfConditionsService
{
    public function __construct(public TermsOfConditions $model)
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
