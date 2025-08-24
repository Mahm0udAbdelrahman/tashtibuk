<?php

namespace App\Services\Dashboard;

use App\Models\Complaint;


class ComplaintService
{
    public function __construct(public Complaint $complaint){}

    public function index()
    {
        return $this->complaint->paginate();
    }

    public function show($id)
    {
        return $this->complaint->findOrFail($id);
    }

    public function destory($id)
    {
        $complaint = $this->show($id);
        $complaint->delete();
    }





}
