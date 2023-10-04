<?php

namespace App\Http\Controllers\Challans;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Challans\Challan;


use DB;
use Auth;
use PDF;
use Carbon\Carbon;

class CommissionChallanReportController extends Controller
{

    public function singleChallan(Request $request){

        $this->max_execution_time_and_backtrack_limit();
        $this->set_setting();

        // request assign
        $encrypt = $request->input('encrypt');
        $output = $request->input('output');
        $size = $request->input('size');
        $data['page_header'] = $request->input('page_header');

        // Query start
        $query = Challan::query()->with(
            'rental_vehicle.vendor',

            // vendor bill part
            'vendor_bills',
            'vendor_contract_bills',
            'vendor_demurrage_bills',

            // vendor payment part
            'vendor_payments',
            'vendor_advance_payments',
            'vendor_after_advance_payments',
            'vendor_demurrage_payments',

            // client bill part
            'client_bills.client_info',
            'client_contract_bills',
            'client_demurrage_bills',

            // client payment part
            'client_payments',
            'client_advance_payments',
            'client_after_advance_payments',
            'client_demurrage_payments',

            // client part
            'created_info',
            'updated_info'
        )->where('company_id', Auth::user()->company_id);

        if($encrypt){
            $query = $query->where('encrypt', $encrypt);
        }

        $query = $query->first();
        $data['list'] = $this->addData($query);

        // common data
        $data['reporting_time'] = date('d M Y, h:i A');
        $data['page_title'] = __('cmn.challan') . ' - ' . $data['list']->challan_number . ' - '. $data['reporting_time'];
        $data['file_title'] = __('cmn.challan') . ' - ' . $data['list']->challan_number . ' - '. $data['reporting_time'];
        $data['header_title'] = __('cmn.challan') . ' - ' . $data['list']->challan_number;

        // assign page config
        $config['format'] = $size??'A4-L';
        $pdf = PDF::loadView('pdf_report.challan.commission_challan.single_print', $data, [], $config);

        if($request->input('output') == 'stream'){
            return $pdf->stream($data['file_title'] . '.pdf');
        } else {
            return $pdf->download($data['file_title'] . '.pdf');
        }
    }

    public function addData($item){

        // if has load point
        if(count($item['points'])){

            $load = '';
            $unload = '';

            foreach($item['points'] as $point){

                if($point->pivot->point == 'load'){
                    $load .= $point->name . ', ';
                }

                if($point->pivot->point == 'unload'){
                    $unload .= $point->name . ', ';
                }
            }

            $item['load'] = $load;
            $item['unload'] = $unload;
        }
       
        return $item;
    }

