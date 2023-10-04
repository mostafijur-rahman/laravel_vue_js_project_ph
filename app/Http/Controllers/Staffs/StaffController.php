<?php

namespace App\Http\Controllers\Staffs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Staffs\Staff;

use DB;
use Auth;

class StaffController extends Controller
{

    public function index(Request $request)
    {

        $query = Staff::query()->with('designation');
        
        // if($request->input('designation_id')){
        //     $query = $query->where('designation_id', $request->input('designation_id'));
        // }

        if($request->input('name')){
            $query = $query->where('name', 'like', '%' . $request->input('name') . '%');
        }
        if($request->input('phone')){
            $query = $query->where('phone', 'like', '%' . $request->input('phone') . '%');
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

        if($request->input('orderType')){
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

            $model = new Staff();
            $model->gender = $request->input('gender');
            $model->name = $request->input('name');
            $model->phone = $request->input('phone');
            $model->email = $request->input('email');
            $model->father_name = $request->input('father_name');
            $model->mother_name = $request->input('mother_name');
            $model->spouse_name = $request->input('spouse_name');

            $model->present_address = $request->input('present_address');
            $model->permanent_address = $request->input('permanent_address');
            $model->dob = $request->input('dob');
            $model->blood_group = $request->input('blood_group');
            $model->status = $request->input('status');

            $model->company_id_number = $request->input('company_id_number');
            $model->joining_date = $request->input('joining_date');
            $model->designation_id = $request->input('designation_id');
            $model->nid_number = $request->input('nid_number');
            $model->driving_license_number = $request->input('driving_license_number');

            $model->passport_number = $request->input('passport_number');
            $model->birth_certificate_number = $request->input('birth_certificate_number');
            $model->port_id = $request->input('port_id');
            $model->bank_id = $request->input('bank_id');
            $model->bank_account_number = $request->input('bank_account_number');

            $model->salary_amount = $request->input('salary_amount');
            $model->termination_date = $request->input('termination_date');
            // $model->sort = $request->input('name');
            $model->photo = $request->input('photo');
            $model->note = $request->input('note');
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

        $data['data'] = Staff::whereId($id)->first();
        return response()->json($data, 200);

    }


    public function update(Request $request, $id)
    {
        // return $request;
        // $this->validate($request,[
        //     'name' => 'required'
        // ]);

        try{

            $model = Staff::find($id);
            $model->gender = $request->input('gender');
            $model->name = $request->input('name');
            $model->phone = $request->input('phone');
            $model->email = $request->input('email');
            $model->father_name = $request->input('father_name');
            $model->mother_name = $request->input('mother_name');
            $model->spouse_name = $request->input('spouse_name');

            $model->present_address = $request->input('present_address');
            $model->permanent_address = $request->input('permanent_address');
            $model->dob = $request->input('dob');
            $model->blood_group = $request->input('blood_group');
            $model->status = $request->input('status');

            $model->company_id_number = $request->input('company_id_number');
            $model->joining_date = $request->input('joining_date');
            $model->designation_id = $request->input('designation_id');
            $model->nid_number = $request->input('nid_number');
            $model->driving_license_number = $request->input('driving_license_number');

            $model->passport_number = $request->input('passport_number');
            $model->birth_certificate_number = $request->input('birth_certificate_number');
            $model->port_id = $request->input('port_id');
            $model->bank_id = $request->input('bank_id');
            $model->bank_account_number = $request->input('bank_account_number');

            $model->salary_amount = $request->input('salary_amount');
            $model->termination_date = $request->input('termination_date');
            // $model->sort = $request->input('name');
            $model->photo = $request->input('photo');
            $model->note = $request->input('note');
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

            $model = Staff::find($id);
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