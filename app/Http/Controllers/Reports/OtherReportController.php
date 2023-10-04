<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Settings\SettingExpense;
use App\Models\Expenses\Oil;

// use App\Models\Challans\Challan;
use App\Models\Clients\ClientPayment;
use App\Models\Expenses\General;

use PDF;
use Carbon\Carbon;


class OtherReportController extends Controller
{

    private $sum_previous_expense = 0;
    private $sum_today_expense = 0;
    private $sum_total_expense = 0;


    public function nagadanOrDepositExpenseFirstFormat(Request $request){

        $this->max_execution_time_and_backtrack_limit();
        $this->set_setting();

        // common data
        $data['header_title'] = __('cmn.deposit_expense');
        $data['request'] = $request;

        $range_status = $request->input('range_status');
        $month = $request->input('month');
        $year = $request->input('year');
        $date_range = $request->input('daterange');

        $data['page_header'] = $request->input('page_header');
        $size = $request->input('size');

        // Query start
        $expenseQuery = SettingExpense::query();
        $expense_row_count = with(clone $expenseQuery)->count();

        $data['general_expenses'] = with(clone $expenseQuery)->with('expenses')->get()->map(function($item) {

            $item['previous'] = $item->expenses()->whereDate('date_time', '<', Carbon::today())->sum('amount');
            $item['today'] = $item->expenses()->whereDate('date_time', Carbon::today())->sum('amount');
            $item['total'] = $item['previous_status'] + $item['today_status'];

            $this->sum_previous_expense += $item['previous']; 
            $this->sum_today_expense += $item['today']; 
            $this->sum_total_expense += $item['total']; 

            return $item;
        });

        $data['previous_fuel'] = Oil::whereDate('date_time', '<' ,Carbon::today())->sum('amount');
        $data['today_fuel'] = Oil::whereDate('date_time', Carbon::today())->sum('amount');
        $data['total_fuel'] = $data['previous_fuel'] + $data['today_fuel'];

        // common
        $data['sum_previous_expense'] = $this->sum_previous_expense += $data['previous_fuel']; // A4
        $data['sum_today_expense'] = $this->sum_today_expense += $data['today_fuel']; // A5
        $data['sum_total_expense'] = $this->sum_total_expense += $data['total_fuel']; // A6

        $data['A4'] = $data['sum_previous_expense'];
        $data['A5'] = $data['sum_today_expense'];
        $data['A6'] = $data['sum_total_expense'];

        // deposit challan start ---------
        $clientPayment = ClientPayment::query()->where('purposeable_type', 'challans');
        $previous_client_payment_sum = with(clone $clientPayment)->whereDate('date_time', '<' ,Carbon::today())->sum('amount');
        $today_client_payment_sum = with(clone $clientPayment)->whereDate('date_time', Carbon::today())->sum('amount');

        $generalExpense = General::query()->whereNotNull('challan_id');
        $previous_general_expense_sum = with(clone $generalExpense)->whereDate('date_time', '<' ,Carbon::today())->sum('amount');
        $today_general_expense_sum = with(clone $generalExpense)->whereDate('date_time', Carbon::today())->sum('amount');

        $oilExpense = Oil::query()->whereNotNull('challan_id');
        $previous_oil_expense_sum = with(clone $oilExpense)->whereDate('date_time', '<' ,Carbon::today())->sum('amount');
        $today_oil_expense_sum = with(clone $oilExpense)->whereDate('date_time', Carbon::today())->sum('amount');

        $data['previous_deposit_challan'] = $previous_client_payment_sum - ($previous_general_expense_sum + $previous_oil_expense_sum);
        $data['today_deposit_challan'] = $today_client_payment_sum - ($today_general_expense_sum + $today_oil_expense_sum);
        $data['total_deposit_challan'] = $data['previous_deposit_challan'] + $data['today_deposit_challan'];

        // deposit challan end ---------

        $data['previous_commission_challan'] = 0;
        $data['today_commission_challan'] = 0;
        $data['total_commission_challan'] = 0;

        $data['sum_previous_deposit'] = $data['previous_deposit_challan']  + $data['previous_commission_challan'] ;
        $data['sum_today_deposit'] = $data['today_deposit_challan']  + $data['today_commission_challan'] ;
        $data['sum_total_deposit'] = $data['total_deposit_challan']  + $data['total_commission_challan'] ;

        // deposite side
        $data['A1'] = $data['sum_previous_deposit'];
        $data['A2'] = $data['sum_today_deposit'];
        $data['A3'] = $data['sum_total_deposit'];

        $data['B1'] = 0;
        $data['B2'] = $data['A1'] - $data['A4'];
        $data['B3'] = 0;

        $data['C1'] = $data['A1'];
        $data['C2'] = $data['B2'] + $data['A2'];
        $data['C3'] = $data['A3'];

        // expense side
        $data['B4'] = $data['B2'];
        $data['B5'] = ($data['B4'] + $data['A2']) - $data['A5'];
        $data['B6'] = $data['C3'] - $data['A6'];

        $data['C4'] = $data['B4'] + $data['A4'];
        $data['C5'] = $data['A5'] + (($data['B4'] + $data['A2']) - $data['A5']);
        $data['C6'] = $data['C3'] - $data['A6'] - $data['A6'];


        // row counting
        $deposit_row_count = 2; // deposit and commission challan are twos
        $data['row_count'] = ($expense_row_count + 2) - $deposit_row_count;

        // common data
        $data['reporting_time'] = date('d M Y, h:i A');
        $data['page_title'] = __('cmn.deposit_expense') . ' - '. $data['reporting_time'];
        $data['file_title'] = __('cmn.deposit_expense') . ' - '. $data['reporting_time'];

        // assign page config
        $config['format'] = $size;
        $pdf = PDF::loadView('pdf_report.other_report.nagadan_deposit_expense_first_format', $data, [], $config);

        if($request->input('output') == 'stream'){
            return $pdf->stream($data['file_title'] . '.pdf');
        } else {
            return $pdf->download($data['file_title'] . '.pdf');
        }
    }
    
