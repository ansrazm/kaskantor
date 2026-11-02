<?php

namespace App\Models;

use CodeIgniter\Model;

class AuditModel extends Model
{
    protected $table         = 'audit_logs';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['user_id', 'action', 'table_name', 'record_id', 'detail', 'ip_address', 'created_at'];
    protected $useTimestamps = false;
}
