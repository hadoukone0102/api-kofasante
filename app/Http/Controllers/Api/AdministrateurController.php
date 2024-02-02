<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\administrateur;
use App\Models\typeAdmin;
use Illuminate\Http\Request;

class AdministrateurController extends Controller
{
    /**
     * Api pour Enregistrer un Administrateur
     * Sauf un admin de type super peut créer un admin de type super
     */
    public function register(Request $request)
    {
        $request->validate([
            'nom' => 'required',
            'prenom' => 'required',
            'contact' => 'required|unique:administrateurs',
            'mot_de_passe' => 'required|min:6',
            'type' => 'required',
        ]);

        // Check if the request is authorized to create a "Super Admin"
        if ($request->type === 'Super' && !$this->isAuthorizedToCreateSuperAdmin()) {
            return response()->json(['message' => 'Vous n\'êtes pas autorisé à créer un Super Admin.'], 403);
        }

        // Check if an administrator with the same contact already exists
        if (administrateur::where('contact', $request->contact)->exists()) {
            return response()->json(['message' => 'Un administrateur avec ce contact existe déjà.'], 422);
        }

        $administrator = new administrateur;
        $administrator->nom = $request->nom;
        $administrator->prenom = $request->prenom;
        $administrator->contact = $request->contact;
        $administrator->mot_de_passe = bcrypt($request->mot_de_passe);
        $administrator->type = $request->type;
        $resut = $administrator->save();
        if($resut){
            return response()->json([
                'status'=>true,
                'message' => 'Administrateur créé avec succès'

            ], 201);
        }else{
            return response()->json([
                'status'=>false,
                'erreur' => "échec d'enregistrement"

            ], 201);
        }

    }

    /**
     * api Autorisation de créer un super admin
     */
    private function isAuthorizedToCreateSuperAdmin()
    {
        $authenticatedAdmin = auth()->user();

        return $authenticatedAdmin && $authenticatedAdmin->type === 'Super';
    }

    /**
     * api de Connexion d'un admin sur le back-office
     */
    public function login(Request $request)
    {
        $request->validate([
            'contact' => 'required|exists:administrateurs',
            'mot_de_passe' => 'required',
        ],[
            'contact.required'=>"le contact est requis",
            'mot_de_passe.required'=>"le mot de passe est requis",
        ]);

        $admin =administrateur::where('contact', '=',$request->contact)->first();
        if(!$admin){
            return response()->json([
                'status'=>0,
                'message'=>'Compte Introuvable ',
                ],404);
        }else{
            if(password_verify($request->input('mot_de_passe'), $admin->mot_de_passe)){
                $token = $admin->createToken('auth_token')->plainTextToken;
                return response()->json([
                    'status'=>1,
                    'message'=>'connexion reussit',
                    'user_token'=>$token,
                    'user'=>$admin
                ], 200);
            }else{
                return response()->json([
                    'status'=>0,
                    'message'=>'mot de passe ou contact incorecte',
                ], 404);
            }
        }

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }


        $admin = Utilisateur::where('email', $credentials['contact'])->first();
        $token = $admin->createToken('auth_token')->plainTextToken;

        return response()->json([
            'Adminstrateur' => $admin,
            'token' => $token
        ], 200);

    }
    /**
     * Obténir la liste de tous les administrateurs
     */
    public function adminGet(){
        $allAdmin = administrateur::all();
        return response()->json([
            'status'=>1,
            'message'=>'liste des administrateurs récupérer avec succès',
            'type'=>$allAdmin
        ], 200);
    }
    /**
     * api de gestion de profils administrateur
     */
    public function adminProfils(){
        if(auth()->user()){
            return response()->json([
                'status'=>1,
                'message'=>'Profils Administrateur',
                'data'=>auth()->user()
            ],200);

           }else{

            return response()->json([
                'status'=>0,
                'message'=>'vous etes pas connecté',
            ],401);
           }
    }
    /**
     * Api de gestion de deconnexion d'un administrateur
     */
    public function adminLogout(){
        Auth()->user()->tokens()->delete();
        return response()->json([
            'status'=>1,
            'message'=>'Deconnexion reusit'
        ],200);
    }

    public function adminUpdate(Request $request, $id){
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:200',
        ], [
            'nom.required' => 'Le nom est requis',
            'prenom.required' => 'Le prénom est requis',
        ]);

        $admin = administrateur::find($id);

        if (!$admin) {
            return response()->json([
                'status' => 0,
                'message' => 'Utilisateur non trouvé.',
            ], 404);
        }

        $admin->nom = $request->input('nom');
        $admin->prenom = $request->input('prenom');

        $admin->save();

        return response()->json([
            'status' => 1,
            'message' => 'Utilisateur mis à jour avec succès.',
            'user' => $admin,
        ], 200);
    }
    /**
     * api pour créer un type d'admin
     */

    public function typePost(Request $request){
        $request->validate([
            'nom'=>'required'
        ],[
            'nom.requied'=>'le nom du type est requis'
        ]);

        $typeExiste = typeAdmin::where('nom',$request->input('nom'))->first();
        if($typeExiste){
            return response()->json([
                'status'=>0,
                'message'=>'ce type existe déja',
            ], 200);
        }else{
            $type = typeAdmin::firstOrCreate(
                ['nom' => $request->input('nom')]
            );
        }

        return response()->json([
            'status'=>1,
            'message'=>'type créer avec succès',
            'type'=>$type
        ], 200);
    }

    public function typeGet(Request $request){
        $type = typeAdmin::all();
        return response()->json([
            'status'=>1,
            'message'=>'type récupérer avec succès',
            'type'=>$type
        ], 200);
    }

}
