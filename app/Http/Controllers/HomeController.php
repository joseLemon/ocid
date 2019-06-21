<?php

namespace App\Http\Controllers;

use App\User;
use App\Branch;
use App\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $services = Service::get([
            'id',
            DB::raw('CONCAT(name, " (", time_slot, " min)") AS name'),
            'time_slot',
        ]);
        $branches = Branch::all()->pluck('name', 'id');
        $doctors = User::join('users_roles', 'users_roles.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'users_roles.role_id')
            ->where('roles.id', 3)
            ->get('users.*')
            ->pluck('name', 'id');
        $params = compact('services', 'branches', 'doctors');
        return view('calendar.index', $params);
    }
}
