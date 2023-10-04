<?php

namespace App\Http\Controllers\Vendors;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Vendor\VendorBillRequest;

use App\Models\Vendors\VendorBill;

use DB;
use Auth;
use Carbon\Carbon;

class VendorBillController extends Controller
{

    public function index(Request $request)
    {

        $query = VendorBill::query()->with('vendor_info','purposeable');

        if($request->input('purposeable_id')){
            $query->where('purposeable_type', $request->input('purposeable_type'))
                    ->where('purposeable_id', $request->input('purposeable_id'));
        }

        if($request->input('vendor_id')){
            $query = $query->where('vendor_id', $request->input('vendor_id'));
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

    public function store(VendorBillRequest $request)
    {

        DB::beginTransaction();
        try {

            $model = new VendorBill();
            $model->encrypt = uniqid();

            $model->purposeable_type = $request->input('purposeable_type');
            $model->purposeable_id  = $request->input('purposeable_id');
            $model->vendor_id = $request->input('vendor_id')['id'];

            $model->bill_type = $request->input('bill_type');
            $model->date_time = Carbon::parse($request->input('date_time'))->format('Y-m-d');

            $model->amount = $request->input('amount');
            $model->note = $request->input('note');    
        
            $model->company_id = Auth::user()->company_id;
            $model->created_by = Auth::user()->id;
            $model->save();

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
        try {
            $model = VendorBill::find($id);
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