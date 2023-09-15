<?php

namespace App\Http\Controllers;
use App\Models\Crew;
use Illuminate\Http\Request;

class CrewController extends Controller
{
    public function index()
    {
        $crews = Crew::all();
        return response()->json($crews, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'position' => 'required|string|max:255'
        ]);

        $crew = new Crew([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'position' => $request->position
        ]);
        $crew->save();

        return response()->json($crew, 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'position' => 'required|string|max:255'
        ]);

        $crew = Crew::findOrFail($id);
        $crew->first_name = $request->first_name;
        $crew->last_name = $request->last_name;
        $crew->position = $request->position;
        $crew->save();

        return response()->json($crew, 200);
    }

    public function destroy($id)
    {
        $crew = Crew::findOrFail($id);
        $crew->delete();

        return response()->json(null, 204);
    }

    public function show($id)
    {
        $crew = Crew::findOrFail($id);
        return response()->json($crew, 200);
    }

    public function getAllCrews()
{
    $crews = Crew::all();
    return response()->json($crews, 200);
}

}
