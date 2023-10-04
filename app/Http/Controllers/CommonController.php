<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\Settings\SettingBank;
use App\Models\Settings\SettingDesignation;
use App\Models\Settings\SettingArea;
use App\Models\Settings\SettingExpense;

use App\Models\Staffs\Staff;
use App\Models\Vehicles\Vehicle;

use App\Models\Clients\Client;
use App\Models\Challans\ChallanClient;
use App\Models\Challans\ChallanClientTrans;

use App\Models\Challans\Challan;
use App\Models\Challans\ChallanRentalVehicle;

use App\Models\Vendors\Vendor;
use App\Models\Expenses\General;

use App\Models\Accounts\Account;

class CommonController extends Controller
{
    

    public function getDesignation(Request $request){
        $query = SettingDesignation::query();

        if($request->input('orderBy')){
            $query = $query->orderBy('id', $request->input('orderType'));
        }else{
            $query = $query->orderBy('sort', 'asc');
        }

        if($request->input('paginate') == true){
            $query = $query->paginate($request->input('perPage'), ['*'], 'page', $request->input('currentPage'));
        } else {
            $query = $query->get();
        }

        $data['lists'] = $query;
        return response()->json($data, 200);
    }

    public function getBank(Request $request){
        $query = SettingBank::query();

        if($request->input('orderBy')){
            $query = $query->orderBy('id', $request->input('orderType'));
        }else{
            $query = $query->orderBy('sort', 'asc');
        }

        if($request->input('paginate') == true){
            $query = $query->paginate($request->input('perPage'), ['*'], 'page', $request->input('currentPage'));
        } else {
            $query = $query->get();
        }

        $data['lists'] = $query;
        return response()->json($data, 200);
    }

    public function getStaff(Request $request){

        $query = Staff::query()->select('id', 'name');

        if($request->input('designation_id')){
            $query = $query->where('designation_id', $request->input('designation_id'));
        }

        if($request->input('orderBy')){
            $query = $query->orderBy('id', $request->input('orderType'));
        }else{
            $query = $query->orderBy('sort', 'asc');
        }
      
        if($request->input('paginate') == true){
            $query = $query->paginate($request->input('perPage'), ['*'], 'page', $request->input('currentPage'));
        } else {
            $query = $query->get();
        }

        $data['lists'] = $query;
        return response()->json($data, 200);

    }

    public function getAreas(Request $request){
        $query = SettingArea::query();

        if($request->input('orderBy')){
            $query = $query->orderBy('id', $request->input('orderType'));
        }else{
            $query = $query->orderBy('sort', 'asc');
        }

        if($request->input('paginate') == true){
            $query = $query->paginate($request->input('perPage'), ['*'], 'page', $request->input('currentPage'));
        } else {
            $query = $query->get(['id','name']);
        }

        $data['lists'] = $query;
        return response()->json($data, 200);
    }

    public function getVehicles(Request $request){

        $query = Vehicle::query();

        if($request->input('orderBy')){
            $query = $query->orderBy('id', $request->input('orderType'));
        }else{
            $query = $query->orderBy('sort', 'asc');
        }
      
        if($request->input('paginate') == true){
            $query = $query->paginate($request->input('perPage'), ['*'], 'page', $request->input('currentPage'));
        } else {
            $query = $query->get(['id', 'number_plate']);
        }

        $data['lists'] = $query;
        return response()->json($data, 200);

    }

    public function getStaffBasedOnVehicle(Request $request){

        if(Vehicle::whereId($request->input('vehicle_id'))->exists()){

            $data['data'] = Vehicle::whereId($request->input('vehicle_id'))->with('driver', 'helper', 'guide')->first();
            return response()->json($data, 200);

        } else {
            $data['message'] = 'something_went_wrong';
            return response()->json($data, 500);
        }
    }

    public function getClients(Request $request){

        $query = Client::query();

        if($request->input('orderBy')){
            $query = $query->orderBy('id', $request->input('orderType'));
        }else{
            $query = $query->orderBy('sort', 'asc');
        }
      
        if($request->input('paginate') == true){
            $query = $query->paginate($request->input('perPage'), ['*'], 'page', $request->input('currentPage'));
        } else {
            $query = $query->get();
        }

        $data['lists'] = $query;
        return response()->json($data, 200);
    }

