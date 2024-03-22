<?php

namespace App\Http\Controllers\Api\types;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\type_medecine;
use App\Models\type_document;

class TypesControllers extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'service' => 'required',
            'type_service' => 'required',
            'desc' => 'required',
        ], [
            'service.required' => 'le service est requis',
            'type_service.required' => 'le type_service est requis', // corriger la faute de frappe ici
            'desc.required' => 'desc est requis',
        ]);
        $services = $request->input('service');

        if ($services == "Medecine") {
            $med = type_medecine::where('desc',$request->input('desc'))->first();
            if($med){
                return response()->json([
                    'message'=>"$med->desc existe déja dans le service",
                ], 200);
            }else{
                $create_type_of_med = type_medecine::create([
                    'type'=>$request->input('type_service'),
                    'desc'=>$request->input('desc')
                ]);
                return response()->json([
                    "message"=>"$create_type_of_med->desc créer avec succès",
                    "data"=>$create_type_of_med
                ], 200);
            }
        } elseif($services == "Document") {
            $med = type_document::where('desc',$request->input('desc'))->first();
            if($med){
                return response()->json([
                    'message'=>"$med->desc existe déja dans le service",
                ], 200);
            }else{
                $create_type_of_med = type_document::create([
                    'type'=>$request->input('type_service'),
                    'desc'=>$request->input('desc')
                ]);
                return response()->json([
                    "message"=>"$create_type_of_med->desc créer avec succès",
                    "data"=>$create_type_of_med
                ], 200);
            }
        }else{
            var_dump("Je ne te connais pas");
        }
    }

    public function ListeMed (string $type){
        $lecture = type_medecine::where('type',$type)->orderBy('created_at', 'desc')->get();
        return response()->json(
            [
                'message' =>"toutes les donnée recupérer",
                'data' => $lecture
            ]
        );
    }


    public function ListeDoc (string $type){
        $lecture = type_document::where('type',$type)->orderBy('created_at', 'desc')->get();
        return response()->json(
            [
                'message' =>"toutes les donnée recupérer",
                'data' => $lecture
            ]
        );
    }

    /**
     * Display the specified resource.
     */
    public function ListeMedDelete(string $id)
    {
        //
        $lecture = type_medecine::findOrFail($id);
        $lecture->delete();
        return response()->json([
            'message' =>"lecture supprimer avec succès",
            null
        ], 204);
    }

    public function ListeDocDelete(string $id)
    {
        //
        $lecture = type_document::findOrFail($id);
        $lecture->delete();
        return response()->json([
            'message' =>"lecture supprimer avec succès",
            null
        ], 204);
    }

    /**
     * Update the specified resource in storage.
     */
    public function medFonction()
    {
        //
        $lecture = type_medecine::orderBy('created_at', 'desc')->get();
        return response()->json(
            [
                'message' =>"toutes les donnée recupérer",
                'data' => $lecture
            ]
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function docFonction()
    {
        //
        $lecture = type_document::orderBy('created_at', 'desc')->get();
        return response()->json(
            [
                'message' =>"toutes les donnée recupérer",
                'data' => $lecture
            ]
        );
    }

}
