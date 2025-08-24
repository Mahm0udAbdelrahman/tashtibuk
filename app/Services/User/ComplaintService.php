<?php

namespace App\Services\User;

use App\Models\Complaint;


class ComplaintService
{
    public function __construct(public Complaint $complaint){}

    

    public function store($data)
    {
        $data['user_id'] = auth()->id();
        return $this->complaint->create($data);
    }



}
