<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function index()
    {
        $dateNowReadable = Carbon::now()->locale('id')->isoFormat("dddd, D MMMM YYYY");

        return view("pages.dashboard.index", compact("dateNowReadable"));
    }
}
