<?php

namespace App\Http\Controllers\Expenses;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Expenses\Oil;
use App\Http\Requests\Expense\OilRequest;

use DB;
use Auth;
use Carbon\Carbon;

class OilController extends Controller
{

    public function index(Request $request)
    {
        $query = Oil::query()->with('transaction.account.bank_info', 'loan.transactions', 'vendor_info', 'vehicle_info', 'challan_info', 'created_info', 'updated_info');

        if($request->input('onlyChallan')){
            $query = $query->whereNotNull('challan_id');
        }

        if($request->input('challan_id')){
            $query = $query->where('challan_id', $request->input('challan_id'));
        }

        if($request->input('challan_number')){
            $query = $query->whereHas('challan_info', function ($subQuery) use ($request) {
                $subQuery->where('challan_number', $request->input('challan_number'));
            });
        }

        if($request->input('transaction_id')){
            $query = $query->whereHas('transaction', function ($subQuery) use ($request) {
                $subQuery->where('encrypt', $request->input('transaction_id'));
            });
        }

        if($request->input('vehicle_id')){
            $query = $query->where('vehicle_id', $request->input('vehicle_id'));
        }

        if($request->input('vendor_id')){
            $query = $query->where('vendor_id', $request->input('vendor_id'));
        }

        if($request->input('voucher_number')){
            $query = $query->where('voucher_number', $request->input('voucher_number'));
        }

        // for time range
        $range_status = $request->input('range_status');
        $month = $request->input('month');
        $year = $request->input('year');
        $date_range = $request->input('daterange');

        if($range_status == 'monthly_report'){
            if(!$month){

                $data['message'] = 'please_select_month_first';
                return response()->json($data, 422);

            }
            if(!$year){

                $data['message'] = 'please_select_year_first';
                return response()->json($data, 422);

            }
            $query = $query->whereMonth('date_time', $month)->whereYear('date_time', $year);
        }

        if($range_status == 'yearly_report'){
            if(!$year){
                $data['message'] = 'please_select_year_first';
                return response()->json($data, 422);
            }
            $query = $query->whereYear('date_time', $year);
        }

        if($range_status == 'date_wise' && $date_range){
            
            $date = explode(',', $date_range);
            $start_date = Carbon::parse($date[0])->startOfDay();
            $end_date = Carbon::parse($date[1])->endOfDay();
            
            $query = $query->whereBetween('date_time', [$start_date, $end_date]);
        }

        if($request->input('orderType')){
            $query = $query->orderBy('id', $request->input('orderType'));
        }

        // if($request->input('paginate') == 'true'){
        //     $query = $query->paginate($request->input('perPage'), ['*'], 'page', $request->input('currentPage'));
        // } else{
        //     $query = $query->get();
        // }

        if($request->input('loanCalculation') == 'true'){

            if($request->input('paginate') == 'true'){
                $query = $query->paginate($request->input('perPage'), ['*'], 'page', $request->input('currentPage'))
                                ->through(function($item){
                                    return $this->addData($item);
                                });
            } else {
                $query = $query->get()->map(function($item) {
                    return $this->addData($item);
                });
            }

        } else {

            if($request->input('paginate') == 'true'){
                $query = $query->paginate($request->input('perPage'), ['*'], 'page', $request->input('currentPage'));
            } else{
                $query = $query->get();
            }
        }

        $data['lists'] = $query;
        return response()->json($data, 200);

    }

    public function addData($item){

        if($item['loan']){

            $trans_amount = 0;

            if($item['loan']['transactions']){
                foreach($item['loan']['transactions'] as $trans){
                    $trans_amount += $trans['amount'] ;
                }
            }

            $item['loan_paid_sum'] = $trans_amount;
        } else {
            $item['loan_paid_sum'] = 0;
        }

        return $item;
    }

    public function store(OilRequest $request)
    {

        try {

            $model = new Oil();
            $model->encrypt = uniqid();

            if($request->input('vendor_id') && $request->input('vendor_id')['id']){
                $model->vendor_id = $request->input('vendor_id')['id'];
            }

            if($request->input('vehicle_id') && $request->input('vehicle_id')['id']){
                $model->vehicle_id = $request->input('vehicle_id')['id'];
            }
            
            $model->challan_id = $request->input('challan_id');
            $model->voucher_number = $request->input('voucher_number');

            $model->date_time = Carbon::parse($request->input('date_time'))->format('Y-m-d');
            $model->liter = $request->input('liter');
            $model->rate = $request->input('rate');
            $model->amount = $request->input('amount');
            $model->note = $request->input('note');

            $model->company_id = Auth::user()->company_id;
            $model->created_by = Auth::user()->id;
            $model->save();

            // loan enable check
            if($this->loan_feature() == 'enable' && $request->input('account_id') == 'loan'){

                $loans['providerable_id'] = $request->input('vendor_id')['id'];
                $loans['providerable_type'] = 'vendors';

                $loans['purposeable_id'] = $model->id;;
                $loans['purposeable_type'] = 'expense_oils';

                $loans['type'] = 'in'; // loan received
                $loans['amount'] =  $request->input('amount');
                $loans['date_time'] = Carbon::parse($request->input('date_time'))->format('Y-m-d');
                $loans['note'] = $request->input('note');
                $loanId = $this->loanTrans($loans);
            }

            // transection
            if($this->payment_feature() == 'enable' && $request->input('account_id') != 'loan'){

                // transection
                $trans['account_id'] = $request->input('account_id');
                $trans['transactionable_id'] = $model->id;
                $trans['transactionable_type'] = 'expense_oils';
                $trans['type'] = 'out';
                $trans['amount'] =  $request->input('amount');
                $trans['date_time'] = Carbon::parse($request->input('date_time'))->format('Y-m-d');
                $trans['note'] = $request->input('note');
                $transId = $this->insertTrans($trans);

            }

            $data['message'] = 'successfully_posted';
            $data['data'] = $model->load('vendor_info');

            return response()->json($data, 200);

        }catch (\Exception $e) {
            $data['message'] = 'did_not_posted';
            return response()->json($data, 500);
        }
    }

