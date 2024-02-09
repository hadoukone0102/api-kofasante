<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Utilisateur;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class AuthController extends Controller
{

    /**
     * api pour creation d'un utilisateur
     */
    // enregistrement d'un utilisateur
    public function register(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom'=>'required|string|max:200',
            'email' => 'required|string|email|unique:utilisateurs',
            'contact'=>'required|max:10',
            'mot_de_passe' => 'required|string|min:8',
        ],[
            'nom.required'=>'le nom est requis',
            'prenom.requied'=>'le prenom est requis',
            'email.required'=>"l'adresse email est requis",
            'contact.requied'=>'le contact est requis',
            'mot_de_passe.required'=>'le mot de passe est requis',
        ]);

            // Vérifier si l'utilisateur existe déjà par email
        $existingUser = Utilisateur::where('email', $request->input('email'))->first();

        if ($existingUser) {
            return response()->json([
                'status' => 0,
                'message' => 'Cet utilisateur existe déjà.',
            ], 400);
        }

        // L'utilisateur n'existe pas, le créer
        $user = Utilisateur::firstOrCreate(
            ['email' => $request->input('email')],
            [
                'nom' => $request->input('nom'),
                'prenom' => $request->input('prenom'),
                'contact' => $request->input('contact'),
                'mot_de_passe' => bcrypt($request->input('mot_de_passe')),
                'role'=> 'admin'
            ]
        );

        return response()->json([
            'status' => 1,
            'message' => 'Utilisateur enregistré',
            'user' => $user,
        ], 201);

    }

    /**
     *
     * api pour recupérer la liste de tous les utilisateur de kafa mobile
     *
     */
    public function userAll(){
        $allUsers = Utilisateur::all();
        return response()->json([
            'status'=>1,
            'message'=>'liste des Utilisateurs récupérer avec succès',
            'type'=>$allUsers
        ], 200);
    }
// mis à jour d'un utilisateur
    public function update(Request $request, $id)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:200',
        ], [
            'nom.required' => 'Le nom est requis',
            'prenom.required' => 'Le prénom est requis',
        ]);

        $user = Utilisateur::find($id);

        if (!$user) {
            return response()->json([
                'status' => 0,
                'message' => 'Utilisateur non trouvé.',
            ], 404);
        }

        $user->nom = $request->input('nom');
        $user->prenom = $request->input('prenom');

        $user->save();

        return response()->json([
            'status' => 1,
            'message' => 'Utilisateur mis à jour avec succès.',
            'user' => $user,
        ], 200);
}

    // Supprimer un utilisateur
    public function DeleteUser (string $id){

        $service = Utilisateur::findOrFail($id);
        $service->delete();
        return response()->json(null, 204);
    }


    // Connexion
    public function login(Request $request)
{
        $request->validate([
            'email' => 'required|string|email',
            'mot_de_passe' => 'required|string',
        ],[
            'email.required'=>"l'adresse email est requis",
            'mot_de_passe.required'=>"le mot_de_passe est requis",
        ]);

        $user =Utilisateur::where('email', '=',$request->email)->first();
        if(!$user){
            return response()->json([
                'status'=>0,
                'message'=>'Une erreur est survenu lors de la connexion ',
                'user'=>$user,
                ],404);
        }else{
            if(password_verify($request->input('mot_de_passe'), $user->mot_de_passe)){
                $token = $user->createToken('auth_token')->plainTextToken;
                return response()->json([
                    'status'=>1,
                    'message'=>'connexion reussit',
                    'user_token'=>$token,
                    'user'=>$user
                ], 200);
            }else{
                return response()->json([
                    'status'=>0,
                    'message'=>'mot de passe ou e-mail incorecte',
                ], 404);
            }
        }

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = Utilisateur::where('email', $credentials['email'])->first();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['user' => $user, 'token' => $token], 200);
}

// profils utlisateurs
public function profils(Request $request){
    if(auth()->user()){
     return response()->json([
         'status'=>1,
         'message'=>'Profils utilisateurs',
         'data'=>auth()->user()
     ],200);

    }else{

     return response()->json([
         'status'=>0,
         'message'=>'vous etes pas connecté',
     ],401);
    }
 }

 // Deconnexion
public function logout(Request $request)
{
        Auth()->user()->tokens()->delete();
        return response()->json([
            'status'=>1,
            'message'=>'Deconnexion reusit'
        ],200);
}

// Réinitialisation du Mot de Passe

public function resetPassword(Request $request)
{
    $request->validate([
        'email' => 'required|email',
    ]);

    $user = Utilisateur::where('email', $request->email)->first();

    if (!$user) {
        return response()->json([
            'status' => 0,
            'message' => 'Utilisateur introuvable',
        ], 404);
    }

    // Générer un token de réinitialisation de mot de passe
    $token = \Str::random(60);

    // Stocker le token dans la base de données
    $user->update([
        'reset_password_token' => $token,
    ]);

    // Envoyer la notification par e-mail
    $user->notify(new ResetPasswordNotification($token));
    Notification::send($user, new ResetPasswordNotification($token));
    return response()->json([
        'status' => 1,
        'message' => 'Un e-mail de réinitialisation de mot de passe a été envoyé avec succès.',
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
     * Show the form for creating a new resource.
     */
    public function create()
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
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
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
