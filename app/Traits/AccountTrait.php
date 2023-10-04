<?php 

namespace App\Traits;


use App\Models\Accounts\Account;
use App\Models\Accounts\AccountTransection;
use Auth;

trait AccountTrait {

	public function insertTrans($data){

		try {

			$model = new AccountTransection();
			$model->encrypt = uniqid();
			$model->account_id = $data['account_id'];

			if($data['transactionable_id'] == ''){
				$model->transactionable_id = null;
			} else {
				$model->transactionable_id = $data['transactionable_id'];
			}
			
			$model->transactionable_type = $data['transactionable_type'];
			$model->type = $data['type'];
			$model->amount = $data['amount'];
			$model->date_time = $data['date_time'];
			$model->note = $data['note'];

			$model->company_id = Auth::user()->company_id;
			$model->created_by = Auth::user()->id;
			$model->save();

			// if transection in current table
			if($data['transactionable_id'] == ''){
				$model->update(['transactionable_id'=> $model->id]);
			}

			// balance update on account
			$account = Account::where('id', $data['account_id'])->first();
			if($data['type'] == 'in'){
				$account->increment('balance', $data['amount'], ['updated_by'=> Auth::user()->id]);
			} else {
				$account->decrement('balance', $data['amount'], ['updated_by'=> Auth::user()->id]);
			}

			// data array need to reset
			$data = [];
			
			// return transection
			return $model->id;

		} catch(\Exception $e){
			return false;
		}
	}

	public function insertTransAndReturnModel($data){

		try {

			$model = new AccountTransection();
			$model->encrypt = uniqid();
			$model->account_id = $data['account_id'];

			if($data['transactionable_id'] == ''){
				$model->transactionable_id = null;
				$model->transactionable_type = null;
			} else {
				$model->transactionable_id = $data['transactionable_id'];
				$model->transactionable_type = $data['transactionable_type'];
			}

			$model->type = $data['type'];
			$model->amount = $data['amount'];
			$model->date_time = $data['date_time'];
			$model->note = $data['note'];

			$model->company_id = Auth::user()->company_id;
			$model->created_by = Auth::user()->id;
			$model->save();

			// if transection in current table
			if($data['transactionable_id'] == ''){
				$model->update(['transactionable_id'=> $model->id, 'transactionable_type'=> 'account_trans']);
			}

			// balance update on account
			$account = Account::where('id', $data['account_id'])->first();
			if($data['type'] == 'in'){
				$account->increment('balance', $data['amount'], ['updated_by'=> Auth::user()->id]);
			} else {
				$account->decrement('balance', $data['amount'], ['updated_by'=> Auth::user()->id]);
			}

			// data array need to reset
			$data = [];
			
			// return transection
			return $model;

		} catch(\Exception $e){
			return false;
		}
	}



}