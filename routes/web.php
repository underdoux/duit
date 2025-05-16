<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::redirect('/', '/duit');

Route::prefix('duit')->group(function () {

    Route::get('/', [App\Http\Controllers\HomeController::class, 'index']);
    Route::get('/modern', [App\Http\Controllers\ModernDashboardController::class, 'index'])->name('modern.dashboard');

    Route::controller(App\Http\Controllers\AccountController::class)->group(function () {
        Route::get('/account', 'index');
        Route::get('/account/index', 'index');
        Route::get('/account/detail/{id}', 'detail');
        Route::get('/account/getdatadetail/{id}', 'getdatadetail');
        Route::get('/account/accountbalancebymonth/{id}', 'accountbalancebymonth');
        Route::get('/account/getaccounttransaction/{id}', 'getaccounttransaction');
        Route::get('/account/getdata', 'getdata');
        Route::post('/account/save', 'save');
        Route::post('/account/delete', 'delete');
        Route::post('/account/getedit', 'getedit');
        Route::post('/account/edit', 'saveedit');
    });
    Route::resource('account', App\Http\Controllers\AccountController::class);

    Route::controller(App\Http\Controllers\IncomeTransactionController::class)->group(function () {
        // Income routes
        Route::get('/income', 'dashboard');
        Route::get('/income/index', 'dashboard');
        Route::get('/income/getdata', 'getdata');
        Route::get('/income/getdatacalendar', 'getdatacalendar');
        Route::get('/income/gettotal', 'total');
        Route::post('/income/save', 'save');
        Route::post('/income/delete', 'delete');
        Route::post('/income/getedit', 'getedit');
        Route::post('/income/edit', 'saveedit');
        Route::post('/income/gettotalfilterdate', 'gettotalfilterdate');

        // Upcoming income routes
        Route::get('/upcomingincome', 'upcomingincome');
        Route::get('/upcomingincome/index', 'upcomingincome');
        Route::get('/upcomingincome/getdataupcoming', 'getdataupcoming');
        Route::post('/upcomingincome/saveupcoming', 'saveupcoming');
        Route::post('/upcomingincome/getedit', 'geteditupcoming');
        Route::post('/upcomingincome/edit', 'saveeditupcoming');
        Route::get('/upcomingincome/gettotal', 'totalupcoming');
        Route::post('/upcomingincome/dopay', 'dopay');
    });

    Route::controller(App\Http\Controllers\ExpenseTransactionController::class)->group(function () {
        // Expense routes
        Route::get('/expense', 'dashboard');
        Route::get('/expense/index', 'dashboard');
        Route::get('/expense/getdata', 'getdata');
        Route::get('/expense/getdatacalendar', 'getdatacalendar');
        Route::get('/expense/gettotal', 'total');
        Route::post('/expense/save', 'save');
        Route::post('/expense/delete', 'delete');
        Route::post('/expense/getedit', 'getedit');
        Route::post('/expense/edit', 'saveedit');

        // Upcoming expense routes
        Route::get('/upcomingexpense', 'upcomingexpense');
        Route::get('/upcomingexpense/index', 'upcomingexpense');
        Route::get('/upcomingexpense/getdataupcoming', 'getdataupcoming');
        Route::post('/upcomingexpense/saveupcoming', 'saveupcoming');
        Route::post('/upcomingexpense/getedit', 'geteditupcoming');
        Route::post('/upcomingexpense/edit', 'saveeditupcoming');
        Route::get('/upcomingexpense/gettotal', 'totalupcoming');
        Route::post('/upcomingexpense/dopay', 'dopay');
    });

    Route::controller(App\Http\Controllers\ReportsController::class)->group(function () {
        Route::get('/reports/allreports', 'allreports');
        Route::get('/reports/income', 'incomereports');
        Route::get('/reports/upcomingincome', 'upcomingincomereports');
        Route::get('/reports/expense', 'expensereports');
        Route::get('/reports/upcomingexpense', 'upcomingexpensereports');
        Route::get('/reports/gettransactionsupcoming', 'gettransactionsupcoming');
        Route::get('/reports/incomevsexpense', 'incomevsexpensereports');
        Route::get('/reports/gettransactions', 'gettransactions');
        Route::get('/reports/getaccounttransaction', 'getaccounttransaction');
        Route::get('/reports/incomemonth', 'incomemonth');
        Route::get('/reports/expensemonth', 'expensemonth');
        Route::get('/reports/getincomemonthly', 'getincomemonthly');
        Route::get('/reports/getexpensemonthly', 'getexpensemonthly');
        Route::get('/reports/getbalance', 'getbalance');
    });
    Route::resource('reports', App\Http\Controllers\ReportsController::class);

    Route::controller(App\Http\Controllers\CategoryController::class)->group(function () {
        // Income category routes
        Route::get('/incomecategory', 'incomeindex');
        Route::get('/incomecategory/index', 'incomeindex');
        Route::get('/incomecategory/getdata', 'incomegetdata');
        Route::post('/incomecategory/save', 'incomesave');
        Route::post('/incomecategory/delete', 'incomedelete');
        Route::post('/incomecategory/getedit', 'incomegetedit');
        Route::post('/incomecategory/edit', 'incomesaveedit');

        // Income subcategory routes
        Route::get('/incomecategory/subgetdata', 'incomesubgetdata');
        Route::post('/incomecategory/subgetdatabycat', 'incomesubcategorybycat');
        Route::post('/incomecategory/subsave', 'incomesubsave');
        Route::post('/incomecategory/subdelete', 'incomedelete');
        Route::post('/incomecategory/subgetedit', 'incomesubgetedit');
        Route::post('/incomecategory/subedit', 'incomesubsaveedit');
    });

    Route::controller(App\Http\Controllers\ExpenseCategoryController::class)->group(function () {
        // Expense category routes
        Route::get('/expensecategory', 'expenseindex');
        Route::get('/expensecategory/index', 'expenseindex');
        Route::get('/expensecategory/getdata', 'expensegetdata');
        Route::post('/expensecategory/save', 'expensesave');
        Route::post('/expensecategory/delete', 'expensedelete');
        Route::post('/expensecategory/getedit', 'expensegetedit');
        Route::post('/expensecategory/edit', 'expensesaveedit');

        // Expense subcategory routes
        Route::get('/expensecategory/subgetdata', 'expensesubgetdata');
        Route::post('/expensecategory/subgetdatabycat', 'expensesubcategorybycat');
        Route::post('/expensecategory/subsave', 'expensesubsave');
        Route::post('/expensecategory/subdelete', 'expensesubdelete');
        Route::post('/expensecategory/subgetedit', 'expensesubgetedit');
        Route::post('/expensecategory/subedit', 'expensesubsaveedit');
    });

    Route::controller(App\Http\Controllers\BudgetController::class)->group(function () {
        Route::get('/budget', 'index');
        Route::get('/budget/index', 'index');
        Route::get('/budget/getdata', 'getdata');
        Route::post('/budget/save', 'save');
        Route::post('/budget/delete', 'deleteitem');
        Route::post('/budget/getedit', 'budgetgetedit');
        Route::post('/budget/edit', 'saveedit');
        Route::post('/budget/gettransactionbydate', 'gettransactionbydate');
    });
    Route::resource('budget', App\Http\Controllers\BudgetController::class);

    Route::controller(App\Http\Controllers\GoalController::class)->group(function () {
        Route::get('/goals', 'index');
        Route::get('/goals/index', 'index');
        Route::get('/goals/getdata', 'getdata');
        Route::post('/goals/save', 'save');
        Route::post('/goals/delete', 'deleteitem');
        Route::post('/goals/getedit', 'goalsgetedit');
        Route::post('/goals/edit', 'saveedit');
        Route::post('/goals/deposit', 'deposit');
    });
    Route::resource('goals', App\Http\Controllers\GoalController::class);

    Route::controller(App\Http\Controllers\CalendarController::class)->group(function () {
        Route::get('/calendar/index', 'index');
    });
    Route::resource('calendar', App\Http\Controllers\CalendarController::class);

    Route::controller(App\Http\Controllers\IncomeTransactionController::class)->group(function () {
        Route::get('/transaction', 'index');
        Route::get('/transaction/index', 'index');
        Route::post('/transaction/saveincome', 'saveincome');
        Route::get('/transaction/downloadcsv', 'downloadcsv');
        Route::post('/transaction/importcsv', 'importcsv');
    });

    Route::controller(App\Http\Controllers\ExpenseTransactionController::class)->group(function () {
        Route::post('/transaction/saveexpense', 'saveexpense');
        Route::post('/transaction/importcsv2', 'importcsv');
    });

    Route::controller(App\Http\Controllers\SettingController::class)->group(function () {
        Route::get('/settings/application', 'applicationindex');
        Route::get('/settings/getapplication', 'getapplication');
        Route::post('/settings/saveapplication', 'saveapplication');
        Route::post('/settings/insertrole', 'insertrole');
        Route::get('/settings/getrole', 'getrole');
    });
    Route::resource('settings', App\Http\Controllers\SettingController::class);

    Route::controller(App\Http\Controllers\UserController::class)->group(function () {
        Route::get('/settings/getprofile', 'getprofile');
        Route::get('/settings/profile', 'profile');
        Route::get('/settings/allusers', 'allusers');
        Route::get('/settings/getuser', 'getuser');
        Route::post('/settings/getuseredit', 'getedit');
        Route::get('/settings/totalusers', 'totalusers');
        Route::post('/settings/saveuser', 'save');
        Route::post('/settings/deleteuser', 'delete');
        Route::post('/settings/saveprofile', 'saveprofile');
        Route::post('/settings/saveprofilebyadmin', 'saveprofilebyadmin');
    });

    Route::controller(App\Http\Controllers\Auth\LoginController::class)->group(function () {
        Route::get('logout', 'logout');
        Route::get('login', 'showLoginForm')->name('login');
        Route::post('login', 'authenticate');
    });

    Route::controller(App\Http\Controllers\HomeController::class)->group(function () {
        Route::get('/home', 'index')->name('home');
        Route::get('/home/incomevsexpense', 'incomevsexpense');
        Route::get('/home/totalbalance', 'totalbalance');
        Route::get('/home/expensebycategory', 'expensebycategory');
        Route::get('/home/incomebycategory', 'incomebycategory');
        Route::get('/home/expensebycategoryyearly', 'expensebycategoryyearly');
        Route::get('/home/incomebycategoryyearly', 'incomebycategoryyearly');
        Route::get('/home/upcomingexpensebycategoryyearly', 'upcomingexpensebycategoryyearly');
        Route::get('/home/upcomingincomebycategoryyearly', 'upcomingincomebycategoryyearly');
        Route::get('/home/budgetlist', 'budgetlist');
        Route::get('/home/accountbalance', 'accountbalance');
        Route::get('/home/latestincome', 'latestincome');
        Route::get('/home/latestexpense', 'latestexpense');
    });
    Route::resource('home', App\Http\Controllers\HomeController::class);

}); // End of duit prefix group
