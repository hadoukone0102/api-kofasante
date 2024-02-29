<?php

namespace App\Http\Controllers;

use App\Models\AnalyseData;
use App\Models\RapportData;
use Carbon\Carbon;
use Illuminate\Http\Request;

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

        $bilan = AnalyseData::where('email', trim($userEmail))->orderBy('created_at', 'desc')->first();
        // Variables pour les informations de santé
        $poidsInfo = $bilan->poids > 0 ? "Vous pesez $bilan->poids kg" : "";
        $tailleInfo = $bilan->taille > 0 ? "avec une taille de $bilan->taille cm" : "";
        $IMC = $bilan->poids > 0 && $bilan->taille > 0 ? "$poidsInfo $tailleInfo" : "";

        $tension = "";
        $conseil = "";
        $conseilAdmin ="Tout à l'air de bien aller. Continuer à maintenir une bonne hygiène de vie. Toutefois, faites-vous consulter si vous remarquez des symptômes inhabituels";
        if ($bilan->systolique > 0 && $bilan->diastolique > 0) {
            // Vérification de la tension
            $tensionAnormale = false;
            $tensionValue = "$bilan->systolique/$bilan->diastolique";

            if ($bilan->systolique < 109 || $bilan->systolique > 119 || $bilan->diastolique < 66 || $bilan->diastolique > 79) {
                $tensionAnormale = true;
                $conseil = "Votre tension artérielle est trop basse";
            }

            $tension = "Votre tension artérielle est de $tensionValue";

            if ($tensionAnormale) {
                $tension = "Votre tension artérielle est de $tensionValue ";
                $conseilAdmin ="$conseil. Buvez suffisamment d’eau, mangez des aliments salés, portez des bas de contention, levez-vous lentement, fractionnez vos repas.
                Toutefois, faites-vous consulter si vous remarquez des symptômes inhabituels";
            }
        }

        $glycemie = $bilan->valeurGly > 0 ? "Votre glycémie est de $bilan->valeurGly $bilan->unite" : "";
        $temperature = $bilan->valeurTemp > 0 ? "Votre température corporelle est de $bilan->valeurTemp °C" : "";

        // le rapport du spécialiste
        $messageAdmin = "";

        // Ajouter uniquement les parties non vides au message
        if ($IMC != "") {
            $messageAdmin .= $IMC . ". ";
        }

        if ($tension != "") {
            $messageAdmin .= $tension . ". ";
        }

        if ($glycemie != "") {
            $messageAdmin .= $glycemie . ". ";
        }

        if ($temperature != "") {
            $messageAdmin .= $temperature;
        }

        // Vérifier si le message est vide, s'il l'est, vous pouvez ajouter un message par défaut
        if ($messageAdmin === "") {
            $messageAdmin = "Aucune donnée disponible.";
        } else {
            // Supprimer le dernier point et l'espace s'il y en a un à la fin du message
            $messageAdmin = rtrim($messageAdmin, '. ');
        }

     // ...

        $rapport_du_medecin = RapportData::where('email', $userEmail)->orderBy('created_at', 'desc')->take(5)->get();
        if ($rapport_du_medecin->count() > 0 || $rapport_du_medecin->count() == 0) {
            // L'utilisateur existe déjà, mettre à jour le rapport existant
            $rapport_medec = RapportData::create([
                'email' => auth()->user()->email,
                'nom' => auth()->user()->nom,
                'prenom' => auth()->user()->prenom,
                'contact' => auth()->user()->contact,
                'age' => auth()->user()->age,
                'sexe' => auth()->user()->sexe,
                'desc' => $messageAdmin,
                'conseil' => $conseilAdmin,
            ]);

        }
        // Formater la date pour chaque rapport médical
        $rapports_formatés = $rapport_du_medecin->map(function ($item) {
            return [
                'id' => $item->id,
                'email' => $item->email,
                'nom' => $item->nom,
                'prenom' => $item->prenom,
                'contact' => $item->contact,
                'age' => $item->age,
                'sexe' => $item->sexe,
                'desc' => $item->desc,
                'conseil' => $item->conseil,
                'created_at' => Carbon::parse($item->created_at)->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::parse($item->updated_at)->format('Y-m-d H:i:s'),
                ];
        });

        return response()->json([
            'message' => "Les analyses de l'utilisateur connecté",
            'data' => $rapports_formatés,
            // 'specialiste' => $messageAdmin,
            // 'conseil' => $conseilAdmin,
        ], 200);

    }


    /**
     * Display the specified resource.
     */
    public function BilanRapports()
    {
        //
        $bilan = RapportData::orderBy('created_at', 'desc')->get();

        return response()->json([
            'message' => 'Liste complète récupérée avec succès',
            'data' => $bilan,
        ], 200);
    }

    public function BilanRapportsDel(string $id)
    {
        $lecture = RapportData::findOrFail($id);
        $lecture->delete();
        return response()->json([
            'message' =>"lecture supprimer avec succès",
            null
        ], 204);
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
