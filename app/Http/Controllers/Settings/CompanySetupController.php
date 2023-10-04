<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Toastr;
use Setting;


class CompanySetupController extends Controller
{

    public function getCompany()
    {   

        Setting::setExtraColumns(['company_id' => Auth::user()->company_id]);
        $data['company'] = Setting::get('company');
        return response()->json($data, 200);

    }

    public function saveCompany(Request $request)
    {         

        try{

            Setting::setExtraColumns(['company_id' => Auth::user()->company_id]);

            // company setting
            Setting::set('company.name', $request->input('name'));
            Setting::set('company.slogan', $request->input('slogan'));
            Setting::set('company.address', $request->input('address'));
            Setting::set('company.phone', $request->input('phone'));
            Setting::set('company.email', $request->input('email'));
            Setting::set('company.website', $request->input('website'));


            // system setting
            Setting::set('company.oil_rate', $request->input('oil_rate'));
            Setting::set('company.payment_feature', $request->input('payment_feature'));
            Setting::set('company.loan_feature', $request->input('loan_feature'));
            
            // image
            // Setting::set('company.logo', $request->input('logo'));
            // Setting::set('company.favicon', $request->input('favicon'));
    
            // notifcation setting
            Setting::set('company.notify_days_for_document', $request->input('notify_days_for_document'));
            Setting::set('company.notify_days_for_mobil', $request->input('notify_days_for_mobil'));
            Setting::set('company.notify_days_for_tyre', $request->input('notify_days_for_tyre'));


            Setting::save();

            $data['message'] = 'successfully_updated';
            return response()->json($data, 200);

        } catch(\Exception $e){

            // $data['message'] = $e->getMessage();
            $data['message'] = 'something_went_wrong';
            return response()->json($data, 500);

        }
    }

    public function admin()
    {
        $data['title'] = __('cmn.admin') . ' '. __('cmn.setting');
        $data['menu'] = 'setting';
        $data['sub_menu'] = 'system';

        Setting::setExtraColumns(['company_id' => Auth::user()->company_id]);

        $data['setting'] = Setting::get('admin_system');
        return view('settings.admin', $data);
    }

    // SettingRequest
    public function saveAdmin(Request $request)
    {
        Setting::setExtraColumns(['company_id' => Auth::user()->company_id]);

        Setting::set('admin_system.business_type', $request->input('business_type'));
        Setting::set('admin_system.max_own_vehicle_qty', $request->input('max_own_vehicle_qty'));
        Setting::set('admin_system.max_challan_qty_per_month', $request->input('max_challan_qty_per_month'));
        Setting::set('admin_system.last_date_of_bill_payment', $request->input('last_date_of_bill_payment'));
        Setting::set('admin_system.total_bill', $request->input('total_bill'));
        Setting::set('admin_system.notify_days_for_bill', $request->input('notify_days_for_bill'));
        Setting::set('admin_system.due_payment_action', $request->input('due_payment_action'));
        
        Setting::save();
        Toastr::success('Successfully Saved', 'Success');
        return redirect()->back();
    }
}
