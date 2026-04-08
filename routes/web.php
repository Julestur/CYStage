<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TestController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\accueilController;
use Illuminate\Support\Facades\Hash;







// --------------------------------------------------------------------------------------------------------------------------------------
// ------------------------------ GESTION DES CONNEXIONS ET DECONNEXION -----------------------------------------------------------------
// --------------------------------------------------------------------------------------------------------------------------------------



// Lien vers page de connexion
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');

// Traitement des données du formulaire
Route::post('/login-process', [AuthController::class, 'login'])->name('login.post');

// Redirection vers la page d'accueil
Route::get('/accueil', [accueilController::class, 'index'])->name('accueil');



// Processus de déconnexion
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');









// --------------------------------------------------------------------------------------------------------------------------------------
// ------------------------------ GESTION DES PROBLEMES DE MDP --------------------------------------------------------------------------
// --------------------------------------------------------------------------------------------------------------------------------------




// 4. Les routes pour le changement de mot de passe temporaire
Route::get('/changer-mdp-temp', [AuthController::class, 'showChangeMdpForm'])->name('password.temp');

//A FAIRE

// Processus de réinitialisation des MDP

use App\Http\Controllers\MDP_OublieController;

// 1. Page de saisie de l'email
Route::get('/mot-de-passe-oublie', function () {
    return view('gestionMDP.MDP_Oublie'); 
})->name('password.request');

// 2. Traitement de l'envoi du mail
Route::post('/mot-de-passe-oublie', [MDP_OublieController::class, 'sendResetLink'])
    ->name('password.email.process');

// 3. Affichage du formulaire (Le lien cliqué dans le mail arrive ICI)
// J'utilise le nom 'password.reset' car c'est celui que ton contrôleur génère pour le mail
Route::get('/reinitialisation-mot-de-passe/{token}', [MDP_OublieController::class, 'showResetForm'])
    ->name('password.reset');

// 4. Validation du nouveau mot de passe (Le bouton "Enregistrer")
Route::post('/Réinitialisation-mot-de-passe', [MDP_OublieController::class, 'updatePassword'])
    ->name('reinitMDP_GESTION');







// --------------------------------------------------------------------------------------------------------------------------------------
// ------------------------------ GESTION DES INSCRIPTIONS DEPUIS LE PORTAIL ------------------------------------------------------------
// --------------------------------------------------------------------------------------------------------------------------------------


use App\Http\Controllers\InscriptionController;


//ETAPE 1 : Formulaire avec les infos 
Route::get('/inscription', [InscriptionController::class, 'Vu_Inscription_Etape1'])->name('inscription.Etape1_VU');
Route::post('/inscription', [InscriptionController::class, 'Traitement_Inscription_Etape1'])->name('inscription.Etape1_GESTION');

//ETAPE 2 : Vérification du code recu par mail
Route::get('/inscription/verification', [InscriptionController::class, 'Vu_Inscription_Etape2'])->name('inscription.Etape2_VU');
Route::post('/inscription/verification', [InscriptionController::class, 'Traitement_Inscription_Etape2'])->name('inscription.Etape2_GESTION');

//ETAPE 3 : Si code bon affichage page de succès 
Route::get('/inscription/succes', [InscriptionController::class, 'Vu_Inscription_Etape3'])->name('inscription.Etape3_VU');








