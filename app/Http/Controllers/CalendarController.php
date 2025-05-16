<?php

namespace App\Http\Controllers;

use App;
use Auth;

class CalendarController extends Controller
{
    use TraitSettings;

    // show default data
    public function index()
    {
        if (Auth::user()->isrole('8')) {
            return view('calendar.index');
        } else {
            return redirect('home');
        }

    }

    public function __construct()
    {
        $data = $this->getapplications();
        $lang = $data[0]->languages;
        App::setLocale($lang);
        $this->middleware('auth');
    }
}
