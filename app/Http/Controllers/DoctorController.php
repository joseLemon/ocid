<?php

namespace App\Http\Controllers;

use App\Branch;
use App\User;
use App\DoctorsDaysOff;
use App\DoctorsSchedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class DoctorController extends Controller
{
    public function __construct()
    {
        View::share("crumb1", [
            'name' => 'Doctores',
            'route' => '/doctors',
        ]);
    }

    public function index()
    {
        return view('doctors.index');
    }

    public function create()
    {
        $crumb2 = [
            'name' => 'Crear',
            'route' => '/doctor',
        ];
        $branches = Branch::all();
        $params = compact('crumb2', 'branches');
        return view('doctors.create', $params);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        /*$beginHour = Carbon::parse($data['mon-time-start']);
        $endHour = Carbon::parse($data['mon-time-end']);
        if($beginHour->addMinute()->gt($endHour)){
            return false;
        }*/


        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['string', 'email', 'max:255', 'unique:users', 'nullable'],
            'password' => ['sometimes', 'required_with:email', 'string', 'min:8', 'confirmed', 'nullable'],
            'branch' => ['required', 'exists:branches,id'],
            /*'mon-time-start' => ['required', 'date_format:H:i'],
            'mon-time-end' => ['required', 'date_format:H:i'],
            'tus-time-start' => ['required', 'date_format:H:i'],
            'tus-time-end' => ['required', 'date_format:H:i'],
            'wed-time-start' => ['required', 'date_format:H:i'],
            'wed-time-end' => ['required', 'date_format:H:i'],
            'thu-time-start' => ['required', 'date_format:H:i'],
            'thu-time-end' => ['required', 'date_format:H:i'],
            'fri-time-start' => ['required', 'date_format:H:i'],
            'fri-time-end' => ['required', 'date_format:H:i'],
            'sat-time-start' => ['required', 'date_format:H:i'],
            'sat-time-end' => ['required', 'date_format:H:i'],*/
        ], [], [
            'name' => 'nombre',
            'email' => 'correo electrónico',
            'password' => 'contraseña',
            'branch' => 'sucursal',
        ]);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function updateValidator(array $data, $id)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $id],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],

        ], [], [
            'name' => 'nombre',
            'email' => 'correo electrónico',
            'password' => 'contraseña',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param Request $request
     * @return \App\Doctor
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $this->validator($request->all())->validate();

        DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);
            $user->save();
            $user->roles()->attach(3);
            $user->branches()->sync($data['branch']);

            $dataSchedule = [];
            if ($data['mon-time-start'])
                foreach ($data['mon-time-start'] as $index => $start)
                $dataSchedule[] = [
                    'user_id' => $user->id,
                    'day' => 1,
                    'start_time' => $start,
                    'end_time' => $data['mon-time-end'][$index],
                    'main' => $index == 0 ? 1 : null,
                ];

            if ($data['tue-time-start'])
                foreach ($data['tue-time-start'] as $index => $start)
                $dataSchedule[] = [
                    'user_id' => $user->id,
                    'day' => 2,
                    'start_time' => $start,
                    'end_time' => $data['tue-time-end'][$index],
                    'main' => $index == 0 ? 1 : null,
                ];

            if ($data['wed-time-start'])
                foreach ($data['wed-time-start'] as $index => $start)
                $dataSchedule[] = [
                    'user_id' => $user->id,
                    'day' => 3,
                    'start_time' => $start,
                    'end_time' => $data['wed-time-end'][$index],
                    'main' => $index == 0 ? 1 : null,
                ];

            if ($data['thu-time-start'])
                foreach ($data['thu-time-start'] as $index => $start)
                $dataSchedule[] = [
                    'user_id' => $user->id,
                    'day' => 4,
                    'start_time' => $start,
                    'end_time' => $data['thu-time-end'][$index],
                    'main' => $index == 0 ? 1 : null,
                ];

            if ($data['fri-time-start'])
                foreach ($data['fri-time-start'] as $index => $start)
                $dataSchedule[] = [
                    'user_id' => $user->id,
                    'day' => 5,
                    'start_time' => $start,
                    'end_time' => $data['fri-time-end'][$index],
                    'main' => $index == 0 ? 1 : null,
                ];

            if ($data['sat-time-start'])
                foreach ($data['sat-time-start'] as $index => $start)
                $dataSchedule[] = [
                    'user_id' => $user->id,
                    'day' => 6,
                    'start_time' => $start,
                    'end_time' => $data['sat-time-end'][$index],
                    'main' => $index == 0 ? 1 : null,
                ];

            DoctorsSchedule::insert($dataSchedule);

            $doctorDaysOff = [];
            $off = json_decode(urldecode($data['days_off']));
            foreach ($off as $item) {
                $doctorDaysOff[] = [
                    'user_id' => $user->id,
                    'day_off' => Carbon::parse($item->date)->format('Y-m-d'),
                    'end_day_off' => $item->date_end ? Carbon::parse($item->date_end)->format('Y-m-d') : null,
                    'title' => $item->title,
                ];
            }
            DoctorsDaysOff::insert($doctorDaysOff);
        });

        return redirect('/doctors');
    }

    public function edit($id)
    {
        $crumb2 = [
            'name' => 'Editar',
            'route' => "/doctor/$id",
        ];
        $branches = Branch::all();
        $user = User::where('users.id', $id)
            ->join('users_roles', 'users_roles.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'users_roles.role_id')
            ->where('roles.id', 3)
            ->first(['users.*']);
        if (!$user)
            abort(404);
        $user->roles;
        $user->branches;
        $user->role = $user->roles[0]->id ?? null;
        $user->branch = $user->branches[0]->id ?? null;
        $schedules = DoctorsSchedule::where('user_id', $id)
            ->get();
        $arrangedSchedules = [];
        foreach ($schedules as $item) {
            $arrangedSchedules[$item->day][]  = [
                'start_time' => Carbon::parse($item->start_time)->format('H:i'),
                'end_time' => Carbon::parse($item->end_time)->format('H:i'),
            ];
        }
        $params = compact('crumb2',  'branches', 'user', 'arrangedSchedules', 'days_off');
        return view('doctors.edit', $params);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();

        $this->validator($request->all(), $id)->validate();

        DB::transaction(function () use ($data, $id) {
            $user = User::find($id);
            $user->name = $data['name'];
            $user->email = $data['email'];
            if ($data['password'])
                $user->password = $data['password'];
            $user->save();
            $user->roles()->sync(3);
            $user->branches()->sync($data['branch']);

            DoctorsSchedule::where('user_id', $id)
                ->delete();
            DoctorsDaysOff::where('user_id', $id)
                ->delete();

            $dataSchedule = [];
            if ($data['mon-time-start'])
                foreach ($data['mon-time-start'] as $index => $start)
                    $dataSchedule[] = [
                        'user_id' => $user->id,
                        'day' => 1,
                        'start_time' => $start,
                        'end_time' => $data['mon-time-end'][$index],
                        'main' => $index == 0 ? 1 : null,
                    ];

            if ($data['tue-time-start'])
                foreach ($data['tue-time-start'] as $index => $start)
                    $dataSchedule[] = [
                        'user_id' => $user->id,
                        'day' => 2,
                        'start_time' => $start,
                        'end_time' => $data['tue-time-end'][$index],
                        'main' => $index == 0 ? 1 : null,
                    ];

            if ($data['wed-time-start'])
                foreach ($data['wed-time-start'] as $index => $start)
                    $dataSchedule[] = [
                        'user_id' => $user->id,
                        'day' => 3,
                        'start_time' => $start,
                        'end_time' => $data['wed-time-end'][$index],
                        'main' => $index == 0 ? 1 : null,
                    ];

            if ($data['thu-time-start'])
                foreach ($data['thu-time-start'] as $index => $start)
                    $dataSchedule[] = [
                        'user_id' => $user->id,
                        'day' => 4,
                        'start_time' => $start,
                        'end_time' => $data['thu-time-end'][$index],
                        'main' => $index == 0 ? 1 : null,
                    ];

            if ($data['fri-time-start'])
                foreach ($data['fri-time-start'] as $index => $start)
                    $dataSchedule[] = [
                        'user_id' => $user->id,
                        'day' => 5,
                        'start_time' => $start,
                        'end_time' => $data['fri-time-end'][$index],
                        'main' => $index == 0 ? 1 : null,
                    ];

            if ($data['sat-time-start'])
                foreach ($data['sat-time-start'] as $index => $start)
                    $dataSchedule[] = [
                        'user_id' => $user->id,
                        'day' => 6,
                        'start_time' => $start,
                        'end_time' => $data['sat-time-end'][$index],
                        'main' => $index == 0 ? 1 : null,
                    ];

            DoctorsSchedule::insert($dataSchedule);

            $doctorDaysOff = [];
            $off = json_decode(urldecode($data['days_off']));
            foreach ($off as $item) {
                $doctorDaysOff[] = [
                    'user_id' => $user->id,
                    'day_off' => Carbon::parse($item->date)->format('Y-m-d'),
                    'end_day_off' => $item->date_end ? Carbon::parse($item->date_end)->format('Y-m-d') : null,
                    'title' => $item->title,
                ];
            }
            DoctorsDaysOff::insert($doctorDaysOff);
        });
        return redirect('/doctors');
    }

    /**
     * @param Request $request
     * @return array
     */
    public function getDaysOff(Request $request)
    {
        $id = $request->id;
        $off = DoctorsDaysOff::where('user_id', $id)
            ->get();
        $days_off = [];
        foreach ($off as $item) {
            $date = Carbon::parse($item->day_off);
            $month = $date->month;
            $days_off[$month][] = [
                'title' => $item->title,
                'date' => $date->format('m-d'),
                'end_date' => $item->end_day_off ? Carbon::parse($item->end_day_off)->format('m-d') : null,
            ];
        }
        return [
            'success' => true,
            'data' => $days_off,
        ];
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
                $column = 'users.id';
                break;
            case 1:
                $column = 'users.name';
                break;
            case 2:
                $column = 'roles.name';
                break;
            default:
                $column = 'users.id';
                break;
        }

        $query = User::select([
            "users.id",
            "users.name",
            "roles.name AS role",
        ])
            ->join('users_roles', 'users_roles.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'users_roles.role_id')
            ->where('roles.id', 3)
            ->orderBy($column, $dir);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where("users.name", "LIKE", "%$search%");
            });
        }

        $total = $query->count();
        $query = $query->skip($skip)->take($take);
        $result = $query->get();

        foreach ($result as $i => $item)
            $result[$i]->roles;

        $params = [
            'data' => $result,
            'draw' => $request->draw,
            'recordsFiltered' => $total,
            'recordsTotal' => $total,
        ];

        return response()->json($params);
    }
}