    public function update(Request $request, $id)
    {

        if($request->input('amount') <= 0){
            $data['message'] = 'give_the_correct_amount';
            return response()->json($data, 422);
        }

        $this->validate($request,[
            'date_time' => 'required',
            'vendor_id' => 'required',
            'account_id' => 'required',
            'liter' => 'required',
            'rate' => 'required',
            'amount' => 'required',
        ]);

        try{

            $model = Oil::where('id', $id)->with('transaction')->first();
            $model->vendor_id = $request->input('vendor_id');
            $model->vehicle_id = $request->input('vehicle_id');
            $model->challan_id = $request->input('challan_id');
            $model->voucher_number = $request->input('voucher_number');

            $model->date_time = $request->input('date_time');
            $model->liter = $request->input('liter');
            $model->rate = $request->input('rate');
            $model->amount = $request->input('amount');
            $model->note = $request->input('note');
            
            $model->updated_by = Auth::user()->id;
            $model->save();

            // fetch old transection for delete
            $transection = $model->transaction()->first();

            // if new account and old account is not same
            if($request->input('account_id') != $model->transaction->account_id){

                // trasection delete
                if($transection){
                    $transection->account()->increment('balance', $transection->amount, ['updated_by'=> Auth::user()->id]);
                    $transection->delete();
                }

                // insert new transection
                $trans['account_id'] = $request->input('account_id');
                $trans['transactionable_id'] = $model->id;
                $trans['transactionable_type'] = 'expense_oils';
                $trans['type'] = 'out';
                $trans['amount'] =  $request->input('amount');
                $trans['date_time'] =  Carbon::parse($request->input('date_time'))->format('Y-m-d');
                $trans['note'] = $request->input('note');
                $transId = $this->insertTrans($trans);

            // if keep old account but amount is changed
            } elseif ($request->input('amount') != $transection->amount){

                // updated amount is bigger then old then increment
                if($request->input('amount') > $transection->amount){

                    $added_amount = $request->input('amount') - $transection->amount;
                    $transection->update(['amount'=> $request->input('amount'), 'date_time'=> $request->input('date_time'), 'updated_by'=> Auth::user()->id]);

                    // added amount need to decrease from current account
                    $transection->account()->decrement('balance', $added_amount, ['updated_by'=> Auth::user()->id]);

                // updated amount is smaller then old then decriment
                } elseif($request->input('amount') < $transection->amount){

                    $minus_amount = $transection->amount - $request->input('amount');
                    $transection->update(['amount'=> $request->input('amount'), 'date_time'=> $request->input('date_time'), 'updated_by'=> Auth::user()->id]);

                    // minus amount need to increase from current account
                    $transection->account()->increment('balance', $minus_amount, ['updated_by'=> Auth::user()->id]);
                
                }

            // if old date_time and new date_time is not same 
            } elseif ($request->input('date_time') != $transection->date_time){

                $transection->update(['date_time'=> $request->input('date_time'), 'updated_by'=> Auth::user()->id]);
            }

            DB::commit();
            $data['message'] = 'successfully_updated';
            return response()->json($data, 200);

        }catch (\Exception $e) {
            $data['message'] = 'did_not_updated';
            return response()->json($data, 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {

            $model = Oil::find($id);

            // has transection
            if($model->transaction()->exists()){

                // fetch transection
                $transection = $model->transaction()->first();

                // trasection update
                $transection->account()->increment('balance', $transection->amount, ['updated_by'=> Auth::user()->id]);
                $transection->update(['updated_by'=> Auth::user()->id]);
                $transection->delete();
            }

            // has loan
            if($model->loan()->exists()){

                // fetch transection
                $loan = $model->loan()->first();

                // has transection
                if($loan->transactions()->exists()){

                    // delete each transection
                    foreach($loan->transactions as $transaction){

                        $transaction->account()->increment('balance', $transaction->amount, ['updated_by'=> Auth::user()->id]);
                        $transaction->update(['updated_by'=> Auth::user()->id]);
                        $transaction->delete();

                    }
                }

                // loan delete
                $loan->update(['updated_by'=> Auth::user()->id]);
                $loan->delete();
            }
            
            // or transection delete
            $model->update(['updated_by'=> Auth::user()->id]);
            $model->delete();

            DB::commit();
            $data['message'] = 'successfully_deleted';
            return response()->json($data, 200);

        } catch (\Exception $e) {

            DB::rollBack();
            $data['message'] = 'something_went_wrong';
            return response()->json($data, 500);

        }
    }

}