    public function getChallanClient($id){

        $data['data'] =  ChallanClient::whereId($id)->first();
        return response()->json($data, 200);

    }

    public function getExpenses(Request $request){

        $query = SettingExpense::query();

        if($request->input('orderBy')){
            $query = $query->orderBy('id', $request->input('orderType'));
        }else{
            $query = $query->orderBy('sort', 'asc');
        }
      
        if($request->input('paginate') == true){
            $query = $query->paginate($request->input('perPage'), ['*'], 'page', $request->input('currentPage'));
        } else {
            $query = $query->get();
        }

        $data['lists'] = $query;
        return response()->json($data, 200);

    }

    public function getVendors(Request $request){

        $query = Vendor::query();

        if($request->input('type')){
            $query = $query->where('type', $request->input('type'));
        }

        if($request->input('orderBy')){
            $query = $query->orderBy('id', $request->input('orderType'));
        }else{
            $query = $query->orderBy('sort', 'asc');
        }
      
        if($request->input('paginate') == true){
            $query = $query->paginate($request->input('perPage'), ['*'], 'page', $request->input('currentPage'));
        } else {
            $query = $query->get();
        }

        $data['lists'] = $query;
        return response()->json($data, 200);

    }

    public function getAccounts(Request $request){

        $query = Account::query()->with('bank_info');

        if($request->input('orderBy')){
            $query = $query->orderBy('id', $request->input('orderType'));
        }else{
            $query = $query->orderBy('sort', 'asc');
        }
      
        if($request->input('paginate') == true){
            $query = $query->paginate($request->input('perPage'), ['*'], 'page', $request->input('currentPage'));
        } else {
            $query = $query->get();
        }

        $data['lists'] = $query;
        return response()->json($data, 200);

    }

    public function getGeneralExpenses(Request $request){

        $query = General::query();

        if($request->input('challan_id')){
            $query = $query->where('challan_id', $request->input('challan_id'));
        }

        if($request->input('orderBy')){
            $query = $query->orderBy('id', $request->input('orderType'));
        }else{
            $query = $query->orderBy('sort', 'asc');
        }
      
        if($request->input('paginate') == true){
            $query = $query->paginate($request->input('perPage'), ['*'], 'page', $request->input('currentPage'));
        } else {
            $query = $query->get();
        }

        $data['lists'] = $query;
        return response()->json($data, 200);

    }
    
    public function verifyUniqueChallan($challanNumber){

        if(!Challan::where('challan_number', $challanNumber)->exists()){
            return response()->json(['status' => true], 200);
        } else {
            return response()->json(['status' => false], 422);
        }
        
    }

    public function cacheOptimize(){
        try{
            \Artisan::call('optimize:clear');
            return response()->json(['message' => 'successfully_optimized'], 200);
        } catch (\Exception $e){
            // return $e->getMessage();
            return response()->json(['message' => 'something_went_wrong'], 200);
        }
    }

    public function getVehiclesBasedOnVendor(Request $request){
        $results = ChallanRentalVehicle::where('vendor_id', $request->input('vendor_id'))
                                                ->distinct('vendor_id', 'vehicle_number')
                                                ->get()
                                                ->toArray();

        if(count($results) > 0){
            $data['vehicle_numbers'] =  array_column($results, 'vehicle_number');
            $data['driver_names'] =  array_column($results, 'driver_name');
            $data['driver_phones'] =  array_column($results, 'driver_phone');
            $data['owner_names'] =  array_column($results, 'owner_name');
            $data['owner_phones'] =  array_column($results, 'owner_phone');
            $data['reference_names'] =  array_column($results, 'reference_name');
            $data['reference_phones'] =  array_column($results, 'reference_phone');
        } else {
            $data['vehicle_numbers'] = [];
            $data['driver_names'] = [];
            $data['driver_phones'] = [];
            $data['owner_names'] = [];
            $data['owner_phones'] = [];
            $data['reference_names'] = [];
            $data['reference_phones'] = [];
        }
       
        return response()->json($data, 200);
    }

    public function getUniqueChallanNumber(){
        $data['data'] =  Challan::latest()->get(['challan_number'])->unique('challan_number');
        return response()->json($data, 200);
    }

    

}
