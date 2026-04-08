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
        } else {
            // Pour Etudiant, Prof, Entreprise (ton interface de dépôt CSV)
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
            'stage'    => DB::table('stage as s')
                            ->join('entreprise as e', 's.idEntreprise', '=', 'e.idEntreprise')
                            ->select('s.*', 'e.nom as nomEntreprise')->get(),
            default    => DB::table('candidature as c')
                            ->join('stage as s', 'c.idStage', '=', 's.idStage')
                            ->join('entreprise as e', 'c.idEntreprise', '=', 'e.idEntreprise')
                            ->join('utilisateur as u', 'c.idUtilisateur', '=', 'u.idUtilisateur')
                            ->select('s.intitule', 'e.nom as nomEntreprise', 'u.nom', 'u.prenom', 'c.statut as numStatut', 's.dateDebut', 's.dateFin', 's.detail as stageDetail')
                            ->get(),
        };

        if (request()->ajax()) {
        // ICI : On pointe vers le PETIT fichier créé à l'étape 1
        return view('accueil.tableauAff.tableau', compact('donnees', 'choix'))->render();
    }

        return view('accueil.admin', compact('salutation', 'prenom', 'stats', 'donnees', 'choix'));
    }

    private function vueStandard($salutation, $prenom, $grade) {
        // Logique de lecture du dossier "sorties/" pour l'historique CSV
        $fichiers = [];
        $repertoire = public_path('sorties/'); // Dans Laravel, on pointe vers public/
        
        if (is_dir($repertoire)) {
            foreach (glob($repertoire . "*.csv") as $filepath) {
                $fichiers[basename($filepath)] = filemtime($filepath);
            }
            arsort($fichiers);
        }

        return view('accueil.standard', compact('salutation', 'prenom', 'grade', 'fichiers'));
    }
}