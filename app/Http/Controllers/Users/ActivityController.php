<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use Spatie\Activitylog\Models\Activity;

use DB;
use Auth;

class ActivityController extends Controller
{

    public function index(Request $request)
    {

        $query = Activity::query()->with('causer.roles');
        
        // if($request->input('name')){
        //     $query = $query->where('name', 'like', '%' . $request->input('name') . '%');
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
        $data['users'] = User::select('id','first_name', 'last_name')->get('id');
        
        return response()->json($data, 200);

    }

}