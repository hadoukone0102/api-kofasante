<?php

use App\Http\Controllers\Api\AuthController;
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

// Inscription et Connexion
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Informations de l'Utilisateur (Protégé par l'authentification)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profils', [AuthController::class, 'profils']);
    Route::put('/update/{id}', [AuthController::class, 'update']);
    Route::post('/logout', [AuthController::class, 'logout']);
    // Autres routes protégées
});

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
