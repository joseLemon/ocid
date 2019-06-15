<?php

namespace App\Http\Controllers;

use App\Branch;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;

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
        $roles = Role::where("slug", "!=", "doctor")->get();
        $branches = Branch::all();
        $params = compact('crumb2', 'roles', 'branches');
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
        $roles = Role::where("slug", "!=", "doctor")->get();
        $branches = Branch::all();
        $user = User::find($id);
        $user->roles;
        $user->branches;
        $user->role = $user->roles[0]->id ?? null;
        $user->branch = $user->branches[0]->id ?? null;
        $params = compact('crumb2', 'roles', 'branches', 'user');
        return view('users.edit', $params);
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
            $user->roles()->sync($data['role']);
            $user->branches()->sync($data['branch']);
        });
        return redirect('/users');
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
