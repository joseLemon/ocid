<?php

namespace App\Http\Controllers;

use App\User;
use App\Branch;
use App\Service;
use App\Customer;
use Carbon\Carbon;
use App\Appointment;
use App\DoctorsDaysOff;
use App\DoctorsSchedule;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    protected $branch;
    protected $role;
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

        $user = auth()->user();
        $role = $user->getRole();

        if ($role === 'admin')
            $this->branch = null;
        else
            $this->branch = $user->branches[0]->id;

        $branches = Branch::all()->pluck('name', 'id');

        $doctors = User::join('users_roles', 'users_roles.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'users_roles.role_id')
            ->join('branches_users', 'branches_users.user_id', 'users.id')
            ->where(function ($q) {
                $q->where('roles.id', 3);

                if ($this->branch)
                    $q->where('branches_users.branch_id', $this->branch);
            })
            ->get('users.*');

        foreach ($doctors as $index => $doctor) {
            $dates = DoctorsDaysOff::where('user_id', $doctor->id)->get([
                'day_off',
                'end_day_off',
            ])
                ->where('day_off', '>', Carbon::now()->format('Y-m-d'));
            $data = [];
            foreach ($dates as $i => $item) {
                $start = Carbon::parse($item->day_off)->setYear(Carbon::now()->year)->format('Y/m/d');
                $end = $item->end_day_off ? Carbon::parse($item->end_day_off)->setYear(Carbon::now()->year)->format('Y/m/d') : null;
                $data[] = $start;
                if ($end) {
                    $data[] = $end;
                    $period = CarbonPeriod::create($item->day_off, $item->end_day_off);
                    foreach ($period as $date) {
                        $data[] = $date->setYear(Carbon::now()->year)->format('Y/m/d');
                    }
                }
            }
            $doctors[$index]->daysOff = $data;

            $schedules = DoctorsSchedule::where('user_id', $doctor->id)
                ->get();
            $arrangedSchedules = [];
            foreach ($schedules as $item) {
                $arrangedSchedules[$item->day][]  = [
                    'start_time' => Carbon::parse($item->start_time)->format('H:i'),
                    'end_time' => Carbon::parse($item->end_time)->format('H:i'),
                ];
            }
            $doctors[$index]->schedules = $arrangedSchedules;
        }

        $params = compact('services', 'branches', 'doctors', 'role');
        return view('calendar.index', $params);
    }

    public function getCalendarData(Request $request) {
        $user = auth()->user();
        $role = $user->getRole();

        if ($role === 'admin')
            $this->branch = null;
        else
            $this->branch = $user->branches[0]->id;

        $doctor_f = $request->doctor;
        $branch_f = $this->branch ?? $request->branch;

        $doctors = User::join('users_roles', 'users_roles.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'users_roles.role_id')
            ->join('branches_users', 'branches_users.user_id', 'users.id')
            ->where(function ($q) use ($doctor_f, $branch_f) {
                $q->where('roles.id', 3);

                /*if ($doctor_f)
                    $q->where('users.id', $doctor_f);*/

                if ($branch_f)
                    $q->where('branches_users.branch_id', $branch_f);
            })
            ->get(['users.*', 'branches_users.branch_id']);

        foreach ($doctors as $index => $doctor) {
            $dates = DoctorsDaysOff::where('user_id', $doctor->id)->get([
                'day_off',
                'end_day_off',
            ])
                ->where('day_off', '>', Carbon::now()->format('Y-m-d'));
            $data = [];
            foreach ($dates as $i => $item) {
                $start = Carbon::parse($item->day_off)->setYear(Carbon::now()->year)->format('Y/m/d');
                $end = $item->end_day_off ? Carbon::parse($item->end_day_off)->setYear(Carbon::now()->year)->format('Y/m/d') : null;
                $data[] = $start;
                if ($end) {
                    $data[] = $end;
                    $period = CarbonPeriod::create($item->day_off, $item->end_day_off);
                    foreach ($period as $date) {
                        $data[] = $date->setYear(Carbon::now()->year)->format('Y/m/d');
                    }
                }
            }
            $doctors[$index]->daysOff = $data;

            $schedules = DoctorsSchedule::where('user_id', $doctor->id)
                ->get();
            $arrangedSchedules = [];
            foreach ($schedules as $item) {
                $arrangedSchedules[$item->day][]  = [
                    'start_time' => Carbon::parse($item->start_time)->format('H:i'),
                    'end_time' => Carbon::parse($item->end_time)->format('H:i'),
                ];
            }
            $doctors[$index]->schedules = $arrangedSchedules;
        }

        $query = Appointment::join('users', 'users.id', '=', 'appointments.doctor_id')
            ->join('services', 'services.id', '=', 'appointments.service_id')
            ->join('branches_users', 'branches_users.user_id', 'users.id')
            ->where(function ($q) use ($doctor_f, $branch_f) {
                $q->whereYear('appointments.date', Carbon::now()->year);

                if ($doctor_f)
                    $q->where('users.id', $doctor_f);

                if ($branch_f)
                    $q->where('branches_users.branch_id', $branch_f);
            })
            ->select([
                'appointments.id',
                'appointments.doctor_id',
                'appointments.service_id',
                'appointments.customer_id',
                'appointments.date',
                'appointments.start',
                'appointments.end',
                'appointments.status',
                'services.name AS service_name',
            ]);

        if (auth()->user()->hasRole('doctor'))
            $query->where('status', 'active');

        $appointments = $query->get();

        $customers = [];
        foreach ($appointments as $index => $item) {
            $appointments[$index]->status = $this->getStatusVal($item->status);
            if (!isset($customers[$item->customer_id]))
                $customers[$item->customer_id] = Customer::find($item->customer_id)->full_name;
            $appointments[$index]->customer_name = $customers[$item->customer_id];
        }

        return [
            'doctors' => $doctors,
            'appointments' => $appointments,
        ];
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
