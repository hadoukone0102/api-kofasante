<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\abonnements;
use Illuminate\Http\Request;

class AbonnementController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services = abonnements::orderBy('created_at', 'desc')->paginate(25);

        return response()->json([
            'message' => "Toutes les données récupérées",
            'data' => $services
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $service = abonnements::create($request->all());
        return response()->json([
            'message'=> "Enregistrer avec succès",
            'data'=>$service
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $service = abonnements::findOrFail($id);
        return response()->json($service);
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, string $id)
    {
        //
        $service = abonnements::findOrFail($id);
        $service->update($request->only('status'));
        return response()->json(
            [
             'message'=>'Modifier avec succès',
             'data'=>$service
            ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $service = abonnements::findOrFail($id);
        $service->delete();
        return response()->json([
            'message' =>"toutes les donnée recupérer",
            'data' => null
        ], 204);
    }
}
