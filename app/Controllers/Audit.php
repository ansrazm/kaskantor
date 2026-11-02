<?php

namespace App\Controllers;

use App\Models\AuditModel;

class Audit extends BaseController
{
    public function index()
    {
        $m = new AuditModel();
        $rows = $m->select('audit_logs.*, users.name AS user_name')
            ->join('users', 'users.id = audit_logs.user_id', 'left')
            ->orderBy('audit_logs.id', 'DESC')
            ->paginate(20);

        return view('audit/index', ['rows' => $rows, 'pager' => $m->pager]);
    }
}
