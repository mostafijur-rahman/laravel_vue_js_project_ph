<?php

namespace App\Http\Controllers\Challans;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Challans\ChallanOwnVehicle;

use DB;
use Auth;

class OwnVehicleChallanController extends Controller
{

    public function store(Request $request)
    {

        try {

            $model = new ChallanOwnVehicle();

            $model->encrypt = uniqid();
            $model->challan_id = $request->input('challan_id');

            $model->vehicle_id = $request->input('vehicle_id');
            $model->driver_id = $request->input('driver_id');
            $model->helper_id = $request->input('helper_id');

            $model->save();

            $data['message'] = 'successfully_posted';
            return response()->json($data, 200);

        } catch (\Exception $e) {
            $data['message'] = 'something_went_wrong';
            return response()->json($data, 500);
        }
    }

    public function destroy($id)
    {
        try {

            $model = ChallanOwnVehicle::find($id);
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