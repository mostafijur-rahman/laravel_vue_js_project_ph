<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Accounts\Account;
use App\Models\Accounts\AccountTransection;

use Spatie\Activitylog\Models\Activity;

use DB;
use Auth;
use Carbon\Carbon;

class BalanceTransferController extends Controller
{

    public function store(Request $request)
    {

        if($request->input('amount') <= 0){
            $data['message'] = 'give_the_correct_amount';
            return response()->json($data, 422);
        }
        if($request->input('sender_account_id') == $request->input('receiver_account_in')){
            $data['message'] = 'balance_cannot_be_transferred_to_the_same_account';
            return response()->json($data, 422);
        }
        // if($request->input('amount') > Account::find($request->input('sender_account_id'))->balance){
        //     Toastr::error('',__('cmn.there_is_no_balance_in_the_senders_account_so_the_balance_transfer_is_not_acceptable'));
        //     return redirect()->back();
        // }

        $this->validate($request,[
            'date_time' => 'required',
            'sender_account_id' => 'required',
            'receiver_account_in' => 'required',
            'amount' => 'required',
        ]);

        DB::beginTransaction();
        try {

            // out transection
            $trans = [];
            $trans['account_id'] = $request->input('sender_account_id');
            $trans['transactionable_id'] = '';
            $trans['transactionable_type'] = 'account_trans';
            $trans['type'] = 'out';
            $trans['amount'] =  $request->input('amount');
            $trans['date_time'] = Carbon::parse($request->input('date_time'))->toDateTimeString();

            $trans['note'] = $request->input('note');
            $transId = $this->insertTrans($trans);

            // in transection
            $InTrans = [];
            $InTrans['account_id'] = $request->input('receiver_account_in');
            $InTrans['transactionable_id'] =  $transId;
            $InTrans['transactionable_type'] = 'account_trans';
            $InTrans['type'] = 'in';
            $InTrans['amount'] =  $request->input('amount');
            $InTrans['date_time'] = $trans['date_time'];

            $InTrans['note'] = $request->input('note');
            $InTransId = $this->insertTrans($InTrans);

            $accountTrans = AccountTransection::where('id', $transId)->first();
            $accountTrans->transactionable_id = $InTransId;
            $accountTrans->save();

            DB::commit();
            $data['message'] = 'successfully_posted';
            $data['data'] = AccountTransection::whereIn('id', [$transId, $InTransId])->with('account', 'created_by')->get();
            return response()->json($data, 200);

        } catch (\Exception $e) {
            DB::rollBack();
            $data['message'] = 'did_not_posted';
            return response()->json($data, 422);
        }
    }

    // public function destroy($id)
    // {
    //     try {

    //         $model = Account::find($id);
    //         $model->update(['updated_by'=> Auth::user()->id]);
    //         $model->delete();

    //         $data['message'] = 'successfully_deleted';
    //         return response()->json($data, 200);

    //     } catch (\Exception $e) {
    //         $data['message'] = 'something_went_wrong';
    //         return response()->json($data, 500);
    //     }
    // }

}