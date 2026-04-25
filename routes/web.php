<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TestController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\accueilController;
use Illuminate\Support\Facades\Hash;


use App\Http\Controllers\OPTION_changementMDPController;
use App\Http\Controllers\OPTION_changementPDPController;
use App\Http\Controllers\OPTION_ajoutUtilisateurController;
use App\Http\Controllers\OPTION_suppressionUtilisateurController;
use App\Http\Controllers\OPTION_ajoutOffreStageController;
use App\Http\Controllers\OPTION_ajoutCandidatureController;
use App\Http\Controllers\MDP_OublieController;



// --------------------------------------------------------------------------------------------------------------------------------------
// ------------------------------ GESTION DES CONNEXIONS ET DECONNEXION -----------------------------------------------------------------
// --------------------------------------------------------------------------------------------------------------------------------------



// Lien vers page de connexion
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');

// Traitement des données du formulaire
Route::post('/login-process', [AuthController::class, 'login'])->name('login.post');

// Processus de déconnexion
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');




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




















Route::middleware(['check.connexion'])->group(function () {


    // Redirection vers la page d'accueil
    Route::get('/accueil', [accueilController::class, 'index'])->name('accueil');



    // --------------------------------------------------------------------------------------------------------------------------------------
    // ---------------------------- GESTION DES FONCTIONNALITES DEPUIS ACCEUIL --------------------------------------------------------------
    // --------------------------------------------------------------------------------------------------------------------------------------



    // GESTION DU CHANGEMNT DE MDP DEPUIS L'ACCUEIL
    Route::post('/changement-mot-de-passe', [OPTION_changementMDPController::class, 'changementMDP'])
        ->name('changementMDP_GESTION');

    Route::get('/changement-mot-de-passe', [OPTION_changementMDPController::class, 'affichageChangement'])->name('aff.changementMDP');




    // GESTION DU CHANGEMNT DE PDP DEPUIS L'ACCUEIL
    Route::get('/changement-photo-de-profil', [OPTION_changementPDPController::class, 'affichageChangementPDP'])->name('aff.changementPDP');

    Route::post('/changement-photo-de-profil', [OPTION_changementPDPController::class, 'changementPDP'])
        ->name('changementPDP_GESTION');




    // GESTION DE L'AJOUT D'UTILISATEUR DEPUIS ACCUEIL

    //ETAPE 1 : Formulaire avec les infos 
    Route::get('/ajout-utilisateur', [OPTION_ajoutUtilisateurController::class, 'Vu_InscriptionAdmin_Etape1'])->name('inscriptionAdmin.Etape1_VU');
    Route::post('/ajout-utilisateur', [OPTION_ajoutUtilisateurController::class, 'Traitement_InscriptionAdmin_Etape1'])->name('inscriptionAdmin.Etape1_GESTION');

    //ETAPE 2 : Page de confirmation d'incription d'un nouvel utiliusateur
    Route::get('/ajout-utilisateur/confirmation', [OPTION_ajoutUtilisateurController::class, 'Vu_InscriptionAdmin_Etape2'])->name('inscriptionAdmin.Etape2_VU');



    // GESTION DE LA SUPPRESSION D'UTILISATEUR DEPUIS ACCUEIL

   // ETAPE 1 : Liste et Recherche
    Route::get('/suppression-utilisateur', [OPTION_suppressionUtilisateurController::class, 'Vu_SuppressionAdmin_Etape1'])
        ->name('suppressionAdmin.Etape1_VU');
    
    // ETAPE 2 : Confirmation (J'ai changé Inscription par Suppression ici)
    Route::get('/suppression-utilisateur/confirmation', [OPTION_suppressionUtilisateurController::class, 'Vu_SuppressionAdmin_Etape2'])
        ->name('suppressionAdmin.Etape2_VU');

    // ETAPE 3 : Action de suppression (J'ai changé Inscription par Suppression ici)
    Route::post('/suppression-utilisateur/traitement', [OPTION_suppressionUtilisateurController::class, 'Traitement_SuppressionAdmin_Etape1'])
        ->name('suppressionAdmin.Etape1_GESTION');






    
    // GESTION DE L'AJOUT D'UNE OFFRE DE STAGE

    //ETAPE 1 : Formulaire avec les infos 
    Route::get('/ajout-offre-stage', [OPTION_ajoutOffreStageController::class, 'Vu_AjoutStage_Etape1'])->name('ajoutStage.Etape1_VU');
    Route::post('/ajout-offre-stage', [OPTION_ajoutOffreStageController::class, 'Traitement_AjoutStage_Etape1'])->name('ajoutStage.Etape1_GESTION');

    //ETAPE 2 : Page de confirmation l'ajout d'une nouvelle offre de stage
    Route::get('/ajout-offre-stage/confirmation', [OPTION_ajoutOffreStageController::class, 'Vu_InscriptionAdmin_Etape2'])->name('inscriptionAdmin.Etape2_VU');



    // GESTION DE L'AJOUT D'UNE CANDIDATURE

    //ETAPE 1 : Formulaire avec les infos 
    Route::get('/ajout-candidature', [OPTION_ajoutCandidatureController::class, 'Vu_AjoutCandidature_Etape1'])->name('ajoutCandidature.Etape1_VU');
    Route::post('/ajout-candidature', [OPTION_ajoutCandidatureController::class, 'Traitement_AjoutCandidature_Etape1'])->name('ajoutCandidature.Etape1_GESTION');

    //ETAPE 2 : Page de confirmation l'ajout d'une nouvelle offre de stage
    Route::get('/ajout-candidature/confirmation', [OPTION_ajoutCandidatureController::class, 'Vu_InscriptionAdmin_Etape2'])->name('inscriptionAdmin.Etape2_VU');


    Route::get('/candidature/supprimer/{id}', [App\Http\Controllers\OPTION_ajoutCandidatureController::class, 'Supprimer_Candidature'])
    ->name('candidature.supprimer');





    // --------------------------------------------------------------------------------------------------------------------------------------
    // ------------------------------ GESTION DES PROBLEMES DE MDP --------------------------------------------------------------------------
    // --------------------------------------------------------------------------------------------------------------------------------------



    // Processus de réinitialisation des MDP


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




});








