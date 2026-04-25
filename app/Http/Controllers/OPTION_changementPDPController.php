<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\ReinitialisationMDP;
use Illuminate\Support\Facades\Hash;


class OPTION_changementPDPController extends Controller {


    public function affichageChangementPDP() {
        return view('OptionAccueil.change_pdp'); 
    }

    public function changementPDP(Request $requete) {
    
    
        // Definition du type de fichier 
        $requete->validate(['photo_profil' => 'required|image|mimes:jpeg,png,jpg|max:2048',]);



        
        $utilisateurID = session('id');
        $pseudo = session('pseudo');

        if ($requete->hasFile('photo_profil')) {
            $file = $requete->file('photo_profil');
            
            // 2. Nommer le fichier (pseudo + timestamp)
            $extension = $file->getClientOriginalExtension();
            $nomFichier = $pseudo . "_" . time() . "." . $extension;

            // 3. Gestion de l'ancienne photo
            // On suppose que tes photos sont dans public/images_profil/
            if (session('photo-profil') && session('photo-profil') != 'profil.png') {
                $ancienChemin = public_path('images_profil/' . session('photo-profil'));
                if (file_exists($ancienChemin)) {
                    unlink($ancienChemin);
                }
            }

            // 4. Déplacement vers le dossier public
            $file->move(public_path('images_profil'), $nomFichier);

            // 5. Mise à jour de la Base de Données (Query Builder comme ton CSV)
            DB::table('utilisateur')
                ->where('idUtilisateur', $utilisateurID)
                ->update(['pdp' => $nomFichier]);

            // 6. Mise à jour de la Session
            session(['photo-profil' => $nomFichier]);

            return back()->with('confirmation', 'Votre photo de profil a été mise à jour !');
        }

        return back()->with('erreur', 'Une erreur est survenue lors de l\'envoi.');
    }
}