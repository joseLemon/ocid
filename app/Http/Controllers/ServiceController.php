<?php

namespace App\Http\Controllers;

use App\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
    public function __construct()
    {
        View::share("crumb1", [
            'name' => 'Servicios',
            'route' => '/services',
        ]);
    }

    public function index()
    {
        return view('services.index');
    }

    public function create()
    {
        $crumb2 = [
            'name' => 'Crear',
            'route' => '/service',
        ];
        $params = compact('crumb2');
        return view('services.create', $params);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $this->validator($request->all())->validate();

        DB::transaction(function () use ($data, &$service) {
            $service = Service::create([
                'name' => $data['name'],
                'time_slot' => $data['time_slot'] > 0 ? $data['time_slot'] : null,
            ]);
        });

        if ($request->ajax())
            return response()->json(['success' => true, 'service' => $service]);
        else
            return redirect('/services');
    }

    public function edit($id)
    {
        $crumb2 = [
            'name' => 'Editar',
            'route' => "/service/$id",
        ];
        $service = Service::find($id);
        $params = compact('crumb2', 'service');
        return view('services.edit', $params);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();

        $this->validator($request->all())->validate();

        DB::transaction(function () use ($data, $id, &$service) {
            $service = Service::find($id);
            $service->name = $data['name'];
            $service->time_slot = $data['time_slot'] > 0 ? $data['time_slot'] : null;
            $service->save();
        });

        if ($request->ajax())
            return response()->json(['success' => true, 'service' => $service]);
        else
            return redirect('/services');
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
            'name' => 'servicio',
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
                $column = 'services.id';
                break;
            case 1:
                $column = 'services.name';
                break;
            default:
                $column = 'services.id';
                break;
        }

        $query = Service::select([
            'services.id',
            'services.name',
        ])
            ->orderBy($column, $dir);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where("services.name", "LIKE", "%$search%");
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
