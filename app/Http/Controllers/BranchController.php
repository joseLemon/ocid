<?php

namespace App\Http\Controllers;

use App\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class BranchController extends Controller
{
    public function __construct()
    {
        View::share("crumb1", [
            'name' => 'Sucursales',
            'route' => '/branches',
        ]);
    }

    public function index()
    {
        return view('branches.index');
    }

    public function create()
    {
        $crumb2 = [
            'name' => 'Crear',
            'route' => '/branch',
        ];
        $params = compact('crumb2');
        return view('branches.create', $params);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $this->validator($request->all())->validate();

        DB::transaction(function () use ($data, &$branch) {
            $branch = Branch::create([
                'name' => $data['name'],
            ]);
        });

        if ($request->ajax())
            return response()->json(['success' => true, 'branch' => $branch]);
        else
            return redirect('/branches');
    }

    public function edit($id)
    {
        $crumb2 = [
            'name' => 'Editar',
            'route' => "/branch/$id",
        ];
        $branch = Branch::find($id);
        $params = compact('crumb2', 'branch');
        return view('branches.edit', $params);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();

        $this->validator($request->all())->validate();

        DB::transaction(function () use ($data, $id, &$branch) {
            $branch = Branch::find($id);
            $branch->name = $data['name'];
            $branch->save();
        });


        if ($request->ajax())
            return response()->json(['success' => true, 'branch' => $branch]);
        else
            return redirect('/branches');
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
        ], [], [
            'name' => 'sucursal',
        ]);
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
                $column = 'branches.id';
                break;
            case 1:
                $column = 'branches.name';
                break;
            default:
                $column = 'branches.id';
                break;
        }

        $query = Branch::select([
            'branches.id',
            'branches.name',
        ])
            ->orderBy($column, $dir);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where("branches.name", "LIKE", "%$search%");
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
