<?php

namespace App\Models;

use CodeIgniter\Model;

class TopUpsModel extends Model
{
    protected $table = 'top_ups';
    protected $primaryKey = 'top_up_id';
    protected $allowedFields = ['top_up_id', 'user_id', 'amount_top_up', 'balance_before', 'balance_after', 'status', 'created_date'];
    protected $useAutoIncrement = false;
}
