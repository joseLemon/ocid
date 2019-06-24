<?php

namespace App\Http\Controllers;

use App\Appointment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AppointmentController extends Controller
{
    private function validator($data)
    {
        return Validator::make($data, [
            'doctor_id' => ['required', 'exists:users,id'],
            'service_id' => ['required', 'exists:services,id'],
            'customer_id' => ['required'],
            'date' => ['required'],
            'start' => ['required'],
            'end' => ['required'],
        ], [], [
            'doctor_id' => 'médico',
            'service_id' => 'servicio',
            'customer_id' => 'cliente',
            'date' => 'fecha',
            'start' => 'hora inicio',
            'end' => 'hora fin',
        ]);

    }

    public function store(Request $request)
    {
        $data = $request->all();
        $data['start'] = Carbon::parse($request->start)->format('H:i');
        $data['end'] = Carbon::parse($request->end)->format('H:i');
        $data['date'] = Carbon::createFromFormat('d/m/Y', $request->date)->format('Y/m/d');

        $this->validator($request->all())->validate();

        DB::transaction(function () use ($data, &$appointment) {
            $appointment = Appointment::create([
                'doctor_id' => $data['doctor_id'],
                'service_id' => $data['service_id'],
                'customer_id' => $data['customer_id'],
                'date' => $data['date'],
                'start' => $data['start'],
                'end' => $data['end'],
            ]);
        });

        if ($request->ajax())
            return response()->json(['success' => true, 'appointment' => $appointment]);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $data['start'] = Carbon::parse($request->start)->format('H:i');
        $data['end'] = Carbon::parse($request->end)->format('H:i');
        $data['date'] = Carbon::createFromFormat('d/m/Y', $request->date)->format('Y/m/d');

        $this->validator($request->all())->validate();

        DB::transaction(function () use ($data, $id, &$appointment) {
            $appointment = Appointment::find($id);
            $appointment->doctor_id = $data['doctor_id'];
            $appointment->service_id = $data['service_id'];
            $appointment->customer_id = $data['customer_id'];
            $appointment->date = $data['date'];
            $appointment->start = $data['start'];
            $appointment->end = $data['end'];
            $appointment->status = $this->getStatus($data['status']);
            $appointment->save();
        });

        if ($request->ajax())
            return response()->json(['success' => true, 'appointment' => $appointment]);
    }

    private function getStatus($value)
    {
        switch ($value) {
            case 1:
            default:
                return null;
            case 2:
                return 'active';
            case 3:
                return 'delayed';
            case 4:
                return 'cancelled';
        }
    }
}
