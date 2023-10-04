<?php

namespace App\Http\Controllers\Challans;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Challans\Challan;
use App\Models\Challans\ChallanRentalVehicle;

use App\Models\Challans\ChallanClient;
use App\Http\Requests\Challan\CommissionChallanRequest;


use DB;
use Auth;

class CommissionChallanController extends Controller
{

    public function index(Request $request)
    {

        $query = Challan::query()->with(
            'rental_vehicle.vendor',

            // vendor bill part
            'vendor_bills',
            'vendor_contract_bills',
            'vendor_demurrage_bills',

            // vendor payment part
            'vendor_payments',
            'vendor_advance_payments',
            'vendor_after_advance_payments',
            'vendor_demurrage_payments',
            // security money
            'vendor_security_received_money_payments',
            'vendor_security_paid_money_payments',

            // client bill part
            'client_bills.client_info',
            'client_contract_bills',
            'client_demurrage_bills',

            // client payment part
            'client_payments',
            'client_advance_payments',
            'client_after_advance_payments',
            'client_demurrage_payments',

            'created_info',
            'updated_info'
        );

        $query->where('type', 'commission');
        $query->where('company_id', Auth::user()->company_id);

        if($request->input('vehicle_number')){
            $vehicle_number = $request->input('vehicle_number');
            $query = $query->whereHas('rental_vehicle', function($subQuery) use($vehicle_number) {
                $subQuery->where('vehicle_number', 'like', '%' . $vehicle_number . '%');
            });
        }

        if($request->input('client_id')){
            $client_id = $request->input('client_id');
            $query = $query->whereHas('client_bills', function($subQuery) use($client_id) {
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

            // $query = $query->paginate($request->input('perPage'), ['*'], 'page', $request->input('currentPage'));

        } else {
            $query = $query->get()->map(function($item) {
                return $this->addData($item);
            });
        }

        $data['lists'] = $query;
        return response()->json($data, 200);

    }

    public function addData($item){
        
        // add vehicle info
        // $challanOwnVehicle = ChallanOwnVehicle::where('challan_id', $item['id'])->with('vehicle_info', 'driver_info', 'helper_info')->first();

        // if($challanOwnVehicle){
        //     $item['vehicle_id'] = $challanOwnVehicle->vehicle_id;
        //     $item['number_plate'] = $challanOwnVehicle->vehicle_info->number_plate;

        //     $item['driver_name'] = $challanOwnVehicle->driver_info ? $challanOwnVehicle->driver_info->name :'';
        //     $item['driver_phone'] = $challanOwnVehicle->driver_info ? $challanOwnVehicle->driver_info->phone : '';

        //     $item['helper_name'] = $challanOwnVehicle->helper_info ? $challanOwnVehicle->helper_info->name: '';
        //     $item['helper_phone'] = $challanOwnVehicle->helper_info ? $challanOwnVehicle->helper_info->phone: '';

        // } else {
        //     $item['vehicle_id'] = '';
        //     $item['number_plate'] = '';
        //     $item['driver_name'] = '';
        //     $item['driver_phone'] = '';
        //     $item['helper_name'] = '';
        //     $item['helper_phone'] = '';
        // }

        // if has load point
        if(count($item['points'])){

            $load = '';
            $unload = '';

            foreach($item['points'] as $point){

                if($point->pivot->point == 'load'){
                    $load .= $point->name . ', ';
                }

                if($point->pivot->point == 'unload'){
                    $unload .= $point->name . ', ';
                }
            }

            $item['load'] = $load;
            $item['unload'] = $unload;
        }
       
        return $item;
    }

    public function store(CommissionChallanRequest $request)
    {

        DB::beginTransaction();
        try {

            $model = new Challan();
            $model->encrypt = uniqid();
            $model->type = 'commission';
            // $model->group_id = '';
            // $model->voucher_number = '';
            $model->challan_number = $request->input('challan_number');
            $model->start_date_time = $request->input('start_date_time');
            $model->account_take_date_time = $request->input('account_take_date_time');

            $model->box = $request->input('box');
            $model->weight = $request->input('weight');
            $model->unit_id = $request->input('unit_id');
            $model->goods = $request->input('goods');
            $model->buyer_name = $request->input('buyer_name');
            $model->buyer_code = $request->input('buyer_code');
            $model->order_no = $request->input('order_no');
            $model->depu_change_bill = $request->input('depu_change_bill');
            $model->gate_pass_no = $request->input('gate_pass_no');
            $model->lock_no = $request->input('lock_no');
            $model->load_point_reach_time = $request->input('load_point_reach_time');
            $model->note = $request->input('note');

            $model->created_by = Auth::user()->id;
            $model->company_id = Auth::user()->company_id;
            $model->save();
            
            // add load point adds
            $load_ids = $request->input('load_ids');
            $only_load_ids = [];

            if(count($load_ids) > 0){
                foreach($load_ids as $load){
                    $only_load_ids[] = $load['id'];
                }
                $model->points()->attach($only_load_ids, ['challan_id' => $model->id, 'point'=>'load']);
            }

            // add unload point adds
            $unload_ids = $request->input('unload_ids');
            $only_unload_ids = [];

            if(count($unload_ids) > 0){
                foreach($unload_ids as $load){
                    $only_unload_ids[] = $load['id'];
                }
                $model->points()->attach($only_unload_ids, ['challan_id' => $model->id, 'point'=>'unload']);
            }

            // if any supplier selection then insert it
            if($request->input('vendor_id')){

                $vehicle = new ChallanRentalVehicle();
                $vehicle->encrypt = uniqid();

                $vehicle->challan_id = $model->id; // primary key of challan table
                $vehicle->vendor_id = $request->input('vendor_id')['id'];

                $vehicle->vehicle_number =  $request->input('vehicle_number');

                $vehicle->driver_name = $request->input('driver_name');
                $vehicle->driver_phone = $request->input('driver_phone');

                $vehicle->owner_name = $request->input('owner_name');
                $vehicle->owner_phone = $request->input('owner_phone');

                $vehicle->reference_name = $request->input('reference_name');
                $vehicle->reference_phone = $request->input('reference_phone');

                $vehicle->company_id = Auth::user()->company_id;
                $vehicle->created_by = Auth::user()->id;
                $vehicle->save();
            }

            DB::commit();
            $data['message'] = 'successfully_posted';
            return response()->json($data, 200);

        } catch (\Exception $e) {

            DB::rollBack();
            $data['message'] = 'something_went_wrong';
            return response()->json($data, 500);
        }
    }

    public function show($id)
    {
        $data['data'] = Challan::whereId($id)->with(
            'rental_vehicle.vendor',

            // vendor bill part
            'vendor_bills',
            'vendor_contract_bills',
            'vendor_demurrage_bills',

            // vendor payment part
            'vendor_payments',
            'vendor_advance_payments',
            'vendor_after_advance_payments',
            'vendor_demurrage_payments',
            // security money
            'vendor_security_received_money_payments',
            'vendor_security_paid_money_payments',
            
            // client bill part
            'client_bills.client_info',
            'client_contract_bills',
            'client_demurrage_bills',

            // client payment part
            'client_payments',
            'client_advance_payments',
            'client_after_advance_payments',
            'client_demurrage_payments',

            // client part
            'created_info',
            'updated_info'
        )
        ->where('type', 'commission')
        ->where('company_id', Auth::user()->company_id)
        ->first();

        return response()->json($data, 200);
    }

    public function update(Request $request, $id)
    {

        DB::beginTransaction();
        try {

            $model = Challan::find($id);

            $model->challan_number = $request->input('challan_number');
            $model->start_date_time = $request->input('start_date_time');
            $model->account_take_date_time = $request->input('account_take_date_time');
            
            $model->box = $request->input('box');
            $model->weight = $request->input('weight');
            $model->unit_id = $request->input('unit_id');
            $model->goods = $request->input('goods');
            $model->buyer_name = $request->input('buyer_name');
            $model->buyer_code = $request->input('buyer_code');
            $model->order_no = $request->input('order_no');
            $model->depu_change_bill = $request->input('depu_change_bill');
            $model->gate_pass_no = $request->input('gate_pass_no');
            $model->lock_no = $request->input('lock_no');
            $model->load_point_reach_time = $request->input('load_point_reach_time');
            $model->note = $request->input('note');

            $model->updated_by = Auth::user()->id;
            $model->save();

            // before points update need to detach
            $model->points()->detach();

            // add load point adds
            $load_ids = $request->input('load_ids');
            $only_load_ids = [];

            if(count($load_ids) > 0){
                foreach($load_ids as $load){
                    $only_load_ids[] = $load['id'];
                }
                $model->points()->attach($only_load_ids, ['challan_id' => $model->id, 'point'=>'load']);
            }

            // add unload point adds
            $unload_ids = $request->input('unload_ids');
            $only_unload_ids = [];

            if(count($unload_ids) > 0){
                foreach($unload_ids as $load){
                    $only_unload_ids[] = $load['id'];
                }
                $model->points()->attach($only_unload_ids, ['challan_id' => $model->id, 'point'=>'unload']);
            }

            // if has rental vehicle, then update attempt
            if($request->input('rental_vehicle')){

                $vehicle = ChallanRentalVehicle::find($request->input('rental_vehicle')['id']);

                if($request->input('vendor_id')){

                    $vehicle->vendor_id = $request->input('vendor_id')['id'];
                    $vehicle->vehicle_number =  $request->input('vehicle_number');
    
                    $vehicle->driver_name = $request->input('driver_name');
                    $vehicle->driver_phone = $request->input('driver_phone');
    
                    $vehicle->owner_name = $request->input('owner_name');
                    $vehicle->owner_phone = $request->input('owner_phone');
    
                    $vehicle->reference_name = $request->input('reference_name');
                    $vehicle->reference_phone = $request->input('reference_phone');
    
                    $vehicle->updated_by = Auth::user()->id;
                    $vehicle->save();

                } else {
                    $vehicle->delete();
                }

            // other wise if has vehicle_id then insert attempt 
            } elseif($request->input('vendor_id')) {

                // if not found anything then insert new one
                $vehicle = new ChallanRentalVehicle();
                $vehicle->encrypt = uniqid();
                $vehicle->challan_id = $model->id;
                
                $vehicle->vendor_id = $request->input('vendor_id')['id'];
                $vehicle->vehicle_number =  $request->input('vehicle_number');

                $vehicle->driver_name = $request->input('driver_name');
                $vehicle->driver_phone = $request->input('driver_phone');

                $vehicle->owner_name = $request->input('owner_name');
                $vehicle->owner_phone = $request->input('owner_phone');

                $vehicle->reference_name = $request->input('reference_name');
                $vehicle->reference_phone = $request->input('reference_phone');

                $vehicle->company_id = Auth::user()->company_id;
                $vehicle->created_by = Auth::user()->id;

                $vehicle->save();

            }

            DB::commit();
            $data['message'] = 'successfully_updated';
            return response()->json($data, 200);

        } catch (\Exception $e) {
            DB::rollBack();
            $data['message'] = 'did_not_updated';
            return response()->json($data, 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            
            $model = Challan::where('company_id', Auth::user()->company_id)->where('id', $id)->first();

            // load points delete
            $model->points()->detach();

            // single delete
            if($model->rental_vehicle()->exists()){
                $model->rental_vehicle()->update(['updated_by'=> Auth::user()->id]);
                $model->rental_vehicle()->delete();
            }

            // vendor delete
            if($model->vendor_bills()->exists()){
                $model->vendor_bills()->update(['updated_by'=> Auth::user()->id]);
                $model->vendor_bills()->delete();
            }

            // if has vendor payments
            if($model->vendor_payments()->exists()){

                // then dive into each payment
                foreach($model->vendor_payments as $vendor_payment){

                    if($vendor_payment->transaction){
                        $vendor_payment->transaction->account()->increment('balance', $vendor_payment->transaction->amount, ['updated_by'=> Auth::user()->id]);
                        $vendor_payment->transaction->update(['updated_by'=> Auth::user()->id]);
                        $vendor_payment->transaction->delete();
                    }
                }
                $model->vendor_payments()->update(['updated_by'=> Auth::user()->id]);
                $model->vendor_payments()->delete();
            }

            // client
            if($model->client_bills()->exists()){
                $model->client_bills()->update(['updated_by'=> Auth::user()->id]);
                $model->client_bills()->delete();
            }

            if($model->client_payments()->exists()){

                // then dive into each payment
                foreach($model->client_payments as $client_payment){
                    if($client_payment->transaction){
                        $client_payment->transaction->account()->decrement('balance', $client_payment->transaction->amount, ['updated_by'=> Auth::user()->id]);
                        $client_payment->transaction->update(['updated_by'=> Auth::user()->id]);
                        $client_payment->transaction->delete();
                    }
                }

                $model->client_payments()->update(['updated_by'=> Auth::user()->id]);
                $model->client_payments()->delete();
            }

            // challan delete
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

    
    public function challanNote(Request $request){

        try {

            $challan = Challan::whereId($request->input('id'))->first();
            $challan->note = $request->input('note');
            $challan->note_color = $request->input('note_color');
            $challan->save();

            $data['message'] = 'successfully_updated';
            return response()->json($data, 200);

        } catch (\Exception $e) {

            $data['message'] = 'something_went_wrong';
            return response()->json($data, 500);
        }
    }

}