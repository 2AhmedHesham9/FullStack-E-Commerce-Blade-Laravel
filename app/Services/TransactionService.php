<?php
namespace App\Services;

use App\Models\Transaction;

class TransactionService {
    public function store_order_Transaction($mode,$user_id,$order_id){
        if ($mode == "card") {
            //
        } elseif ($mode == "paypal") {
            //
        } elseif ($mode == "cod") {

            $transaction = new Transaction();
            $transaction->user_id = $user_id;
            $transaction->order_id = $order_id;
            $transaction->mode = $mode;
            $transaction->save();

        }
    }
}
