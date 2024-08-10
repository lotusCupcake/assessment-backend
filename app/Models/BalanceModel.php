<?php

namespace App\Models;

use CodeIgniter\Model;

class BalanceModel extends Model
{
    protected $table = 'balances';
    protected $primaryKey = 'user_id';
    protected $allowedFields = ['user_id', 'balance'];
    protected $useAutoIncrement = false;
}
