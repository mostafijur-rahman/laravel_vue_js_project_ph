<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Accounts\Account;
use App\Models\Accounts\AccountTransection;

use App\Http\Requests\Account\AccountRequest;

use Spatie\Activitylog\Models\Activity;

use DB;
use Auth;

class AccountController extends Controller
{

    public function index(Request $request)
    {

        $query = Account::query()->with('bank_info', 'transections');
        
        if($request->input('name')){
            $query = $query->where('name', 'like', '%' . $request->input('name') . '%');
        }

        if($request->input('orderBy')){
            $query = $query->orderBy('id', $request->input('orderType'));
        }

        if($request->input('paginate') == true){
            $query = $query->paginate($request->input('perPage'), ['*'], 'page', $request->input('currentPage'));
        } else {
            $query = $query->get();
        }

        $data['lists'] = $query;
        return response()->json($data, 200);

    }

    public function store(AccountRequest $request)
    {

        try {

            $model = new Account();
            $model->encrypt = uniqid();
            // $model->sort = 0;

            $model->type = $request->input('type');

            if($request->input('type') == 'bank'){
                $model->bank_id = $request->input('bank_id');
                $model->holder_name = $request->input('holder_name');
                $model->account_number = $request->input('account_number');
            }

            $model->balance = 0;
            $model->user_name = $request->input('user_name');
            $model->note = $request->input('note');


            $model->status = $request->input('status');
            $model->created_by = Auth::user()->id;
            $model->company_id = Auth::user()->company_id;
            $model->save();

            $data['message'] = 'successfully_posted';
            $data['data'] = $model->load('bank_info');
            return response()->json($data, 200);

        } catch (\Exception $e) {
            $data['message'] = 'did_not_posted';
            return response()->json($data, 500);
        }
    }

    public function update(AccountRequest $request, $id)
    {

        // $this->validate($request,[
        //     'type' => 'required',
        //     'bank_id' => 'required_if:type,bank',
        //     'holder_name' => 'required_if:type,bank',
        //     'account_number' => 'required_if:type,bank',
        //     // 'account_number' => 'required_if:type,bank | unique:accounts,account_number,' . $id . ',id,deleted_at,NULL',
        //     'user_name' => 'required',
        // ]);

        // try{

            $model = Account::whereId($id)->first();
            $model->type = $request->input('type');

            // if bank
            if($model->type == 'bank'){
                $model->bank_id = $request->input('bank_id');
                $model->holder_name = $request->input('holder_name');
                $model->account_number = $request->input('account_number');
            // else cash
            } else {
                $model->bank_id = null;
                $model->holder_name = '';
                $model->account_number = '';
            }
            $model->user_name = $request->input('user_name');

            $model->note = $request->input('note');
            // $model->user_id = $request->input('user_id');
            $model->status = $request->input('status');
            $model->updated_by = Auth::user()->id;
            $model->save();

            $data['message'] = 'successfully_updated';
            $data['data'] = $model->load('bank_info');
            return response()->json($data, 200);

        // }catch (\Exception $e) {
        //     $data['message'] = 'did_not_updated';
        //     return response()->json($data, 500);
        // }
    }

    public function destroy($id)
    {
        try {

            $model = Account::find($id);
            $model->update(['updated_by'=> Auth::user()->id]);
            $model->delete();

            $data['message'] = 'successfully_deleted';
            return response()->json($data, 200);

        } catch (\Exception $e) {
            $data['message'] = 'something_went_wrong';
            return response()->json($data, 500);
        }
    }

    public function accountTransactions(Request $request){

        $query = AccountTransection::query()->with('account.bank_info', 'created_by');

        if($request->input('transactionable_id')){
            $query = $query->where('transactionable_id', $request->input('transactionable_id'));
        }

        if($request->input('transactionable_type')){
            $query = $query->where('transactionable_type', $request->input('transactionable_type'));
        }

        if($request->input('transactionable_type')){
            $query = $query->where('transactionable_type', $request->input('transactionable_type'));
        }

        if($request->input('bank_id')){
            $bank_id = $request->input('bank_id');
            $query = $query->whereHas('account', function($subQuery) use($bank_id) {
                $subQuery->where('bank_id', $bank_id);
            });
        }

        if($request->input('transaction_id')){
            $query = $query->where('encrypt', 'like', '%' . $request->input('transaction_id') . '%');
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
        return response()->json($data, 200);
    }

    public function transactionDestroy($id)
    {
        try {

            $accountTrans = AccountTransection::where('id', $id)->with('account')->first();

            // delete cash out or cash in delete action
            if($accountTrans->transactionable_id == $id){
        
                if($accountTrans->type == 'in'){
                    $accountTrans->account()->decrement('balance', $accountTrans->amount, ['updated_by'=> Auth::user()->id]);
                } else {
                    $accountTrans->account()->increment('balance', $accountTrans->amount, ['updated_by'=> Auth::user()->id]);
                }

                $accountTrans->update(['updated_by'=> Auth::user()->id]);
                $accountTrans->delete();

                $data['message'] = 'successfully_deleted';
                return response()->json($data, 200);

            // balance transfer delete action
            } else {

                // first transaction
                if($accountTrans->type == 'in'){
                    $accountTrans->account->decrement('balance', $accountTrans->amount, ['updated_by'=> Auth::user()->id]);
                } else {
                    $accountTrans->account->increment('balance', $accountTrans->amount, ['updated_by'=> Auth::user()->id]);
                }

                $accountTrans->update(['updated_by'=> Auth::user()->id]);
                $accountTrans->delete();

                // second transaction
                $secondTrans = AccountTransection::where('id', $accountTrans->transactionable_id)->with('account')->first();

                if($secondTrans->type == 'in'){
                    $secondTrans->account->decrement('balance', $secondTrans->amount, ['updated_by'=> Auth::user()->id]);
                } else {
                    $secondTrans->account->increment('balance', $secondTrans->amount, ['updated_by'=> Auth::user()->id]);
                }

                $secondTrans->update(['updated_by'=> Auth::user()->id]);
                $secondTrans->delete();

                $data['message'] = 'successfully_deleted';
                return response()->json($data, 200);
            }

        } catch (\Exception $e) {

            $data['message'] = 'something_went_wrong';
            return response()->json($data, 422);

        }
    }



}