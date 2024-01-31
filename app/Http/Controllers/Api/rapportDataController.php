<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\rapportData;
use Illuminate\Http\Request;

class rapportDataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $analyse = rapportData::orderBy('created_at', 'desc')->get();
        return response()->json(
            [
                'message' =>"toutes les donnée recupérer",
                'data' => $analyse
            ]
        );
    }


    public function myAnalyse(Request $request){
        $analyse = rapportData::where([
            'nom' => auth()->user()->nom,
            'prenom' => auth()->user()->prenom,
            'contact' =>auth()->user()->contact,
            'email' => auth()->user()->email,
        ])->orderBy('created_at', 'desc')->get();

        return response()->json([
            'message' => "liste des analyses récupérer avec succès",
            'data' => $analyse,
        ], 200);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $analyse = rapportData::create($request->all());
        return response()->json($analyse, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $analyse = rapportData::findOrFail($id);
        return response()->json($analyse);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
         // Retrouver le rappel par son ID
         $analyse = rapportData::findOrFail($id);

         // Mettre à jour les attributs du rappel avec les données de la requête
         $analyse->update($request->all());

         return response()->json([
             'message' => "Mise à jour effectuée avec succès",
             'data' => $analyse
         ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $analyse = rapportData::findOrFail($id);
        $analyse->delete();
        return response()->json([
            'message' =>"analyse supprimer avec succès",
            null
        ], 204);
    }
}
