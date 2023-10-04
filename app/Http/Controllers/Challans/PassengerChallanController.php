<?php

namespace App\Http\Controllers\Challans;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Challans\Challan;
use App\Models\Challans\ChallanOwnVehicle;

use App\Http\Requests\Challan\DepositChallanRequest;
use App\Http\Requests\Challan\PassengerChallanRequest;


use DB;
use Auth;
use Carbon\Carbon;

class PassengerChallanController extends Controller
{

    public function index(Request $request)
    {

        $query = Challan::query()->with(

            'general_expenses.expense_info',
            'general_expense_sum',

            'oil_expenses',
            'oil_expense_sum',
            'unit_info',

            'created_info',
            'updated_info'
        ); 
        
        $query->where('type', 'passenger_going');
        $query->where('company_id', Auth::user()->company_id);
        
        if($request->input('vehicle_id')){
            $vehicle_id = $request->input('vehicle_id');

            $query = $query->whereHas('own_vehicle', function($subQuery) use($vehicle_id) {
                $subQuery->where('vehicle_id', $vehicle_id);
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

    public function addData($item){
        
        // add vehicle info
        $challanOwnVehicle = ChallanOwnVehicle::where('challan_id', $item['id'])->with('vehicle_info', 'driver_info', 'guide_info', 'helper_info')->first();

        if($challanOwnVehicle){
            $item['vehicle_id'] = $challanOwnVehicle->vehicle_id;
            $item['number_plate'] = $challanOwnVehicle->vehicle_info->number_plate;

            $item['driver_name'] = $challanOwnVehicle->driver_info ? $challanOwnVehicle->driver_info->name :'';
            $item['driver_phone'] = $challanOwnVehicle->driver_info ? $challanOwnVehicle->driver_info->phone : '';

            $item['guide_name'] = $challanOwnVehicle->guide_info ? $challanOwnVehicle->guide_info->name :'';
            $item['guide_phone'] = $challanOwnVehicle->guide_info ? $challanOwnVehicle->guide_info->phone : '';

            $item['helper_name'] = $challanOwnVehicle->helper_info ? $challanOwnVehicle->helper_info->name: '';
            $item['helper_phone'] = $challanOwnVehicle->helper_info ? $challanOwnVehicle->helper_info->phone: '';

        } else {
            $item['vehicle_id'] = '';
            $item['number_plate'] = '';

            $item['driver_name'] = '';
            $item['driver_phone'] = '';

            $item['guide_name'] = '';
            $item['guide_phone'] = '';

            $item['helper_name'] = '';
            $item['helper_phone'] = '';
        }
       
        return $item;
    }

    public function store(PassengerChallanRequest $request)
    {

        // DB::beginTransaction();
        // try {

            $model = new Challan();
            $model->encrypt = uniqid();
            $model->type = $request->input('challan_type');
            $model->challan_number = $request->input('challan_number');
            $model->coach_number = $request->input('coach_number');
            $model->start_date_time = Carbon::parse($request->input('start_date_time'))->format('Y-m-d');
            $model->note = $request->input('note');

            $model->created_by = Auth::user()->id;
            $model->company_id = Auth::user()->company_id;
            $model->save();
            
            // if any vehicle selection then insert it
            if($request->input('vehicle_id')){

                $vehicle = new ChallanOwnVehicle();
                $vehicle->encrypt = uniqid();
                $vehicle->challan_id = $model->id;

                if($request->input('vehicle_id') && $request->input('vehicle_id')['id']){
                    $vehicle->vehicle_id = $request->input('vehicle_id')['id'];
                }
                if($request->input('driver_id') && $request->input('driver_id')['id']){
                    $vehicle->driver_id = $request->input('driver_id')['id'];
                }
                if($request->input('guide_id') && $request->input('guide_id')['id']){
                    $vehicle->guide_id = $request->input('guide_id')['id'];
                }
                if($request->input('helper_id') &&  $request->input('helper_id')['id']){
                    $vehicle->helper_id = $request->input('helper_id')['id'];
                }

                $vehicle->save();
            }

            // DB::commit();
            $data['message'] = 'successfully_posted';
            return response()->json($data, 200);

        // } catch (\Exception $e) {

        //     DB::rollBack();
        //     $data['message'] = 'something_went_wrong';
        //     return response()->json($data, 500);
        // }
    }

    public function show($id)
    {

        $query = Challan::whereId($id)->with(   
            'own_vehicle',
            
            // client bill part
            'client_bills.client_info',
            'client_contract_bills',
            'client_demurrage_bills',

            // client payment part
            'client_payments',
            'client_advance_payments',
            'client_after_advance_payments',
            'client_demurrage_payments',

            'general_expenses.expense_info',
            'general_expense_sum',

            'oil_expenses',
            'oil_expense_sum',
            'unit_info',

            'created_info',
            'updated_info'
            )
            ->where('type', 'deposit')
            ->where('company_id', Auth::user()->company_id)
            ->first();

        $data['data'] = $this->addData($query);
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

            // if has own vehicle, then update attempt
            if($request->input('own_vehicle')){

                $vehicle = ChallanOwnVehicle::find($request->input('own_vehicle')['id']);

                if($request->input('vehicle_id')){

                    // update
                    if($request->input('vehicle_id') && $request->input('vehicle_id')['id']){
                        $vehicle->vehicle_id = $request->input('vehicle_id')['id'];
                    }
                    if($request->input('driver_id') && $request->input('driver_id')['id']){
                        $vehicle->driver_id = $request->input('driver_id')['id'];
                    }
                    if($request->input('helper_id') &&  $request->input('helper_id')['id']){
                        $vehicle->helper_id = $request->input('helper_id')['id'];
                    }

                    $vehicle->save();
                
                // delete
                } else {

                    $vehicle->delete();
                }

            // other wise if has vehicle_id then insert attempt 
            } elseif($request->input('vehicle_id')) {

                // if not found anything then insert new one
                $vehicle = new ChallanOwnVehicle();
                $vehicle->encrypt = uniqid();
                $vehicle->challan_id = $model->id;
    
                if($request->input('vehicle_id') && $request->input('vehicle_id')['id']){
                    $vehicle->vehicle_id = $request->input('vehicle_id')['id'];
                }
                if($request->input('driver_id') && $request->input('driver_id')['id']){
                    $vehicle->driver_id = $request->input('driver_id')['id'];
                }
                if($request->input('helper_id') &&  $request->input('helper_id')['id']){
                    $vehicle->helper_id = $request->input('helper_id')['id'];
                }

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
        // DB::beginTransaction();
        // try {
            
            $model = Challan::where('company_id', Auth::user()->company_id)->where('id', $id)->first();

            // load points delete
            $model->points()->detach();

            // vehicle delete
            if($model->own_vehicle()->exists()){
                $model->own_vehicle->delete();
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

            // general expense delete
            if($model->general_expenses()->exists()){

                foreach($model->general_expenses as $list){

                    // if has transaction then delete it
                    if($list->transaction){
                        $list->transaction->account()->increment('balance', $list->transaction->amount, ['updated_by'=> Auth::user()->id]);
                        $list->transaction->update(['updated_by'=> Auth::user()->id]);
                        $list->transaction->delete();
                    }

                    // if has loan then delete it
                    if($list->loan()->exists()){

                        // fetch transection
                        $loan = $list->loan()->first();
        
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

                }
                $model->general_expenses()->update(['updated_by'=> Auth::user()->id]);
                $model->general_expenses()->delete();
            }
            // oil expense delete
            if($model->oil_expenses()->exists()){

                foreach($model->oil_expenses as $list){
                    
                    // if has transaction then delete it
                    if($list->transaction){
                        $list->transaction->account()->increment('balance', $list->transaction->amount, ['updated_by'=> Auth::user()->id]);
                        $list->transaction->update(['updated_by'=> Auth::user()->id]);
                        $list->transaction->delete();
                    }

                    // if has loan then delete it
                    if($list->loan()->exists()){

                        // fetch transection
                        $loan = $list->loan()->first();
        
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
                }
                $model->oil_expenses()->update(['updated_by'=> Auth::user()->id]);
                $model->oil_expenses()->delete();
            }

            // challan delete
            $model->update(['updated_by'=> Auth::user()->id]);
            $model->delete();

            DB::commit();
            $data['message'] = 'successfully_deleted';
            return response()->json($data, 200);

        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     $data['message'] = 'something_went_wrong';
        //     return response()->json($data, 500);
        // }
    }

}