<?php

namespace App\Http\Controllers;

use App;
use App\AccountModel;
use Auth;
use DB;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class AccountController extends Controller
{
    use TraitSettings;

    public function __construct()
    {
        $data = $this->getapplications();
        if (!empty($data) && isset($data[0]->languages)) {
            $lang = $data[0]->languages;
            App::setLocale($lang);
        }
        $this->middleware('auth');
    }

    // show default data
    public function index()
    {
        if (Auth::user()->isrole('5')) {
            return view('account.index');
        } else {
            return redirect('home');
        }
    }

    // show default data
    public function detail($id)
    {
        if (Auth::user()->isrole('5')) {
            return view('account.detail', compact('id'));
        } else {
            return redirect('home');
        }
    }

    /**
     * Get account data
     *
     * @return object
     */
    public function getdata()
    {
        $account = AccountModel::select(['accountid', 'name', 'balance', 'description']);

        return Datatables::of($account)
            ->addColumn('balance', function ($single) {
                $setting = DB::table('settings')->where('settingsid', '1')->get();

                return $setting[0]->currency.number_format($single->balance, 2);
            })
            ->addColumn('action', function ($accountsingle) {
                return '<a href="account/detail/'.$accountsingle->accountid.'" id="btnedit" customdata='.$accountsingle->accountid.' class="btn btn-sm btn-success"><i class="ti-check-box"></i>'.trans('lang.detail').'</a>
						<a href="#" id="btnedit" customdata='.$accountsingle->accountid.' class="btn btn-sm btn-primary" data-toggle="modal" data-target="#edit"><i class="ti-pencil"></i> '.trans('lang.edit').'</a>
						<a href="#" id="btndelete" customdata='.$accountsingle->accountid.' class="btn btn-sm btn-danger" data-toggle="modal" data-target="#delete"><i class="ti-trash"></i> '.trans('lang.delete').'</a>';
            })->make(true);
    }

    /**
     * Get account data detail
     *
     * @return object
     */
    public function getdatadetail($id)
    {

        $data = DB::select("SELECT p.accountnumber, p.name,COALESCE(a.amount,0) as income,COALESCE(b.amount,0) as expense, COALESCE(p.balance+(COALESCE(a.amount,0)-COALESCE(b.amount,0)),0) as balance from account as p left join (select accountid,sum(amount) as amount from transaction where type=1 group by accountid) as a on a.accountid = p.accountid left join (select accountid,sum(amount) as amount from transaction where type=2 group by accountid) as b on b.accountid = p.accountid where p.accountid = $id group by p.accountid");

        $res['accountnumber'] = $data[0]->accountnumber;
        $res['name'] = $data[0]->name;
        $res['balance'] = number_format($data[0]->balance);

        return response($res);

    }

    /**
     * Get account balance by month
     *
     * @return object
     */
    public function accountbalancebymonth($id)
    {
        $year = date('Y');

        // Query income and expense grouped by month for the account
        $transactions = DB::table('transaction')
            ->select(DB::raw('type, MONTH(transactiondate) as month, SUM(amount) as amount'))
            ->whereIn('type', [1, 2])
            ->whereYear('transactiondate', $year)
            ->where('accountid', $id)
            ->groupBy('type', 'month')
            ->get()
            ->groupBy('type');

        // Initialize result array with zeros for each month and type
        $res = [];
        for ($m = 1; $m <= 12; $m++) {
            $res['ijan'] = $transactions[1][$m - 1]->amount ?? 0;
            $res['ifeb'] = $transactions[1][$m - 1]->amount ?? 0;
            $res['imar'] = $transactions[1][$m - 1]->amount ?? 0;
            $res['iapr'] = $transactions[1][$m - 1]->amount ?? 0;
            $res['imay'] = $transactions[1][$m - 1]->amount ?? 0;
            $res['ijun'] = $transactions[1][$m - 1]->amount ?? 0;
            $res['ijul'] = $transactions[1][$m - 1]->amount ?? 0;
            $res['iags'] = $transactions[1][$m - 1]->amount ?? 0;
            $res['isep'] = $transactions[1][$m - 1]->amount ?? 0;
            $res['iokt'] = $transactions[1][$m - 1]->amount ?? 0;
            $res['inov'] = $transactions[1][$m - 1]->amount ?? 0;
            $res['ides'] = $transactions[1][$m - 1]->amount ?? 0;

            $res['ejan'] = $transactions[2][$m - 1]->amount ?? 0;
            $res['efeb'] = $transactions[2][$m - 1]->amount ?? 0;
            $res['emar'] = $transactions[2][$m - 1]->amount ?? 0;
            $res['eapr'] = $transactions[2][$m - 1]->amount ?? 0;
            $res['emay'] = $transactions[2][$m - 1]->amount ?? 0;
            $res['ejun'] = $transactions[2][$m - 1]->amount ?? 0;
            $res['ejul'] = $transactions[2][$m - 1]->amount ?? 0;
            $res['eags'] = $transactions[2][$m - 1]->amount ?? 0;
            $res['esep'] = $transactions[2][$m - 1]->amount ?? 0;
            $res['eokt'] = $transactions[2][$m - 1]->amount ?? 0;
            $res['enov'] = $transactions[2][$m - 1]->amount ?? 0;
            $res['edes'] = $transactions[2][$m - 1]->amount ?? 0;
        }

        // Get account info
        $account = DB::table('account')->where('accountid', $id)->first();

        $res['accountnumber'] = $account->accountnumber ?? '';
        $res['name'] = $account->name ?? '';
        $res['balance'] = number_format(($account->balance ?? 0) + array_sum(array_column($transactions[1]->toArray() ?? [], 'amount')) - array_sum(array_column($transactions[2]->toArray() ?? [], 'amount')), 2);

        return response($res);
    }

    /**
     * insert data to database
     *
     * @param  string  $name
     * @param  float  $balance
     * @param  string  $description
     * @param  string  $accountnumber
     * @return object
     */
    public function save(Request $request)
    {
        $name = $request->input('name');
        $balance = $request->input('balance');
        $description = $request->input('description');
        $accountnumber = $request->input('accountnumber');

        $data = ['name' => $name, 'balance' => $balance, 'description' => $description, 'accountnumber' => $accountnumber];
        $insert = DB::table('account')->insert($data);

        if ($insert) {
            $res['success'] = true;
            $res['message'] = 'Account has been added';

            return response($res);
        }
    }

    /**
     * update data to database
     *
     * @param  int  $id
     * @param  string  $name
     * @param  float  $balance
     * @param  string  $description
     * @param  string  $accountnumber
     * @return object
     */
    public function saveedit(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');
        $balance = $request->input('balance');
        $description = $request->input('description');
        $accountnumber = $request->input('accountnumber');

        $update = DB::table('account')->where('accountid', $id)
            ->update(
                [
                    'name' => $name,
                    'balance' => $balance,
                    'description' => $description,
                    'accountnumber' => $accountnumber,
                ]
            );

        if ($update) {
            $res['success'] = true;
            $res['message'] = 'Account has been updated';

            return response($res);
        }

    }

    /**
     * delete data from database
     *
     * @param  int  $id
     * @return object
     */
    public function delete(Request $request)
    {
        $id = $request->input('iddelete');

        $delete = DB::table('account')->where('accountid', $id)->delete();
        $deletetransaction = DB::table('transaction')->where('accountid', $id)->delete();
        if ($delete) {
            $res['success'] = true;
            $res['message'] = 'Account has been deleted';

            return response($res);
        }
    }

    /**
     * get single data from database
     *
     * @param  int  $id
     * @return object
     */
    public function getedit(Request $request)
    {
        $id = $request->input('id');

        $data = DB::table('account')->where('accountid', $id)->get();

        if ($data) {
            $res['success'] = true;
            $res['message'] = $data;

            return response($res);
        }
    }

    /**
     * get account transaction
     *
     * @return object
     */
    public function getaccounttransaction($id)
    {
        $data = DB::table('transaction')->select(['transaction.*', 'category.name as category', 'subcategory.name as subcategory', DB::raw("IFNULL(a.amount,'-') as income, IFNULL(b.amount,'-') as expense")])
            ->leftJoin(DB::raw('(select transactionid,amount from transaction where type=1) as a'), function ($join) {
                $join->on('a.transactionid', '=', 'transaction.transactionid');
            })
            ->leftJoin(DB::raw('(select transactionid,amount from transaction where type=2) as b'), function ($join) {
                $join->on('b.transactionid', '=', 'transaction.transactionid');
            })
            ->leftJoin('subcategory', 'subcategoryid', '=', 'transaction.categoryid')
            ->leftJoin('category', 'category.categoryid', '=', 'subcategory.categoryid')
            ->leftJoin('account', 'account.accountid', '=', 'transaction.accountid')
            ->where('transaction.accountid', "$id")
            ->orderBy('transactiondate', 'desc');

        return Datatables::of($data)
            ->addColumn('income', function ($single) {
                $setting = DB::table('settings')->where('settingsid', '1')->get();
                $return = '';
                if ($single->income == '-') {
                    $return = '-';
                } else {

                    $return = '<p class="text-success">'.$setting[0]->currency.number_format($single->income, 2).'</p>';
                }

                return $return;
            })
            ->addColumn('expense', function ($single) {
                $setting = DB::table('settings')->where('settingsid', '1')->get();
                $return = '';
                if ($single->expense == '-') {
                    $return = '-';
                } else {

                    $return = '<p class="text-danger">'.$setting[0]->currency.number_format($single->expense, 2).'</p>';
                }

                return $return;
            })
            ->addColumn('transactiondate', function ($single) {
                $setting = DB::table('settings')->where('settingsid', '1')->get();

                return date($setting[0]->dateformat, strtotime($single->transactiondate));
            })
            ->rawColumns(['expense', 'income'])
            ->make(true);
    }
}
