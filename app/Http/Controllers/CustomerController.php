<?php

namespace App\Http\Controllers;

use App\Customer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function __construct()
    {
        View::share("crumb1", [
            'name' => 'Clientes',
            'route' => '/customers',
        ]);
    }

    public function index()
    {
        return view('customers.index');
    }

    public function create()
    {
        $crumb2 = [
            'name' => 'Crear',
            'route' => '/customer',
        ];
        $params = compact('crumb2');
        return view('customers.create', $params);
    }

    public function edit($id)
    {
        $crumb2 = [
            'name' => 'Editar',
            'route' => "/customer/$id",
        ];
        $customer = Customer::find($id);
        $params = compact('crumb2', 'customer');
        return view('customers.edit', $params);
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
        ], [], [
            'name' => 'nombre',
            'last_name' => 'apellido',
        ]);
    }

    public function store(Request $request)
    {
        $this->validator($request->all())->validate();

        $customer = new Customer();
        $customer->first_name = trim($request->first_name);
        $customer->last_name = trim($request->last_name);
        $customer->full_name = $customer->first_name . ' ' . $customer->last_name;
        $customer->email = $request->email ?? '';
        $customer->birthday = $request->birthday ?? null;
        $customer->country = $request->country ?? null;
        $customer->state = $request->state ?? null;
        $customer->postcode = $request->postcode ?? null;
        $customer->city = $request->city ?? null;
        $customer->street = $request->street ?? null;
        $customer->street_number = $request->street_number ?? null;
        $customer->additional_address = $request->additional_address ?? null;
        $customer->notes = $request->notes ?? '';
        $customer->created = Carbon::now();
        $customer->save();

        if ($request->ajax())
            return response()->json(['success' => true, 'customer' => $customer]);
        else
            return redirect('/customers');
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
                $column = 'id';
                break;
            case 1:
                $column = 'full_name';
                break;
            default:
                $column = 'id';
                break;
        }

        $query = Customer::select([
            'id',
            'full_name',
        ])
            ->orderBy($column, $dir);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'LIKE', "%$search%")
                    ->orWhere('first_name', 'LIKE', "%$search%")
                    ->orWhere('last_name', 'LIKE', "%$search%");
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

    public function searchSelect(Request $request)
    {
        $string = $request->string;
        $result = Customer::select([
            'id',
            'full_name as text',
        ])
            ->where(function ($q) use ($string) {
                $q->where('full_name', 'LIKE', "%$string%")
                    ->orWhere('first_name', 'LIKE', "%$string%")
                    ->orWhere('last_name', 'LIKE', "%$string%");
            })
            ->paginate(15);

        return response()->json($result);
    }
}
