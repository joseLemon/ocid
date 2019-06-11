<?php

namespace App\Http\Controllers;

use App\Customer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
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
