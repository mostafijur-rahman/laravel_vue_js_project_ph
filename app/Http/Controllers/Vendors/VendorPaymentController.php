<?php

namespace App\Http\Controllers\Vendors;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Vendors\VendorPayment;

use DB;
use Auth;
use Carbon\Carbon;

class VendorPaymentController extends Controller
{

    public function index(Request $request)
    {

        $query = VendorPayment::query()->with('vendor_info','purposeable','transaction.account.bank_info');

        if($request->input('purposeable_id')){
            $query->where('purposeable_type', $request->input('purposeable_type'))
                    ->where('purposeable_id', $request->input('purposeable_id'));
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

        if($request->input('vendor_id')){
            $query = $query->where('vendor_id', $request->input('vendor_id'));
        }

        if($request->input('security_money') == 'true'){
            $query = $query->whereIn('payment_type', ['received_money', 'paid_money']);
        } else {
            $query = $query->whereNotIn('payment_type', ['received_money', 'paid_money']);
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

            $payment_type = $request->input('payment_type');

            $model = new VendorPayment();
            $model->encrypt = uniqid();

            $model->vendor_id = $request->input('vendor_id');
            $model->payment_type = $payment_type;

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

                switch ($payment_type) {
                    case 'after_advance_rent':
                    case 'advance_rent':
                    case 'paid_money':
                        $type = 'out';
                        break;

                    case 'received_money':
                        $type = 'in';
                        break;
                    
                    default:
                        $type = 'out';
                        break;
                }

                $trans['account_id'] = $request->input('account_id');
                $trans['transactionable_id'] = $model->id;
                $trans['transactionable_type'] = 'vendor_payments';
                $trans['type'] = $type;
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

            $model = VendorPayment::find($id);

            // if has transection
            if($model->transaction){

                // update balance in account
                switch ($model->transaction->type) {
                    case 'out':
                        $model->transaction->account->increment('balance', $model->transaction->amount, ['updated_by'=> Auth::user()->id]);
                        break;

                    case 'in':
                        $model->transaction->account->decrement('balance', $model->transaction->amount, ['updated_by'=> Auth::user()->id]);
                        break;
                }

                // destroy transection
                $model->transaction->update(['updated_by'=> Auth::user()->id]);
                $model->transaction->delete();
            }

            // destroy Vendor Payment
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