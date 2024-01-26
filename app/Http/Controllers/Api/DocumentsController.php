<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service\documents;
use Illuminate\Http\Request;

class DocumentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $services = documents::orderBy('created_at', 'desc')->paginate(25);
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
        $service = documents::create($request->all());
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
        $service = documents::findOrFail($id);
        return response()->json($service);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $service = documents::findOrFail($id);
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
        $service = documents::findOrFail($id);
        $service->delete();
        return response()->json(null, 204);
    }

}
