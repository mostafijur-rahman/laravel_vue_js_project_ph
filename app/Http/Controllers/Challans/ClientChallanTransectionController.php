<?php

namespace App\Http\Controllers\Challans;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Challans\Challan;
use App\Models\Challans\ChallanOwnVehicle;
use App\Models\Challans\ChallanClient;
use App\Models\Challans\ChallanClientTrans;


use App\Http\Requests\Challan\ChallanClientTransRequest;

use DB;
use Auth;

class ClientChallanTransectionController extends Controller
{


    public function index(Request $request)
    {

        $query = ChallanClientTrans::query()->with('challan_client_info.challan_info.own_vehicle.vehicle_info', 'challan_client_info.client_info', 'transaction.account');
    
        if($request->input('challan_client_id')){
            $query = $query->where('challan_client_id', $request->input('challan_client_id'));
        }

        if($request->input('orderBy')){
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
    
    public function store(ChallanClientTransRequest $request)
    {   

        DB::beginTransaction();
        try {
            
            $model = new ChallanClientTrans();

            $model->encrypt = uniqid();
            $model->challan_client_id = $request->input('challan_client_id');
            $model->type = $request->input('type');
            $model->voucher_number = $request->input('voucher_number');
            $model->note = $request->input('note');

            $model->created_by = Auth::user()->id;
            $model->company_id = Auth::user()->company_id;
            $model->save();

            // transection
            $trans['account_id'] = $request->input('account_id');
            $trans['transactionable_id'] = $model->id;
            $trans['transactionable_type'] = 'challan_client_trans';
            $trans['type'] = 'in';
            $trans['amount'] =  $request->input('amount');
            $trans['date_time'] = $request->input('date_time');
            $trans['note'] = $request->input('note');
            $transId = $this->insertTrans($trans);

            DB::commit();
            $data['message'] = 'successfully_posted';
            $data['data'] = $model->load('transaction.account', 'challan_client_info');
            return response()->json($data, 200);

        } catch (\Exception $e) {
            DB::rollBack();
            $data['message'] = 'something_went_wrong';
            return response()->json($data, 500);
        }
    }

    public function update(Request $request, $id){

        try {

            $model = ChallanClient::whereId($id)->first();
            $model->client_id = $request->input('client_id');
            $model->contract_fare = $request->input('contract_fare');
            $model->demarage_fare = $request->input('demarage_fare');
            $model->demarage_fare = $request->input('demarage_fare');
            $model->save();

            $data['data'] = ChallanClient::whereId($model->id)->with('client_info')->first();
            $data['message'] = 'successfully_post_updated';
            return response()->json($data, 200);

        } catch (\Exception $e) {
            $data['message'] = 'something_went_wrong';
            return response()->json($data, 500);
        }

    }

    public function destroy($id)
    {
        // DB::beginTransaction();
        // try {

            $model = ChallanClientTrans::find($id);
            // loan delete

            // fetch transection
            $transection = $model->transaction()->first();
            if($transection){
                // trasection update
                $transection->account()->decrement('balance', $transection->amount, ['updated_by'=> Auth::user()->id]);
                $transection->delete();
            }
            
            // main model detele
            $model->update(['updated_by'=> Auth::user()->id]);
            $model->delete();

            // DB::commit();
            $data['message'] = 'successfully_deleted';
            return response()->json($data, 200);

        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     $data['message'] = 'something_went_wrong';
        //     return response()->json($data, 500);
        // }
    }

    public function allTransOfAChallan(Request $request)
    {

        $query = Challan::query()->with(
            'client.client_info',
            'client.all_trans.transaction',
            'client.advance_and_bill_deposit_trans.transaction',
            'client.advance_deposit_trans.transaction',
            'client.bill_deposit_trans.transaction',
            'client.demurrage_deposit_trans.transaction',

        )->where('challan_number', $request->input('challan_id'))
        ->first();

        $data['list'] = $query;
        return response()->json($data, 200);

    }

}