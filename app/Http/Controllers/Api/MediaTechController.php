<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\abonnements;
use App\Models\administrateur;
use App\Models\Categorie;
use App\Models\mediaTech;
use App\Models\Service\documents;
use App\Models\Service\medecine_en_lignes;
use App\Models\Service\renseigner;
use App\Models\Service\visites;
use App\Models\Utilisateur;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class MediaTechController extends Controller
{
    /**
     * Api de Creation des asctuce et conseils
     *
    */
    public function mediaCreate(Request $request){
        $request->validate([
            'titre'=>'required|string|max:255',
            'categorie'=>'required',
            'media'=>'max:102400',
            'desc'=>'required'
        ],[
            'titre.required'=>'le titre est requis',
            'categorie.required'=>'la categorie est requise',
            'desc.required'=>'la description est requise'
        ]);

        $filename ="";
        if($request->hasFile('media')){
            $filename = $request->file('media')->store('medias','public');
        }else{
            $filename = null;
        }

        $media = new mediaTech();
        $media->id_admin = 1;
        $media->titre = $request->input('titre');
        $media->categorie = $request->input('categorie');
        $media->media = $filename;
        $media->desc = $request->input('desc');
        $media->type = $request->input('type');
        $result = $media->save();
        if($result){
            return response()->json([
                'status'=>1,
                'message' => 'publication Enregistré',
                'pub'=>$result
            ], 200);
        }else{
            return response()->json([
                'status'=>0,
                'message' => 'Impossible d\' Enregistré'
            ], 401);
        }
    }
    /**
     * api pour afficher liste de tous faire le bilan
     */

     public function MediaBilan() {
        $pub = mediaTech::all();
        $allUsers = Utilisateur::all();
        $allAdmin = administrateur::all();
        $services = abonnements::all();
        $servicesDoc = documents::all();
        $serviceRn = renseigner::all();
        $servicesVs = visites::all();
        $serviceMl = medecine_en_lignes::all();

        $nbPublications = $pub->count();
        $nbUsers = $allUsers->count();
        $nbAdmin = $allAdmin->count();
        $nbServices = $services->count();
        $nbservicesDoc = $servicesDoc->count();
        $nbserviceRn = $serviceRn->count();
        $nbservicesVs = $servicesVs->count();
        $nbserviceMl = $serviceMl->count();

        return response()->json([
            'message' => 'Nombre de publications récupéré avec succès',
            'pub' => $nbPublications,
            'user' => $nbUsers,
            'admin'=>$nbAdmin,
            'abonnement'=>$nbServices,
            'document'=>$nbservicesDoc,
            'renseignemnt'=>$nbserviceRn,
            'visite'=>$nbservicesVs,
            'medecine'=>$nbserviceMl,
        ],200);
    }


    /**
     * api pour afficher liste de toute les publications de kofa
     */

     public function mediaLists(){
        $pub = mediaTech::orderBy('created_at', 'desc')->get();

        // Formater les dates avec Carbon
         $formattedPub = $pub->map(function ($item) {
        return [
            'id' => $item->id,
            'id_admin' => $item->id_admin,
            'titre' => $item->titre,
            'categorie' => $item->categorie,
            'media' => $item->media,
            'desc' => $item->desc,
            'type'=>$item->type,
            'created_at' => Carbon::parse($item->created_at)->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::parse($item->updated_at)->format('Y-m-d H:i:s'),
            ];
        });

        return response()->json([
            'status'=>1,
            'message'=>'liste des publications récupérer avec succès',
            'pub'=>$formattedPub
        ], 200);
     }

     public function astucesCate()
     {
         $pub = mediaTech::where('categorie', 'Actuces')->orderBy('created_at', 'desc')->get();

        // Formater les dates avec Carbon
         $formattedPub = $pub->map(function ($item) {
        return [
            'id' => $item->id,
            'id_admin' => $item->id_admin,
            'titre' => $item->titre,
            'categorie' => $item->categorie,
            'media' => $item->media,
            'desc' => $item->desc,
            'type'=>$item->type,
            'created_at' => Carbon::parse($item->created_at)->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::parse($item->updated_at)->format('Y-m-d H:i:s'),
            ];
        });

         return response()->json([
             'message' => "Toutes les astuces de la catégorie 'Actuces' récupérées",
             'data' => $formattedPub
         ]);
     }

     public function conseilsCate()
     {
         $pub = mediaTech::where('categorie', 'accueil')->orderBy('created_at', 'desc')->get();

          // Formater les dates avec Carbon
          $formattedPub = $pub->map(function ($item) {
            return [
                'id' => $item->id,
                'id_admin' => $item->id_admin,
                'titre' => $item->titre,
                'categorie' => $item->categorie,
                'media' => $item->media,
                'desc' => $item->desc,
                'type'=>$item->type,
                'created_at' => Carbon::parse($item->created_at)->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::parse($item->updated_at)->format('Y-m-d H:i:s'),
                ];
            });

         return response()->json([
             'message' => "Toutes les astuces de la catégorie 'Conseils' récupérées",
             'data' => $formattedPub
         ]);
     }

     public function actualiteCate()
     {
         $pub = mediaTech::where('categorie', 'Actualité')->orderBy('created_at', 'desc')->get();


        // Formater les dates avec Carbon
        $formattedPub = $pub->map(function ($item) {
            return [
                'id' => $item->id,
                'id_admin' => $item->id_admin,
                'titre' => $item->titre,
                'categorie' => $item->categorie,
                'media' => $item->media,
                'desc' => $item->desc,
                'type'=>$item->type,
                'created_at' => Carbon::parse($item->created_at)->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::parse($item->updated_at)->format('Y-m-d H:i:s'),
                ];
            });


         return response()->json([
             'message' => "Toutes les astuces de la catégorie 'Actualité' récupérées",
             'data' => $formattedPub
         ]);
     }


    /**
     *
     * api pour supprimer un publication
     *
     */

    public function mediaDelete(Request $request,$id){

        $mediaExist = mediaTech::findOrFail($id);
        $destination = public_path("storage/" . $mediaExist->media);

        if(File::exists($destination)){
            File::delete($destination);
        }

        $result = $mediaExist->delete();

        if($result){
            return response()->json([
                'status' => 1,
                'message' => 'pub supprimée avec succès'
            ], 200);
        } else {
            return response()->json([
                'status' => 0,
                'message' => 'Échec de la suppression'
            ], 404);
        }
    }

    /**
     *
     * Api pour mettre une publication à jours
     *
     */

     public function mediaUpdate(Request $request,$id){

        $media= mediaTech::findOrFail($id);
        $destination = public_path("storage\\".$media->media);
        $filename ="";
        if($request->hasFile('new_media')){
            if(File::exists($destination)){
                File::delete($destination);
            }
            $filename = $request->file('new_media')->store('medias','public');
        }else{
            $filename = $request->media;
        }

        $media->id_admin = auth()->user()->id;
        $media->titre = $request->input('titre');
        $media->categorie = $request->input('categorie');
        $media->media = $filename;
        $media->desc = $request->input('desc');
        $result = $media->save();

        if($result){
            return response()->json([
                'status'=>1,
                'message'=>'image enregistrer avec succès'
            ], 200);
        }else{
            return response()->json([
                'status'=>0,
                'message'=>'échec d\'enregistrement'
            ], 404);
        }
     }

     public function update(Request $request, string $id)
     {
         //
         $media = mediaTech::findOrFail($id);
         $media->update($request->only('desc'));
         return response()->json([
             'message' =>"Mise à jour effectuer avec succès",
             'data'=>$media
         ], 200);
     }

     /**
      * api pour créer une categorie
      */

      public function categoriePost(Request $request){
        $request->validate([
            'nom'=>'required|string|max:100'
        ],[
            'nom.required'=>'le nom de la categorie est requis'
        ]);
        $categorieExiste = categorie::where('nom',$request->nom)->first();
        if($categorieExiste){
            return response()->json([
                'status'=>0,
                'message'=>'Cette categorie Existe déja',
            ], 200);
        }

        $categorie = new Categorie();
        $categorie->nom = $request->input('nom');
        $result = $categorie->save();
        if($result){
            return response()->json([
                'status'=>1,
                'message'=>'categorie créer avec succès',
                'result'=>$result
            ], 200);
        }else{
            return response()->json([
                'status'=>0,
                'message'=>'Erreur lors de la creation',
                'result'=>$result
            ], 401);
        }
      }

      /**
       * Api pour avoir la liste de toutes les categorie de publication
       *
       */

       public function categorieGet(){
            $categorie = Categorie::all();
            return response()->json([
                'status'=>1,
                'message'=>'liste des administrateurs récupérer avec succès',
                'type'=>$categorie
            ], 200);
       }

       /**
        * Api pour modifier une categorie
        */

        public function categorieUpdate(Request $request,$id){
            $request->validate([
                'nom'=>'required|string|max:100'
            ],[
                'nom.required'=>'le nom de la categorie est requis'
            ]);
            $categorie = Categorie::find($id);
            if(!$categorie){
                return response()->json([
                    'status'=>0,
                    'message'=>'Introuvable',
                ], 404);
            }else{
                $categorie->nom = $request->nom;
                $categorie->save();
                return response()->json([
                    'status'=>1,
                    'message'=>'Catégorie Modifier avec succès',
                    'type'=>$categorie
                ], 200);
            }
        }

        /**
         *
         * Api pour supprimer une catégorie
         *
         */

        public function categorieDelete(Request $request,$id){
            $categorie= Categorie::findOrFail($id);
            if(!$categorie){
                return response()->json([
                    'status'=>0,
                    'message'=>'Introuvable',
                ], 404);
            }
            $result = $categorie->delete();
            if($result){
                return response()->json([
                    'status' => 1,
                    'message' => 'Categorie supprimée avec succès'
                ], 200);
            } else {
                return response()->json([
                    'status' => 0,
                    'message' => 'Échec de la suppression'
                ], 404);
            }
        }
}
