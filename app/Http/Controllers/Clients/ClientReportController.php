<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Clients\ClientBill;
use App\Models\Clients\ClientPayment;

use PDF;
use Carbon\Carbon;

class ClientReportController extends Controller
{

    public function billingFirstFormat(Request $request){

        $this->max_execution_time_and_backtrack_limit();
        $this->set_setting();

        // common data
        $data['header_title'] = __('cmn.client') . ' ' .__('cmn.billing');
        $data['request'] = $request;

        $range_status = $request->input('range_status');
        $month = $request->input('month');
        $year = $request->input('year');
        $date_range = $request->input('daterange');

        $data['page_header'] = $request->input('page_header');
        $size = $request->input('size');

        // Query start
        $query = ClientBill::query()->with('client_info', 'purposeable.rental_vehicle', 'transaction');

        if($request->input('challan_number')){
            $value = $request->input('challan_number');
            $query = $query->whereHas('purposeable', function($subQuery) use($value) {
                $subQuery->where('challan_number', $value);
            });
        }

        if($request->input('voucher_number')){
            $query = $query->where('voucher_number', $request->input('voucher_number'));
        }

        if($request->input('vehicle_number')){
            $value = $request->input('vehicle_number');
            
            $query = $query->whereHas('purposeable.rental_vehicle', function($subQuery) use($value) {
                $subQuery->where('vehicle_number', $value);
            });
        }

        if($request->input('client_id') && !empty($request->input('client_id'))){
            $query = $query->where('client_id', $request->input('client_id'));
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
            $data['title'] .= ' - (' . __('cmn.month') .'- ' .$month_name. ', ' . __('cmn.year') .'- ' .__('cmn.'.$year.''). ')';
            $query = $query->whereMonth('date_time', $month)->whereYear('date_time', $year);
        }

        if($range_status == 'yearly_report'){
            if(!$year){
                Toastr::error(__('cmn.please_select_year_first'), __('cmn.warning'));
                return redirect()->back();
            }
            $data['title'] .= ' - (' . __('cmn.year') .'- ' .__('cmn.'.$year.''). ')';
            $query = $query->whereYear('date_time', $year);
        }

        if($range_status == 'date_wise' && $date_range){
            
            $date = explode(',', $date_range);
            $start_date = Carbon::parse($date[0])->startOfDay();
            $end_date = Carbon::parse($date[1])->endOfDay();
            $data['title'] .= ' - '  . __('cmn.date') .' - (' . Carbon::parse($date[0])->format('d F, Y') .' ' .__('cmn.from'). ' '. Carbon::parse($date[1])->format('d F, Y') . ')';
            
            $query = $query->whereBetween('date_time', [$start_date, $end_date]);
        }
        
        if($request->input('order_by')){
            $query = $query->orderBy('date_time', $request->input('order_by'));
        } else {
            $query = $query->orderBy('date_time', 'ASC');
        }

        $data['lists'] = $query->get();

        // common data
        $data['reporting_time'] = date('d M Y, h:i A');
        $data['page_title'] = __('cmn.client') . ' ' . __('cmn.billing') . ' - '. $data['reporting_time'];
        $data['file_title'] = __('cmn.client') . ' ' .__('cmn.billing') . ' - '. $data['reporting_time'];

        // assign page config
        $config['format'] = $size;
        $pdf = PDF::loadView('pdf_report.client.billing_first_format', $data, [], $config);

        if($request->input('output') == 'stream'){
            return $pdf->stream($data['file_title'] . '.pdf');
        } else {
            return $pdf->download($data['file_title'] . '.pdf');
        }
    }

    public function paymentFirstFormat(Request $request){

        $this->max_execution_time_and_backtrack_limit();
        $this->set_setting();

        // common data
        $data['header_title'] = __('cmn.client') . ' ' .__('cmn.payment');
        $data['request'] = $request;

        $range_status = $request->input('range_status');
        $month = $request->input('month');
        $year = $request->input('year');
        $date_range = $request->input('daterange');

        $data['page_header'] = $request->input('page_header');
        $size = $request->input('size');

        // Query start
        $query = ClientPayment::query()->with('client_info', 'purposeable', 'transaction.account');

        if($request->input('challan_number')){
            $value = $request->input('challan_number');
            $query = $query->whereHas('purposeable', function($subQuery) use($value) {
                $subQuery->where('challan_number', $value);
            });
        }

        if($request->input('voucher_number')){
            $query = $query->where('voucher_number', $request->input('voucher_number'));
        }

        if($request->input('vehicle_number')){
            $value = $request->input('vehicle_number');
            
            $query = $query->whereHas('purposeable.rental_vehicle', function($subQuery) use($value) {
                $subQuery->where('vehicle_number', $value);
            });
        }

        if($request->input('client_id') && !empty($request->input('client_id'))){
            $query = $query->where('client_id', $request->input('client_id'));
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
            $query = $query->whereMonth('date_time', $month)->whereYear('date_time', $year);
        }

        if($range_status == 'yearly_report'){
            if(!$year){
                Toastr::error(__('cmn.please_select_year_first'), __('cmn.warning'));
                return redirect()->back();
            }
            $data['header_title'] .= ' - (' . __('cmn.year') .'- ' .__('cmn.'.$year.''). ')';
            $query = $query->whereYear('date_time', $year);
        }

        if($range_status == 'date_wise' && $date_range){
            
            $date = explode(',', $date_range);
            $start_date = Carbon::parse($date[0])->startOfDay();
            $end_date = Carbon::parse($date[1])->endOfDay();
            $data['header_title'] .= ' - '  . __('cmn.date') .' - (' . Carbon::parse($date[0])->format('d F, Y') .' ' .__('cmn.from'). ' '. Carbon::parse($date[1])->format('d F, Y') . ')';
            
            $query = $query->whereBetween('date_time', [$start_date, $end_date]);
        }
        
        if($request->input('order_by')){
            $query = $query->orderBy('date_time', $request->input('order_by'));
        } else {
            $query = $query->orderBy('date_time', 'ASC');
        }

        $data['lists'] = $query->get();

        // common data
        $data['reporting_time'] = date('d M Y, h:i A');
        $data['page_title'] = __('cmn.client') . ' ' . __('cmn.payment') . ' - '. $data['reporting_time'];
        $data['file_title'] = __('cmn.client') . ' ' .__('cmn.payment') . ' - '. $data['reporting_time'];

        // assign page config
        $config['format'] = $size;
        $pdf = PDF::loadView('pdf_report.client.payment_first_format', $data, [], $config);

        if($request->input('output') == 'stream'){
            return $pdf->stream($data['file_title'] . '.pdf');
        } else {
            return $pdf->download($data['file_title'] . '.pdf');
        }
    }


    

}