<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use Spatie\Permission\Models\Role;
// use Spatie\Permission\Models\Permission;

use App\Http\Requests\User\UserRequest;
use App\Http\Requests\User\UserPassRequest;

use Auth;
use Setting;

class UserController extends Controller
{

    public function index(Request $request)
    {

        $query = User::query()->with('roles')->where('company_id', Auth::user()->company_id);
        
        if($request->input('name')){
            $query = $query->where('first_name', 'like', '%' . $request->input('name') . '%');
        }

        if($request->input('orderType')){
            $query = $query->orderBy('id', $request->input('orderType'));
        }

        if($request->input('paginate') == 'true'){
            $query = $query->paginate($request->input('perPage'), ['*'], 'page', $request->input('currentPage'));
        } else {
            $query = $query->get();
        }

        $data['lists'] = $query;
        $data['roles'] = Role::all();
        return response()->json($data, 200);
    }

    public function store(UserRequest $request)
    {
        try {

            $model = new User();
            $model->first_name = $request->input('first_name');
            $model->last_name = $request->input('last_name');
            $model->phone  = $request->input('phone');
            $model->password = bcrypt($request->input('password'));
            $model->status = $request->input('status');

            $model->created_by = Auth::user()->id;
            $model->company_id = Auth::user()->company_id;
            $model->save();

            // assign role
            $role = Role::where('id', $request->input('role_id'))->first();            
            $model->assignRole($role);

            $data['message'] = 'successfully_created';
            return response()->json($data, 200);

        } catch (\Exception $e) {
            $data['message'] = 'did_not_created';
            return response()->json($data, 500);
        }
    }

    public function update(UserRequest $request, $id){

        try {
            $model = User::find($id);
            $model->first_name = $request->input('first_name');
            $model->last_name = $request->input('last_name');
            $model->phone  = $request->input('phone');
            $model->status = $request->input('status');
            $model->updated_by = Auth::user()->id;
            $model->save();

            // assign role
            $role = Role::where('id', $request->input('role_id'))->first();            
            $model->syncRoles($role);

            $data['message'] = 'successfully_updated';
            return response()->json($data, 200);

        } catch (\Exception $e) {
            $data['message'] = 'did_not_updated';
            return response()->json($data, 500);
        }
    }

    public function destroy($id){
        try {
            $user = User::where('company_id', Auth::user()->company_id)->where('id', $id);
            $user->update(['updated_by'=> Auth::user()->id]);
            $user->delete();

            $data['message'] = 'successfully_deleted';
            return response()->json($data, 200);

        }catch (\Exception $e) {

            $data['message'] = 'did_not_deleted';
            return response()->json($data, 500);
        }
    }

    public function updatePassword(UserPassRequest $request, $id){

        $this->validate($request,[
            
        ]);
        try {

            $model = User::where('id', $id)->first();
            $model->password =  bcrypt($request->input('password'));
            $model->updated_by = Auth::user()->id;
            $model->save();

            $data['message'] = 'successfully_updated';
            return response()->json($data, 200);

        } catch (\Exception $e) {
            $data['message'] = 'did_not_updated';
            return response()->json($data, 500);
        }
    }

    public function getAuthUser(){

        $data = Auth::user();

        Setting::setExtraColumns(['company_id' => Auth::user()->company_id]);
        $data['company'] = Setting::get('company');

        return response()->json($data, 200);
    }


}