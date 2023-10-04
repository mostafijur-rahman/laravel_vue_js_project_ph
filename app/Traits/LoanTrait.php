<?php 

namespace App\Traits;


use App\Models\Loans\LoanTransection;
use Auth;

trait LoanTrait {

	public function loanTrans($data){

		try {

			$model = new LoanTransection();
			$model->encrypt = uniqid();

            $model->providerable_id = $data['providerable_id'];
            $model->providerable_type = $data['providerable_type'];

            $model->purposeable_id = $data['purposeable_id'];
            $model->purposeable_type = $data['purposeable_type'];

            $model->type = $data['type'];
			$model->amount = $data['amount'];
			$model->date_time = $data['date_time'];
			$model->note = $data['note'];

			$model->company_id = Auth::user()->company_id;
			$model->created_by = Auth::user()->id;
			$model->save();

            // return last insert id
			return $model->id;

		} catch(\Exception $e){
			return false;
		}
	}


}