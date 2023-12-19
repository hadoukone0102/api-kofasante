<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Categorie;
use App\Models\mediaTech;
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
            'media'=>'max:1024',
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
