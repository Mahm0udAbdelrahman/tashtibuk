<?php
namespace App\Services\Dashboard;

use App\Models\Help;
use App\Traits\HasImage;

class HelpService
{
    use HasImage;
    public function __construct(public Help $model)
    {}

    public function index()
    {
        return $this->model->get();
    }

    public function store($data)
    {

        return $this->model->create($data);
    }

    public function show($id)
    {
        return $this->model->findOrFail($id);
    }

    public function update($id, $data)
    {
        $Help = $this->show($id);
      
         $Help->update($data);
         return $Help;
    }

    public function delete($id)
    {
        $Help = $this->show($id);
        $Help->delete();
    }

}
