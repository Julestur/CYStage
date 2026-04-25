<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class accueilController extends Controller
{
    public function index(Request $request)
    {
        // 1. Sécurité : Vérifier la session (comme ton ancien if(!isset($_SESSION...)))
        if (!Session::get('connecte')) {
            if ($request->ajax()) {
                // Si c'est de l'AJAX, on envoie un code erreur 401 (Non autorisé)
                return response()->json(['error' => 'Session expirée'], 401);
            }
            return redirect('/')->withErrors(['error' => 'Veuillez vous connecter.']);
        }
        else{

               // 2. Redirection si MDP temporaire
        if (!empty(Session::get('mdp_tmp')) && Session::get('mdp_tmp') !== 'vide') {
            return redirect('/changer-mdp-temp');
        }

        $grade = Session::get('grade');
        $prenom = Session::get('prenom');
        $choix = $request->query('choix', 'candidatures'); // Choix par défaut pour l'admin

        // 3. Logique de salutation
        $heure = date("H");
        $salutation = ($heure >= 6 && $heure < 18) ? "Bonjour" : "Bonsoir";

        // 4. Dispatching selon le grade
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
                            ->select('c.idCandidature','s.idStage','e.idEntreprise','s.intitule', 'e.nom as nomEntreprise', 'u.nom', 'u.prenom', 'c.statut as numStatut', 's.dateDebut', 's.dateFin', 's.detail as stageDetail','c.CV','c.LettreMotivation')
                            ->get(),
        };

        if (request()->ajax()) {
        // ICI : On pointe vers le PETIT fichier créé à l'étape 1
        return view('accueil.tableauAff.tableau', compact('donnees', 'choix'))->render();
    }

        return view('accueil.admin', compact('salutation', 'prenom', 'stats', 'donnees', 'choix'));
    }

   // ──────────────────────────────────────────────────────────────────────────
    // VUE ETUDIANT
    // ──────────────────────────────────────────────────────────────────────────
    private function vueEtudiant($salutation, $prenom, $choix)
    {
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
            // Candidatures de CET étudiant uniquement
            $donnees = DB::table('candidature as c')
                ->join('stage as s',      'c.idStage',       '=', 's.idStage')
                ->join('entreprise as e', 'c.idEntreprise',  '=', 'e.idEntreprise')
                ->join('utilisateur as u','c.idUtilisateur', '=', 'u.idUtilisateur')
                ->where('c.idUtilisateur', $idUtilisateur)
                ->select(
                    's.intitule',
                    'e.nom as nomEntreprise',
                    'u.nom', 'u.prenom',
                    'c.statut as numStatut',
                    's.dateDebut', 's.dateFin',
                    's.detail as stageDetail'
                )
                ->get();
        }
 
        if (request()->ajax()) {
            return view('accueil.tableauAff.tableau', compact('donnees', 'choix'))->render();
        }
 
        return view('accueil.etudiant', compact('salutation', 'prenom', 'stats', 'donnees', 'choix'));
    }

    // ──────────────────────────────────────────────────────────────────────────
    // VUE PROFESSEUR
    // ──────────────────────────────────────────────────────────────────────────
    private function vueProfesseur($salutation, $prenom, $choix)
    {
        // Choix autorisés pour le prof
        if (!in_array($choix, ['etudiant', 'stage', 'candidatures'])) {
            $choix = 'etudiant';
        }
 
        $stats = [
            'nbEtudiants' => DB::table('utilisateur')->where('idStatut', 2)->count(),
            'nbStages'    => DB::table('stage')->count(),
        ];
 
        $donnees = $this->getDonnees($choix);
 
        if (request()->ajax()) {
            return view('accueil.tableauAff.tableau', compact('donnees', 'choix'))->render();
        }
 
        return view('accueil.professeur', compact('salutation', 'prenom', 'stats', 'donnees', 'choix'));
    }



    // ──────────────────────────────────────────────────────────────────────────
    // VUE ENTREPRISE
    // ──────────────────────────────────────────────────────────────────────────
    private function vueEntreprise($salutation, $prenom, $choix)
    {
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
                                    ->where('statut', 2)
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
                    's.intitule',
                    'u.nom', 'u.prenom', 'u.email',
                    'c.statut as numStatut',
                    's.dateDebut', 's.dateFin',
                    's.detail as stageDetail'
                )
                ->orderBy('c.idCandidature', 'desc')
                ->get();
        }
 
        if (request()->ajax()) {
            return view('accueil.tableauAff.tableau', compact('donnees', 'choix'))->render();
        }
 
        return view('accueil.entreprise', compact('salutation', 'prenom', 'stats', 'donnees', 'choix', 'idEntreprise'));
    }





    // ──────────────────────────────────────────────────────────────────────────
    // VUE STANDARD (fallback)
    // ──────────────────────────────────────────────────────────────────────────
    private function vueStandard($salutation, $prenom, $grade)
    {
        $fichiers    = [];
        $repertoire  = public_path('sorties/');
 
        if (is_dir($repertoire)) {
            foreach (glob($repertoire . "*.csv") as $filepath) {
                $fichiers[basename($filepath)] = filemtime($filepath);
            }
            arsort($fichiers);
        }
 
        return view('accueil.standard', compact('salutation', 'prenom', 'grade', 'fichiers'));
    }
 



    // ──────────────────────────────────────────────────────────────────────────
    // HELPER : requêtes communes (partagées Admin / Prof)
    // ──────────────────────────────────────────────────────────────────────────
    private function getDonnees(string $choix)
    {
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
                                's.intitule',
                                'e.nom as nomEntreprise',
                                'u.nom', 'u.prenom',
                                'c.statut as numStatut',
                                's.dateDebut', 's.dateFin',
                                's.detail as stageDetail'
                            )
                            ->get(),
        };
    }
}
