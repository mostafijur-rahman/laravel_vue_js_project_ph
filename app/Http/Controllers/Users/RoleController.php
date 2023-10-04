<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Spatie\Permission\Models\Role;
// use Spatie\Permission\Models\Permission;
use Auth;

class RoleController extends Controller
{

    public function index(Request $request)
    {

        $data['lists'] = Role::where('company_id', Auth::user()->company_id)->get();
        return response()->json($data, 200);
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required|unique:roles,name'
        ]);
        try {

            $model = new Role();
            $model->name = $request->input('name');
            $model->guard_name = 'web';
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
            'name' => 'required|unique:roles,name,'. $id .',id'
        ]);

        try{

            $model = Role::find($id);
            $model->name = $request->input('name');
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

            $model = Role::find($id);
            $model->delete();

            $data['message'] = 'successfully_deleted';
            return response()->json($data, 200);

        } catch (\Exception $e) {
            $data['message'] = 'something_went_wrong';
            return response()->json($data, 500);
        }
    }


}