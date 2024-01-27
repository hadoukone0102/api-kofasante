<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Paiements;
use Illuminate\Http\Request;

class PaiementsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $paiement = Paiements::orderBy('created_at', 'desc')->get();

        return response()->json([
            'message' => "Toutes les données récupérées avec succès",
            'data' => $paiement
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $paiement = Paiements::create($request->all());
        return response()->json(
           [
               'message' =>"rappel créer avec succèss",
               'data' => $paiement

           ],201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $paiement = Paiements::findOrFail($id);
        return response()->json($paiement);
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
