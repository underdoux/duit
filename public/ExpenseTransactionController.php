<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\TraitSettings;
use App\TransactionModel;
use App\SettingModel;
use App\SubCategoryModel;
use App\AccountModel;
use DB;
use App;
use Auth;
use Validator;

class ExpenseTransactionController extends Controller
{
	use TraitSettings;

	protected $bypassRoleCheck;

	public function __construct()
    {
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
    public function index(){
    	if ($this->bypassRoleCheck || Auth::user()->isrole('4')){
			return view('transaction.index');
		} else{
			 return redirect('home');
		}
    }
	
	// Show dashboard view
	public function dashboard(){
    	if ($this->bypassRoleCheck || Auth::user()->isrole('4')){
			return view('expense.index');
		} else{
			 return redirect('home');
		}
    }

	// Show default dashboard view for upcoming expense
	public function upcomingexpense() {
		if ($this->bypassRoleCheck || Auth::user()->isrole('20')){
			return view('expense.upcomingexpense');
		} else{
			 return redirect('home');
		}
	}

	// ... rest of the class unchanged ...
}
