<?php

namespace App\Http\Controllers;

use App;
use DB;
use Yajra\Datatables\Datatables;

class HomeController extends Controller
{
    use TraitSettings;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $data = $this->getapplications();
        if (isset($data[0])) {
            $lang = $data[0]->languages;
        } else {
            $lang = 'en'; // default language fallback
        }
        App::setLocale($lang);
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dashboard.index');
    }

    /**
     * Show income vs expense by month.
     *
     * @return object
     */
    public function incomevsexpense()
    {
        $thisyear = date('Y');

        // Query income, upcoming income, expense, upcoming expense grouped by month
        $transactions = DB::table('transaction')
            ->select(DB::raw('type, MONTH(transactiondate) as month, SUM(amount) as amount'))
            ->whereIn('type', [1, 2, 3, 4])
            ->whereYear('transactiondate', $thisyear)
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

            $res['uijan'] = $transactions[3][$m - 1]->amount ?? 0;
            $res['uifeb'] = $transactions[3][$m - 1]->amount ?? 0;
            $res['uimar'] = $transactions[3][$m - 1]->amount ?? 0;
            $res['uiapr'] = $transactions[3][$m - 1]->amount ?? 0;
            $res['uimay'] = $transactions[3][$m - 1]->amount ?? 0;
            $res['uijun'] = $transactions[3][$m - 1]->amount ?? 0;
            $res['uijul'] = $transactions[3][$m - 1]->amount ?? 0;
            $res['uiags'] = $transactions[3][$m - 1]->amount ?? 0;
            $res['uisep'] = $transactions[3][$m - 1]->amount ?? 0;
            $res['uiokt'] = $transactions[3][$m - 1]->amount ?? 0;
            $res['uinov'] = $transactions[3][$m - 1]->amount ?? 0;
            $res['uides'] = $transactions[3][$m - 1]->amount ?? 0;

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

            $res['iejan'] = $transactions[4][$m - 1]->amount ?? 0;
            $res['iefeb'] = $transactions[4][$m - 1]->amount ?? 0;
            $res['iemar'] = $transactions[4][$m - 1]->amount ?? 0;
            $res['ieapr'] = $transactions[4][$m - 1]->amount ?? 0;
            $res['iemay'] = $transactions[4][$m - 1]->amount ?? 0;
            $res['iejun'] = $transactions[4][$m - 1]->amount ?? 0;
            $res['iejul'] = $transactions[4][$m - 1]->amount ?? 0;
            $res['ieags'] = $transactions[4][$m - 1]->amount ?? 0;
            $res['iesep'] = $transactions[4][$m - 1]->amount ?? 0;
            $res['ieokt'] = $transactions[4][$m - 1]->amount ?? 0;
            $res['ienov'] = $transactions[4][$m - 1]->amount ?? 0;
            $res['iedes'] = $transactions[4][$m - 1]->amount ?? 0;
        }

        return response($res);
    }

    /**
     * Show expense by category monthly.
     *
     * @return object
     */
    public function expensebycategory()
    {

        $data = DB::table('transaction')
            ->join('subcategory', 'subcategory.subcategoryid', '=', 'transaction.categoryid')
            ->join('category', 'category.categoryid', '=', 'subcategory.categoryid')
            ->join('users', 'users.userid', '=', 'transaction.userid')
            ->select(DB::raw('sum(amount) as amount, category.name as category, category.color as color'))
            ->where('category.type', '2')
            ->where('transaction.type', '2')
            ->whereMonth('transactiondate', '=', date('m'))
            ->groupBy('category.categoryid')
            ->groupBy('category.name')
            ->groupBy('category.color')
            ->get();

        return response($data);
    }

    /**
     * Show upcoming expense by category monthly.
     *
     * @return object
     */
    public function upcomingexpensebycategory()
    {

        $data = DB::table('transaction')
            ->join('subcategory', 'subcategory.subcategoryid', '=', 'transaction.categoryid')
            ->join('category', 'category.categoryid', '=', 'subcategory.categoryid')
            ->join('users', 'users.userid', '=', 'transaction.userid')
            ->select(DB::raw('sum(amount) as amount, category.name as category, category.color as color'))
            ->where('category.type', '2')
            ->where('transaction.type', '4')
            ->whereMonth('transactiondate', '=', date('m'))
            ->groupBy('category.categoryid')
            ->groupBy('category.name')
            ->groupBy('category.color')
            ->get();

        return response($data);
    }

    /**
     * Show expense by category yearly.
     *
     * @return object
     */
    public function expensebycategoryyearly()
    {

        $data = DB::table('transaction')
            ->join('subcategory', 'subcategory.subcategoryid', '=', 'transaction.categoryid')
            ->join('category', 'category.categoryid', '=', 'subcategory.categoryid')
            ->join('users', 'users.userid', '=', 'transaction.userid')
            ->select(DB::raw('sum(amount) as amount, category.name as category, category.color as color'))
            ->where('category.type', '2')
            ->where('transaction.type', '2')
            ->whereYear('transactiondate', '=', date('Y'))
            ->groupBy('category.categoryid')
            ->groupBy('category.name')
            ->groupBy('category.color')
            ->get();

        return response($data);
    }

    /**
     * Show expense by category yearly.
     *
     * @return object
     */
    public function upcomingexpensebycategoryyearly()
    {

        $data = DB::table('transaction')
            ->join('subcategory', 'subcategory.subcategoryid', '=', 'transaction.categoryid')
            ->join('category', 'category.categoryid', '=', 'subcategory.categoryid')
            ->join('users', 'users.userid', '=', 'transaction.userid')
            ->select(DB::raw('sum(amount) as amount, category.name as category, category.color as color'))
            ->where('category.type', '2')
            ->where('transaction.type', '4')
            ->whereYear('transactiondate', '=', date('Y'))
            ->groupBy('category.categoryid')
            ->groupBy('category.name')
            ->groupBy('category.color')
            ->get();

        return response($data);
    }

    /**
     * Show income by category monthly.
     *
     * @return object
     */
    public function incomebycategory()
    {

        $data = DB::table('transaction')
            ->join('subcategory', 'subcategory.subcategoryid', '=', 'transaction.categoryid')
            ->join('category', 'category.categoryid', '=', 'subcategory.categoryid')
            ->join('users', 'users.userid', '=', 'transaction.userid')
            ->select(DB::raw('sum(amount) as amount, category.name as category, category.color as color'))
            ->where('category.type', '1')
            ->where('transaction.type', '1')
            ->whereMonth('transactiondate', '=', date('m'))
            ->groupBy('category.categoryid')
            ->groupBy('category.name')
            ->groupBy('category.color')
            ->get();

        return response($data);
    }

    /**
     * Show income by category yearly.
     *
     * @return object
     */
    public function incomebycategoryyearly()
    {

        $data = DB::table('transaction')
            ->join('subcategory', 'subcategory.subcategoryid', '=', 'transaction.categoryid')
            ->join('category', 'category.categoryid', '=', 'subcategory.categoryid')
            ->join('users', 'users.userid', '=', 'transaction.userid')
            ->select(DB::raw('sum(amount) as amount, category.name as category, category.color as color'))
            ->where('category.type', '1')
            ->where('transaction.type', '1')
            ->whereYear('transactiondate', '=', date('Y'))
            ->groupBy('category.categoryid')
            ->groupBy('category.name')
            ->groupBy('category.color')
            ->get();

        return response($data);
    }

    /**
     * Show upcoming income by category yearly.
     *
     * @return object
     */
    public function upcomingincomebycategoryyearly()
    {

        $data = DB::table('transaction')
            ->join('subcategory', 'subcategory.subcategoryid', '=', 'transaction.categoryid')
            ->join('category', 'category.categoryid', '=', 'subcategory.categoryid')
            ->join('users', 'users.userid', '=', 'transaction.userid')
            ->select(DB::raw('sum(amount) as amount, category.name as category, category.color as color'))
            ->where('category.type', '1')
            ->where('transaction.type', '3')
            ->whereYear('transactiondate', '=', date('Y'))
            ->groupBy('category.categoryid')
            ->groupBy('category.name')
            ->groupBy('category.color')
            ->get();

        return response($data);
    }

    /**
     * Show total balance.
     *
     * @return object
     */
    public function totalbalance()
    {
        $data = DB::table('transaction')
            ->join('subcategory', 'subcategory.subcategoryid', '=', 'transaction.categoryid')
            ->join('category', 'category.categoryid', '=', 'subcategory.categoryid')
            ->join('users', 'users.userid', '=', 'transaction.userid')
            ->select(DB::raw('sum(amount) as amount, category.name as category, category.color as color'))
            ->where('category.type', '2')
            ->whereMonth('transactiondate', '=', date('m'))
            ->groupBy('transaction.categoryid')
            ->groupBy('category.name')
            ->groupBy('category.color')
            ->get();

        return response($data);
    }

    /**
     * Show account balance.
     *
     * @return object
     */
    public function accountbalance()
    {

        $data = DB::select('SELECT p.name,COALESCE(a.amount,0) as income,COALESCE(b.amount,0) as expense, COALESCE(p.balance+(COALESCE(a.amount,0)-COALESCE(b.amount,0)),0) as balance from account as p left join (select accountid,sum(amount) as amount from transaction where type=1 and year(transactiondate)='.date('Y').' group by accountid) as a on a.accountid = p.accountid left join (select accountid,sum(amount) as amount from transaction where type=2 and year(transactiondate)='.date('Y').' group by accountid) as b on b.accountid = p.accountid group by p.accountid');

        return response($data);
    }

    /**
     * Show budget list.
     *
     * @return object
     */
    public function budgetlist()
    {
        $data = DB::table('budget')
            ->join('subcategory', 'subcategory.subcategoryid', '=', 'budget.categoryid')
            ->join('category', 'subcategory.categoryid', '=', 'category.categoryid')
            ->whereMonth('budget.fromdate', '=', date('m'))
            ->groupBy('budget.categoryid')
            ->get();

        return response($data);
    }

    /**
     * Show latest 10 income from database
     *
     * @return object
     */
    public function latestincome()
    {
        $data = DB::table('transaction')
            ->join('subcategory', 'subcategory.subcategoryid', '=', 'transaction.categoryid')
            ->join('category', 'category.categoryid', '=', 'subcategory.categoryid')
            ->join('users', 'users.userid', '=', 'transaction.userid')
            ->join('account', 'account.accountid', '=', 'transaction.accountid')
            ->select('category.name as category', 'subcategory.name as subcategory', 'transaction.*', 'users.name as user', 'account.name as account')
            ->where('transaction.type', '1')
            ->offset(0)->limit(10)
            ->orderBy('transactiondate', 'desc')
            ->get();

        return Datatables::of($data)
            ->addColumn('amount', function ($single) {
                $setting = DB::table('settings')->where('settingsid', '1')->get();

                return $setting[0]->currency.number_format($single->amount, 2);
            })
            ->addColumn('transactiondate', function ($single) {
                $setting = DB::table('settings')->where('settingsid', '1')->get();

                return date($setting[0]->dateformat, strtotime($single->transactiondate));
            })
            ->make(true);

    }

    /**
     * Show latest 10 expense from database
     *
     * @return object
     */
    public function latestexpense()
    {
        $data = DB::table('transaction')
            ->join('subcategory', 'subcategory.subcategoryid', '=', 'transaction.categoryid')
            ->join('category', 'category.categoryid', '=', 'subcategory.categoryid')
            ->join('users', 'users.userid', '=', 'transaction.userid')
            ->join('account', 'account.accountid', '=', 'transaction.accountid')
            ->select('category.name as category', 'subcategory.name as subcategory', 'transaction.*', 'users.name as user', 'account.name as account')
            ->where('transaction.type', '2')
            ->offset(0)->limit(10)
            ->orderBy('transactiondate', 'desc')
            ->get();

        return Datatables::of($data)
            ->addColumn('amount', function ($single) {
                $setting = DB::table('settings')->where('settingsid', '1')->get();

                return $setting[0]->currency.number_format($single->amount, 2);
            })
            ->addColumn('transactiondate', function ($single) {
                $setting = DB::table('settings')->where('settingsid', '1')->get();

                return date($setting[0]->dateformat, strtotime($single->transactiondate));
            })
            ->make(true);

    }
}
