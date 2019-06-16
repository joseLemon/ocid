<?php

namespace App\Http\Controllers;

use App\Branch;
use App\Service;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $services = Service::all()->pluck('name', 'id');
        $branches = Branch::all()->pluck('name', 'id');
        $params = compact('services', 'branches');
        return view('calendar.index', $params);
    }
}
