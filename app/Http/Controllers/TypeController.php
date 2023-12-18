<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Type;
use Illuminate\Support\Facades\DB;

class TypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function allType()
    {
        $types = DB::table('types')->get();

        if ($types->isEmpty()) {
            return response()->json(['message' => 'Aucun type trouvé'], 404);
        }

        return response()->json($types);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createType(Request $request)
    {
        
        $request->validate([
            'nom_type' => 'required|string',
        ]);

        $type = new Type([
            'nom_type' => $request->input('nom_type'),
        ]);

        $type->save(); 

        return response()->json(['message' => 'Type créé avec succès','menu' => $type], 201);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function type(string $id)
    {
        $type = DB::table('types')->find($id);

        if ($type) {
            return response()->json($type);
        } else {
            return response()->json(['message' => 'type non trouvé'], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateType(Request $request, $id)
    {
        $type = DB::table('types')->find($id);

        if (!$type) {
            return response()->json(['message' => 'Type not found'], 404);
        }

        DB::table('types')->where('id', $id)->update($request->all());
    
        $typeUpdated = DB::table('types')->find($id);
    
        $message = 'Type updated successfully';
        return response()->json(['message' => $message, 'type' => $typeUpdated], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deleteType($id)
    {
        $type = DB::table('types')->find($id);

        if (!$type) {
            $message = 'Type not found';
            return response()->json(['message' => $message], 404);
        }

        DB::table('types')->where('id', $id)->delete();

        $message = 'Type deleted successfully';
        return response()->json(['message' => $message], 200);
    }
}
