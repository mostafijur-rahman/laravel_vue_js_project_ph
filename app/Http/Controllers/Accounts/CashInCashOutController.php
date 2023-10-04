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

class CashInCashOutController extends Controller
{

    public function store(Request $request)
    {

        if($request->input('amount') <= 0){
            $data['message'] = 'give_the_correct_amount';
            return response()->json($data, 422);
        }

        $this->validate($request,[
            'account_id' => 'required',
            'type' => 'required',
            'amount' => 'required',
        ]);

        DB::beginTransaction();
        try {

            // transection
            $trans = [];
            $trans['account_id'] = $request->input('account_id');
            $trans['transactionable_id'] = '';
            $trans['transactionable_type'] = 'account_trans';
            $trans['type'] = $request->input('type');
            $trans['amount'] =  $request->input('amount');

            // $trans['date_time'] = Carbon::parse($request->input('date_time'))->format('Y-m-d');
            // $trans['date_time'] = date('Y-m-d', strtotime($request->input('date_time')));
            // $trans['date_time'] = Carbon::parse($request->input('date_time'))->toDateTimeLocalString('Y-m-d');
            $trans['date_time'] = Carbon::parse($request->input('date_time'))->toDateTimeString();

            $trans['note'] = $request->input('note');
            $transId = $this->insertTrans($trans);

            DB::commit();
            $data['message'] = 'successfully_posted';
            $data['data'] = AccountTransection::whereId($transId)->with('account', 'created_by')->first();
            return response()->json($data, 200);

        } catch (\Exception $e) {
            DB::rollBack();
            $data['message'] = 'did_not_posted';
            return response()->json($data, 500);
        }
    }

    public function destroy($id)
    {
        try {

            $model = Account::find($id);
            $model->update(['updated_by'=> Auth::user()->id]);
            $model->delete();

            $data['message'] = 'successfully_deleted';
            return response()->json($data, 200);

        } catch (\Exception $e) {
            $data['message'] = 'something_went_wrong';
            return response()->json($data, 500);
        }
    }

}