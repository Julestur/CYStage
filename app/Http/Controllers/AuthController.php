<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;



class AuthController extends Controller
{
    // Affichage de la page
    public function showLoginForm() {
        return view('connexion.login');
    }

    public function showChangeMdpForm() {
        return view('connexion.change_mdp'); // Vérifie que ce fichier existe dans resources/views/connexion/
    }

// À ajouter dans AuthController.php
public function logout() {
    // 1. On vide la session Laravel
    Session::flush();
    
    // 2. On redirige vers la page de connexion
    return redirect()->route('login');
}


    // Vérification de la connexion
    public function login(Request $request) {
            
    
        $idSaisi = $request->input('pseudo');
        $mdpSaisi = $request->input('mot-de-passe');

        // 2. Chercher l'utilisateur (identifiant ou email)
        // On fait la même jointure que toi pour avoir le 'libelle' du statut
        $user = DB::table('utilisateur as u')
            ->leftJoin('statut as s', 'u.idStatut', '=', 's.idStatut')
            ->where('u.identifiant', $idSaisi)
            ->orWhere('u.email', $idSaisi)
            ->select('u.*', 's.libelle as grade')
            ->first();

        if ($user) {
                // 3. Vérification du mot de passe (Normal ou Temporaire)
            if (Hash::check($mdpSaisi, $user->mdp)) {
                $this->creerSession($user);
                return redirect('accueil'); // Remplace accueil.php
            } 
            elseif (!empty($user->mdp_tmp) && $mdpSaisi === $user->mdp_tmp) {
                $this->creerSession($user);
                return redirect('/changer-mdp-temp'); // Ta page de changement
            }
        }

        return redirect('/')->withErrors(['error' => 'Identifiant ou mot de passe incorrect']);
    }


    private function creerSession($user) {
    Session::put([
        'connecte'     => true,
        'nom'          => $user->nom,
        'prenom'       => $user->prenom,
        'pseudo'       => $user->identifiant,
        'photo-profil' => $user->pdp,
        'mdp_tmp'      => $user->mdp_tmp,
        'grade'        => $user->grade,
    ]);

    }

}