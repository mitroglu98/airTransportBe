<?php

namespace App\Http\Controllers;

use App\Models\Cost;
use Illuminate\Http\Request;

class CostController extends Controller
{

    public function index()
    {
        // Check user role, and if operator2, retrieve all costs with associated flights
        if (auth()->user()->role === 'operator2') {
            $costs = Cost::with('flight')->get();
        } else {
            $costs = []; // Return an empty array if not operator2
        }
    
        return response()->json($costs, 200);
    }
    
    public function store(Request $request)
    {
        // Only for operator2
        $this->validate($request, [
            'amount' => 'required',
            'description' => 'required',
        ]);
    
        // Map the request data to the correct columns
        $data = [
            'fuel_cost' => $request->input('fuel_cost'),
            'crew_cost' => $request->input('crew_cost'),
            'service_cost' => $request->input('service_cost'),
            'amount' => $request->input('amount'),
            'description' => $request->input('description'),
        ];
    
        $cost = Cost::create($data);
    
        return response()->json($cost, 201);
    }
    
    public function show(Cost $cost)
    {
        // For operator1, read-only
        return response()->json($cost, 200);
    }

    public function update(Request $request, Cost $cost)
    {
        // Only for operator2
        $cost->update($request->all());
        return response()->json($cost, 200);
    }

    public function destroy(Cost $cost)
    {
        // Only for operator2
        $cost->delete();
        return response()->json(null, 204);
    }

    public function getAllPassengers()
{
    $cost = Cost::all();
    return response()->json($cost, 200);
}

}