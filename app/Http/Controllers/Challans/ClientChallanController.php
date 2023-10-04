<?php

namespace App\Http\Controllers\Challans;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Challans\Challan;
use App\Models\Challans\ChallanOwnVehicle;
use App\Models\Challans\ChallanClient;
use App\Models\Challans\ChallanClientTrans;

use App\Http\Requests\Challan\ChallanClientRequest;

use DB;
use Auth;

class ClientChallanController extends Controller
{

    public function store(ChallanClientRequest $request)
    {   

        DB::beginTransaction();
        try {
            $model = new ChallanClient();

            $model->encrypt = uniqid();
            $model->challan_id = $request->input('challan_id');
            $model->client_id = $request->input('client_id');
            $model->contract_fare = $request->input('contract_fare');
            $model->demarage_fare = $request->input('demarage_fare');

            $model->created_by = Auth::user()->id;
            $model->company_id = Auth::user()->company_id;
            $model->save();

            DB::commit();
            $data['data'] = ChallanClient::whereId($model->id)->with('client_info')->first();
            $data['message'] = 'successfully_posted';
            return response()->json($data, 200);

        } catch (\Exception $e) {
            DB::rollBack();
            $data['message'] = 'something_went_wrong';
            return response()->json($data, 500);
        }
    }

    public function update(ChallanClientRequest $request, $id){

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
        try {

            $model = ChallanClient::find($id);
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