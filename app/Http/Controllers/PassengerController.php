<?php

namespace App\Http\Controllers;
use App\Models\Passenger;
use Illuminate\Http\Request;

class PassengerController extends Controller
{
    public function index()
    {
        $passengers = Passenger::all();
        return response()->json($passengers, 200);
    }

    public function store(Request $request)
{
    $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255'
    ]);

    $passenger = new Passenger([
        'first_name' => $request->first_name,
        'last_name' => $request->last_name
    ]);
    $passenger->save();

    return response()->json($passenger, 201);
}


public function update(Request $request, $id)
{
    $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255'
    ]);

    $passenger = Passenger::findOrFail($id);
    $passenger->first_name = $request->first_name;
    $passenger->last_name = $request->last_name;
    $passenger->save();

    return response()->json($passenger, 200);
}


public function destroy($id)
{
    $passenger = Passenger::findOrFail($id);
    $passenger->delete();

    return response()->json(null, 204);
}


public function show($id)
{
    $passenger = Passenger::findOrFail($id);
    return response()->json($passenger, 200);
}

public function getAllPassengers()
{
    $passengers = Passenger::all();
    return response()->json($passengers, 200);
}
public function getPassengerFirstName()
{
    $passenger = Passenger::first();
    return response()->json(['first_name' => $passenger->first_name], 200);
}

}
