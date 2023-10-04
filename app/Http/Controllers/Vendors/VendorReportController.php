<?php

namespace App\Http\Controllers\Vendors;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Expenses\General;
use App\Models\Expenses\Oil;
use App\Models\Loans\LoanTransection;


use App\Models\Vendors\VendorBill;
use App\Models\Vendors\VendorPayment;

use PDF;
use Carbon\Carbon;

class VendorReportController extends Controller
{
    
    public function pumpBillingFirstFormat(Request $request){

        $this->max_execution_time_and_backtrack_limit();
        $this->set_setting();

        // common data
        $data['reporting_time'] = date('d M, Y h:i:s a');
        $data['page_title'] = __('cmn.billing') .' '.__('cmn.report') . ' - '. $data['reporting_time'];
        $data['file_title'] = __('cmn.billing') .' '. __('cmn.report')  . ' - '. $data['reporting_time'];
        $data['header_title'] = __('cmn.billing') .' '.__('cmn.report');
        $data['request'] = $request;

        $range_status = $request->input('range_status');
        $month = $request->input('month');
        $year = $request->input('year');
        $date_range = $request->input('daterange');

        $bank_id =  $request->input('bank_id');
        $account_id = $request->input('account_id');

        $data['page_header'] = $request->input('page_header');
        $size = $request->input('size');

        // Query start
        $query = Oil::query()->with('vendor_info', 'vehicle_info', 'challan_info');
        $query = $query->whereNotNull('vendor_id');

        if($request->input('vendor_id')){
            $query = $query->where('vendor_id', $request->input('vendor_id'));
        }

        if($request->input('vendor_type')){
            $query = $query->whereHas('vendor_info', function($subQuery) use($request) {
                $subQuery->where('type', $request->input('vendor_type'));  
            });
        }
        
        if($request->input('vendor_id')){
            $query = $query->where('vendor_id', $request->input('vendor_id'));
        }

        if($request->input('expense_id')){
            $query = $query->where('expense_id', $request->input('expense_id'));
        }

        if($request->input('own_vehicle_id')){
            $query = $query->where('vehicle_id', $request->input('own_vehicle_id'));
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

        // dd($data['lists']);

        // assign page config
        $config['format'] = $size;
        $pdf = PDF::loadView('pdf_report.vendor.pump_billing_first_format', $data, [], $config);

        if($request->input('output') == 'stream'){
            return $pdf->stream($data['file_title'] . '.pdf');
        } else {
            return $pdf->download($data['file_title'] . '.pdf');
        }

    }

    public function vehicleSupplierBillingFirstFormat(Request $request){

        $this->max_execution_time_and_backtrack_limit();
        $this->set_setting();
        
        // common data
        $data['header_title'] = __('cmn.vehicle_supplier') . ' ' .__('cmn.billing');
        $data['request'] = $request;

        $range_status = $request->input('range_status');
        $month = $request->input('month');
        $year = $request->input('year');
        $date_range = $request->input('daterange');

        $data['page_header'] = $request->input('page_header');
        $size = $request->input('size');

        // Query start
        $query = VendorBill::query()->with('vendor_info', 'purposeable.rental_vehicle', 'transaction');

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

        if($request->input('vendor_id')){
            $query = $query->where('vendor_id', $request->input('vendor_id'));
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
        $data['page_title'] = __('cmn.vehicle_supplier') . ' ' . __('cmn.billing') . ' - '. $data['reporting_time'];
        $data['file_title'] = __('cmn.vehicle_supplier') . ' ' .__('cmn.billing') . ' - '. $data['reporting_time'];

        // assign page config
        $config['format'] = $size;
        $pdf = PDF::loadView('pdf_report.vehicle_supplier.billing_first_format', $data, [], $config);

        if($request->input('output') == 'stream'){
            return $pdf->stream($data['file_title'] . '.pdf');
        } else {
            return $pdf->download($data['file_title'] . '.pdf');
        }
    }

    public function vehicleSupplierPaymentFirstFormat(Request $request){

        $this->max_execution_time_and_backtrack_limit();
        $this->set_setting();

        // common data
        $data['header_title'] = __('cmn.vehicle_supplier') . ' ' .__('cmn.payment');
        $data['request'] = $request;

        $range_status = $request->input('range_status');
        $month = $request->input('month');
        $year = $request->input('year');
        $date_range = $request->input('daterange');

        $data['page_header'] = $request->input('page_header');
        $size = $request->input('size');

        // Query start
        $query = VendorPayment::query()->with('vendor_info', 'purposeable', 'transaction.account');

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

        if($request->input('vendor_id')){
            $query = $query->where('vendor_id', $request->input('vendor_id'));
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
        $data['page_title'] = __('cmn.vehicle_supplier') . ' ' . __('cmn.payment') . ' - '. $data['reporting_time'];
        $data['file_title'] = __('cmn.vehicle_supplier') . ' ' .__('cmn.payment') . ' - '. $data['reporting_time'];

        // assign page config
        $config['format'] = $size;
        $pdf = PDF::loadView('pdf_report.vehicle_supplier.payment_first_format', $data, [], $config);

        if($request->input('output') == 'stream'){
            return $pdf->stream($data['file_title'] . '.pdf');
        } else {
            return $pdf->download($data['file_title'] . '.pdf');
        }
    }

    public function vendorTransectionFirstFormat(Request $request){

        $this->max_execution_time_and_backtrack_limit();
        $this->set_setting();

        // common data
        $data['header_title'] = __('cmn.transection_summary');
        $data['request'] = $request;

        $range_status = $request->input('range_status');
        $month = $request->input('month');
        $year = $request->input('year');
        $date_range = $request->input('daterange');

        $data['page_header'] = $request->input('page_header');
        $size = $request->input('size');

        // Query start
        $query = VendorPayment::query()->with('vendor_info', 'purposeable', 'transaction.account');

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

        if($request->input('vendor_id')){
            $query = $query->where('vendor_id', $request->input('vendor_id'));
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
        $data['page_title'] = __('cmn.transection_summary') . ' - '. $data['reporting_time'];
        $data['file_title'] = __('cmn.transection_summary') . ' - '. $data['reporting_time'];

        // assign page config
        $config['format'] = $size;
        $pdf = PDF::loadView('pdf_report.vendor.vendor_transection_first_format', $data, [], $config);

        if($request->input('output') == 'stream'){
            return $pdf->stream($data['file_title'] . '.pdf');
        } else {
            return $pdf->download($data['file_title'] . '.pdf');
        }
    }

    public function vendorBillingFirstFormat(Request $request){

        $this->max_execution_time_and_backtrack_limit();
        $this->set_setting();

        // common data
        $data['reporting_time'] = date('d M, Y h:i:s a');
        $data['page_title'] = __('cmn.billing') .' '.__('cmn.report') . ' - '. $data['reporting_time'];
        $data['file_title'] = __('cmn.billing') .' '. __('cmn.report')  . ' - '. $data['reporting_time'];
        $data['header_title'] = __('cmn.billing') .' '.__('cmn.report');
        $data['request'] = $request;

        $range_status = $request->input('range_status');
        $month = $request->input('month');
        $year = $request->input('year');
        $date_range = $request->input('daterange');

        $bank_id =  $request->input('bank_id');
        $account_id = $request->input('account_id');

        $data['page_header'] = $request->input('page_header');
        $size = $request->input('size');

        // Query start
        $query = General::query()->with('expense_info', 'vendor_info', 'vehicle_info', 'challan_info');

        $query = $query->whereNotNull('vendor_id');

        if($request->input('vendor_id')){
            $query = $query->where('vendor_id', $request->input('vendor_id'));
        }

        if($request->input('vendor_type')){
            $query = $query->whereHas('vendor_info', function($subQuery) use($request) {
                $subQuery->where('type', $request->input('vendor_type'));  
            });
        }
        
        if($request->input('expense_id')){
            $query = $query->where('expense_id', $request->input('expense_id'));
        }

        if($request->input('own_vehicle_id')){
            $query = $query->where('vehicle_id', $request->input('own_vehicle_id'));
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

        // dd($data['lists']);

        // assign page config
        $config['format'] = $size;
        $pdf = PDF::loadView('pdf_report.vendor.vendor_billing_first_format', $data, [], $config);

        if($request->input('output') == 'stream'){
            return $pdf->stream($data['file_title'] . '.pdf');
        } else {
            return $pdf->download($data['file_title'] . '.pdf');
        }

    }

    public function vendorLoanFirstFormat(Request $request){

        $this->max_execution_time_and_backtrack_limit();
        $this->set_setting();
        
        // common data
        $data['reporting_time'] = date('d M, Y h:i:s a');
        $data['page_title'] = __('cmn.loan') .' '.__('cmn.report') . ' - '. $data['reporting_time'];
        $data['file_title'] = __('cmn.loan') .' '. __('cmn.report')  . ' - '. $data['reporting_time'];
        $data['header_title'] = __('cmn.loan') .' '.__('cmn.report');
        $data['request'] = $request;

        $range_status = $request->input('range_status');
        $month = $request->input('month');
        $year = $request->input('year');
        $date_range = $request->input('daterange');

        $bank_id =  $request->input('bank_id');
        $account_id = $request->input('account_id');

        $data['page_header'] = $request->input('page_header');
        $size = $request->input('size');

        // Query start
        $query = LoanTransection::query()->with('providerable', 'purposeable');
        
        if($request->input('vendor_type')){
            $query = $query->whereHas('providerable', function($subQuery) use($request) {
                $subQuery->where('type', $request->input('vendor_type'));  
            });
        }

        if($request->input('vendor_id')){
            $query = $query->where('providerable_id', $request->input('vendor_id'));
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

        // assign page config
        $config['format'] = $size;
        $pdf = PDF::loadView('pdf_report.vendor.loan_first_format', $data, [], $config);

        if($request->input('output') == 'stream'){
            return $pdf->stream($data['file_title'] . '.pdf');
        } else {
            return $pdf->download($data['file_title'] . '.pdf');
        }

    }

    public function vendorLoanAndPaymentFirstFormat(Request $request){

        $this->max_execution_time_and_backtrack_limit();
        $this->set_setting();
        
        // common data
        $data['reporting_time'] = date('d M, Y h:i:s a');
        $data['page_title'] = __('cmn.loan') .' '. __('cmn.and') .' '. __('cmn.payment') .' '.__('cmn.report') . ' - '. $data['reporting_time'];
        $data['file_title'] = __('cmn.loan') .' '. __('cmn.and') .' '. __('cmn.payment') .' '.__('cmn.report') . ' - '. $data['reporting_time'];
        $data['header_title'] = __('cmn.loan') .' '. __('cmn.and') .' '. __('cmn.payment') .' '.__('cmn.report');
        $data['request'] = $request;

        $range_status = $request->input('range_status');
        $month = $request->input('month');
        $year = $request->input('year');
        $date_range = $request->input('daterange');

        $bank_id =  $request->input('bank_id');
        $account_id = $request->input('account_id');

        $data['page_header'] = $request->input('page_header');
        $size = $request->input('size');

        // Query start
        $query = LoanTransection::query()->with('providerable', 'purposeable', 'transactions.account');
        
        if($request->input('vendor_type')){
            $query = $query->whereHas('providerable', function($subQuery) use($request) {
                $subQuery->where('type', $request->input('vendor_type'));  
            });
        }

        if($request->input('vendor_id')){
            $query = $query->where('providerable_id', $request->input('vendor_id'));
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

        // dd($data['lists']);

        // assign page config
        $config['format'] = $size;
        $pdf = PDF::loadView('pdf_report.vendor.loan_and_payment_first_format', $data, [], $config);

        if($request->input('output') == 'stream'){
            return $pdf->stream($data['file_title'] . '.pdf');
        } else {
            return $pdf->download($data['file_title'] . '.pdf');
        }
    }


}