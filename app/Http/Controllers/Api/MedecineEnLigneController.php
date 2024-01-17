<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service\medecine_en_lignes;
use Illuminate\Http\Request;

class MedecineEnLigneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $services = medecine_en_lignes::orderBy('created_at', 'desc')->get();
        return response()->json([
            'message' =>"toutes les donnée recupérer",
            'data' => $services
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $service = medecine_en_lignes::create($request->all());
        return response()->json([
            'message' =>"Démande effectuer avec succès",
            'data' => $service,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $service = medecine_en_lignes::findOrFail($id);
        return response()->json([
            'message' =>"element recuperé avec succes",
             'data' => $service,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $service = medecine_en_lignes::findOrFail($id);
        $service->update($request->only('prix'));
        return response()->json([
            'message' =>"toutes les donnée recupérer",
           'data' => $ $service
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $service = medecine_en_lignes::findOrFail($id);
        $service->delete();
        return response()->json([
            'message' =>"toutes les donnée recupérer",
            'data' => null
        ], 204);
    }
}
