<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Settings\SettingBank;

use DB;
use Auth;

class BankController extends Controller
{

    public function index(Request $request)
    {

        $query = SettingBank::query();
        
        if($request->input('name')){
            $query = $query->where('name', 'like', '%' . $request->input('name') . '%');
        }

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
        $this->validate($request,[
            'name' => 'required',
        ]);
        try {
            $model = new SettingBank();
            $model->name = $request->input('name');
            $model->status = $request->input('status');
            $model->created_by = Auth::user()->id;
            $model->company_id = Auth::user()->company_id;
            $model->save();

            $data['message'] = 'successfully_created';
            return response()->json($data, 200);

        }catch (\Exception $e) {
            $data['message'] = 'did_not_created';
            return response()->json($data, 500);
        }
    }

    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'name' => 'required'
        ]);
        try{

            $model = SettingBank::find($id);
            $model->name = $request->input('name');
            $model->status = $request->input('status');
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

            $model = SettingBank::find($id);
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