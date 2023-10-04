<?php

namespace App\Http\Controllers\Challans;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Challans\Challan;
use App\Models\Challans\ChallanOwnVehicle;

use Spatie\Activitylog\Models\Activity;

use DB;
use Auth;
use PDF;
use Carbon\Carbon;

class DepositChallanReportController extends Controller
{

    public function firstFormat(Request $request){

        $this->max_execution_time_and_backtrack_limit();
        $this->set_setting();

        // common data
        $data['reporting_time'] = date('d M, Y h:i:s a');
        $data['page_title'] = __('cmn.deposit_challan') .' '.__('cmn.report') . ' - '. $data['reporting_time'];
        $data['file_title'] = __('cmn.deposit_challan') .' '. __('cmn.report')  . ' - '. $data['reporting_time'];
        $data['header_title'] = __('cmn.deposit_challan') .' '. __('cmn.report');

        // request assign
        $data['request'] = $request;
        $range_status = $request->input('range_status');
        $month = $request->input('month');
        $year = $request->input('year');
        $date_range = $request->input('daterange');

        $data['page_header'] = $request->input('page_header');
        $size = $request->input('size');

        // Query start
        $query = Challan::query()->with(
            // client bill part
            'client_bills.client_info',
            'client_contract_bills',
            'client_demurrage_bills',

            // client payment part
            'client_payments',
            'client_advance_payments',
            'client_after_advance_payments',
            'client_demurrage_payments',

            'general_expenses.expense_info',
            'general_expense_sum',

            'oil_expenses',
            'oil_expense_sum',
            'unit_info',

            'created_info',
            'updated_info'
        );
   
        if($request->input('challan_number')){
            $query = $query->where('challan_number', $request->input('challan_number'));
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


            // bills part
            $item['total_client_bills'] = 0;
            $item['total_client_contract_bills'] = 0;
            $item['total_client_demurrage_bills'] = 0;
            
            if(count($item->client_bills) > 0){
                $item['total_client_bills'] = array_sum(array_column(($item->client_bills->toArray()), 'amount'));
            }
            if(count($item->client_bills) > 0){
                $item['total_client_contract_bills'] = array_sum(array_column(($item->client_contract_bills->toArray()), 'amount'));
            }
            if(count($item->client_bills) > 0){
                $item['total_client_demurrage_bills'] = array_sum(array_column(($item->client_demurrage_bills->toArray()), 'amount'));
            }

            // payment part
            $item['total_client_payments'] = 0;
            $item['total_client_challan_due'] = 0;
            if(count($item->client_payments) > 0){
                $item['total_client_payments'] = array_sum(array_column(($item->client_payments->toArray()), 'amount'));
            }
            $item['total_client_challan_due'] = $item['total_client_bills'] - $item['total_client_payments'];

            // expense
            $item['total_general_expense'] = $item->general_expense_sum->total??0; 
            $item['total_oil_expense'] = $item->oil_expense_sum->total??0; 

            $item['total_balance'] = $item['total_client_payments'] - ($item['total_general_expense'] + $item['total_oil_expense']);
            return $item;
        });

        // assign page config
        $config['format'] = $size;
        $pdf = PDF::loadView('pdf_report.challan.deposit_challan.first_format', $data, [], $config);

        if($request->input('output') == 'stream'){
            return $pdf->stream($data['file_title'] . '.pdf');
        } else {
            return $pdf->download($data['file_title'] . '.pdf');
        }

    }

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
            // client bill part
            'client_bills.client_info',
            'client_contract_bills',
            'client_demurrage_bills',

            // client payment part
            'client_payments',
            'client_advance_payments',
            'client_after_advance_payments',
            'client_demurrage_payments',

            'general_expenses.expense_info',
            'general_expense_sum',

            'oil_expenses',
            'oil_expense_sum',
            'unit_info',

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
        $pdf = PDF::loadView('pdf_report.challan.deposit_challan.single_print', $data, [], $config);

        if($request->input('output') == 'stream'){
            return $pdf->stream($data['file_title'] . '.pdf');
        } else {
            return $pdf->download($data['file_title'] . '.pdf');
        }
    }

    public function addData($item){
        
        // add vehicle info
        $challanOwnVehicle = ChallanOwnVehicle::where('challan_id', $item->id)->with('vehicle_info', 'driver_info', 'helper_info')->first();

        if($challanOwnVehicle){
            $item['vehicle_id'] = $challanOwnVehicle->vehicle_id;
            $item['number_plate'] = $challanOwnVehicle->vehicle_info->number_plate;

            $item['driver_name'] = $challanOwnVehicle->driver_info ? $challanOwnVehicle->driver_info->name :'';
            $item['driver_phone'] = $challanOwnVehicle->driver_info ? $challanOwnVehicle->driver_info->phone : '';

            $item['helper_name'] = $challanOwnVehicle->helper_info ? $challanOwnVehicle->helper_info->name: '';
            $item['helper_phone'] = $challanOwnVehicle->helper_info ? $challanOwnVehicle->helper_info->phone: '';

        } else {
            $item['vehicle_id'] = '';
            $item['number_plate'] = '';
            $item['driver_name'] = '';
            $item['driver_phone'] = '';
            $item['helper_name'] = '';
            $item['helper_phone'] = '';
        }

        // if has load point
        if(count($item->points) > 0){

            $load = '';
            $unload = '';

            foreach($item->points as $point){

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



}