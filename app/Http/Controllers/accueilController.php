<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class accueilController extends Controller{




    /* ################################################################################################# */
    /* ------------------------- GESTION DES L'AFFICHAGE DES PAGES D'ACCUEIL --------------------------- */
    /* ################################################################################################# */


    public function index(Request $request){

        // VERIF SI L4UTILISATEUR EST CONNECTE A LA SESSION
        if (!Session::get('connecte')) {
            
            if ($request->ajax()) {
                return response()->json(['error' => 'Session expirée'], 401);
            }
            return redirect('/')->withErrors(['error' => 'Veuillez vous connecter.']);
        }
        
        else{

            // ON RECUPERE LES INFO DE L'UTILISATEUR STOCKE DANS LA SESSION
            $grade = Session::get('grade');
            $prenom = Session::get('prenom');
            $choix = $request->query('choix', 'candidatures');

            // GESTION DES INFOS AFFICHEES HEURE POUR BONSOIR ET BONJOUR
            $heure = date("H");
            $salutation = ($heure >= 6 && $heure < 18) ? "Bonjour" : "Bonsoir";

            // GESTION DU CHOIX DE LA PAGE A AFFICHER
            if ($grade === 'Admin') {
                return $this->vueAdmin($salutation, $prenom, $choix);
            } elseif ($grade === 'Etudiant') {
                return $this->vueEtudiant($salutation, $prenom, $choix);
            } elseif ($grade === 'Professeur') {
                return $this->vueProfesseur($salutation, $prenom, $choix);
            } elseif ($grade === 'Entreprise') {
                return $this->vueEntreprise($salutation, $prenom, $choix);
            } else {
                return $this->vueStandard($salutation, $prenom, $grade);
            }

        }
    }


    /* ################################################################################################# */
    /* --------------------- GESTION DES L'AFFICHAGE DE LA PAGE D'ACCUEIL ADMIN ------------------------ */
    /* ################################################################################################# */

    private function vueAdmin($salutation, $prenom, $choix) {
        $stats = [
            'etudiants'   => DB::table('utilisateur')->where('idStatut', 2)->count(),
            'entreprises' => DB::table('entreprise')->count(),
            'profs'       => DB::table('utilisateur')->where('idStatut', 3)->count(),
            'stages'      => DB::table('stage')->count(),
        ];

        $donnees = match($choix) {
            'prof'     => DB::table('utilisateur')->where('idStatut', 3)->get(),
            'etudiant' => DB::table('utilisateur as u')
                            ->leftJoin('classe as c', 'u.idClasse', '=', 'c.idClasse')
                            ->where('u.idStatut', 2)
                            ->select('u.*', 'c.nom as nomClasse')->get(),

            'entreprise' => DB::table('entreprise')->get(),
            
            'professionnel'    => DB::table('utilisateur as u')
                            ->leftJoin('entreprise as e', 'u.idEntreprise', '=', 'e.idEntreprise')
                            ->select('u.nom', 'u.prenom', 'u.email', 'e.nom as nomEntreprise')
                            ->where('u.idStatut', 4)                            
                            ->get(),
            
            'stage' => DB::table('stage as s')
                            ->join('entreprise as e', 's.idEntreprise', '=', 'e.idEntreprise')
                            ->select('s.*', 'e.nom as nomEntreprise')->get(),
            default    => DB::table('candidature as c')
                            ->join('stage as s', 'c.idStage', '=', 's.idStage')
                            ->join('entreprise as e', 'c.idEntreprise', '=', 'e.idEntreprise')
                            ->join('utilisateur as u', 'c.idUtilisateur', '=', 'u.idUtilisateur')
                            ->select('c.idCandidature',
                                    's.idStage',
                                    'e.idEntreprise',
                                    's.intitule', 
                                    'e.nom as nomEntreprise', 
                                    'u.nom', 'u.prenom', 
                                    'c.statut as numStatut', 
                                    'c.statut_entreprise', 
                                    'c.statut_prof', 
                                    'c.Remarque_Prof',
                                    's.dateDebut', 
                                    's.dateFin', 
                                    's.detail as stageDetail',
                                    'c.CV','c.LettreMotivation',
                                    'c.Convention',
                                    'c.estVerif_Convention_Entreprise',
                                    'c.estVerif_Convention_Prof'
                                    )
                            ->get(),
        };

        // Gestion du cas des requettes Ajax pour éviter d'avoir à recharger la page
        if (request()->ajax()) {
            return view('accueil.tableauAff.tableau', compact('donnees', 'choix'))->render();
        }

        // Retour vars le blade
        return view('accueil.admin', compact('salutation', 'prenom', 'stats', 'donnees', 'choix'));
    }

    
    
    
    
    
    
    
    
    /* ################################################################################################# */
    /* ---------------------- GESTION DES L'AFFICHAGE DE L'ACCUEIL POUR ETUDIANT ----------------------- */
    /* ################################################################################################# */


    private function vueEtudiant($salutation, $prenom, $choix){

        // Choix par défaut pour l'étudiant : stages disponibles
        if ($choix === 'candidatures' || $choix === 'stage') {
            // OK
        } else {
            $choix = 'stage';
        }
 
        $idUtilisateur = Session::get('id');
 
        // Stats personnalisées pour l'étudiant
        $stats = [
            'nbCandidatures' => DB::table('candidature')
                                    ->where('idUtilisateur', $idUtilisateur)
                                    ->count(),
            'nbStages'       => DB::table('stage')->count(),
        ];
 
        // Données du tableau selon le choix
        if ($choix === 'stage') {
            $donnees = DB::table('stage as s')
                ->join('entreprise as e', 's.idEntreprise', '=', 'e.idEntreprise')
                ->select('s.*', 'e.nom as nomEntreprise')
                ->get();
        } else {
    $idUtilisateur = Session::get('id');

    $donnees = DB::table('candidature as c')
        // 1. Liaison avec le stage
        ->leftJoin('stage as s', 'c.idStage', '=', 's.idStage')
        // 2. Liaison avec l'entreprise (via le stage)
        ->leftJoin('entreprise as e', 's.idEntreprise', '=', 'e.idEntreprise')
        // 3. Liaison avec l'utilisateur (C'EST CETTE LIGNE QUI MANQUAIT)
        ->leftJoin('utilisateur as u', 'c.idUtilisateur', '=', 'u.idUtilisateur') 
        ->where('c.idUtilisateur', $idUtilisateur)
        ->select(
            'c.idCandidature', 
            'c.CV',            
            'c.LettreMotivation', 
            'c.statut as info_statut',
            'c.statut as numStatut',
            'c.statut_entreprise',
            'c.statut_prof',
            's.intitule',
            'e.nom as nomEntreprise',
            'u.nom',   
            'u.prenom',  
            'c.Remarque_Prof',
            's.dateDebut', 
            's.dateFin',
            's.detail as description',
            'c.Convention',
            'c.estVerif_Convention_Entreprise',
            'c.estVerif_Convention_Prof'
        )
        ->get();
        }
        // Gestion du cas des requettes Ajax pour éviter d'avoir à recharger la page
        if (request()->ajax()) {
            return view('accueil.tableauAff.tableau', compact('donnees', 'choix'))->render();
        }
 
        // Retour vars le blade
        return view('accueil.etudiant', compact('salutation', 'prenom', 'stats', 'donnees', 'choix'));
    }

    
    
    
    
    
    
    
    
    
    /* ################################################################################################# */
    /* ----------------------- GESTION DES L'AFFICHAGE DE L'ACCUEIL POUR PROF ------------------------- */
    /* ################################################################################################# */


    private function vueProfesseur($salutation, $prenom, $choix){
        
    
        // Choix autorisés pour le prof
        if (!in_array($choix, ['etudiant', 'stage', 'candidatures'])) {
            $choix = 'etudiant';
        }
 
        $stats = [
            'nbEtudiants' => DB::table('utilisateur')->where('idStatut', 2)->count(),
            'nbStages'    => DB::table('stage')->count(),
        ];
 
        $donnees = $this->recupBDD($choix);
 
        // Gestion du cas des requettes Ajax pour éviter d'avoir à recharger la page
        if (request()->ajax()) {
            return view('accueil.tableauAff.tableau', compact('donnees', 'choix'))->render();
        }
 
        // Retour vars le blade
        return view('accueil.professeur', compact('salutation', 'prenom', 'stats', 'donnees', 'choix'));
    }



    /* ################################################################################################# */
    /* --------------------- GESTION DES L'AFFICHAGE DE L'ACCUEIL POUR ENTREPRISE ---------------------- */
    /* ################################################################################################# */


    private function vueEntreprise($salutation, $prenom, $choix) {
        
        // Options dispo pour le profil entreprise
        if (!in_array($choix, ['mes_offres', 'candidatures'])) {
            $choix = 'mes_offres';
        }
 
        // Récupération de l'idEntreprise lié au compte connecté
        $idUtilisateur = Session::get('id');
        $idEntreprise  = DB::table('utilisateur')
                            ->where('idUtilisateur', $idUtilisateur)
                            ->value('idEntreprise');
 
        // Stats du tableau de bord
        $stats = [
            'nbOffres'       => DB::table('stage')
                                    ->where('idEntreprise', $idEntreprise)
                                    ->count(),
            'nbCandidatures' => DB::table('candidature')
                                    ->where('idEntreprise', $idEntreprise)
                                    ->count(),
            'nbEnAttente'    => DB::table('candidature')
                                    ->where('idEntreprise', $idEntreprise)
                                    ->where('statut_entreprise', 0)
                                    ->count(),
        ];
 
        // Données du tableau selon le choix
        if ($choix === 'mes_offres') {
            $donnees = DB::table('stage as s')
                ->where('s.idEntreprise', $idEntreprise)
                ->select('s.*')
                ->orderBy('s.dateDebut', 'desc')
                ->get();
        } else {
            // Candidatures reçues pour les offres de cette entreprise
            $donnees = DB::table('candidature as c')
                ->join('stage as s',       'c.idStage',       '=', 's.idStage')
                ->join('utilisateur as u', 'c.idUtilisateur', '=', 'u.idUtilisateur')
                ->where('c.idEntreprise', $idEntreprise)
                ->select(
                    'c.idCandidature',    
                    'c.CV',               
                    'c.LettreMotivation', 
                    's.intitule',
                    'u.nom', 'u.prenom', 'u.email',
                    'c.statut as numStatut',
                    'c.Remarque_Prof',
                    'c.statut_entreprise', 
                    'c.statut_prof', 
                    's.dateDebut', 's.dateFin',
                    's.detail as stageDetail',
                    'c.Convention',
                    'c.estVerif_Convention_Entreprise',
                    'c.estVerif_Convention_Prof'
                    )
                ->orderBy('c.idCandidature', 'desc')
                ->get();
        }
 
        // Gestion du cas des requettes Ajax pour éviter d'avoir à recharger la page
        if (request()->ajax()) {
            return view('accueil.tableauAff.tableau', compact('donnees', 'choix'))->render();
        }
 
        // Retour vars le blade
        return view('accueil.entreprise', compact('salutation', 'prenom', 'stats', 'donnees', 'choix', 'idEntreprise'));
    }



 



    /* ################################################################################################# */
    /* ------------------------ FONCTION POUR RECUPERER LES DONNEES DANS LA BDD ------------------------ */
    /* ################################################################################################# */

    private function recupBDD(string $choix){
        
        return match ($choix) {
 
            'prof' => DB::table('utilisateur')
                        ->where('idStatut', 3)
                        ->get(),
 
            'etudiant' => DB::table('utilisateur as u')
                            ->leftJoin('classe as c', 'u.idClasse', '=', 'c.idClasse')
                            ->where('u.idStatut', 2)
                            ->select('u.*', 'c.nom as nomClasse')
                            ->get(),
 
            'entreprise' => DB::table('entreprise')->get(),
 
            'professionnel' => DB::table('utilisateur as u')
                                ->leftJoin('entreprise as e', 'u.idEntreprise', '=', 'e.idEntreprise')
                                ->select('u.nom', 'u.prenom', 'u.email', 'e.nom as nomEntreprise')
                                ->where('u.idStatut', 4)
                                ->get(),
 
            'stage' => DB::table('stage as s')
                            ->join('entreprise as e', 's.idEntreprise', '=', 'e.idEntreprise')
                            ->select('s.*', 'e.nom as nomEntreprise')
                            ->get(),
 
            default => DB::table('candidature as c')
                            ->join('stage as s',      'c.idStage',      '=', 's.idStage')
                            ->join('entreprise as e', 'c.idEntreprise', '=', 'e.idEntreprise')
                            ->join('utilisateur as u','c.idUtilisateur','=', 'u.idUtilisateur')
                            ->select(
                                'c.idCandidature',
                                'c.CV', 
                                'c.LettreMotivation',
                                's.intitule',
                                'e.nom as nomEntreprise',
                                'u.nom', 'u.prenom', 'u.email',
                                'c.statut as numStatut',
                                'c.Remarque_Prof',
                                'c.statut_entreprise', 
                                'c.statut_prof', 
                                's.dateDebut', 's.dateFin',
                                's.detail as stageDetail',
                                'c.Convention',
                                'c.estVerif_Convention_Entreprise',
                                'c.estVerif_Convention_Prof'
                            )
                            ->get(),
        };
    }
}
