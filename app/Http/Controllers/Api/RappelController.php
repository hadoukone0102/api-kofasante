<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Rappel\rappel;
use Illuminate\Http\Request;

class RappelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
         //
         $rappel = rappel::all();
         return response()->json(
             [
                 'message' =>"toutes les donnée recupérer",
                 'data' => $rappel
             ]
         );
    }

    public function myRappel(Request $request){
        $rappel = rappel::where([
            'nom' => auth()->user()->nom,
            'prenom' => auth()->user()->prenom,
            'contact' =>auth()->user()->contact,
            'email' => auth()->user()->email,
        ])->orderBy('created_at', 'desc')->get();

        return response()->json([
            'message' => "la facture de l'utilisateur connecté",
            'data' => $rappel,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
         //
         $rappel = rappel::create($request->all());
         return response()->json(
            [
                'message' =>"rappel créer avec succèss",
                'data' => $rappel

            ],201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $rappel = rappel::findOrFail($id);
        return response()->json($rappel);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Retrouver le rappel par son ID
        $rappel = rappel::findOrFail($id);

        // Mettre à jour les attributs du rappel avec les données de la requête
        $rappel->update($request->all());

        return response()->json([
            'message' => "Mise à jour effectuée avec succès",
            'data' => $rappel
        ], 200);
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $rappel = rappel::findOrFail($id);
        $rappel->delete();
        return response()->json([
            'message' =>"Rappel supprimer avec succès",
            null
        ], 204);
    }

}
