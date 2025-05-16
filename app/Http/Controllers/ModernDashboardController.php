<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class ModernDashboardController extends Controller
{
    use TraitSettings;

    public function __construct()
    {
        $this->middleware('auth');
        $data = $this->getapplications();
        if (!empty($data) && isset($data[0]->languages)) {
            $lang = $data[0]->languages;
            \App::setLocale($lang);
        }
    }

    public function index()
    {
        if (!Auth::check()) {
            return redirect('duit/login');
        }

        if (Auth::user()->isrole('5')) {
            return view('dashboard.new');
        }

        return redirect('duit/home');
    }
}