    public function vehicleLedgerFirstFormat(Request $request){

        $this->max_execution_time_and_backtrack_limit();
        $this->set_setting();

        // common data
        $data['header_title'] = __('cmn.vehicle_ledger');
        $data['request'] = $request;

        $range_status = $request->input('range_status');
        $month = $request->input('month');
        $year = $request->input('year');
        $date_range = $request->input('daterange');

        $data['page_header'] = $request->input('page_header');
        $size = $request->input('size');

        // vehicle_ledger_deposit_first_format
        $data['lists'] = ClientPayment::query()
                                        ->with('purposeable.own_vehicle.vehicle_info')
                                        ->where('purposeable_type', 'challans')
                                        ->get();

        // $previous_client_payment_sum = with(clone $clientPayment)->whereDate('date_time', '<' ,Carbon::today())->sum('amount');


        // common data
        $data['reporting_time'] = date('d M Y, h:i A');
        $data['page_title'] = __('cmn.deposit_expense') . ' - '. $data['reporting_time'];
        $data['file_title'] = __('cmn.deposit_expense') . ' - '. $data['reporting_time'];

        // assign page config
        $config['format'] = $size;
        $pdf = PDF::loadView('pdf_report.other_report.vehicle_ledger_deposit_first_format', $data, [], $config);

        if($request->input('output') == 'stream'){
            return $pdf->stream($data['file_title'] . '.pdf');
        } else {
            return $pdf->download($data['file_title'] . '.pdf');
        }

    }

    public function expenseLedgerFirstFormat(Request $request){

        $this->max_execution_time_and_backtrack_limit();
        $this->set_setting();

        // common data
        $data['header_title'] = __('cmn.expense_ledger');
        $data['request'] = $request;

        $range_status = $request->input('range_status');
        $month = $request->input('month');
        $year = $request->input('year');
        $date_range = $request->input('daterange');

        $data['page_header'] = $request->input('page_header');
        $size = $request->input('size');

        // vehicle_ledger_deposit_first_format
        $generalExpenseQuery = General::query()->with('expense_info');
        // if(){
            // $today_general_expense_sum = with(clone $generalExpense)->whereDate('date_time', Carbon::today())->sum('amount');
        // }
        $data['general_expenses'] = $generalExpenseQuery->get();

        
        $oilExpenseQuery = Oil::query();
        $data['oil_expense'] = $oilExpenseQuery->get();

        // dd($data);


        // common data
        $data['reporting_time'] = date('d M Y, h:i A');
        $data['page_title'] = __('cmn.deposit_expense') . ' - '. $data['reporting_time'];
        $data['file_title'] = __('cmn.deposit_expense') . ' - '. $data['reporting_time'];

        // assign page config
        $config['format'] = $size;
        $pdf = PDF::loadView('pdf_report.other_report.expense_ledger_deposit_first_format', $data, [], $config);

        if($request->input('output') == 'stream'){
            return $pdf->stream($data['file_title'] . '.pdf');
        } else {
            return $pdf->download($data['file_title'] . '.pdf');
        }
    }


}