<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Accounts\Account;
use App\Models\Accounts\AccountTransection;

use Spatie\Activitylog\Models\Activity;

use DB;
use Auth;
use PDF;
use Carbon\Carbon;

class AccountReportController extends Controller
{

    public function accountFirstFormat(Request $request){

        $this->max_execution_time_and_backtrack_limit();
        $this->set_setting();

        $data['reporting_time'] = date('d M, Y h:i:s a');
        $data['page_title'] = __('cmn.transection') .' '.__('cmn.report') . ' - '. $data['reporting_time'];
        $data['file_title'] = __('cmn.transection') .' '. __('cmn.report')  . ' - '. $data['reporting_time'];
        $data['header_title'] = __('cmn.transection') .' '. __('cmn.report');
        
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
        $query = AccountTransection::query()->with('account');
        
        if($request->input('account_id')){
            $query = $query->where('account_id', $request->input('account_id'));
        }

        if($request->input('bank_id')){
            $query = $query->whereHas('account', function($subQuery) use($request) {
                $subQuery->where('bank_id', $request->input('bank_id'));  
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
        $pdf = PDF::loadView('pdf_report.account.transection', $data, [], $config);

        if($request->input('output') == 'stream'){
            return $pdf->stream($data['file_title'] . '.pdf');
        } else {
            return $pdf->download($data['file_title'] . '.pdf');
        }

    }


}