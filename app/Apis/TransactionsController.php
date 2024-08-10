<?php

namespace App\Apis;

use App\Models\BalanceModel;
use App\Models\TopUpsModel;
use App\Models\PaymentsModel;
use App\Models\TransfersModel;
use App\Models\UsersModel;
use CodeIgniter\RESTful\ResourceController;

class TransactionsController extends ResourceController
{
    protected $balanceModel;
    protected $topUpsModel;
    protected $paymentsModel;
    protected $transfersModel;
    protected $usersModel;

    public function __construct()
    {
        $this->balanceModel = new BalanceModel();
        $this->topUpsModel = new TopUpsModel();
        $this->paymentsModel = new PaymentsModel();
        $this->transfersModel = new TransfersModel();
        $this->usersModel = new UsersModel();
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
            'status' => 'SUCCESS',
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
            'status' => 'SUCCESS',
            'created_date' => date('Y-m-d H:i:s'),
        ];

        $this->paymentsModel->insert($paymentData);

        return $this->respond([
            'status' => 'SUCCESS',
            'result' => $paymentData,
        ]);
    }

    public function transfer()
    {

        $user = $this->request->user;

        $targetUser = $this->request->getVar('target_user');
        $amount = $this->request->getVar('amount');
        $remarks = $this->request->getVar('remarks');

        $balance = $this->balanceModel->where('user_id', $user->sub)->first();

        if (!$balance || $balance['balance'] < $amount) {
            return $this->respond(['message' => 'Balance is not enough']);
        }

        $target = $this->usersModel->where('user_id', $targetUser)->first();

        if (!$target) {
            return $this->respond(['message' => 'Target user not found']);
        }

        $balanceBefore = $balance['balance'];
        $balanceAfter = $balanceBefore - $amount;

        $this->balanceModel->update($user->sub, ['balance' => $balanceAfter]);

        $targetBalance = $this->balanceModel->where('user_id', $targetUser)->first();

        $targetBalanceAfter = $targetBalance['balance'] ?? 0 + $amount;

        if ($targetBalance) {
            $this->balanceModel->update($targetUser, ['balance' => $targetBalanceAfter]);
        } else {
            $this->balanceModel->insert(['user_id' => $targetUser, 'balance' => $targetBalanceAfter]);
        }

        $transferData = [
            'transfer_id' => uuid(),
            'user_id' => $user->sub,
            'target_user_id' => $targetUser,
            'amount' => $amount,
            'remarks' => $remarks,
            'status' => 'SUCCESS',
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
            'created_date' => date('Y-m-d H:i:s'),
        ];

        $this->transfersModel->insert($transferData);

        return $this->respond([
            'status' => 'SUCCESS',
            'result' => $transferData,
        ]);

        // return $this->respond([
        //     'status' => 'SUCCESS',
        //     'result' => $balance,
        // ]);
    }

    public function transactions()
    {
        $user = $this->request->user;
        $transfers = $this->transfersModel->where('user_id', $user->sub)->findAll();
        $payments = $this->paymentsModel->where('user_id', $user->sub)->findAll();
        $topups = $this->topUpsModel->where('user_id', $user->sub)->findAll();

        $response = [
            'status' => 'SUCCESS',
            'result' => [
                array_merge($transfers, $payments, $topups)
            ]
        ];

        return $this->respond($response);
    }
}
