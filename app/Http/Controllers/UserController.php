<?php

namespace App\Http\Controllers;

use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class UserController extends Controller
{
    public function __construct()
    {
        View::share("crumb1", [
            'name' => 'Usuarios',
            'route' => '/users',
        ]);
    }

    public function index()
    {
        return view('users.index');
    }

    public function create()
    {
        $crumb2 = [
            'name' => 'Crear',
            'route' => '/user',
        ];
        $roles = Role::all();
        $params = compact('crumb2', 'roles');
        return view('users.create', $params);
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
            'role' => ['required', 'exists:roles,id'],
        ], [], [
            'name' => 'nombre',
            'email' => 'correo electrónico',
            'password' => 'contraseña',
            'role' => 'tipo de usuario',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param Request $request
     * @return \App\User
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
            $user->roles()->attach($data['role']);
        });
        return redirect('/users');
    }

    public function edit($id)
    {
        $crumb2 = [
            'name' => 'Editar',
            'route' => "/user/$id",
        ];
        $roles = Role::all();
        $user = User::find($id);
        $user->roles;
        $user->role = $user->roles[0]->id;
        $params = compact('crumb2', 'roles', 'user');
        return view('users.edit', $params);
    }

    public function search()
    {
        $query = User::paginate(15);

        return $query;
    }
}
