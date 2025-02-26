<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sendrapport;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SendrapportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $rapport = Sendrapport::orderBy('created_at', 'desc')->get();
        return response()->json(
            [
                'message' =>"toutes les donnée recupérer",
                'data' => $rapport
            ]
        );
    }

    public function myRapport(Request $request){
        $rapport = Sendrapport::where([
            'nom' => auth()->user()->nom,
            'prenom' => auth()->user()->prenom,
            'contact' =>auth()->user()->contact,
            'email' => auth()->user()->email,
        ])->orderBy('created_at', 'desc')->get();

        // Formater les dates avec Carbon
        $formattedPub = $rapport->map(function ($item) {
            return [
                'id' => $item->id,
                'nom' => $item->nom,
                'prenom' => $item->prenom,
                'email' => $item->email,
                'contact' => $item->contact,
                'nomAdmin' => $item->nomAdmin,
                'titre' => $item->titre,
                'desc' => $item->desc,
                'created_at' => Carbon::parse($item->created_at)->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::parse($item->updated_at)->format('Y-m-d H:i:s'),
                ];
        });

        return response()->json([
            'message' => "liste des rapports récupérer avec succès",
            'data' => $formattedPub,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $rapport = Sendrapport::create($request->all());
        return response()->json($rapport, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $rapport = Sendrapport::findOrFail($id);
        return response()->json($rapport);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Retrouver le rappel par son ID
        $rapport = Sendrapport::findOrFail($id);

        // Mettre à jour les attributs du rappel avec les données de la requête
        $rapport->update($request->all());

        return response()->json([
            'message' => "Mise à jour effectuée avec succès",
            'data' => $rapport
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
         //
         $rapport = Sendrapport::findOrFail($id);
         $rapport->delete();
         return response()->json([
             'message' =>"rapports supprimer avec succès",
             null
         ], 204);
    }
}
