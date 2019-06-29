<?php

namespace App\Http\Controllers;

use App\Branch;
use App\User;
use App\DoctorsDaysOff;
use App\DoctorsShedule;
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
            'route' => '/doctor',
        ]);
    }

    public function index()
    {
        return view('doctor.index');
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
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['sometimes', 'required', 'string', 'min:8', 'confirmed'],
            'branch' => ['required', 'exists:branches,id'],
            'mon-time-start' => ['date_format:H:i'],
            'mon-time-end' => ['date_format:H:i|after:mon-time-start'],
            'tur-time-start' => ['date_format:H:i'],
            'tur-time-end' => ['date_format:H:i|after:tur-time-start'],
            'wen-time-start' => ['date_format:H:i'],
            'wen-time-end' => ['date_format:H:i|after:wen-time-start'],
            'thu-time-start' => ['date_format:H:i'],
            'thu-time-end' => ['date_format:H:i|after:thu-time-start'],
            'fri-time-start' => ['date_format:H:i'],
            'fri-time-end' => ['date_format:H:i|after:fri-time-start'],
            'sat-time-start' => ['date_format:H:i'],
            'sat-time-end' => ['date_format:H:i|after:sat-time-start'],
            'sun-time-start' => ['date_format:H:i'],
            'sun-time-end' => ['date_format:H:i|after:sun-time-start'],
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
            $user->roles()->attach(3);
            $user->save();
        });

        /*DB::transaction(function () use ($data, $user) {
            $doctorDaysOff = DoctorsDaysOff::create([
                'user_id' => $user->id,
                'day_off' => $data['email'],
            ]);
        });

        DB::transaction(function () use ($data, $user) {
            $doctorShedule = DoctorsShedule::insert([
                'user_id' => $user->id,
                'day' => '1',
                'start_time' => $data['mon-time-start'],
                'end_time' => $data['mon-time-end'],
            ],
            [
                'user_id' => $user->id,
                'day' => '2',
                'start_time' => $data['tur-time-start'],
                'end_time' => $data['tur-time-end'],
            ],
            [
                'user_id' => $user->id,
                'day' => '3',
                'start_time' => $data['wen-time-start'],
                'end_time' => $data['wen-time-end'],
            ],
            [
                'user_id' => $user->id,
                'day' => '4',
                'start_time' => $data['thu-time-start'],
                'end_time' => $data['thu-time-end'],
            ],
            [
                'user_id' => $user->id,
                'day' => '5',
                'start_time' => $data['fri-time-start'],
                'end_time' => $data['fri-time-end'],
            ],
            [
                'user_id' => $user->id,
                'day' => '6',
                'start_time' => $data['sat-time-start'],
                'end_time' => $data['sat-time-end'],
            ],
            [
                'user_id' => $user->id,
                'day' => '7',
                'start_time' => $data['sun-time-start'],
                'end_time' => $data['sun-time-end'],
            ]);
        });*/

        return redirect('/doctors.index');
    }

    public function edit($id)
    {
        $crumb2 = [
            'name' => 'Editar',
            'route' => "/doctor/$id",
        ];
        $branches = Branch::all();
        $user = User::find($id);
        $params = compact('crumb2',  'branches', 'user');
        return view('doctors.edit', $params);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();

        $this->updateValidator($request->all(), $id)->validate();

        DB::transaction(function () use ($data, $id) {
            $user = User::find($id);
            $user->name = $data['name'];
            $user->email = $data['email'];
            if ($data['password'])
                $user->password = $data['password'];
            $user->save();
            $user->roles()->sync(3);
        });
        return redirect('/doctors');
    }
}
