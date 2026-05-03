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
use App\Http\Controllers\MessageController;


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


// Formulaire avec les infos 
Route::get('/inscription', [InscriptionController::class, 'Vu_Inscription_Etape1'])->name('inscription.Etape1_VU');
Route::post('/inscription', [InscriptionController::class, 'Traitement_Inscription_Etape1'])->name('inscription.Etape1_GESTION');

// Vérification du code recu par mail
Route::get('/inscription/verification', [InscriptionController::class, 'Vu_Inscription_Etape2'])->name('inscription.Etape2_VU');
Route::post('/inscription/verification', [InscriptionController::class, 'Traitement_Inscription_Etape2'])->name('inscription.Etape2_GESTION');

// Si code bon affichage page de succès 
Route::get('/inscription/succes', [InscriptionController::class, 'Vu_Inscription_Etape3'])->name('inscription.Etape3_VU');









// --------------------------------------------------------------------------------------------------------------------------------------
// ------------------------------ REINITIALISATION MDP DEPUIS PORTAIL -------------------------------------------------------------------
// --------------------------------------------------------------------------------------------------------------------------------------


    // Page de saisie de l'email
    Route::get('/mot-de-passe-oublie', function () {
        return view('gestionMDP.MDP_Oublie'); 
    })->name('password.request');

    // Traitement de l'envoi du mail
    Route::post('/mot-de-passe-oublie', [MDP_OublieController::class, 'sendResetLink'])
        ->name('password.email.process');

    // Affichage du formulaire 
    Route::get('/reinitialisation-mot-de-passe/{token}', [MDP_OublieController::class, 'showResetForm'])
        ->name('password.reset');

    // Validation du nouveau mot de passe
    Route::post('/Réinitialisation-mot-de-passe', [MDP_OublieController::class, 'updatePassword'])
        ->name('reinitMDP_GESTION');










