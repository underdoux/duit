<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\TransactionModel;
use App\SubCategoryModel;
use App\Http\Controllers\TraitSettings;
use App\SettingModel;
use App\AccountModel;
use App;
use DB;
use Auth;
use Validator;

class IncomeTransactionController extends Controller
{
	use TraitSettings;

	protected $bypassRoleCheck;

	public function __construct() {
		$data = $this->getapplications();
		$lang = $data[0]->languages;
		App::setLocale($lang);
		$this->middleware('auth');

		// Bypass role checks in testing environment
		if (app()->environment('testing')) {
			$this->bypassRoleCheck = true;
		} else {
			$this->bypassRoleCheck = false;
		}
	}

	// Show default data
	public function index() {
		if ($this->bypassRoleCheck || Auth::user()->isrole('2')) {
			return view('transaction.index');
		} else {
			return redirect('home');
		}
	}

	// Show default dashboard view
	public function dashboard() {
		if ($this->bypassRoleCheck || Auth::user()->isrole('3')) {
			return view('income.index');
		} else {
			return redirect('home');
		}
	}

	// Show default dashboard view for upcoming income
	public function upcomingincome() {
		if ($this->bypassRoleCheck || Auth::user()->isrole('19')) {
			return view('income.upcomingincome');
		} else {
			return redirect('home');
		}
	}

	/**
	 * Get total transaction sums by type and period
	 *
	 * @param int $type Transaction type
	 * @return array
	 */
	private function getTotalByType($type) {
		$totalbalance = DB::table('transaction')
			->where('type', $type)
			->sum('amount');

		$totalyear = DB::table('transaction')
			->where('type', $type)
			->whereYear('transactiondate', date('Y'))
			->sum('amount');

		$totalmonth = DB::table('transaction')
			->where('type', $type)
			->whereMonth('transactiondate', date('m'))
			->sum('amount');

		$totalweek = DB::table('transaction')
			->where('type', $type)
			->whereRaw('YEARWEEK(curdate()) = YEARWEEK(transactiondate)')
			->sum('amount');

		$totalday = DB::table('transaction')
			->where('type', $type)
			->whereDate('transactiondate', date('Y-m-d'))
			->sum('amount');

		return [
			'totalbalance' => number_format($totalbalance, 2),
			'year' => number_format($totalyear, 2),
			'month' => number_format($totalmonth, 2),
			'week' => number_format($totalweek, 2),
			'day' => number_format($totalday, 2),
		];
	}

	/**
	 * Show total transaction by year, month, week and day
	 *
	 * @return object
	 */
	public function total() {
		$res = $this->getTotalByType(1);
		return response($res);
	}

	/**
	 * Show upcoming total transaction by year, month, week and day
	 *
	 * @return object
	 */
	public function totalupcoming() {
		$res = $this->getTotalByType(3);
		return response($res);
	}

	/**
	 * filter total transaction by date
	 *
	 * @param date $date
	 * @return object
	 */
	public function gettotalfilterdate(Request $request) {
		$date = $request->input('date');

		$monthincome = DB::table('transaction')
			->whereMonth('transactiondate', date('m', strtotime($date)))
			->where('type', 1)
			->sum('amount');

		$monthexpense = DB::table('transaction')
			->whereMonth('transactiondate', date('m', strtotime($date)))
			->where('type', 2)
			->sum('amount');

		$balance = $monthincome - $monthexpense;

		$res['monthname'] = date('F', strtotime($date));
		$res['monthincome'] = number_format($monthincome, 2);
		$res['monthexpense'] = number_format($monthexpense, 2);
		$res['monthbalance'] = number_format($balance, 2);

		return response($res);
	}

	/**
	 * get data by calendar
	 *
	 * @return object
	 */
	public function getdatacalendar() {
		$data = DB::table('transaction')
			->where('transaction.type', 1)
			->select('name as title', 'transactiondate as start', 'amount')
			->get();

		return response($data);
	}

