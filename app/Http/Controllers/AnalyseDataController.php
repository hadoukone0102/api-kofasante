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

            if (($bilan->systolique <= 108 && $bilan->systolique >= 40) || ($bilan->diastolique <= 65 && $bilan->diastolique >= 30)) {
                $tensionAnormale = true;
                $conseil = "Votre tension artérielle est trop basse";
            }elseif($bilan->systolique <= 39 || $bilan->diastolique <= 29 ){
                $tensionAnormale = true;
                $conseil = "Attention, les valeurs que vous avez saisies sont incorrectes($tensionValue)";
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

        // $MessageIMC = "";
        // $MassageTs = "";
        // $MessageTemp = "";
        // $MessageGly = "";

        /**
         * ~~~~~~~~~~~~~~~~~~~~~~ IMC ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
         * Opération pour total des Message d'interpretation d'IMC
         * 1-> valeurIMC =  $bilan->poids / ($bilan->taille * $bilan->taille )
         * si  18.5<= valeurIMC <= 24.9 ALORS MessageIMC = " Vous avez un poids normal. "
         * si valeurIMC < 18.5 ALORS MessageIMC = "Vous etes en sous poids. Conseil :Essayez de prendre du poids de façon saine, en augmentant votre apport calorique et en pratiquant une activité physique adaptée. Consultez un médecin ou un nutritionniste si nécessaire."
         * si valeurIMC >= 40 ALORS MessageIMC = "Vous souffrez d'obésité morbide. "
         * si  25<= valeurIMC <= 29.9  ALORS MessageIMC =" Vous êtes en surpoids "
         * si  30<= valeurIMC <= 34.9  ALORS MessageIMC = " Vous souffrez d'obésité modérée "
         * si  35<= valeurIMC <= 39.9  ALORS MessageIMC =" Vous souffrez d'obésité sévère
         *
         *~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
         *
         *~~~~~~~~~~~~~~~~~~~~~~ TENSION ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
         * systolic = > $bilan->systolique
         * diastolic = > $bilan->diastolique
         * Opération pour total des Message d'interpretation d'IMC
         * si (systolic <= 39 || diastolic <= 29) ALORS  MassageTs = 'Valeur incorrecte.';
         * si ((systolic >= 40 && systolic <= 108) && (diastolic >= 30)) ALORS MessageTs = "Votre tension artérielle est trop basse."
         * si ((systolic >= 109 && systolic <= 119) && (diastolic >= 30)) ALORS MassageTs = 'Votre tension artérielle est idéale.'
         * si  ((systolic >= 130 && systolic <= 139) && (diastolic >= 30)) ALORS MessageTs = 'Votre tension artérielle est à la limite de l’hypertension.'
         * si ((systolic >= 140 && systolic <= 159) && (diastolic >= 30)) ALORS MessageTs = "Vous souffrez d’hypertension légère."
         * si  ((systolic >= 160 && systolic <= 179) && (diastolic >= 30)) ALORS MessageTs = " Vous souffrez d’hypertension modérée."
         * si (systolic >= 180 && diastolic >= 30) AlORS MessageTs = "Vous souffrez d’hypertension sévère."
         *
         **~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
         *
         **~~~~~~~~~~~~~~~~~~~~~~ TEMPERATURE ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
         * Opération pour total des Message d'interpretation de Température
         * si $bilan->valeurTemp < 36,5 ALORS MessageTemp = "Votre température corporelle est Hypothermie."
         * si $bilan->valeurTemp > 40 ALORS MessageTemp = "Votre température corporelle est Hyperpyrexie."
         * si 36.5 <= $bilan->valeurTemp <= 37.2 ALORS MessageTemp = "Votre température corporelle est Normal."
         * si  37.3 <= $bilan->valeurTemp <= 38  ALORS MessageTemp = "Votre température corporelle est Subfébrile."
         * si  38.1 <= $bilan->valeurTemp <= 39  ALORS MessageTemp = "Votre température corporelle indique une Fièvre modéreée."
         * si 39.1 <= $bilan->valeurTemp <= 40  ALORS MessageTemp =  "Votre température corporelle indique une Fièvre élevée."
         *
         **~~~~~~~~~~~~~~~~~~~~~~ Glycémie ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
         * Opération pour total des Message d'interpretation de la Glycémie
         * si $bilan->unite = "g/L"
         *
         *  si $bilan->condition = "À jeun"
         *      si $bilan->valeurGly < 0.80 ALORS MessageGly = " Vous avez une hypoglycémie. "
         *      si $bilan->valeurGly >1.20 ALORS MessageGly = " Vous avez une hyperglycémie. "
         *      sinon MessageGly = " Votre glycémie est normale. "
         *
         *  si $bilan->condition = "2 h après le repas"
         *      si $bilan->valeurGly < 0.94 ALORS MessageGly = " Vous avez une hypoglycémie. "
         *      si $bilan->valeurGly >1.77 ALORS MessageGly = " Vous avez une hyperglycémie. "
         *      sinon MessageGly = " Votre glycémie est normale. "
         *
         *  si $bilan->condition = "Au coucher"
         *      si $bilan->valeurGly < 1.05 ALORS MessageGly = " Vous avez une hypoglycémie. "
         *      si $bilan->valeurGly > 1.77 ALORS MessageGly = " Vous avez une hyperglycémie. "
         *      sinon MessageGly = " Votre glycémie est normale. "
         *
         * * si $bilan->unite = "mg/dL"
         *
         *  si $bilan->condition = "À jeun"
         *      si 80 <= $bilan->valeurGly < 120 ALORS MessageGly = " Vous avez une hypoglycémie. "
         *      si $bilan->valeurGly >120 ALORS MessageGly = " Vous avez une hyperglycémie. "
         *      sinon MessageGly = " Votre glycémie est normale. "
         *
         *  si $bilan->condition = "2 h après le repas"
         *      si 94 <= $bilan->valeurGly < 177 ALORS MessageGly = " Vous avez une hypoglycémie. "
         *      si $bilan->valeurGly >177 ALORS MessageGly = " Vous avez une hyperglycémie. "
         *      sinon MessageGly = " Votre glycémie est normale. "
         *
         *  si $bilan->condition = "Au coucher"
         *      si 105 <= $bilan->valeurGly <= 177 ALORS MessageGly = " Vous avez une hypoglycémie. "
         *      si $bilan->valeurGly > 177 ALORS MessageGly = " Vous avez une hyperglycémie. "
         *      sinon MessageGly = " Votre glycémie est normale. "
         *
         ** si $bilan->unite = "mmol/L"
         *
         *  si $bilan->condition = "À jeun"
         *      si 4.44 <= $bilan->valeurGly < 6.67 ALORS MessageGly = " Vous avez une hypoglycémie. "
         *      si $bilan->valeurGly >6.67 ALORS MessageGly = " Vous avez une hyperglycémie. "
         *      sinon MessageGly = " Votre glycémie est normale. "
         *
         *  si $bilan->condition = "2 h après le repas"
         *      si 5.22 <= $bilan->valeurGly < 9.83 ALORS MessageGly = " Vous avez une hypoglycémie. "
         *      si $bilan->valeurGly >9.83 ALORS MessageGly = " Vous avez une hyperglycémie. "
         *      sinon MessageGly = " Votre glycémie est normale. "
         *
         *  si $bilan->condition = "Au coucher"
         *      si 5.83 <= $bilan->valeurGly <= 9.83 ALORS MessageGly = " Vous avez une hypoglycémie. "
         *      si $bilan->valeurGly > 177 ALORS MessageGly = " Vous avez une hyperglycémie. "
         *      sinon MessageGly = " Votre glycémie est normale. "
         *
         *
         * ~~~~~~~~~~~~~~~~~~~~~~~~~~~ Message de Conseil ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
         * si tout est normal c'est à dire
         *      -> MessageIMC = "Vous avez un poids normal."
         *      -> MessageGly = "Votre glycémie est normale."
         *      -> MessageTemp = "Votre température corporelle est Normal."
         *      -> MassageTs = "Votre tension artérielle est idéale."
         * ALORS
         *       $conseilAdmin = "
         *      Au Total : $MessageConclusion \n
         *      Tout à l'air de bien aller. Continuer à maintenir une bonne hygiène de vie. Toutefois,
         *      faites-vous consulter si vous remarquez des symptômes inhabituels
         *  ";
         *
         *
         *
         */

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

        // Organisation du Message de Conclusion
        $MessageConclusion = "";

         $MessageIMC = "";
         $MassageTs = "";
         $MessageTemp = "";
         $MessageGly = "";

        if ($MessageIMC != "") {
            $MessageConclusion .= $MessageIMC . ". ";
        }

        if ($MassageTs != "") {
            $MessageConclusion .= $MassageTs . ". ";
        }

        if ($MessageTemp != "") {
            $MessageConclusion .= $MessageTemp . ". ";
        }

        if ($MessageGly != "") {
            $MessageConclusion .= $MessageGly;
        }
        // Vérifier si le message est vide, s'il l'est, vous pouvez ajouter un message par défaut
        if ($MessageConclusion === "") {
            $MessageConclusion = "Aucune donnée disponible.";
        } else {
            // Supprimer le dernier point et l'espace s'il y en a un à la fin du message
            $MessageConclusion = rtrim($MessageConclusion, '. ');
        }

        //$rapport_du_medecin = RapportData::where('email', $userEmail)->orderBy('created_at', 'desc')->take(2)->get();

        // Récupérer les deux rapports les plus récents pour aujourd'hui et la date précédente
        $today = now()->format('Y-m-d');
        $yesterday = now()->subDay()->format('Y-m-d');

        $rapport_du_medecin = RapportData::where('email', $userEmail)
            ->whereDate('created_at', '>=', $yesterday)
            ->orderBy('created_at', 'desc')
            ->take(2)
            ->get();
        // Vérifions si le nombre d'utilisateur selectionner est supprieur à 0
        if ($rapport_du_medecin->count() > 0) {
            // Mise à jour du rapport existant (le premier rapport trouvé)
            $rapport_medec = $rapport_du_medecin[0];

            // Vérifions si $rapport_medec->created_at est égale à la date d'aujoud'hui
            if($rapport_medec->created_at->toDateString() == $today){
                    $rapport_medec->update([
                    'email' => auth()->user()->email,
                    'nom' => auth()->user()->nom,
                    'prenom' => auth()->user()->prenom,
                    'contact' => auth()->user()->contact,
                    'age' => auth()->user()->age,
                    'sexe' => auth()->user()->sexe,
                    'desc' => $messageAdmin,
                    'conseil' => $conseilAdmin,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }else{
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
            // Le nombre d'utilisateur selectionner est égale à zero
        } else {
            // L'utilisateur n'a pas de rapport aujourd'hui, créer un nouveau rapport
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

         // Formater la date pour chaque rapport médicalLes analyses de l'utilisateur connecté
        // Retourner les données sous forme de tableau associatif
        $rapports_formatés = $rapport_du_medecin->map(function ($rapport) {
            return [
                'id' => $rapport->id,
                'email' => $rapport->email,
                'nom' => $rapport->nom,
                'prenom' => $rapport->prenom,
                'contact' => $rapport->contact,
                'age' => $rapport->age,
                'sexe' => $rapport->sexe,
                'desc' => $rapport->desc,
                'conseil' => $rapport->conseil,
                'created_at' => Carbon::parse($rapport->created_at)->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::parse($rapport->updated_at)->format('Y-m-d H:i:s'),
            ];
        });

        return response()->json([
            'message' => "Les analyses de l'utilisateur connecté",
            'data' => $rapports_formatés,
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
