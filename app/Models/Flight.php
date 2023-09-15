<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'destination_from',
        'destination_to',
        'departure_time',
        'arrival_time',
        'airplane_id', 
    ];
    
    

    public function costs()
    {
        return $this->hasMany(Cost::class);
    }
    // Define relationships and accessors/mutators here

    public function airplane()
    {
        return $this->belongsTo(Airplane::class);
    }

    public function crews()
    {
        return $this->belongsToMany(Crew::class, 'crew_flight');
    }

    // public function costs()
    // {
    //     return $this->belongsToMany(Cost::class, 'flight_costs');
    // }
    public function flights() {
        return $this->belongsToMany(Flight::class);
    }
    public function passengers()
    {
        return $this->belongsToMany(Passenger::class, 'flight_passengers');
    }
    public function showPassengers($id)
{
    $flight = Flight::with('passengers')->find($id);

    if (!$flight) {
        return response()->json(['message' => 'Flight not found'], 404);
    }

    return response()->json($flight->passengers, 200);
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

    $flight->passengers()->attach($request->passenger_id);

    return response()->json(['message' => 'Passenger added to flight successfully'], 201);
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

    $flight->passengers()->detach($request->passenger_id);

    return response()->json(['message' => 'Passenger removed from flight successfully'], 200);
}

}
