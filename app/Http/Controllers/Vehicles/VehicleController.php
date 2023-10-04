<?php

namespace App\Http\Controllers\Vehicles;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Vehicles\Vehicle;

use DB;
use Auth;

class VehicleController extends Controller
{

    public function index(Request $request)
    {

        $query = Vehicle::query()->with('driver', 'helper');
        

        // if($request->input('designation_id')){
        //     $query = $query->where('designation_id', $request->input('designation_id'));
        // }

        if($request->input('number_plate')){
            $query = $query->where('number_plate', 'like', '%' . $request->input('number_plate') . '%');
        }

        // if($request->input('company_id')){
        //     $query = $query->where('company_id', 'like', '%' . $request->input('company_id') . '%');
        // }

        // if($request->input('nid_number')){
        //     $query = $query->where('nid_number', 'like', '%' . $request->input('nid_number') . '%');
        // }

        // if($request->input('driving_license_number')){
        //     $query = $query->where('driving_license_number', 'like', '%' . $request->input('driving_license_number') . '%');
        // }

        // if($request->input('vehicle_id')){

        //     $staff_ids = SettingVehicle::where('id', $request->input('vehicle_id'))->select('driver_id', 'helper_id')->first();

        //     if($staff_ids->driver_id){
        //         $query = $query->where('id', $staff_ids->driver_id);
        //     }

        //     if($staff_ids->helper_id){
        //         $query = $query->orWhere('id', $staff_ids->helper_id);
        //     }
        // }

        if($request->input('orderBy')){
            $query = $query->orderBy('id', $request->input('orderType'));
        }

        if($request->input('paginate') == true){
            $query = $query->paginate($request->input('perPage'), ['*'], 'page', $request->input('currentPage'));
        } else {
            $query = $query->get();
        }

        $data['lists'] = $query;
        return response()->json($data, 200);

    }

    public function store(Request $request)
    {

        try {

            $model = new Vehicle();
            $model->ownership = $request->input('ownership');
            $model->encrypt = uniqid();
            $model->serial = $request->input('serial');
            $model->number_plate = $request->input('number_plate');
            $model->registration_number = $request->input('registration_number');
            $model->engine_number = $request->input('engine_number');
            $model->chassis_number = $request->input('chassis_number');
            $model->model = $request->input('model');

            $model->manufacturer = $request->input('manufacturer');
            $model->body_type = $request->input('body_type');
            $model->engine_down = $request->input('engine_down');
            $model->fuel_tank_capacity = $request->input('fuel_tank_capacity');
            $model->gps_id = $request->input('gps_id');

            $model->driver_id = $request->input('driver_id');
            $model->helper_id = $request->input('helper_id');
            $model->note = $request->input('note');

            $model->registration_year = $request->input('registration_year');
            $model->tax_token_expire_date = $request->input('tax_token_expire_date');
            $model->fitness_expire_date = $request->input('fitness_expire_date');
            $model->insurance_expire_date = $request->input('insurance_expire_date');
            $model->last_tyre_change_date = $request->input('last_tyre_change_date');
            $model->last_mobil_change_date = $request->input('last_mobil_change_date');
            $model->last_servicing_date = $request->input('last_servicing_date');
    
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

    public function show($id)
    {

        $data['data'] = Vehicle::whereId($id)->first();
        return response()->json($data, 200);

    }


    public function update(Request $request, $id)
    {
        // return $request;
        // $this->validate($request,[
        //     'name' => 'required'
        // ]);

        try{

            $model = Vehicle::find($id);
            $model->ownership = $request->input('ownership');
            $model->serial = $request->input('serial');
            $model->number_plate = $request->input('number_plate');
            $model->registration_number = $request->input('registration_number');
            $model->engine_number = $request->input('engine_number');
            $model->chassis_number = $request->input('chassis_number');
            $model->model = $request->input('model');

            $model->manufacturer = $request->input('manufacturer');
            $model->body_type = $request->input('body_type');
            $model->engine_down = $request->input('engine_down');
            $model->fuel_tank_capacity = $request->input('fuel_tank_capacity');
            $model->gps_id = $request->input('gps_id');

            $model->driver_id = $request->input('driver_id');
            $model->helper_id = $request->input('helper_id');
            $model->note = $request->input('note');

            $model->registration_year = $request->input('registration_year');
            $model->tax_token_expire_date = $request->input('tax_token_expire_date');
            $model->fitness_expire_date = $request->input('fitness_expire_date');
            $model->insurance_expire_date = $request->input('insurance_expire_date');
            $model->last_tyre_change_date = $request->input('last_tyre_change_date');
            $model->last_mobil_change_date = $request->input('last_mobil_change_date');
            $model->last_servicing_date = $request->input('last_servicing_date');

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

            $model = Vehicle::find($id);
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