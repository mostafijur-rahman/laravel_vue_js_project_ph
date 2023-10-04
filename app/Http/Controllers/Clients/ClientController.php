<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Clients\Client;

use App\Models\Challans\Challan;
use App\Models\Challans\ChallanOwnVehicle;

use DB;
use Auth;
use Carbon\Carbon;

class ClientController extends Controller
{

    public function index(Request $request)
    {

        $query = Client::query()->with('bills', 'payments')->withCount('bills', 'payments', 'loans');
    

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
            $item['bills_count'] > 0 ||  
            $item['payments_count'] > 0 || 
            $item['loans_count'] > 0
        ){
            $item['delete_status'] = false;
        }
        return $item;
    }

    public function store(Request $request)
    {
        // return $request;
        $this->validate($request,[
            'name' => 'required'
        ]);

        try {

            $model = new Client();
            $model->encrypt = uniqid();
            $model->sort = 0;
            $model->name = $request->input('name');
            $model->phone = $request->input('phone');
            $model->address = $request->input('address');
            $model->note = $request->input('note');
            $model->status = $request->input('status');

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

            $model->created_by = Auth::user()->id;
            $model->company_id = Auth::user()->company_id;
            $model->save();

            $data['data'] = $model;
            $data['message'] = 'successfully_created';
            return response()->json($data, 200);

        } catch (\Exception $e) {

            $data['message'] = 'something_went_wrong';
            return response()->json($data, 500);

        }
    }

    public function update(Request $request, $id)
    {
        // return $request;
        $this->validate($request,[
            'name' => 'required'
        ]);

        try{

            $model = Client::find($id);
            $model->name = $request->input('name');
            $model->phone = $request->input('phone');
            $model->address = $request->input('address');
            $model->note = $request->input('note');
            $model->status = $request->input('status');

            $model->previous_loan = $request->input('previous_loan');
            if($request->input('loan_date') && $request->has('loan_date')){
                $model->loan_date = Carbon::parse($request->input('loan_date'))->format('Y-m-d');
            }

            $model->previous_advance = $request->input('previous_advance');
            if($request->input('advance_date') && $request->has('advance_date')){
                $model->advance_date = Carbon::parse($request->input('advance_date'))->format('Y-m-d');
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

            $model = Client::find($id);
            $model->update(['updated_by'=> Auth::user()->id]);
            $model->delete();

            $data['message'] = 'successfully_deleted';
            return response()->json($data, 200);

        } catch (\Exception $e) {
            $data['message'] = 'something_went_wrong';
            return response()->json($data, 500);
        }
    }

    public function paymentHistory(Request $request)
    {

        $query = Challan::query()->with(
            'client.client_info',
            'client.all_trans.transaction',
        );
        
        if($request->input('vehicle_id')){
            $vehicle_id = $request->input('vehicle_id');

            $query = $query->whereHas('own_vehicle', function($subQuery) use($vehicle_id) {
                $subQuery->where('vehicle_id', $vehicle_id);
            });
        }

        if($request->input('client_id')){
            $client_id = $request->input('client_id');
            $query = $query->whereHas('client', function($subQuery) use($client_id) {
                $subQuery->where('client_id', $client_id);
            });
        }

        if($request->input('challan_number')){
            $query = $query->where('challan_number', $request->input('challan_number'));
        }
    
        if($request->input('orderBy')){
            $query = $query->orderBy('id', $request->input('orderType'));
        }

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

        $data['lists'] = $query;
        return response()->json($data, 200);

    }

    // public function addData($item){
    //     // add vehicle info
    //     $challanOwnVehicle = ChallanOwnVehicle::where('challan_id', $item['id'])->with('vehicle_info')->first();
    //     if($challanOwnVehicle){
    //         $item['vehicle_id'] = $challanOwnVehicle->vehicle_id;
    //         $item['number_plate'] = $challanOwnVehicle->vehicle_info->number_plate;
    //         $item['driver_name'] = $challanOwnVehicle->vehicle_info->driver->name;
    //         $item['driver_phone'] = $challanOwnVehicle->vehicle_info->driver->phone;
    //         $item['helper_name'] = $challanOwnVehicle->vehicle_info->helper->name;
    //         $item['helper_phone'] = $challanOwnVehicle->vehicle_info->helper->phone;
    //     } else {
    //         $item['vehicle_id'] = '';
    //         $item['number_plate'] = '';
    //         $item['driver_name'] = '';
    //         $item['driver_phone'] = '';
    //         $item['helper_name'] = '';
    //         $item['helper_phone'] = '';
    //     }

    //     return $item;
    // }

}