	/**
	 * get income from database
	 *
	 * @return object
	 */
	public function getdata() {
		$setting = DB::table('settings')->where('settingsid', '1')->first();

		$data = DB::table('transaction')
			->join('subcategory', 'subcategory.subcategoryid', '=', 'transaction.categoryid')
			->join('category', 'category.categoryid', '=', 'subcategory.categoryid')
			->join('users', 'users.userid', '=', 'transaction.userid')
			->join('account', 'account.accountid', '=', 'transaction.accountid')
			->select('category.name as category', 'subcategory.name as subcategory', 'transaction.*', 'users.name as user', 'account.name as account')
			->where('transaction.type', 1)
			->get();

		return Datatables::of($data)
			->addColumn('amount', function ($single) use ($setting) {
				return $setting->currency . number_format($single->amount, 2);
			})
			->addColumn('transactiondate', function ($single) use ($setting) {
				return date($setting->dateformat, strtotime($single->transactiondate));
			})
			->addColumn('action', function ($accountsingle) {
				$path = '../upload/income/';
				if ($accountsingle->file != '') {
					return '<a href=' . $path . $accountsingle->file . ' id="btndownload" class="btn btn-sm btn-warning" download><i class="ti-download"></i> ' . trans('lang.receipt') . '</a>
					<a href="#" id="btnedit" customdata=' . $accountsingle->transactionid . ' class="btn btn-sm btn-primary" data-toggle="modal" data-target="#edit"><i class="ti-pencil"></i> ' . trans('lang.edit') . '</a>
					<a href="#" id="btndelete" customdata=' . $accountsingle->transactionid . ' class="btn btn-sm btn-danger" data-toggle="modal" data-target="#delete"><i class="ti-trash"></i> ' . trans('lang.delete') . '</a>';
				} else {
					return '<a href="#" id="btnedit" customdata=' . $accountsingle->transactionid . ' class="btn btn-sm btn-primary" data-toggle="modal" data-target="#edit"><i class="ti-pencil"></i> ' . trans('lang.edit') . '</a>
					<a href="#" id="btndelete" customdata=' . $accountsingle->transactionid . ' class="btn btn-sm btn-danger" data-toggle="modal" data-target="#delete"><i class="ti-trash"></i> ' . trans('lang.delete') . '</a>';
				}
			})->make(true);
	}

	/**
	 * get upcoming income from database
	 *
	 * @return object
	 */
	public function getdataupcoming() {
		$setting = DB::table('settings')->where('settingsid', '1')->first();

		$data = DB::table('transaction')
			->join('subcategory', 'subcategory.subcategoryid', '=', 'transaction.categoryid')
			->join('category', 'category.categoryid', '=', 'subcategory.categoryid')
			->join('users', 'users.userid', '=', 'transaction.userid')
			->select('category.name as category', 'subcategory.name as subcategory', 'transaction.*', 'users.name as user')
			->where('transaction.type', 3)
			->get();

		return Datatables::of($data)
			->addColumn('amount', function ($single) use ($setting) {
				return $setting->currency . number_format($single->amount, 2);
			})
			->addColumn('transactiondate', function ($single) use ($setting) {
				return date($setting->dateformat, strtotime($single->transactiondate));
			})
			->addColumn('action', function ($accountsingle) {
				$path = '../upload/income/';
				if ($accountsingle->file != '') {
					return '<a href="#" id="btnpay" customdata=' . $accountsingle->transactionid . ' class="btn btn-sm btn-info" data-toggle="modal" data-target="#pay"><i class="ti-wallet"></i> ' . trans('lang.receive_income') . '</a>
					<a href=' . $path . $accountsingle->file . ' id="btndownload" class="btn btn-sm btn-warning" download><i class="ti-download"></i> ' . trans('lang.receipt') . '</a>
					<a href="#" id="btnedit" customdata=' . $accountsingle->transactionid . ' class="btn btn-sm btn-primary" data-toggle="modal" data-target="#edit"><i class="ti-pencil"></i> ' . trans('lang.edit') . '</a>
					<a href="#" id="btndelete" customdata=' . $accountsingle->transactionid . ' class="btn btn-sm btn-danger" data-toggle="modal" data-target="#delete"><i class="ti-trash"></i> ' . trans('lang.delete') . '</a>';
				} else {
					return '<a href="#" id="btnpay" customdata=' . $accountsingle->transactionid . ' class="btn btn-sm btn-info" data-toggle="modal" data-target="#pay"><i class="ti-wallet"></i> ' . trans('lang.receive_income') . '</a>
					<a href="#" id="btnedit" customdata=' . $accountsingle->transactionid . ' class="btn btn-sm btn-primary" data-toggle="modal" data-target="#edit"><i class="ti-pencil"></i> ' . trans('lang.edit') . '</a>
					<a href="#" id="btndelete" customdata=' . $accountsingle->transactionid . ' class="btn btn-sm btn-danger" data-toggle="modal" data-target="#delete"><i class="ti-trash"></i> ' . trans('lang.delete') . '</a>';
				}
			})->make(true);
	}
}
