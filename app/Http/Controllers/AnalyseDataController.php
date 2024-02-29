<?php

namespace App\Http\Controllers;

use App\Models\AnalyseData;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AnalyseDataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $bilan = AnalyseData::orderBy('created_at', 'desc')->get();

        return response()->json([
            'message' => 'Liste complète récupérée avec succès',
            'data' => $bilan,
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
            'sexe' => 'required',
            'age' => 'required',
            'type' => 'required',
        ]);

        $bilan = AnalyseData::create($request->all());

        return response()->json([
            'message' => 'Enregistrement effectué avec succès',
            'data' => $bilan,
        ], 201);
    }


    public function technique(Request $request)
    {
        $request->validate([
            'nom' => 'required',
            'prenom' => 'required',
            'contact' => 'required',
            'email' => 'required|email',
            'sexe' => 'required',
            'age' => 'required',
            'type' => 'required',
        ]);

        // Vérifier si l'utilisateur existe
        $verification = AnalyseData::where('email', $request->email)->orderBy('created_at', 'desc')->first();

        if (!$verification) {
            // Si l'utilisateur n'existe pas encore, créer un nouveau bilan
            $bilan = AnalyseData::create($request->all());

            return response()->json([
                'message' => 'Enregistrement effectué avec succès',
                'data' => $bilan,
            ], 201);

        } else if ($verification && !$verification->created_at->isToday()) {
            // Si l'utilisateur existe, mais la date de création (created_at) n'est pas aujourd'hui
            // Alors créer un nouveau bilan

            $bilan = AnalyseData::create($request->all());

            return response()->json([
                'message' => 'Enregistrement effectué avec succès (nouveau bilan)',
                'data' => $bilan,
            ], 201);

        } else {
            // Si l'utilisateur existe et la date de création (created_at) est aujourd'hui
            // Vérifier le type d'analyse à enregistrer
            $type = $request->input('type');

            if ($type === "IMC") {
                // Modifier uniquement la valeur de taille et poids
                $verification->update([
                    'taille' => $request->input('taille'),
                    'poids' => $request->input('poids'),
                ]);
            } else if ($type === "Tension") {
                // Modifier uniquement la valeur de systolique et diastolique
                $verification->update([
                    'systolique' => $request->input('systolique'),
                    'diastolique' => $request->input('diastolique'),
                ]);
            } else if ($type === "Temperature") {
                // Modifier uniquement la valeur de valeurTemp
                $verification->update([
                    'valeurTemp' => $request->input('valeurTemp'),
                ]);
            } else if ($type === "Glycemie") {
                // Modifier uniquement la valeur de condition, valeurGly et unite
                $verification->update([
                    'condition' => $request->input('condition'),
                    'valeurGly' => $request->input('valeurGly'),
                    'unite' => $request->input('unite'),
                ]);
            }

            return response()->json([
                'message' => 'Mise à jour effectuée avec succès',
                'data' => $verification,
            ], 200);
        }
    }


    public function BilanMy() {
        $userEmail = auth()->user()->email;
        var_dump($userEmail);

        $bilan = AnalyseData::where('email', trim($userEmail))->orderBy('created_at', 'desc')->first();

        // Variables pour les informations de santé
        $poidsInfo = $bilan->poids > 0 ? "Vous pesez $bilan->poids kg" : "";
        $tailleInfo = $bilan->taille > 0 ? "avec une taille de $bilan->taille cm" : "";
        $IMC = $bilan->poids > 0 && $bilan->taille > 0 ? "$poidsInfo $tailleInfo" : "";

        $tension = "";
        if ($bilan->systolique > 0 && $bilan->diastolique > 0) {
            // Vérification de la tension
            $tensionAnormale = false;
            $tensionValue = "$bilan->systolique  / $bilan->diastolique";

            if ($bilan->systolique < 109 || $bilan->systolique > 119 || $bilan->diastolique < 66 || $bilan->diastolique > 79) {
                $tensionAnormale = true;
                $conseil="Votre tension artérielle est trop basse";
            }

            $tension = "Votre tension artérielle est de $tensionValue";

            if ($tensionAnormale) {
                $tension = "Votre tension artérielle est de $tensionValue, Conseil : $conseil";
            }
        }

        $glycemie = $bilan->valeurGly > 0 ? "Votre glycémie est de $bilan->valeurGly" : "";
        $temperature = $bilan->valeurTemp > 0 ? "Votre température corporelle est de $bilan->valeurTemp °C" : "";

        return response()->json([
            'message' => "Les analyses de l'utilisateur connecté",
            'data' => $bilan,
            'IMC' => $IMC,
            'tension' => $tension,
            'temperature' => $temperature,
            'glycemie' => $glycemie,
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
