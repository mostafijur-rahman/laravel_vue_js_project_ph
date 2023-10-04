<?php 

namespace App\Traits;
use Auth;
use Setting;

trait CommonTrait {

	public function max_execution_time_and_backtrack_limit(){
                ini_set('max_execution_time', '600'); // 10 minutes
                ini_set("pcre.backtrack_limit", "5000000");
	}

        public function loan_feature(){
                Setting::setExtraColumns(['company_id' => Auth::user()->company_id]);
                return Setting::get('company.loan_feature');
        }

        public function payment_feature(){
                Setting::setExtraColumns(['company_id' => Auth::user()->company_id]);
                return Setting::get('company.payment_feature');
        }

        public function set_setting(){
                Setting::setExtraColumns(['company_id' => Auth::user()->company_id]);
        }

}