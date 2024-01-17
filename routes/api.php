<?php

use App\Http\Controllers\Api\AbonnementController;
use App\Http\Controllers\Api\AdministrateurController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DocumentsController;
use App\Http\Controllers\Api\MedecineEnLigneController;
use App\Http\Controllers\Api\MediaTechController;
use App\Http\Controllers\Api\RenseignerController;
use App\Http\Controllers\Api\VisitesController;
use App\Http\Controllers\ServiceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;











/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//
/*
|--------------------------------------------------------------------------
| API Routes for projet kofasante
|--------------------------------------------------------------------------
| 1- creation des api d'authentification
|
*/

// Inscription et Connexion utilisateur
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
// Inscription et connexion d'un administrateur
Route::post('/admin-register', [AdministrateurController::class, 'register']);
Route::post('/admin-login', [AdministrateurController::class, 'login']);
// Informations de l'Utilisateur (Protégé par l'authentification)

Route::middleware('auth:sanctum')->group(function () { // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ //



    /**
     * ####################~~~~~~~~~~~~ for back-office ~~~~~~~~~~~~~~~~~###############
     * Administrateur
     * login
     * register
     * profils
     * logout
     * update
     * types get and post
     * get all admin
     */

    Route::post('type-post', [AdministrateurController::class,'typePost']);
    Route::get('admin-profils', [AdministrateurController::class,'adminProfils']);
    Route::post('admin-logout', [AdministrateurController::class,'adminLogout']);
    Route::put('/admin/{id}', [AdministrateurController::class, 'adminUpdate']);

     /**
     * ####################~~~~~~~~~~~~ for user in mobile app of kofasante ~~~~~~~~~~~~~~~~~###############
     * Utilisateur
     * login
     * register
     * profils
     * logout
     * update
     * get all users
     */

    Route::get('/profils', [AuthController::class, 'profils']);
    Route::put('/update/{id}', [AuthController::class, 'update']);
    Route::post('/logout', [AuthController::class, 'logout']);
     /**
    *
     * ####################~~~~~~~~~~~~ Gestion du second module ~~~~~~~~~~~~~~~~~###############
     *
     *
     * Asctuce et Conseil
     * CreateAsctuceOrConseil
     * deleteA&C
     * updateA&C
     * ListeCreation
     *
     *
     */


    Route::delete('media-delete/{id}',[MediaTechController::class,'mediaDelete']);
    Route::put('media-update/{id}',[MediaTechController::class,'mediaUpdate']);
    /**
     * ####################~~~~~~~~~~~~ Gestion du second module ~~~~~~~~~~~~~~~~~###############
     * Categories
     * create
     * listes
     * delete
     * update
     *
     */
    Route::post('categorie/create',[MediaTechController::class,'categoriePost']);
    Route::delete('categorie/delete/{id}',[MediaTechController::class,'categorieDelete']);
    Route::put('categorie/update/{id}',[MediaTechController::class,'categorieUpdate']);

});// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ //

// Réinitialisation du Mot de Passe
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

/**
 *~~~~~~~~~~~~~~~~~~~~~~~ LES DIFFERENT LIENS API AUTHENTIFICATIO ~~~~~~~~~~~~~~~
 *~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * http://localhost:8000/api/register   => POUR CREER UN UTILISATEUR post
 * http://localhost:8000/api/login       => POUR SE CONNECTER  post
 * http://localhost:8000/api/profils    => POUR AFFICHER LE PROFILS D'UN UTILISATEUR get
 * http://localhost:8000/api/update/1    => POUR METTRE À JOUR UN UTILISATEUR put
 * http://localhost:8000/api/logout       => POUR DECONNECTER UN UTILISATEUR post
 * http://localhost:8000/api/reset-password  =>POUR RENITIALISER LE MOT DE PASSE
 *
 */

 //################## ############################## Possiblité de voir sans etre forcement connecté #######################

    // services
    Route::apiResource('services', ServiceController::class);
        // Abonnement
        Route::apiResource('abonnement', AbonnementController::class);
        // Documents
        Route::apiResource('document', DocumentsController::class);
        // Renseigner
        Route::apiResource('medecine', MedecineEnLigneController::class);
        // Renseigner
        Route::apiResource('renseigner', RenseignerController::class);
        // visite
        Route::apiResource('visite', VisitesController::class);


 Route::get('categorie/get',[MediaTechController::class,'categorieGet']);
 Route::get('type-get', [AdministrateurController::class,'typeGet']);
 Route::get('admin-get', [AdministrateurController::class,'adminGet']);
 Route::get('/all-user', [AuthController::class, 'userAll']);

 Route::post('media',[MediaTechController::class,'mediaCreate']);
 Route::get('media-liste',[MediaTechController::class,'mediaLists']);
