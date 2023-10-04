<?php

namespace App\Http\Controllers\Loans;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Accounts\AccountTransection;
use DB;
use Carbon\Carbon;
use Auth;

class LoanController extends Controller
{

    public function singleLoanPaymentStore(Request $request)
    {
        DB::beginTransaction();
        try {

            $trans['account_id'] = $request->input('account_id');
            $trans['transactionable_id'] = $request->input('loan_id');
            $trans['transactionable_type'] = 'loan_trans';
            $trans['type'] = 'out';
            $trans['amount'] = $request->input('amount');
            $trans['date_time'] = Carbon::parse($request->input('date_time'))->format('Y-m-d');
            $trans['note'] = $request->input('note');
            $model = $this->insertTransAndReturnModel($trans);
            DB::commit();

            $data['message'] = 'successfully_posted';
            $data['data'] = $model->load('account')->toArray();
            return response()->json($data, 200);

        } catch (\Exception $e) {

            DB::rollBack();
            $data['message'] = 'did_not_posted';
            return response()->json($data, 500);
        }
    }

    public function singleLoanPaymentDestroy($id)
    {
        try {

            $accountTrans = AccountTransection::where('id', $id)->first();
            $accountTrans->account()->increment('balance', $accountTrans->amount, ['updated_by'=> Auth::user()->id]);
            $accountTrans->update(['updated_by'=> Auth::user()->id]);
            $accountTrans->delete();

            $data['message'] = 'successfully_deleted';
            return response()->json($data, 200);

        } catch (\Exception $e) {

            $data['message'] = 'something_went_wrong';
            return response()->json($data, 422);

        }
    }

    // public function loanPaymentStore(Request $request)
    // {
    //     // #parameters: array:7 [
    //     //   "id" => null
    //     //   "date_time" => "2023-05-24"
    //     //   "account_id" => 1
    //     //   "voucher_number" => null
    //     //   "amount" => 1000
    //     //   "note" => null
    //     //   "items" => array:1 [
    //     //     0 => array:24 [
    //     //       "id" => 2
    //     //       "encrypt" => "646cec18c00fa"
    //     //       "vendor_id" => 6
    //     //       "vehicle_id" => null
    //     //       "challan_id" => 1
    //     //       "expense_id" => 4
    //     //       "voucher_number" => null
    //     //       "amount" => "1000.00"
    //     //       "date_time" => "2023-05-23 00:00:00"
    //     //       "note" => null
    //     //       "company_id" => 1
    //     //       "created_by" => 1
    //     //       "updated_by" => null
    //     //       "created_at" => "2023-05-23T16:38:48.000000Z"
    //     //       "updated_at" => "2023-05-23T16:38:48.000000Z"
    //     //       "deleted_at" => null
    //     //       "transaction" => null
    //     //     ]
    //     //   ]
    //     // dd( $request);

    //     DB::beginTransaction();
    //     try {

    //         // amount split process (distribute amount by loan amount)
    //         $main_amount = $request->input('amount');
    //         $remaining_amount = 0;

    //         $items = $request->input('items');
    //         if($items){
    //             foreach($items as  $key => $item){

    //                 if($key == 0){

    //                     $remaining_amount = $main_amount - $item['loan']['amount'];
                        
    //                 } else {

    //                     if($remaining_amount == 0){
    //                         $data['message'] = 'successfully_posted';
    //                         return response()->json($data, 200);
    //                     }

    //                     $remaining_amount = $remaining_amount - $item['loan']['amount'];
    //                 }

    //                 $trans['account_id'] = $request->input('account_id');
    //                 $trans['transactionable_id'] = $item['loan']['id'];
    //                 $trans['transactionable_type'] = 'loan_trans';
    //                 $trans['type'] = 'out';
    //                 $trans['amount'] = $item['loan']['amount'];
    //                 $trans['date_time'] = $request->input('date_time');
    //                 $trans['note'] = $request->input('note');
    //                 $this->insertTrans($trans);
    //             }
    //         }

    //         DB::commit();
    //         $data['message'] = 'successfully_posted';
    //         // $data['data'] = $model->load('expense_info', 'vendor_info');
    //         return response()->json($data, 200);

    //     } catch (\Exception $e) {

    //         DB::rollBack();
    //         $data['message'] = 'did_not_posted';
    //         return response()->json($data, 500);
    //     }
    // }


}