    public function firstFormat(Request $request){

        // dd($request);

        $this->max_execution_time_and_backtrack_limit();
        $this->set_setting();

        $data['reporting_time'] = date('d M, Y h:i:s a');
        $data['page_title'] = __('cmn.commission_challan') .' '.__('cmn.report') . ' - '. $data['reporting_time'];
        $data['file_title'] = __('cmn.commission_challan') .' '. __('cmn.report')  . ' - '. $data['reporting_time'];
        $data['header_title'] = __('cmn.commission_challan') .' '. __('cmn.report');
        
        $data['request'] = $request;

        $range_status = $request->input('range_status');
        $month = $request->input('month');
        $year = $request->input('year');
        $date_range = $request->input('daterange');

        $data['page_header'] = $request->input('page_header');
        $size = $request->input('size');

        // Query start
        // Query start
        $query = Challan::query()->with(
            'rental_vehicle.vendor',

            // vendor bill part
            'vendor_bills',
            'vendor_contract_bills',
            'vendor_demurrage_bills',

            // vendor payment part
            'vendor_payments',
            'vendor_advance_payments',
            'vendor_after_advance_payments',
            'vendor_demurrage_payments',

            // client bill part
            'client_bills.client_info',
            'client_contract_bills',
            'client_demurrage_bills',

            // client payment part
            'client_payments',
            'client_advance_payments',
            'client_after_advance_payments',
            'client_demurrage_payments',

            // client part
            'created_info',
            'updated_info'
        );

        if($request->input('encrypt')){
            $query = $query->where('encrypt', $request->input('encrypt'));
        }

        if($request->input('challan_number')){
            $query = $query->where('challan_number', $request->input('challan_number'));
        }
    
        if($request->input('vehicle_number')){
            $value = $request->input('vehicle_number');
            $query = $query->whereHas('rental_vehicle', function($subQuery) use($value) {
                $subQuery->where('vehicle_number', $value);  
            });
        }

        if($request->input('vendor_id')){
            $value = $request->input('vendor_id');
            $query = $query->whereHas('rental_vehicle', function($subQuery) use($value) {
                $subQuery->where('vendor_id', $value);  
            });
        }

        if($request->input('client_id')){
            $value = $request->input('client_id');
            $query = $query->whereHas('client_bills.client_info', function($subQuery) use($value) {
                $subQuery->where('id', $value);  
            });
        }
        
        if($range_status == 'monthly_report'){
            if(!$month){

                $data['message'] = 'please_select_month_first';
                return response()->json($data, 422);

            }
            if(!$year){

                $data['message'] = 'please_select_year_first';
                return response()->json($data, 422);

            }
            // $month_name = CommonService::getMonthNameByMonthId($month);
            $month_name = 'dynamic month name';
            $data['header_title'] .= ' - (' . __('cmn.month') .'- ' .$month_name. ', ' . __('cmn.year') .'- ' .__('cmn.'.$year.''). ')';
            $query = $query->whereMonth('start_date_time', $month)->whereYear('start_date_time', $year);
        }

        if($range_status == 'yearly_report'){
            if(!$year){
                Toastr::error(__('cmn.please_select_year_first'), __('cmn.warning'));
                return redirect()->back();
            }
            $data['header_title'] .= ' - (' . __('cmn.year') .'- ' .__('cmn.'.$year.''). ')';
            $query = $query->whereYear('start_date_time', $year);
        }

        if($range_status == 'date_wise' && $date_range){

            // date process
            $date = explode(',', $date_range);
            $start_date = Carbon::parse($date[0])->startOfDay();
            $end_date = Carbon::parse($date[1])->endOfDay();

            $data['header_title'] .= ' - '  . __('cmn.date') .' - (' . Carbon::parse($date[0])->format('d F, Y') .' ' .__('cmn.from'). ' '. Carbon::parse($date[1])->format('d F, Y') . ')';
            $query = $query->whereBetween('start_date_time', [$start_date, $end_date]);
        }
        
        if($request->input('order_by')){
            $query = $query->orderBy('start_date_time', $request->input('order_by'));
        } else {
            $query = $query->orderBy('start_date_time', 'ASC');
        }

        $data['lists'] = $query->get()->map(function($item) {

            // vendor part
            $item['total_vendor_bills'] = 0;
            $item['total_vendor_payments'] = 0;
            if(count($item->vendor_bills) > 0){
                $item['total_vendor_bills'] = array_sum(array_column(($item->vendor_bills->toArray()), 'amount'));
            }
            if(count($item->vendor_payments) > 0){
                $item['total_vendor_payments'] = array_sum(array_column(($item->vendor_payments->toArray()), 'amount'));
            }
            $item['total_vendor_challan_due'] = $item['total_vendor_bills'] - $item['total_vendor_payments'];

            // client part
            $item['total_client_bills'] = 0;
            $item['total_client_payments'] = 0;
            if(count($item->client_bills) > 0){
                $item['total_client_bills'] = array_sum(array_column(($item->client_bills->toArray()), 'amount'));
            }
            if(count($item->client_payments) > 0){
                $item['total_client_payments'] = array_sum(array_column(($item->client_payments->toArray()), 'amount'));
            }
            $item['total_client_challan_due'] = $item['total_client_bills'] - $item['total_client_payments'];

            $item['balance'] = $item['total_client_payments'] - $item['total_vendor_payments'];

            return $item;
        });

        // assign page config
        $config['format'] = $size;
        $pdf = PDF::loadView('pdf_report.challan.commission_challan.first_format', $data, [], $config);

        if($request->input('output') == 'stream'){
            return $pdf->stream($data['file_title'] . '.pdf');
        } else {
            return $pdf->download($data['file_title'] . '.pdf');
        }
    }

}