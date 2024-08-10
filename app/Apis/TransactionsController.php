<?php

namespace App\Apis;

use App\Models\BalanceModel;
use App\Models\TopUpsModel;
use App\Models\PaymentsModel;
use CodeIgniter\RESTful\ResourceController;

class TransactionsController extends ResourceController
{
    protected $balanceModel;
    protected $topUpsModel;
    protected $paymentsModel;

    public function __construct()
    {
        $this->balanceModel = new BalanceModel();
        $this->topUpsModel = new TopUpsModel();
        $this->paymentsModel = new PaymentsModel();
    }

    public function topup()
    {
        $user = $this->request->user;
        $amount = $this->request->getVar('amount');

        $balances = $this->balanceModel->find($user->sub);
        $balanceBefore = $balances['balance'] ?? 0;

        $balanceAfter = $balanceBefore + $amount;

        $topUpData = [
            'top_up_id' => uuid(),
            'user_id' => $user->sub,
            'amount_top_up' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
            'status' => '1',
            'created_date' => date('Y-m-d H:i:s')
        ];

        $this->topUpsModel->insert($topUpData);

        if (!empty($balances)) {
            $this->balanceModel->update($user->sub, ['balance' => $balanceAfter]);
        } else {
            $this->balanceModel->insert(['user_id' => $user->sub, 'balance' => $balanceAfter]);
        }

        $response = [
            'status' => 'SUCCESS',
            'result' => $topUpData
        ];

        return $this->respond($response);
    }

    public function pay()
    {
        $user = $this->request->user;
        $amount = $this->request->getVar('amount');
        $remarks = $this->request->getVar('remarks');

        $balances = $this->balanceModel->find($user->sub);

        if (!$balances || $balances['balance'] < $amount) {
            return $this->respond(['message' => 'Balance is not enough']);
        }

        $balanceBefore = $balances['balance'];
        $balanceAfter = $balanceBefore - $amount;

        $this->balanceModel->update($user->sub, ['balance' => $balanceAfter]);

        $paymentData = [
            'payment_id' => uuid(),
            'user_id' => $user->sub,
            'amount' => $amount,
            'remarks' => $remarks,
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
            'status' => '1',
            'created_date' => date('Y-m-d H:i:s'),
        ];

        $this->paymentsModel->insert($paymentData);

        return $this->respond([
            'status' => 'SUCCESS',
            'result' => $paymentData,
        ]);
    }
}
