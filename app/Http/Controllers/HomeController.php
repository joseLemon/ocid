<?php

namespace App\Http\Controllers;

use App\User;
use App\Branch;
use App\Service;
use App\Appointment;
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
            DB::raw('IF(time_slot, CONCAT(name, " (", time_slot, " min)"), name) AS name'),
            'time_slot',
        ]);
        $branches = Branch::all()->pluck('name', 'id');
        $doctors = User::join('users_roles', 'users_roles.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'users_roles.role_id')
            ->where('roles.id', 3)
            ->get('users.*')
            ->pluck('name', 'id');
        $appointments = Appointment::join('users', 'users.id', '=', 'appointments.doctor_id')
            //->join('customers', 'customers.id', '=', 'appointments.customer_id')
            ->join('services', 'services.id', '=', 'appointments.service_id')
            ->get([
                'appointments.id',
                'appointments.doctor_id',
                'appointments.service_id',
                'appointments.customer_id',
                'appointments.date',
                'appointments.start',
                'appointments.end',
                'appointments.status',
                'services.name AS service_name',
                DB::raw('"Example" AS customer_name'),
            ]);

        foreach ($appointments as $index => $item) {
            $appointments[$index]->status = $this->getStatusVal($item->status);
        }

        $params = compact('services', 'branches', 'doctors', 'appointments');
        return view('calendar.index', $params);
    }

    private function getStatusVal($value)
    {
        switch ($value) {
            default:
                return 1;
            case 'active':
                return 2;
            case 'delayed':
                return 3;
            case 'cancelled':
                return 4;
        }
    }
}
