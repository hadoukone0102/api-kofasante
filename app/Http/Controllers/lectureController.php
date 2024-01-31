<?php

namespace App\Http\Controllers;

use App\Models\lecture;
use Illuminate\Http\Request;

class lectureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $lecture = lecture::orderBy('created_at', 'desc')->get();
        return response()->json(
            [
                'message' =>"toutes les donnée recupérer",
                'data' => $lecture
            ]
        );
    }

    public function myLecture(Request $request){
        $lecture = lecture::where([
            'nom' => auth()->user()->nom,
            'prenom' => auth()->user()->prenom,
            'contact' =>auth()->user()->contact,
            'email' => auth()->user()->email,
        ])->orderBy('created_at', 'desc')->get();

        return response()->json([
            'message' => "liste des lecture récupérer avec succès",
            'data' => $lecture,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $lecture = lecture::create($request->all());
        return response()->json($lecture, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $lecture = lecture::findOrFail($id);
        return response()->json($lecture);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        // Retrouver le rappel par son ID
        $lecture = lecture::findOrFail($id);

        // Mettre à jour les attributs du rappel avec les données de la requête
        $lecture->update($request->all());

        return response()->json([
            'message' => "Mise à jour effectuée avec succès",
            'data' => $lecture
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $lecture = lecture::findOrFail($id);
         $lecture->delete();
         return response()->json([
             'message' =>"lecture supprimer avec succès",
             null
         ], 204);
    }
}
