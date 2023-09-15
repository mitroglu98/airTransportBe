<?php

namespace App\Http\Controllers;

use App\Models\Flight;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class FlightController extends Controller
{
    
  
    public function index()
    {
        $flights = Flight::with('airplane')->get();
        return response()->json($flights, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'destination_from' => 'required|string|max:255',
            'destination_to' => 'required|string|max:255',
            'airplane_id' => 'required|exists:airplanes,id',
            'departure_datetime' => 'required|date_format:Y-m-d H:i:s',
            'arrival_datetime' => 'required|date_format:Y-m-d H:i:s|after:departure_datetime',
        ]);

        if (!$this->validateAirplaneAvailability(
            $request->airplane_id,
            $request->departure_datetime,
            $request->arrival_datetime
        )) {
            return response()->json(['message' => 'Airplane is already in use for the selected period.'], 400);
        }

        $flight = new Flight([
            'destination_from' => $request->destination_from,
            'destination_to' => $request->destination_to,
            'airplane_id' => $request->airplane_id,
            'departure_time' => $request->departure_datetime,
            'arrival_time' => $request->arrival_datetime,
        ]);
        $flight->save();

        return response()->json($flight, 201);
    }




    public function __construct() {
        $this->middleware('role:operator2')->only(['store', 'update', 'destroy']);
    }
    public function showPassengers($flightId)
{
    try {
        $flight = Flight::findOrFail($flightId);
        $passengers = $flight->passengers;
        
        return response()->json($passengers, 200);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Error retrieving passengers for the flight.'], 500);
    }
}

public function show($id)
{
    $flight = Flight::with('airplane', 'crews', 'costs')->findOrFail($id);
    return response()->json($flight);
}

    public function update(Request $request, $id)
    {
        $flight = Flight::find($id);
    
        if (!$flight) {
            return response()->json(['message' => 'Flight not found'], 404);
        }
    
        $request->validate([
            'destination_from' => 'required|string|max:255',
            'destination_to' => 'required|string|max:255',
            'departure_datetime' => 'required|date_format:Y-m-d H:i:s',
            'arrival_datetime' => 'required|date_format:Y-m-d H:i:s|after:departure_datetime',
        ]);
    
        $flight->update([
            'destination_from' => $request->destination_from,
            'destination_to' => $request->destination_to,
            'departure_time' => $request->departure_datetime,
            'arrival_time' => $request->arrival_datetime,
        ]);
    
        return response()->json($flight, 200);
    }
    

    public function destroy(Flight $flight)
    {
        $flight->delete();
        return response()->json(null, 204);
    }

    public function addCrew(Request $request, Flight $flight)
    {
        $validatedData = $request->validate([
            'crew_id' => 'required|exists:crews,id',
        ]);

        $flight->crews()->attach($validatedData['crew_id']);

        return response()->json(['message' => 'Crew added to flight successfully'], 201);
    }

    public function addCost(Request $request, Flight $flight)
    {
        $validatedData = $request->validate([
            'cost_id' => 'required|exists:costs,id',
        ]);

        $flight->costs()->attach($validatedData['cost_id']);

        return response()->json(['message' => 'Cost added to flight successfully'], 201);
    }

    public function addPassenger(Request $request, Flight $flight)
    {
        $validatedData = $request->validate([
            'passenger_id' => 'required|exists:passengers,id',
        ]);

        if (!$this->validatePassengerOnFlight($flight->id, $validatedData['passenger_id'])) {
            return response()->json(['message' => 'Passenger is not on the selected flight.'], 400);
        }

        $flight->passengers()->attach($validatedData['passenger_id']);

        return response()->json(['message' => 'Passenger added to flight successfully'], 201);
    }


    public function validatePassengerOnFlight($flight_id, $passenger_id)
    {
        $exists = Flight::where('id', $flight_id)
            ->whereExists(function ($query) use ($passenger_id) {
                $query->select(DB::raw(1))
                    ->from('passengers')
                    ->join('flight_passengers', 'passengers.id', '=', 'flight_passengers.passenger_id')
                    ->whereRaw('flights.id = flight_passengers.flight_id')
                    ->where('passengers.id', $passenger_id);
            })
            ->exists();
    
        return $exists;
    }
    
    public function getFlightDetails($id)
{
    $flight = Flight::with('crews', 'passengers', 'costs')->find($id);

    if (!$flight) {
        return response()->json(['message' => 'Flight not found'], 404);
    }

    return response()->json($flight, 200);
}

public function validateAirplaneAvailability($airplane_id, $departure_datetime, $arrival_datetime)
{
    $overlappingFlights = Flight::where('airplane_id', $airplane_id)
        ->where(function ($query) use ($departure_datetime, $arrival_datetime) {
            $query->whereBetween('departure_time', [$departure_datetime, $arrival_datetime])
                ->orWhereBetween('arrival_time', [$departure_datetime, $arrival_datetime]);
        })
        ->exists();

    return !$overlappingFlights;
}
public function removePassengerFromFlight(Request $request, $flight_id)
{
    $request->validate([
        'passenger_id' => 'required|exists:passengers,id'
    ]);

    $flight = Flight::find($flight_id);

    if (!$flight) {
        return response()->json(['message' => 'Flight not found'], 404);
    }

    // Check if the passenger is on the flight
    if (!$this->validatePassengerOnFlight($flight->id, $request->passenger_id)) {
        return response()->json(['message' => 'Passenger is not on the selected flight.'], 400);
    }

    $flight->passengers()->detach($request->passenger_id);

    return response()->json(['message' => 'Passenger removed from flight successfully'], 200);
}


public function removeCost(Request $request, Flight $flight) {
    $validatedData = $request->validate([
        'cost_id' => 'required|exists:costs,id',
    ]);
    $flight->costs()->detach($validatedData['cost_id']);
    return response()->json(['message' => 'Cost removed from flight successfully'], 200);
}
public function showDetailedFlight($id)
{
    $flight = Flight::with(['crews', 'passengers', 'costs'])->find($id);
    if (!$flight) {
        return response()->json(['message' => 'Flight not found'], 404);
    }
    return response()->json($flight);
}


public function addPassengerToFlight(Request $request, $flight_id)
{
    $request->validate([
        'passenger_id' => 'required|exists:passengers,id'
    ]);

    $flight = Flight::find($flight_id);

    if (!$flight) {
        return response()->json(['message' => 'Flight not found'], 404);
    }

    // Check if the passenger is already on the flight
    if ($this->validatePassengerOnFlight($flight->id, $request->passenger_id)) {
        return response()->json(['message' => 'Passenger is already on the flight.'], 400);
    }

    $flight->passengers()->attach($request->passenger_id);

    return response()->json(['message' => 'Passenger added to flight successfully'], 201);
}



public function checkAirplaneAvailability(Request $request)
{
    $request->validate([
        'airplane_id' => 'required|exists:airplanes,id',
        'departure_time' => 'required|date_format:Y-m-d H:i:s',
        'arrival_time' => 'required|date_format:Y-m-d H:i:s|after:departure_time',
    ]);

    $conflictingFlights = Flight::where('airplane_id', $request->airplane_id)
        ->where(function ($query) use ($request) {
            $query->whereBetween('departure_time', [$request->departure_time, $request->arrival_time])
                ->orWhereBetween('arrival_time', [$request->departure_time, $request->arrival_time]);
        })
        ->count();

    if ($conflictingFlights > 0) {
        return response()->json(['message' => 'Airplane already in use for the selected time period'], 400);
    }

    return response()->json(['message' => 'Airplane available for the selected time period']);
}
}
