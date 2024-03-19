<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AnalyseData;
use App\Models\RapportData;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BilanPersonnelControllers extends Controller
{
    public function BilanPersonnel(){
        // Email de l'utilisateur connecté
        $userEmail = auth()->user()->email;
        // son premier bilan de santé
        $bilan = AnalyseData::where('email', trim($userEmail))->orderBy('created_at', 'desc')->first();
        // ~~~~~~~~~~~~~~~~~~~~~~~~~~ VARIABLE DE CALCULE ~~~~~~~~~~~~~~~~~~~~~~~~~~~
        $tension = "";
        $MessageIMC = "";
        $MassageTs = "";
        $MessageTemp = "";
        $MessageGly = "";

        $ConseilIMC ="";
        $ConseilTs ="";
        $ConseilTemp ="";
        $ConseilGly ="";
        // ~~~~~~~~~~~~~~~~~~~~~~~~~~ VARIABLE DE CALCULE ~~~~~~~~~~~~~~~~~~~~~~~~~~~
/**
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~+
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~+
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ MESSAGE RAPPORT ADMIN ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~+
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~+
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~+
 */

        /**
         * ~~~~~~~~~~~~~~~~~~ OPERATION SUR INFORMATION DE SANTE ~~~~~~~~~~~~~~~~~~~~
         */
        // Variables pour les informations de santé
        $poidsInfo = $bilan->poids > 0 ? "Vous pesez $bilan->poids kg," : "";
        $tailleInfo = $bilan->taille > 0 ? "avec une taille de " . number_format($bilan->taille, 2) . " cm" : "";
        // var_dump($tailleInfo);
        $IMC = $bilan->poids > 0 && $bilan->taille > 0 ? "$poidsInfo $tailleInfo" : "";
        // Vérifions les informations de la tension artérielle
        if ($bilan->systolique > 0 && $bilan->diastolique > 0) {
            // Vérification de la tension
            $tensionValue = "$bilan->systolique/$bilan->diastolique";
            $tension = "Votre tension artérielle est de $tensionValue";
        }
        // Information sur la glycemie de l'utilisateur
        $glycemie = $bilan->valeurGly > 0 ? "Votre glycémie est de $bilan->valeurGly $bilan->unite" : "";
        // Information sur la température corporelle de l'utilisateur
        $temperature = $bilan->valeurTemp > 0 ? "Votre température corporelle est de $bilan->valeurTemp °C" : "";

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ RAPPORT  DU SPECIALISTE ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
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

/**
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~+
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~+
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ MESSAGE RAPPORT ADMIN FIN~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~+
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~+
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~+
 */

 /**
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~+
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~+
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ MESSAGE BILAN TOTAL ADMIN START~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~+
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~+
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~+
 */

    //~~~~~~~~~~~~~~~~~~~~~~ IMC ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    //$valeurIMC =  $bilan->poids / ($bilan->taille * $bilan->taille );
    $normalIMC = false;

    // if (is_numeric($bilan->poids) && is_numeric($bilan->taille)) {
    //     $valeurIMC = $bilan->poids / ($bilan->taille * $bilan->taille);

    //     if( $valeurIMC >= 18.5 && $valeurIMC <= 24.9){
    //         $MessageIMC = "Vous avez un poids normal.";
    //         $normalIMC = true;
    //     }elseif($valeurIMC < 18.5){
    //         $MessageIMC = "Vous etes en sous poids.";
    //         $ConseilIMC ="Conseil pour votre IMC : Essayez de prendre du poids de façon saine, en augmentant votre apport calorique et en pratiquant une activité physique adaptée. Consultez un médecin ou un nutritionniste si nécessaire.";
    //     }elseif($valeurIMC >= 40){
    //         $MessageIMC = "Vous souffrez d'obésité morbide.";
    //         $ConseilIMC ="Conseil pour votre IMC : Suivez un régime alimentaire très hypocalorique, équilibré et varié, sous la supervision d’un médecin ou d’un nutritionniste. Augmentez votre dépense énergétique, en faisant de l’exercice physique adapté à votre condition physique, au moins 60 minutes par jour, 5 jours par semaine. Bénéficiez d’une prise en charge médicale, psychologique et chirurgicale si nécessaire.";
    //     }elseif($valeurIMC >= 25 && $valeurIMC <= 29.9){
    //         $MessageIMC ="Vous êtes en surpoids.";
    //         $ConseilIMC ="Conseil pour votre IMC : Faites attention à votre alimentation, en réduisant les aliments gras, sucrés et salés, et en privilégiant les fruits, les légumes, les céréales complètes et les protéines maigres. Pratiquez une activité physique modérée, au moins 30 minutes par jour, 5 jours par semaine. Consultez un médecin ou un nutritionniste si nécessaire.";
    //     }elseif($valeurIMC >= 30 && $valeurIMC <= 34.9){
    //         $MessageIMC = "Vous souffrez d'obésité modérée.";
    //         $ConseilIMC ="Conseil pour votre IMC : Suivez un régime alimentaire hypocalorique, équilibré et varié, sous la supervision d’un médecin ou d’un nutritionniste. Augmentez votre dépense énergétique, en faisant de l’exercice physique adapté à votre condition physique, au moins 45 minutes par jour, 5 jours par semaine.";
    //     }elseif($valeurIMC >= 35 && $valeurIMC <= 39.9){
    //         $MessageIMC ="Vous souffrez d'obésité sévère.";
    //         $ConseilIMC ="Conseil pour votre IMC : Suivez un régime alimentaire très hypocalorique, équilibré et varié, sous la supervision d’un médecin ou d’un nutritionniste. Augmentez votre dépense énergétique, en faisant de l’exercice physique adapté à votre condition physique, au moins 60 minutes par jour, 5 jours par semaine. Envisagez une prise en charge médicale, psychologique et chirurgicale si nécessaire.";
    //     }
    //     // Reste du code pour l'interprétation de l'IMC...
    // } else {

        $valeurIMC = $bilan->poids / ($bilan->taille * $bilan->taille);
        var_dump($valeurIMC);
        // var_dump($valeurIMC);
        if( ($valeurIMC >= 18.5 && $valeurIMC <= 24.9) || ($valeurIMC >= 18.5 && $valeurIMC < 25) ){
            $MessageIMC = "Vous avez un poids normal.";
            $normalIMC = true;
        }elseif($valeurIMC < 18.5){
            $MessageIMC = "Vous etes en sous poids.";
            $ConseilIMC ="Conseil pour votre IMC : Essayez de prendre du poids de façon saine, en augmentant votre apport calorique et en pratiquant une activité physique adaptée. Consultez un médecin ou un nutritionniste si nécessaire.";
        }elseif($valeurIMC >= 40){
            $MessageIMC = "Vous souffrez d'obésité morbide.";
            $ConseilIMC ="Conseil pour votre IMC : Suivez un régime alimentaire très hypocalorique, équilibré et varié, sous la supervision d’un médecin ou d’un nutritionniste. Augmentez votre dépense énergétique, en faisant de l’exercice physique adapté à votre condition physique, au moins 60 minutes par jour, 5 jours par semaine. Bénéficiez d’une prise en charge médicale, psychologique et chirurgicale si nécessaire.";
        }elseif(($valeurIMC >= 25 && $valeurIMC <= 29.9) || ($valeurIMC >= 25 && $valeurIMC < 30)){
            $MessageIMC ="Vous êtes en surpoids.";
            $ConseilIMC ="Conseil pour votre IMC : Faites attention à votre alimentation, en réduisant les aliments gras, sucrés et salés, et en privilégiant les fruits, les légumes, les céréales complètes et les protéines maigres. Pratiquez une activité physique modérée, au moins 30 minutes par jour, 5 jours par semaine. Consultez un médecin ou un nutritionniste si nécessaire.";
        }elseif( ($valeurIMC >= 30 && $valeurIMC <= 34.9) || ($valeurIMC >= 30 && $valeurIMC < 35) ){
            $MessageIMC = "Vous souffrez d'obésité modérée.";
            $ConseilIMC ="Conseil pour votre IMC : Suivez un régime alimentaire hypocalorique, équilibré et varié, sous la supervision d’un médecin ou d’un nutritionniste. Augmentez votre dépense énergétique, en faisant de l’exercice physique adapté à votre condition physique, au moins 45 minutes par jour, 5 jours par semaine.";
        }elseif( ($valeurIMC >= 35 && $valeurIMC <= 39.9) || ($valeurIMC >= 35 && $valeurIMC < 40)){
            $MessageIMC ="Vous souffrez d'obésité sévère.";
            $ConseilIMC ="Conseil pour votre IMC : Suivez un régime alimentaire très hypocalorique, équilibré et varié, sous la supervision d’un médecin ou d’un nutritionniste. Augmentez votre dépense énergétique, en faisant de l’exercice physique adapté à votre condition physique, au moins 60 minutes par jour, 5 jours par semaine. Envisagez une prise en charge médicale, psychologique et chirurgicale si nécessaire.";
        }
    //}

    //~~~~~~~~~~~~~~~~~~~~~~ TENSION ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    $normalTs = false;
    if ($bilan->systolique > 0 && $bilan->diastolique > 0) {
        if (($bilan->systolique <= 108 && $bilan->systolique >= 40) && $bilan->diastolique >= 30) {
            $MessageTs = "Votre tension artérielle est trop basse.";
            $ConseilTs = "Conseil pour votre Tension : Buvez suffisamment d’eau, mangez des aliments salés, portez des bas de contention, levez-vous lentement, fractionnez vos repas, consultez un médecin si nécessaire.";
        }elseif(($bilan->systolique <= 119 && $bilan->systolique >= 109) && $bilan->diastolique >= 30){
            $MassageTs = "Votre tension artérielle est idéale.";
            $ConseilTs = "Conseil pour votre Tension : Continuez à adopter un mode de vie sain.";
        }elseif(($bilan->systolique <= 129 && $bilan->systolique >= 120) && $bilan->diastolique >= 30){
            $MassageTs = "Votre tension artérielle est normale.";
            $normalTs = true;
        }elseif(($bilan->systolique <= 139 && $bilan->systolique >= 130) && $bilan->diastolique >= 30){
            $MassageTs = 'Votre tension artérielle est à la limite de l’hypertension.';
            $ConseilTs = "Conseil pour votre Tension : Faites attention à votre alimentation, votre activité physique et votre stress.";
        }elseif(($bilan->systolique <= 159 && $bilan->systolique >= 140) && $bilan->diastolique >= 30){
            $MassageTs = "Vous souffrez d’hypertension légère.";
            $ConseilTs = "Conseil pour votre Tension : Consultez votre médecin pour un suivi et un traitement adaptés.";
        }elseif(($bilan->systolique <= 179 && $bilan->systolique >= 160) && $bilan->diastolique >= 30){
            $MassageTs = "Vous souffrez d’hypertension modérée.";
            $ConseilTs = "Conseil pour votre Tension : Consultez votre médecin rapidement pour un traitement efficace.";
        }elseif($bilan->systolique >= 180 && $bilan->diastolique >= 30){
            $MassageTs = "Vous souffrez d’hypertension sévère.";
            $ConseilTs = "Conseil pour votre Tension : Consultez votre médecin en urgence pour éviter des complications graves.";
        }
    }
    //~~~~~~~~~~~~~~~~~~~~~~ TEMPERATURE ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    $normalTemp = false;
     if($bilan->valeurTemp < 36.5){
        $MessageTemp = "Votre température corporelle est Hypothermie.";
        $ConseilTemp ="Conseil pour votre Température : Réchauffez-vous progressivement avec des couvertures, des boissons chaudes, un chauffage, etc. Consultez un médecin en urgence si vous avez des symptômes graves.";
     }elseif($bilan->valeurTemp >= 36.5 && $bilan->valeurTemp <= 37.2){
        $MessageTemp = "Votre température corporelle est Normale.";
        $normalTemp = true;
     }elseif($bilan->valeurTemp >= 37.3 && $bilan->valeurTemp <= 38){
        $MessageTemp = "Votre température corporelle est Subfébrile.";
        $ConseilTemp ="Conseil pour votre Température : Surveillez votre température et vos symptômes. Prenez un antipyrétique (médicament contre la fièvre) si besoin. Consultez un médecin si la fièvre persiste ou s’aggrave.";
     }elseif($bilan->valeurTemp >= 38.1 && $bilan->valeurTemp <= 39){
        $MessageTemp = "Votre température corporelle indique une Fièvre modéreée.";
        $ConseilTemp ="Conseil pour votre Température : Prenez un antipyrétique, buvez beaucoup d’eau, reposez-vous, évitez les efforts physiques. Consultez un médecin si la fièvre dure plus de 3 jours ou si vous avez d’autres symptômes inquiétants.";
     }elseif($bilan->valeurTemp >= 39.1 && $bilan->valeurTemp <= 40){
        $MessageTemp =  "Votre température corporelle indique une Fièvre élevée.";
        $ConseilTemp ="Conseil pour votre Température : Prenez un antipyrétique, buvez beaucoup d’eau, reposez-vous, évitez les efforts physiques. Consultez un médecin rapidement pour identifier la cause de la fièvre et recevoir un traitement adapté.";
     }if($bilan->valeurTemp > 40){
        $MessageTemp = "Votre température corporelle est Hyperpyrexie.";
        $ConseilTemp ="Conseil pour votre Température : Consultez un médecin en urgence, car il s’agit d’une situation potentiellement mortelle.";
     }
     //~~~~~~~~~~~~~~~~~~~~~~ Glycémie ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
     $normalGly = false;
      if($bilan->unite == "g/L"){
        if($bilan->condition == "À jeun"){
            if($bilan->valeurGly < 0.80){
                $MessageGly = "Vous avez une hypoglycémie.";
                $ConseilGly ="Conseil pour votre Glycémie : Prenez 15 g de glucides rapides, comme du jus, du miel, ou du sucre. Vérifiez votre glycémie 15 minutes plus tard. Si elle est toujours basse, répétez l’opération. Une fois votre glycémie remontée, prenez un repas ou une collation.";
            }elseif($bilan->valeurGly >1.20){
                $MessageGly = "Vous avez une hyperglycémie.";
                $ConseilGly ="Conseil pour votre Glycémie : Vérifiez si vous avez des cétones dans les urines ou le sang. Si oui, contactez votre médecin ou votre infirmière. Si non, ajustez votre dose d’insuline ou de médicament, si vous en prenez. Revoyez votre alimentation et votre activité physique.";
            }else{
                $MessageGly = "Votre glycémie est normale.";
                $normalGly = true;
            }
        }elseif($bilan->condition == "2 h après le repas"){
            if($bilan->valeurGly < 0.94){
                $MessageGly = "Vous avez une hypoglycémie.";
                $ConseilGly ="Conseil pour votre Glycémie : Prenez 15 g de glucides rapides, comme du jus, du miel, ou du sucre. Vérifiez votre glycémie 15 minutes plus tard. Si elle est toujours basse, répétez l’opération. Une fois votre glycémie remontée, prenez un repas ou une collation.";
            }elseif($bilan->valeurGly >1.77){
                $MessageGly = "Vous avez une hyperglycémie.";
                $ConseilGly ="Conseil pour votre Glycémie : Vérifiez si vous avez des cétones dans les urines ou le sang. Si oui, contactez votre médecin ou votre infirmière. Si non, ajustez votre dose d’insuline ou de médicament, si vous en prenez. Revoyez votre alimentation et votre activité physique.";
            }else{
                $MessageGly = "Votre glycémie est normale.";
            }
        }elseif($bilan->condition == "Au coucher"){
            if($bilan->valeurGly < 1.05){
                $MessageGly = "Vous avez une hypoglycémie.";
                $ConseilGly ="Conseil pour votre Glycémie : Prenez 15 g de glucides rapides, comme du jus, du miel, ou du sucre. Vérifiez votre glycémie 15 minutes plus tard. Si elle est toujours basse, répétez l’opération. Une fois votre glycémie remontée, prenez un repas ou une collation.";
            }elseif($bilan->valeurGly >1.77){
                $MessageGly = "Vous avez une hyperglycémie.";
                $ConseilGly ="Conseil pour votre Glycémie : Vérifiez si vous avez des cétones dans les urines ou le sang. Si oui, contactez votre médecin ou votre infirmière. Si non, ajustez votre dose d’insuline ou de médicament, si vous en prenez. Revoyez votre alimentation et votre activité physique.";
            }else{
                $MessageGly = "Votre glycémie est normale.";
            }
        }
      }elseif($bilan->unite == "mg/dL"){
        if($bilan->condition == "À jeun"){
            if($bilan->valeurGly <= 120 && $bilan->valeurGly >= 80){
                $MessageGly = "Vous avez une hypoglycémie.";
                $ConseilGly ="Conseil pour votre Glycémie : Prenez 15 g de glucides rapides, comme du jus, du miel, ou du sucre. Vérifiez votre glycémie 15 minutes plus tard. Si elle est toujours basse, répétez l’opération. Une fois votre glycémie remontée, prenez un repas ou une collation.";
            }elseif($bilan->valeurGly >120){
                $MessageGly = "Vous avez une hyperglycémie.";
                $ConseilGly ="Conseil pour votre Glycémie : Vérifiez si vous avez des cétones dans les urines ou le sang. Si oui, contactez votre médecin ou votre infirmière. Si non, ajustez votre dose d’insuline ou de médicament, si vous en prenez. Revoyez votre alimentation et votre activité physique.";
            }else{
                $MessageGly = "Votre glycémie est normale.";
            }
        }elseif($bilan->condition == "2 h après le repas"){
            if($bilan->valeurGly <= 177 && $bilan->valeurGly >= 94){
                $MessageGly = "Vous avez une hypoglycémie.";
                $ConseilGly ="Conseil pour votre Glycémie : Prenez 15 g de glucides rapides, comme du jus, du miel, ou du sucre. Vérifiez votre glycémie 15 minutes plus tard. Si elle est toujours basse, répétez l’opération. Une fois votre glycémie remontée, prenez un repas ou une collation.";
            }elseif($bilan->valeurGly >177){
                $MessageGly = "Vous avez une hyperglycémie.";
                $ConseilGly ="Conseil pour votre Glycémie : Vérifiez si vous avez des cétones dans les urines ou le sang. Si oui, contactez votre médecin ou votre infirmière. Si non, ajustez votre dose d’insuline ou de médicament, si vous en prenez. Revoyez votre alimentation et votre activité physique.";
            }else{
                $MessageGly = "Votre glycémie est normale.";
            }
        }elseif($bilan->condition == "Au coucher"){
             if($bilan->valeurGly <= 177 && $bilan->valeurGly >= 105){
                $MessageGly = "Vous avez une hypoglycémie.";
                $ConseilGly ="Conseil pour votre Glycémie : Prenez 15 g de glucides rapides, comme du jus, du miel, ou du sucre. Vérifiez votre glycémie 15 minutes plus tard. Si elle est toujours basse, répétez l’opération. Une fois votre glycémie remontée, prenez un repas ou une collation.";
            }elseif($bilan->valeurGly >177){
                $ConseilGly ="Conseil pour votre Glycémie : Vérifiez si vous avez des cétones dans les urines ou le sang. Si oui, contactez votre médecin ou votre infirmière. Si non, ajustez votre dose d’insuline ou de médicament, si vous en prenez. Revoyez votre alimentation et votre activité physique.";
                $ConseilGly ="Conseil pour votre Glycémie : ";
            }else{
                $MessageGly = "Votre glycémie est normale.";
            }
        }
      }elseif($bilan->unite == "mmol/L"){
        if($bilan->condition == "À jeun"){
            if($bilan->valeurGly <= 6.67 && $bilan->valeurGly >= 4.44){
                $MessageGly = "Vous avez une hypoglycémie.";
                $ConseilGly ="Conseil pour votre Glycémie : Prenez 15 g de glucides rapides, comme du jus, du miel, ou du sucre. Vérifiez votre glycémie 15 minutes plus tard. Si elle est toujours basse, répétez l’opération. Une fois votre glycémie remontée, prenez un repas ou une collation.";
            }elseif($bilan->valeurGly >6.67){
                $MessageGly = "Vous avez une hyperglycémie.";
                $ConseilGly ="Conseil pour votre Glycémie : Vérifiez si vous avez des cétones dans les urines ou le sang. Si oui, contactez votre médecin ou votre infirmière. Si non, ajustez votre dose d’insuline ou de médicament, si vous en prenez. Revoyez votre alimentation et votre activité physique.";
            }else{
                $MessageGly = "Votre glycémie est normale.";
            }
        }elseif($bilan->condition == "2 h après le repas"){
            if($bilan->valeurGly <= 9.83 && $bilan->valeurGly >= 5.22){
                $MessageGly = "Vous avez une hypoglycémie.";
                $ConseilGly ="Conseil pour votre Glycémie : Prenez 15 g de glucides rapides, comme du jus, du miel, ou du sucre. Vérifiez votre glycémie 15 minutes plus tard. Si elle est toujours basse, répétez l’opération. Une fois votre glycémie remontée, prenez un repas ou une collation.";
            }elseif($bilan->valeurGly >9.83){
                $MessageGly = "Vous avez une hyperglycémie.";
                $ConseilGly ="Conseil pour votre Glycémie : Vérifiez si vous avez des cétones dans les urines ou le sang. Si oui, contactez votre médecin ou votre infirmière. Si non, ajustez votre dose d’insuline ou de médicament, si vous en prenez. Revoyez votre alimentation et votre activité physique.";
            }else{
                $MessageGly = "Votre glycémie est normale.";
            }
        }elseif($bilan->condition == "Au coucher"){
            if($bilan->valeurGly <= 9.83 && $bilan->valeurGly >= 5.83){
                $MessageGly = "Vous avez une hypoglycémie.";
                $ConseilGly ="Conseil pour votre Glycémie : Prenez 15 g de glucides rapides, comme du jus, du miel, ou du sucre. Vérifiez votre glycémie 15 minutes plus tard. Si elle est toujours basse, répétez l’opération. Une fois votre glycémie remontée, prenez un repas ou une collation.";
            }elseif($bilan->valeurGly >9.83){
                $MessageGly = "Vous avez une hyperglycémie.";
                $ConseilGly ="Conseil pour votre Glycémie : Vérifiez si vous avez des cétones dans les urines ou le sang. Si oui, contactez votre médecin ou votre infirmière. Si non, ajustez votre dose d’insuline ou de médicament, si vous en prenez. Revoyez votre alimentation et votre activité physique.";
            }else{
                $MessageGly = "Votre glycémie est normale.";
            }
        }
      }

    // Organisation du Message de Conclusion
    $MessageConclusion = "Au Total : ";
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
    if ($MessageConclusion === "Au Total : ") {
        $MessageConclusion = "Aucune donnée disponible.";
    } else {
        // Supprimer le dernier point et l'espace s'il y en a un à la fin du message
        $MessageConclusion = rtrim($MessageConclusion, '. ');
    }


    // Organistion du conseil pour les valeur normales et anormale
    $conseil = "";

    if($normalIMC && $normalTs && $normalTemp && $normalGly){
        $conseil = "Tout à l'air de bien aller. Continuer à maintenir une bonne hygiène de vie. Toutefois, faites-vous consulter si vous remarquez des symptômes inhabituels";
    }else{
        if ($ConseilIMC != "") {
            $conseil .= $ConseilIMC . ". ";
        }

        if ($ConseilTs != "") {
            $conseil .= $ConseilTs . ". ";
        }

        if ($ConseilTemp != "") {
            $conseil .= $ConseilTemp . ". ";
        }

        if ($ConseilGly != "") {
            $conseil .= $ConseilGly;
        }
         // Vérifier si le message est vide, s'il l'est, vous pouvez ajouter un message par défaut
        if ($conseil === "") {
            $conseil = "Aucune donnée disponible.";
        } else {
            // Supprimer le dernier point et l'espace s'il y en a un à la fin du message
            $conseil = rtrim($conseil, '. ');
        }
    }
 /**
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~+
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~+
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ MESSAGE BILAN TOTAL ADMIN END ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~+
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~+
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~+
 */

  /**
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~+
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~+
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ MESSAGE AFICHARGE DES DONNÉES START ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~+
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~+
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~+
 */

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
                'conseil' => $MessageConclusion,
                'total' => $conseil,
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
                'conseil' => $MessageConclusion,
                'total' => $conseil,
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
            'conseil' => $MessageConclusion,
            'total' => $conseil,
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
            'total' => $rapport->total,
            'created_at' => Carbon::parse($rapport->created_at)->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::parse($rapport->updated_at)->format('Y-m-d H:i:s'),
        ];
    });

  /**
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~+
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~+
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ MESSAGE AFICHARGE DES DONNÉES END ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~+
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~+
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~+
 */
        return response()->json([
            'message' => "Les analyses de l'utilisateur connecté",
            'data' => $rapports_formatés,
        ], 200);

    }

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
        //
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
