<?php

namespace App\Http\Controllers\Staffs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Staffs\StaffReference;
use App\Models\Staffs\Staff;

use DB;
use Auth;

class StaffReferenceController extends Controller
{

    public function index(Request $request)
    {

        $query = StaffReference::query()->where('company_id', Auth::user()->company_id);
        
        if($request->input('name')){
            $query = $query->where('name', 'like', '%' . $request->input('name') . '%');
        }

        if($request->input('staff_id')){
            $query = $query->where('staff_id', $request->input('staff_id'));
            $data['staff_info'] = Staff::whereId($request->input('staff_id'))->select('name')->first();
        } else {
            $data['message'] = 'something_went_wrong';
            return response()->json($data, 500);
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

            $model = new StaffReference();
            $model->staff_id = $request->input('staff_id');
            $model->name = $request->input('name');
            $model->relation = $request->input('relation');
            $model->phone = $request->input('phone');
            $model->nid_number = $request->input('nid_number');
            $model->address = $request->input('address');
            $model->main_referrer = $request->input('main_referrer');
            
            $model->company_id = Auth::user()->company_id;
            $model->created_by = Auth::user()->id;
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

            $model = StaffReference::find($id);
            $model->name = $request->input('name');
            $model->relation = $request->input('relation');
            $model->phone = $request->input('phone');
            $model->nid_number = $request->input('nid_number');
            $model->address = $request->input('address');
            $model->main_referrer = $request->input('main_referrer');
        
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
            $model = StaffReference::find($id);
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