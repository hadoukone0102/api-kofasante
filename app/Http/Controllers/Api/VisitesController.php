<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service\visites;
use Illuminate\Http\Request;

class VisitesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $services = visites::orderBy('created_at', 'desc')->paginate(25);
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
        $service = visites::create($request->all());
        return response()->json([
            'message'=> "Enregistrer avec succès",
            'data'=>$service
        ], 201);    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $service = visites::findOrFail($id);
        return response()->json($service);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $service = visites::findOrFail($id);
        $service->update($request->only('prix'));
        return response()->json($service, 200);
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(string $id)
    {
        //
        $service = visites::findOrFail($id);
        $service->delete();
        return response()->json(null, 204);
    }
}
