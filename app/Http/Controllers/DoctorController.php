<?php

namespace App\Http\Controllers;

use App\Role;
use App\User;
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
        $params = compact('crumb2', 'roles');
        return view('doctor.create', $params);
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
        ], [], [
            'name' => 'nombre',
            'email' => 'correo electrónico',
            'password' => 'contraseña',
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
        });
        return redirect('/doctor');
    }

    public function edit($id)
    {
        $crumb2 = [
            'name' => 'Editar',
            'route' => "/doctor/$id",
        ];
        $roles = Role::where("slug", "==", "doctor")->get();
        $user = User::find($id);
        $user->roles;
        $user->role = $user->roles[0]->id;
        $params = compact('crumb2', 'roles', 'user');
        return view('doctor.edit', $params);
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
        return redirect('/doctor');
    }
}
