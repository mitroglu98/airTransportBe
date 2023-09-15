<?php

namespace App\Http\Controllers;

use App\Models\Airplane;
use Illuminate\Http\Request;

class AirplaneController extends Controller
{
    public function index()
    {
        return response()->json(Airplane::all());
    }
    public function getAirplanes() {
        return Airplane::all();
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name_en' => 'required|string',
            'name_cg' => 'required|string',
            'type' => 'required|string',
        ]);
    
        $airplane = new Airplane([
            'name_en' => $request->input('name_en'),
            'name_cg' => $request->input('name_cg'),
            'type' => $request->input('type'),
        ]);
        $airplane->save();
    
        return response()->json($airplane, 201);
    }

    public function update(Request $request, $id)
    {
        $airplane = Airplane::find($id);
        if (!$airplane) {
            return response()->json(['message' => 'Airplane not found'], 404);
        }
    
        $request->validate([
            'name_en' => 'sometimes|required|string',
            'name_cg' => 'sometimes|required|string',
            'type' => 'sometimes|required|string',
        ]);
    
        $airplane->update($request->all());
    
        return response()->json(['message' => 'Airplane updated successfully']);
    }
    

    public function destroy($id)
    {
        $airplane = Airplane::find($id);
        if (!$airplane) {
            return response()->json(['message' => 'Airplane not found'], 404);
        }
    
        $airplane->delete();
    
        return response()->json(['message' => 'Airplane deleted successfully']);
    }
}
