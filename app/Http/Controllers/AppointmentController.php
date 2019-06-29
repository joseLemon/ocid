<?php

namespace App\Http\Controllers;

use App\User;
use App\Branch;
use App\Service;
use App\Customer;
use Carbon\Carbon;
use App\Appointment;
use App\DoctorsDaysOff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AppointmentController extends Controller
{
    public function index()
    {
        return view('appointments.index');
    }

    public function create()
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
            ->get('users.*');

        foreach ($doctors as $index => $doctor) {
            $dates = DoctorsDaysOff::where('user_id', $doctor->id)->pluck('day_off');
            foreach ($dates as $i => $date) {
                $dates[$i] = Carbon::parse($date)->setYear(Carbon::now()->year);
            }
            $doctors[$index]->daysOff = $dates;
        }

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
            ]);

        foreach ($appointments as $index => $item) {
            $appointments[$index]->status = $this->getStatusVal($item->status);
            $appointments[$index]->customer_name = Customer::find($item->customer_id)->full_name;
        }

        $params = compact('services', 'branches', 'doctors', 'appointments');
        return view('appointments.create', $params);
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

    public function edit($id)
    {
        $appointment = Appointment::find($id);
        $appointment->date = Carbon::parse($appointment->date)->format('d/m/Y');
        $appointment->start = Carbon::parse($appointment->start)->format('H:i');
        $appointment->end = Carbon::parse($appointment->end)->format('H:i');
        $appointment->customer_name = Customer::find($appointment->customer_id)->full_name;
        $services = Service::get([
            'id',
            DB::raw('IF(time_slot, CONCAT(name, " (", time_slot, " min)"), name) AS name'),
            'time_slot',
        ]);
        $branches = Branch::all()->pluck('name', 'id');
        $doctors = User::join('users_roles', 'users_roles.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'users_roles.role_id')
            ->where('roles.id', 3)
            ->get('users.*');

        foreach ($doctors as $index => $doctor) {
            $dates = DoctorsDaysOff::where('user_id', $doctor->id)->pluck('day_off');
            foreach ($dates as $i => $date) {
                $dates[$i] = Carbon::parse($date)->setYear(Carbon::now()->year);
            }
            $doctors[$index]->daysOff = $dates;
        }

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
            ]);

        foreach ($appointments as $index => $item) {
            $appointments[$index]->status = $this->getStatusVal($item->status);
            $appointments[$index]->customer_name = Customer::find($item->customer_id)->full_name;
        }

        $params = compact('services', 'branches', 'doctors', 'appointments', 'appointment');
        return view('appointments.edit', $params);
    }

    private function validator($data)
    {
        return Validator::make($data, [
            'doctor_id' => ['required', 'exists:users,id'],
            'service_id' => ['required', 'exists:services,id'],
            'customer_id' => ['required'],
            'date' => ['required'],
            'start' => ['required'],
            'end' => ['required'],
        ], [], [
            'doctor_id' => 'médico',
            'service_id' => 'servicio',
            'customer_id' => 'cliente',
            'date' => 'fecha',
            'start' => 'hora inicio',
            'end' => 'hora fin',
        ]);

    }

    public function store(Request $request)
    {
        $data = $request->all();
        $data['start'] = Carbon::parse($request->start)->format('H:i');
        $data['end'] = Carbon::parse($request->end)->format('H:i');
        $data['date'] = Carbon::createFromFormat('d/m/Y', $request->date)->format('Y/m/d');

        $this->validator($request->all())->validate();

        DB::transaction(function () use ($data, &$appointment) {
            $appointment = Appointment::create([
                'doctor_id' => $data['doctor_id'],
                'service_id' => $data['service_id'],
                'customer_id' => $data['customer_id'],
                'date' => $data['date'],
                'start' => $data['start'],
                'end' => $data['end'],
            ]);
        });

        if ($request->ajax())
            return response()->json(['success' => true, 'appointment' => $appointment]);
        else
            return redirect('/appointments');
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $data['start'] = Carbon::parse($request->start)->format('H:i');
        $data['end'] = Carbon::parse($request->end)->format('H:i');
        $data['date'] = Carbon::createFromFormat('d/m/Y', $request->date)->format('Y/m/d');

        $this->validator($request->all())->validate();

        DB::transaction(function () use ($data, $id, &$appointment) {
            $appointment = Appointment::find($id);
            $appointment->doctor_id = $data['doctor_id'];
            $appointment->service_id = $data['service_id'];
            $appointment->customer_id = $data['customer_id'];
            $appointment->date = $data['date'];
            $appointment->start = $data['start'];
            $appointment->end = $data['end'];
            $appointment->status = $this->getStatus($data['status']);
            $appointment->save();
        });

        if ($request->ajax())
            return response()->json(['success' => true, 'appointment' => $appointment]);
        else
            return redirect('/appointments');
    }

    private function getStatus($value)
    {
        switch ($value) {
            case 1:
            default:
                return null;
            case 2:
                return 'active';
            case 3:
                return 'delayed';
            case 4:
                return 'cancelled';
        }
    }

    public function search(Request $request)
    {
        // Order-by data
        $column = $request->order[0]['column'];
        $dir = $request->order[0]['dir'];

        // Skip-Take data
        $take = $request->length;
        $current = $request->start / $take;
        $skip = $take * $current;

        // Searchbar string
        $search = $request->search['value'];

        switch ($column) {
            case 0:
                $column = 'appointments.id';
                break;
            case 1:
                $column = 'appointments.status';
                break;
            case 2:
                $column = 'appointments.date';
                break;
            case 3:
                $column = 'appointments.start';
                break;
            case 4:
                $column = 'appointments.end';
                break;
            case 5:
                $column = 'users.name';
                break;
            default:
                $column = 'appointments.id';
                break;
        }

        $query = Appointment::select([
            "appointments.id",
            "appointments.status",
            "appointments.customer_id",
            DB::raw("DATE_FORMAT(appointments.date, '%d-%m-%Y') AS date"),
            DB::raw("DATE_FORMAT(appointments.start, '%r') AS start"),
            DB::raw("DATE_FORMAT(appointments.end, '%r') AS end"),
            "users.name AS doctor_name",
        ])
            ->join('users', 'users.id', '=', 'appointments.doctor_id')
            ->join('services', 'services.id', '=', 'appointments.service_id')
            ->orderBy($column, $dir);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where("users.name", "LIKE", "%$search%");
            });
        }

        $total = $query->count();
        $query = $query->skip($skip)->take($take);
        $result = $query->get();

        foreach ($result as $i => $item) {
            $result[$i]->roles;
            $result[$i]->customer_name = Customer::find($item->customer_id)->full_name;
        }
        $params = [
            'data' => $result,
            'draw' => $request->draw,
            'recordsFiltered' => $total,
            'recordsTotal' => $total,
        ];

        return response()->json($params);
    }
}
