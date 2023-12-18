<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Citation;
use Illuminate\Support\Facades\DB;

class CitationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function allCitation()
    { 
        $citations = DB::table('citations')->get();

        if ($citations->isEmpty()) {
            return response()->json(['message' => 'Aucune citation trouvée'], 404);
        }

        return response()->json($citations);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createCitation(Request $request)
    {
        
        $request->validate([
            'user_id' => 'required|integer',
            'contenu' => 'required|string',
        ]);

        $citation = new Citation([
            'user_id' => $request->input('user_id'),
            'contenu' => $request->input('contenu'),
        ]);

        $citation->save(); 

        return response()->json(['message' => 'Citation créé avec succès','Citation' => $citation], 201);
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
    public function citation(string $id)
    {
        $citation = DB::table('citations')->find($id);

        if ($citation) {
            return response()->json($citation);
        } else {
            return response()->json(['message' => 'citation non trouvé'], 404);
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
    public function updateCitation(Request $request, $id)
    {
        $citation = DB::table('citations')->find($id);

        if (!$citation) {
            return response()->json(['message' => 'Citation not found'], 404);
        }

        DB::table('citations')->where('id', $id)->update($request->all());
    
        $citationUpdated = DB::table('citations')->find($id);
    
        $message = 'Citation updated successfully';
        return response()->json(['message' => $message, 'citation' => $citationUpdated], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deleteCitation($id)
    {
        $citation = DB::table('citations')->find($id);

        if (!$citation) {
            $message = 'Citation not found';
            return response()->json(['message' => $message], 404);
        }

        DB::table('citations')->where('id', $id)->delete();

        $message = 'Citation deleted successfully';
        return response()->json(['message' => $message], 200);
    }
}
