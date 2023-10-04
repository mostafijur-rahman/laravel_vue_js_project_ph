<?php

namespace App\Http\Controllers\Vendors;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Vendors\Vendor;

use DB;
use Auth;
use Carbon\Carbon;

class VendorController extends Controller
{

    public function index(Request $request)
    {

        $query = Vendor::query()->withCount('general_expenses', 'oil_expenses', 'loans', 'vendor_bills', 'vendor_payments', 'challan_rental_vehicles');

        if($request->input('type')){
            $query = $query->where('type', $request->input('type'));
        }

        if($request->input('name')){
            $query = $query->where('name', 'like', '%' . $request->input('name') . '%');
        }
        
        if($request->input('orderType')){
            $query = $query->orderBy('id', $request->input('orderType'));
        }

        if($request->input('paginate') == true){

            $query = $query->paginate($request->input('perPage'), ['*'], 'page', $request->input('currentPage'))
                                ->through(function($item){
                                    return $this->addData($item);
                                });
        } else {
            $query = $query->get()->map(function($item) {
                return $this->addData($item);
            });
        }

        $data['lists'] = $query;
        return response()->json($data, 200);

    }

    public function addData($item){

        $item['total_loan_sum'] = $item->previous_loan + $item->loans->sum('amount');

        $item['delete_status'] = true;
        if(
            $item['general_expenses_count'] > 0 ||  
            $item['oil_expenses_count'] > 0 || 
            $item['loans_count'] > 0 ||  
            $item['vendor_bills_count'] > 0 ||  
            $item['vendor_payments_count'] > 0 || 
            $item['challan_rental_vehicles_count'] > 0
        ){
            $item['delete_status'] = false;
        }

        return $item;
    }

    public function store(Request $request)
    {   
        $this->validate($request,[
            'name' => 'required'
        ]);

        try {

            $model = new Vendor();
            $model->encrypt = uniqid();
            $model->sort = 0;
            $model->name = $request->input('name');
            $model->phone = $request->input('phone');
            $model->address = $request->input('address');
            $model->note = $request->input('note');
            $model->status = $request->input('status');
            $model->type = $request->input('type');

            $model->previous_loan = $request->input('previous_loan')??0;
            if($request->input('loan_date') && $request->has('loan_date')){
                $model->loan_date = Carbon::parse($request->input('loan_date'))->format('Y-m-d');
            } else {
                $model->loan_date = null;
            }

            $model->previous_advance = $request->input('previous_advance')??0;
            if($request->input('advance_date') && $request->has('advance_date')){
                $model->advance_date = Carbon::parse($request->input('advance_date'))->format('Y-m-d');
            } else {
                $model->advance_date = null;
            }

            $model->company_id = Auth::user()->company_id;
            $model->created_by = Auth::user()->id;
            $model->save();

            $data['data'] = $model;
            $data['message'] = 'successfully_created';
            return response()->json($data, 200);

        } catch (\Exception $e) {

            $data['message'] = 'something_went_wrong';
            return response()->json($data, 500);

        }
    }

    public function show($id)
    {
        $data['data'] = Vehicle::whereId($id)->first();
        return response()->json($data, 200);
    }


    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'name' => 'required'
        ]);

        try{

            $model = Vendor::find($id);
            $model->name = $request->input('name');
            $model->phone = $request->input('phone');
            $model->address = $request->input('address');
            $model->note = $request->input('note');
            $model->status = $request->input('status');
            $model->type = $request->input('type');


            $model->previous_loan = $request->input('previous_loan');
            if($request->input('loan_date') && $request->has('loan_date')){
                $model->loan_date = Carbon::parse($request->input('loan_date'))->format('Y-m-d');
            } else {
                $model->loan_date = null;
            }

            $model->previous_advance = $request->input('previous_advance');
            if($request->input('advance_date') && $request->has('advance_date')){
                $model->advance_date = Carbon::parse($request->input('advance_date'))->format('Y-m-d');
            } else {
                $model->advance_date = null;
            }

            $model->updated_by = Auth::user()->id;
            $model->save();

            $data['message'] = 'successfully_updated';
            return response()->json($data, 200);

        }catch (\Exception $e) {
            $data['message'] = 'did_not_updated';
            return response()->json($data, 500);
        }
    }

    public function destroy($id)
    {
        try {

            $model = Vendor::find($id);
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