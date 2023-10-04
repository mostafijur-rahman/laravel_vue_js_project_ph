<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Clients\Client;
use App\Models\Clients\ClientPayment;

use DB;
use Auth;
use Carbon\Carbon;

class ClientPaymentController extends Controller
{

    public function index(Request $request)
    {

        $query = ClientPayment::query()->with('client_info', 'purposeable', 'transaction.account.bank_info');

        if($request->input('client_id')){
            $query = $query->where('client_id', $request->input('client_id'));
        }

        if($request->input('challan_number')){
            $value = $request->input('challan_number');
            $query = $query->whereHas('purposeable', function($subQuery) use($value) {
                $subQuery->where('challan_number', $value);
            });
        }

        if($request->input('voucher_number')){
            $query = $query->where('voucher_number', $request->input('voucher_number'));
        }

        if($request->input('vehicle_number')){
            $value = $request->input('vehicle_number');
            
            $query = $query->whereHas('purposeable.rental_vehicle', function($subQuery) use($value) {
                $subQuery->where('vehicle_number', $value);
            });
        }

        if($request->input('purposeable_type')){
            $query = $query->where('purposeable_type', $request->input('purposeable_type'));
        }

        if($request->input('purposeable_id')){
            $query = $query->where('purposeable_id', $request->input('purposeable_id'));
        }

        if($request->input('orderType')){
            $query = $query->orderBy('id', $request->input('orderType'));
        }

        if($request->input('paginate') == 'true'){
            $query = $query->paginate($request->input('perPage'), ['*'], 'page', $request->input('currentPage'));
        } else{
            $query = $query->get();
        }

        $data['lists'] = $query;
        return response()->json($data, 200);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {

            $model = new ClientPayment();
            $model->encrypt = uniqid();

            $model->client_id = $request->input('client_id');
            $model->payment_type = $request->input('payment_type');

            $model->purposeable_type = $request->input('purposeable_type');
            $model->purposeable_id = $request->input('purposeable_id');
            
            $model->voucher_number = $request->input('voucher_number');
            $model->date_time = Carbon::parse($request->input('date_time'))->format('Y-m-d');
            $model->amount = $request->input('amount');
            $model->note = $request->input('note');    
            
            $model->company_id = Auth::user()->company_id;
            $model->created_by = Auth::user()->id;
            $model->save();

            // transection
            if($this->payment_feature() == 'enable' && $request->input('account_id') != 'loan'){

                $trans['account_id'] = $request->input('account_id');
                $trans['transactionable_id'] = $model->id;
                $trans['transactionable_type'] = 'client_payments';
                $trans['type'] = 'in';
                $trans['amount'] =  $request->input('amount');
                $trans['date_time'] = Carbon::parse($request->input('date_time'))->format('Y-m-d');
                $trans['note'] = $request->input('note');
                $transId = $this->insertTrans($trans);
            }

            DB::commit();
            $data['message'] = 'successfully_posted';
            $data['data'] = $model;
            return response()->json($data, 200);

        } catch (\Exception $e) {

            DB::rollBack();
            $data['message'] = 'something_went_wrong';
            return response()->json($data, 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $model = ClientPayment::find($id);

            // if has transection
            if($model->transaction){
                // update balance in account
                $model->transaction->account->decrement('balance', $model->transaction->amount, ['updated_by'=> Auth::user()->id]);

                // destroy transection
                $model->transaction->update(['updated_by'=> Auth::user()->id]);
                $model->transaction->delete();
            }

            // destroy Client Payment
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