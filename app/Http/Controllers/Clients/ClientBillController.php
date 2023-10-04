<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Client\ClientBillRequest;

use App\Models\Clients\Client;
use App\Models\Clients\ClientBill;

use DB;
use Auth;
use Carbon\Carbon;

class ClientBillController extends Controller
{

    public function index(Request $request)
    {

        $query = ClientBill::query()->with('client_info', 'purposeable.rental_vehicle', 'transaction');

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

        if($request->input('client_id') && !empty($request->input('client_id'))){
            $query = $query->where('client_id', $request->input('client_id'));
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

    public function store(ClientBillRequest $request)
    {
        DB::beginTransaction();
        try {

            $model = new ClientBill();
            $model->encrypt = uniqid();

            $model->purposeable_type = $request->input('purposeable_type');
            $model->purposeable_id  = $request->input('purposeable_id');
            $model->client_id = $request->input('client_id')['id'];

            $model->bill_type = $request->input('bill_type');
            $model->date_time = Carbon::parse($request->input('date_time'))->format('Y-m-d');
            $model->amount = $request->input('amount');
            $model->voucher_number = $request->input('voucher_number'); 
            $model->note = $request->input('note'); 
               
            $model->company_id = Auth::user()->company_id;
            $model->created_by = Auth::user()->id;
            $model->save();

            DB::commit();
            $data['message'] = 'successfully_posted';
            $data['data'] = $model->load('client_info');
            return response()->json($data, 200);

        } catch (\Exception $e) {

            DB::rollBack();
            $data['message'] = 'something_went_wrong';
            return response()->json($data, 500);
        }
    }

    public function destroy($id)
    {
        try {
            $model = ClientBill::find($id);
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