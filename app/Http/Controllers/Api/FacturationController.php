<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Facturation;
use Illuminate\Http\Request;

class FacturationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $models = Facturation::orderBy('created_at', 'desc')->paginate(50);

        return response()->json([
            'message' => 'Liste complète récupérée avec succès',
            'data' => $models,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'nom' => 'required',
            'prenom' => 'required',
            'contact' => 'required',
            'email' => 'required|email',
        ]);

        $model = Facturation::create($request->all());

        return response()->json([
            'message' => 'Enregistrement réussi',
            'data' => $model,
        ], 201);
    }



    public function personnel(Request $request)
    {
        $request->validate([
            'nom' => 'required',
            'prenom' => 'required',
            'contact' => 'required',
            'email' => 'required|email',
        ]);

        $models = Facturation::where([
            'nom' => $request->input('nom'),
            'prenom' => $request->input('prenom'),
            'contact' => $request->input('contact'),
            'email' => $request->input('email'),
        ])->orderBy('created_at', 'desc')->get();

        return response()->json([
            'message' => 'Résultats de recherche récupérés avec succès',
            'data' => $models,
        ], 200);
    }


    public function MyFacture (Request $request){
        $models = Facturation::where([
            'nom' => auth()->user()->nom,
            'prenom' => auth()->user()->prenom,
            'contact' =>auth()->user()->contact,
            'email' => auth()->user()->email,
        ])->orderBy('created_at', 'desc')->get();

        return response()->json([
            'message' => "la facture de l'utilisateur connecté",
            'data' => $models,
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
