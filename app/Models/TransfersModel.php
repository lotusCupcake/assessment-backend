<?php

namespace App\Models;

use CodeIgniter\Model;

class TransfersModel extends Model
{
    protected $table = 'transfers';
    protected $primaryKey = 'transfer_id';
    protected $allowedFields = ['transfer_id', 'user_id', 'target_user_id', 'amount', 'remarks', 'balance_before', 'balance_after', 'created_date', 'status'];
    protected $useAutoIncrement = false;
}
