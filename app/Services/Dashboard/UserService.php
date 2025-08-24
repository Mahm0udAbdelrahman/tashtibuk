<?php
namespace App\Services\Dashboard;

use App\Models\User;
use App\Traits\HasImage;
use Illuminate\Support\Facades\DB;

class UserService
{
    use HasImage;
    public function __construct(public User $model)
    {}

    public function index()
    {
        return $this->model->latest()->paginate();
    }

    public function store($data)
    {
        if (isset($data['image'])) {
            $data['image'] = $this->saveImage($data['image'], 'User');
        } else {
            $data['image'] = asset('default/default.png');
        }



       $user = $this->model->create($data);
        if(isset($data['role_id']))
        {
            DB::table('model_has_roles')->insert([
                'model_type' => 'App\\Models\\User',
                'model_id' => $user->id,
                'role_id' => $data['role_id']
            ]);
        }
        return $user;

    }

    public function show($id)
    {
        return $this->model->findOrFail($id);
    }

    public function update($id, $data)
    {
        $user = $this->show($id);
        if (isset($data['image'])) {
            $data['image'] = $this->saveImage($data['image'], 'User');
        } else {
            $data['image'] = asset('default/default.png');
        }


         $user->update($data);

        if (isset($data['role_id']) && !empty($data['role_id'])) {
            $criteria = ['model_id' => $user->id];
            $attributes = [
                'model_type' => 'App\\Models\\User',
                'model_id' => $user->id,
                'role_id' => $data['role_id']
            ];
            DB::table('model_has_roles')->updateOrInsert($criteria, $attributes);
        } else {
            DB::table('model_has_roles')->where('model_id', $user->id)->delete();
        }
         return $user;
    }

    public function delete($id)
    {
        $User = $this->show($id);
        $User->delete();
    }

}
