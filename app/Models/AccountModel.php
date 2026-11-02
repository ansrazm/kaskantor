<?php

namespace App\Models;

use CodeIgniter\Model;

class AccountModel extends Model
{
    protected $table         = 'accounts';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['code', 'name', 'type', 'is_active'];
    protected $useTimestamps = true;

    public function active(): array
    {
        return $this->where('is_active', 1)->orderBy('code')->findAll();
    }
}
