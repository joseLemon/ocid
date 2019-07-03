<?php

namespace App\Http\Controllers;

use App\Branch;
use App\User;
use App\DoctorsDaysOff;
use App\DoctorsShedule;
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
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['sometimes', 'required', 'string', 'min:8', 'confirmed'],
            'branch' => ['required', 'exists:branches,id'],
            /*'mon-time-start' => ['required', 'date_format:H:i'],
            'mon-time-end' => ['required', 'date_format:H:i'],
            'tus-time-start' => ['required', 'date_format:H:i'],
            'tus-time-end' => ['required', 'date_format:H:i'],
            'wen-time-start' => ['required', 'date_format:H:i'],
            'wen-time-end' => ['required', 'date_format:H:i'],
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

        dd($data);

        $this->validator($request->all())->validate();

        DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);
            $user->roles()->attach(3);
            $user->save();



            $dataShedule = array(
                array(
                    'user_id' => $user->id,
                    'day' => 1,
                    'start_time' => $data['mon-time-start'],
                    'end_time' => $data['mon-time-end'],
                ),
                array(
                    'user_id' => $user->id,
                    'day' => 2,
                    'start_time' => $data['tur-time-start'],
                    'end_time' => $data['tur-time-end'],
                ),
                array(
                    'user_id' => $user->id,
                    'day' => 3,
                    'start_time' => $data['wen-time-start'],
                    'end_time' => $data['wen-time-end'],
                ),
                array(
                    'user_id' => $user->id,
                    'day' => 4,
                    'start_time' => $data['thu-time-start'],
                    'end_time' => $data['thu-time-end'],
                ),
                array(
                    'user_id' => $user->id,
                    'day' => 5,
                    'start_time' => $data['fri-time-start'],
                    'end_time' => $data['fri-time-end'],
                ),
                array(
                    'user_id' => $user->id,
                    'day' => 6,
                    'start_time' => $data['sat-time-start'],
                    'end_time' => $data['sat-time-end'],
                ),
            );

            DoctorsShedule::insert($dataShedule);

            return redirect('/doctors');


            /*
            $doctorShedule = DoctorsShedule::create([
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
                ]);

            $doctorShedule->save();
*/
        });







        /*
        DB::transaction(function () use ($data, $user) {
            $doctorDaysOff = DoctorsDaysOff::create([
                'user_id' => $user->id,
                'day_off' => $data['email'],
            ]);
        });
        */
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
