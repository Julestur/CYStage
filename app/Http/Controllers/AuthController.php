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
   public function showLoginForm(Request $request) {
    // On regarde si l'URL contient ?timeout=1
    if ($request->query('timeout') == 1) {
        return view('connexion.login')->with('session_expiree', true);
    }

    return view('connexion.login');
}



public function logout() {
    // On vide la session Laravel
    Session::flush();
    
    // On redirige vers la page de connexion
    return redirect()->route('login');
}


    // Vérification de la connexion
    public function login(Request $request) {
            
    
        $idSaisi = $request->input('pseudo');
        $mdpSaisi = $request->input('mot-de-passe');

        // Cherche l'utilisateur (identifiant ou email)
        $user = DB::table('utilisateur as u')
            ->leftJoin('statut as s', 'u.idStatut', '=', 's.idStatut')
            ->where('u.identifiant', $idSaisi)
            ->orWhere('u.email', $idSaisi)
            ->select('u.*', 's.libelle as grade')
            ->first();

        if ($user) {
                // Vérification du mot de passe (Normal ou Temporaire)
            if (Hash::check($mdpSaisi, $user->mdp)) {
                $this->creerSession($user);
                return redirect('accueil'); 
            } 
            elseif (!empty($user->mdp_tmp) && $mdpSaisi === $user->mdp_tmp) {
                $this->creerSession($user);
                return redirect('/changer-mdp-temp'); 
            }
        }

        return redirect()->route('login')->withErrors(['login_error' => true]);
    }


    private function creerSession($user) {
    Session::put([
        'id'           => $user->idUtilisateur,
        'connecte'     => true,
        'nom'          => $user->nom,
        'prenom'       => $user->prenom,
        'pseudo'       => $user->identifiant,
        'photo-profil' => $user->pdp,
        'mdp_tmp'      => $user->mdp_tmp,
        'grade'        => $user->grade,
        'idStatut'     => $user->idStatut,
        'idEntreprise' => $user->idEntreprise,
    ]);

    }

}