Route::middleware(['check.connexion'])->group(function () {


    // Redirection vers la page d'accueil
    Route::get('/accueil', [accueilController::class, 'index'])->name('accueil');

    Route::get('/candidature/supprimer/{id}', [OPTION_ajoutCandidatureController::class, 'Supprimer_Candidature'])
    ->name('candidature.supprimer');

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

    // Formulaire avec les infos 
    Route::get('/ajout-utilisateur', [OPTION_ajoutUtilisateurController::class, 'Vu_InscriptionAdmin_Etape1'])->name('inscriptionAdmin.Etape1_VU');
    Route::post('/ajout-utilisateur', [OPTION_ajoutUtilisateurController::class, 'Traitement_InscriptionAdmin_Etape1'])->name('inscriptionAdmin.Etape1_GESTION');

    // Page de confirmation d'incription d'un nouvel utiliusateur
    Route::get('/ajout-utilisateur/confirmation', [OPTION_ajoutUtilisateurController::class, 'Vu_InscriptionAdmin_Etape2'])->name('inscriptionAdmin.Etape2_VU');



    // GESTION DE LA SUPPRESSION D'UTILISATEUR DEPUIS ACCUEIL

   // Liste et Recherche
    Route::get('/suppression-utilisateur', [OPTION_suppressionUtilisateurController::class, 'Vu_SuppressionAdmin_Etape1'])
        ->name('suppressionAdmin.Etape1_VU');
    
    // Confirmation
    Route::get('/suppression-utilisateur/confirmation', [OPTION_suppressionUtilisateurController::class, 'Vu_SuppressionAdmin_Etape2'])
        ->name('suppressionAdmin.Etape2_VU');

    // Action de suppression
    Route::post('/suppression-utilisateur/traitement', [OPTION_suppressionUtilisateurController::class, 'Traitement_SuppressionAdmin_Etape1'])
        ->name('suppressionAdmin.Etape1_GESTION');






    
    // GESTION DE L'AJOUT D'UNE OFFRE DE STAGE

    // Formulaire avec les infos 
    Route::get('/ajout-offre-stage', [OPTION_ajoutOffreStageController::class, 'Vu_AjoutStage_Etape1'])->name('ajoutStage.Etape1_VU');
    Route::post('/ajout-offre-stage', [OPTION_ajoutOffreStageController::class, 'Traitement_AjoutStage_Etape1'])->name('ajoutStage.Etape1_GESTION');

    // Page de confirmation l'ajout d'une nouvelle offre de stage
    Route::get('/ajout-offre-stage/confirmation', [OPTION_ajoutOffreStageController::class, 'Vu_InscriptionAdmin_Etape2'])->name('ajoutStage.Etape2_VU');



    // GESTION DE L'AJOUT D'UNE CANDIDATURE

    // Formulaire avec les infos 
    Route::get('/ajout-candidature', [OPTION_ajoutCandidatureController::class, 'Vu_AjoutCandidature_Etape1'])->name('ajoutCandidature.Etape1_VU');
    Route::post('/ajout-candidature', [OPTION_ajoutCandidatureController::class, 'Traitement_AjoutCandidature_Etape1'])->name('ajoutCandidature.Etape1_GESTION');

    // Page de confirmation l'ajout d'une nouvelle offre de stage
    Route::get('/ajout-candidature/confirmation', [OPTION_ajoutCandidatureController::class, 'Vu_InscriptionAdmin_Etape2'])->name('ajoutCandidature.Etape2_VU');




    // STATUT D'UNE CANDIDATURE
    Route::get('/candidature/accepter/entreprise/{id}', [OPTION_ajoutCandidatureController::class, 'Accepter_Candidature_Entreprise']);
    Route::get('/candidature/accepter/prof/{id}',       [OPTION_ajoutCandidatureController::class, 'Accepter_Candidature_Prof']);
    Route::get('/candidature/accepter/{id}', [OPTION_ajoutCandidatureController::class, 'Accepter_Candidature'])->name('candidature.accepter');
   
    Route::get('/candidature/refuser/{id}', [OPTION_ajoutCandidatureController::class, 'Refuser_Candidature'])->name('candidature.refuser');
    
    //COMMENTAIRE D'UNE CANDIDATURE
    Route::post('/candidature/commenter/{id}', [OPTION_ajoutCandidatureController::class, 'Commenter_Candidature'])->name('candidature.commenter');
    
    
    
    // SUPRESSION D'UNE CANDIDATURE
    Route::get('/candidature/supprimer/{id}', [App\Http\Controllers\OPTION_ajoutCandidatureController::class, 'Supprimer_Candidature'])
    ->name('candidature.supprimer');

    // SUPRESSION D'UN STAGE
    Route::get('/stage/supprimer/{id}', [OPTION_ajoutOffreStageController::class, 'Supprimer_Stage'])
    ->name('stage.supprimer');



    // Upload convention par l'étudiant
    Route::post('/candidature/convention/{id}', [OPTION_ajoutCandidatureController::class, 'Upload_Convention'])->name('candidature.convention');

    // Signer convention (entreprise et prof uploadent leur version signée)
    Route::post('/candidature/convention/signer/entreprise/{id}', [OPTION_ajoutCandidatureController::class, 'Signer_Convention_Entreprise'])->name('candidature.convention.signer.entreprise');
    Route::post('/candidature/convention/signer/prof/{id}', [OPTION_ajoutCandidatureController::class, 'Signer_Convention_Prof'])->name('candidature.convention.signer.prof');


    // ENVOYER UN MESSAGE
    Route::get('/messages', [MessageController::class, 'discussion'])->name('messages.discussion');
    Route::post('/message/envoyer', [MessageController::class, 'Envoyer_Message'])->name('message.envoyer');
    Route::get('/message/recuperer/{idCandidature}/{canal}', [MessageController::class, 'Recuperer_Messages'])->name('message.recuperer');


    });








