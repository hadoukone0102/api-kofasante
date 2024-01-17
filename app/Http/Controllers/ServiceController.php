<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $services = Service::all();
        return response()->json(
            [
                'message' =>"toutes les donnée recupérer",
                'data' => $services
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $service = Service::create($request->all());
        return response()->json($service, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $service = Service::findOrFail($id);
        return response()->json($service);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $service = Service::findOrFail($id);
        $service->update($request->only('prix'));
        return response()->json([
            'message' =>"Mise à jour effectuer avec succès",
            'data'=>$service
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $service = Service::findOrFail($id);
        $service->delete();
        return response()->json(null, 204);
    }
}
