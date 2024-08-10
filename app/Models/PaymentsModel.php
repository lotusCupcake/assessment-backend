<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentsModel extends Model
{
    protected $table = 'payments';
    protected $primaryKey = 'payment_id';
    protected $allowedFields = ['payment_id', 'user_id', 'amount', 'remarks', 'balance_before', 'balance_after', 'created_date', 'status'];
    protected $useAutoIncrement = false;
}
